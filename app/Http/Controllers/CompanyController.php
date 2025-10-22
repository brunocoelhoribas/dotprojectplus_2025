<?php

namespace App\Http\Controllers;

// Namespaces dos seus Models
use App\Models\Company\Company;
use App\Models\Company\CompanyPolicy;
use App\Models\User\User;

// Imports do Laravel
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller {
    private function getOwnerList() {
        return User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('dotp_contacts.contact_first_name')
            ->orderBy('dotp_contacts.contact_last_name')
            ->pluck(DB::raw("CONCAT(contact_first_name, ' ', contact_last_name)"), 'dotp_users.user_id');
    }

    private function getCompanyTypes(): array {
        $getTypes = DB::table('dotp_sysvals')
            ->where('sysval_title', 'CompanyType')
            ->pluck('sysval_value');

        $data = $getTypes->first();
        preg_match_all('/(\d+)\|(\D+)/', $data, $matches);

        $types = [];
        if (!empty($matches[1]) && !empty($matches[2])) {
            $types = array_combine($matches[1], $matches[2]);
        }

        return $types;
    }


    public function index(Request $request): Factory|View {
        $query = Company::with('owner.contact')
            ->withCount([
                'projects AS active_projects_count' => function ($query) {
                    $query->whereNotNull('project_id');
                },
                'projects AS archived_projects_count' => function ($query) {
                    $query->where('project_status', 7);
                }
            ]);

        if ($request->has('search') && $request->input('search') !== '') {
            $searchTerm = $request->input('search');
            $query->where('company_name', 'LIKE', '%' . $searchTerm . '%');
        }

        if ($request->has('owner') && $request->input('owner') !== 'all') {
            $query->where('company_owner', $request->input('owner'));
        }

        $companies = $query->paginate(15)->appends($request->query());
        $types = $this->getCompanyTypes();
        $owners = $this->getOwnerList();

        return view('companies.index', [
            'companies' => $companies,
            'types' => $types,
            'owners' => $owners
        ]);
    }


    public function create(): Factory|View {
        return view('companies.create', [
            'users' => $this->getOwnerList(),
            'types' => $this->getCompanyTypes()
        ]);
    }


    public function store(Request $request): RedirectResponse {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_owner' => 'required|exists:dotp_users,user_id',
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')->with('success', 'Empresa criada com sucesso.');
    }


    public function show(Company $company): Factory|View {
        $company->load('owner.contact', 'policies');

        return view('companies.show', compact('company'));
    }


    public function edit(Company $company): Factory|View {
        $company->load('policies');

        if (is_null($company->policies)) {
            $company->policies = new CompanyPolicy();
        }

        $users = $this->getOwnerList();
        $types = $this->getCompanyTypes();

        return view('companies.edit', compact('company', 'users', 'types'));
    }


    public function update(Request $request, Company $company): RedirectResponse {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_owner' => 'required|exists:dotp_users,user_id',
            'company_email' => 'nullable|email|max:255',
            'company_policies_recognition' => 'nullable|string',
            'company_policies_policy' => 'nullable|string',
            'company_policies_safety' => 'nullable|string',
        ]);

        $companyData = $request->except([
            '_token', '_method',
            'company_policies_recognition',
            'company_policies_policy',
            'company_policies_safety'
        ]);
        $policyData = $request->only([
            'company_policies_recognition',
            'company_policies_policy',
            'company_policies_safety'
        ]);

        $company->update($companyData);

        CompanyPolicy::updateOrCreate(
            ['company_policies_company_id' => $company->company_id],
            $policyData
        );

        return redirect()->route('companies.index')->with('success', 'Empresa atualizada com sucesso.');
    }


    public function destroy(Company $company): RedirectResponse {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Empresa excluída com sucesso.');
    }
}
