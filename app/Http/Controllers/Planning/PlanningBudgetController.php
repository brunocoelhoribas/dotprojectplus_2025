<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Planning\Cost\Budget\Budget;
use App\Models\Planning\Cost\Budget\BudgetReserve;
use App\Models\Planning\Cost\Cost;
use App\Models\Project\Project;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanningBudgetController extends Controller {
    public function edit(Project $project) {
        $start = $project->project_start_date ? Carbon::parse($project->project_start_date) : now();
        $end = $project->project_end_date ? Carbon::parse($project->project_end_date) : now()->addMonths(6);

        $start->startOfMonth();
        $end->endOfMonth();

        $period = CarbonPeriod::create($start, '1 month', $end);
        $months = [];
        foreach ($period as $dt) {
            $months[] = [
                'label' => $dt->format('m/Y'),
                'key' => $dt->format('Y-m'),
                'obj' => $dt
            ];
        }

        $costs = Cost::where('cost_project_id', $project->project_id)->get();
        $hrCosts = $costs->where('cost_human_resource_id', '<>', null);
        $nonHrCosts = $costs->where('cost_human_resource_id', null);

        $reserves = BudgetReserve::where('budget_reserve_project_id', $project->project_id)->get();

        $budget = Budget::firstOrCreate(['budget_project_id' => $project->project_id], [
            'budget_reserve_management' => 0,
            'budget_sub_total' => 0,
            'budget_total' => 0
        ]);

        $spreadCost = static function ($item, $totalValue, $dateBegin, $dateEnd) use ($months) {
            $distribution = array_fill_keys(array_column($months, 'key'), 0);

            if (!$dateBegin || !$dateEnd || $totalValue <= 0) {
                return $distribution;
            }

            $itemStart = Carbon::parse($dateBegin)->startOfMonth();
            $itemEnd = Carbon::parse($dateEnd)->endOfMonth();

            $diffInMonths = $itemStart->diffInMonths($itemEnd) + 1;

            if ($diffInMonths < 1) {
                $diffInMonths = 1;
            }

            $monthlyValue = $totalValue / $diffInMonths;
            foreach ($months as $m) {
                if ($m['obj']->between($itemStart, $itemEnd)) {
                    $distribution[$m['key']] = $monthlyValue;
                }
            }
            return $distribution;
        };

        $hrRows = $hrCosts->map(fn($c) => [
            'name' => $c->cost_description,
            'total' => $c->cost_value_total,
            'monthly' => $spreadCost($c, $c->cost_value_total, $c->cost_date_begin, $c->cost_date_end)
        ]);

        $nonHrRows = $nonHrCosts->map(fn($c) => [
            'name' => $c->cost_description,
            'total' => $c->cost_value_total,
            'monthly' => $spreadCost($c, $c->cost_value_total, $c->cost_date_begin, $c->cost_date_end)
        ]);

        $reserveRows = $reserves->map(fn($r) => [
            'name' => $r->budget_reserve_description,
            'total' => $r->budget_reserve_value_total,
            'monthly' => $spreadCost($r, $r->budget_reserve_value_total, $r->budget_reserve_inicial_month, $r->budget_reserve_final_month)
        ]);

        $monthlyTotals = array_fill_keys(array_column($months, 'key'), 0);
        $grandTotalCalculated = 0;

        foreach ([$hrRows, $nonHrRows, $reserveRows] as $group) {
            foreach ($group as $row) {
                $grandTotalCalculated += $row['total'];
                foreach ($row['monthly'] as $key => $val) {
                    $monthlyTotals[$key] += $val;
                }
            }
        }

        if (abs($budget->budget_sub_total - $grandTotalCalculated) > 0.01) {
            $budget->budget_sub_total = $grandTotalCalculated;
            $reserveVal = ($grandTotalCalculated * $budget->budget_reserve_management) / 100;
            $budget->budget_total = $grandTotalCalculated + $reserveVal;
            $budget->save();
        }

        return view('projects.planning.tabs.costs.budget_modal', compact(
            'project', 'months', 'hrRows', 'nonHrRows', 'reserveRows', 'monthlyTotals', 'budget'
        ));
    }

    public function update(Request $request, Project $project): JsonResponse {
        $budget = Budget::where('budget_project_id', $project->project_id)->firstOrFail();

        $request->validate([
            'budget_reserve_management' => 'required|numeric|min:0|max:100'
        ]);

        $reservePercent = $request->budget_reserve_management;
        $subTotal = $budget->budget_sub_total;

        $reserveValue = ($subTotal * $reservePercent) / 100;
        $total = $subTotal + $reserveValue;

        $budget->update([
            'budget_reserve_management' => $reservePercent,
            'budget_total' => $total
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Orçamento atualizado com sucesso.',
            'new_total' => number_format($total, 2, ',', '.')
        ]);
    }
}
