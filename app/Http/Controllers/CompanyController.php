<?php

namespace App\Http\Controllers;

use App\Models\Company\Company;
use App\Models\User\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Resourceful controller for managing companies.
 *
 * Handles CRUD operations for companies, including filtering,
 * validation, and managing related company policies.
 */
class CompanyController extends Controller {

    /**
     * Display a paginated list of companies with filters.
     *
     * Eager loads owner and project counts (active/archived).
     * Supports filtering by search term, owner, and company type.
     *
     * @param Request $request
     * @return Factory|View|Application
     */
    public function index(Request $request): Factory|View|Application {
        $selectedTypeId = $request->input('type');

        $query = Company::with('owner.contact')
            ->withCount([
                // Eager load count of all projects
                'projects AS active_projects_count' => function ($query) {
                    $query->whereNotNull('project_id'); // A simple non-null check
                },
                // Eager load count of only archived projects
                'projects AS archived_projects_count' => function ($query) {
                    $query->where('project_status', 7); // '7' likely means archived
                }
            ]);

        // Apply search filter
        if ($request->has('search') && $request->input('search') !== '') {
            $searchTerm = $request->input('search');
            $query->where('company_name', 'LIKE', '%' . $searchTerm . '%');
        }

        // Apply owner filter
        if ($request->has('owner') && $request->input('owner') !== 'all') {
            $query->where('company_owner', $request->input('owner'));
        }

        // Apply company type filter
        if ($selectedTypeId) {
            $query->where('company_type', $selectedTypeId);
        }

        // Paginate results and append query string to pagination links
        $companies = $query->paginate(15)->appends($request->query());

        // Get data for filter dropdowns
        $types = $this->getCompanyTypes();
        $owners = $this->getOwnerList();

        return view('companies.index', [
            'companies' => $companies,
            'types' => $types,
            'owners' => $owners,
            'selectedTypeId' => $selectedTypeId,
        ]);
    }

    /**
     * Show the form for creating a new company.
     *
     * @return View|Application
     */
    public function create(): View|Application {
        return view('companies.create', [
            'company' => new Company(),
            'users' => $this->getOwnerList(),
            'types' => $this->getCompanyTypes()
        ]);
    }

    /**
     * Store a newly created company in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        $validated = $this->validateCompany($request);

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Empresa criada com sucesso.');
    }

    /**
     * Display the specified company.
     *
     * Eager loads the owner (with contact) and policy details.
     *
     * @param Company $company Route-model binding
     * @return Factory|View|Application
     */
    public function show(Company $company): Factory|View|Application {
        return view('companies.show', ['company' => $company->load('owner.contact', 'policy')]);
    }

    /**
     * Show the form for editing the specified company.
     *
     * @param Company $company Route-model binding
     * @return View|Application
     */
    public function edit(Company $company): View|Application {
        $company->load('policy');

        return view('companies.edit', [
            'company' => $company,
            'users' => $this->getOwnerList(),
            'types' => $this->getCompanyTypes()
        ]);
    }

    /**
     * Update the specified company and its related policies in storage.
     *
     * @param Request $request
     * @param Company $company Route-model binding
     * @return RedirectResponse
     */
    public function update(Request $request, Company $company): RedirectResponse {
        // Validate and update main company data
        $validatedCompany = $this->validateCompany($request);
        $company->update($validatedCompany);

        // Validate and update (or create) related company policies
        $validatedPolicies = $request->validate([
            'company_policies_recognition' => 'nullable|string',
            'company_policies_policy' => 'nullable|string',
            'company_policies_safety' => 'nullable|string',
        ]);
        $company->policy()->updateOrCreate(
            ['company_policies_company_id' => $company->company_id], // Find by this
            $validatedPolicies                                        // Update with this
        );

        return redirect()->route('companies.edit', $company) // Redirect back to edit page
        ->with('success', 'Empresa e Políticas atualizadas com sucesso.');
    }

    /**
     * Remove the specified company from storage.
     *
     * @param Company $company Route-model binding
     * @return RedirectResponse
     */
    public function destroy(Company $company): RedirectResponse {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Empresa excluída com sucesso.');
    }

    /**
     * Private helper to validate company data from a request.
     *
     * @param Request $request
     * @return array The validated data.
     */
    private function validateCompany(Request $request): array {
        return $request->validate([
            'company_name' => 'required|string|min:3|max:255',
            'company_owner' => 'required|exists:dotp_users,user_id',
            'company_type' => 'nullable|integer',
            'company_description' => 'nullable|string',
            'company_email' => 'nullable|email|max:255',
            'company_phone1' => 'nullable|string|max:30',
            'company_fax' => 'nullable|string|max:30',
            'company_address1' => 'nullable|string|max:255',
            'company_city' => 'nullable|string|max:50',
            'company_state' => 'nullable|string|max:50',
            'company_zip' => 'nullable|string|max:15',
            'company_primary_url' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Private helper to get a list of owners (Users) for dropdowns.
     *
     * @return Collection
     */
    private function getOwnerList(): Collection {
        return User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('dotp_contacts.contact_first_name')
            ->orderBy('dotp_contacts.contact_last_name')
            ->pluck(DB::raw("CONCAT(contact_first_name, ' ', contact_last_name)"), 'dotp_users.user_id');
    }

    /**
     * Private helper to get company types from a system value setting.
     *
     * Parses a 'key|value' string (e.g., "1|Type A\n2|Type B")
     * from the `dotp_sysvals` table into an associative array.
     *
     * @return array<int, string>
     */
    private function getCompanyTypes(): array {
        $getTypes = DB::table('dotp_sysvals')
            ->where('sysval_title', 'CompanyType')
            ->pluck('sysval_value');

        $data = $getTypes->first();
        if (!$data) {
            return [];
        }

        // Use regex to parse the "1|TypeA\n2|TypeB" format
        preg_match_all('/(\d+)\|(\D+)/', $data, $matches);

        $types = [];
        if (!empty($matches[1]) && !empty($matches[2])) {
            // Combine the keys (matches[1]) with the values (matches[2])
            $types = array_combine($matches[1], array_map('trim', $matches[2]));
        }

        return $types;
    }
}
