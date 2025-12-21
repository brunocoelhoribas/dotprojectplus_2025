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
     * (Index method maintained as is)
     */
    public function index(Request $request): Factory|View|Application {
        $selectedTypeId = $request->input('type');

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

        if ($selectedTypeId) {
            $query->where('company_type', $selectedTypeId);
        }

        $companies = $query->paginate(15)->appends($request->query());

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
     */
    public function store(Request $request): RedirectResponse {
        $validated = $this->validateCompany($request);

        Company::create($validated);

        // ALTERADO: Mensagem traduzida
        return redirect()->route('companies.index')
            ->with('success', __('companies/messages.created'));
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company): Factory|View|Application {
        $company->loadMissing([
            'owner.contact',
            'policy',
            'activeProjects.owner.contact',
            'archivedProjects.owner.contact'
        ]);

        $projectStatus = $this->getProjectStatus();

        return view('companies.show', [
            'company' => $company,
            'projectStatus' => $projectStatus,
        ]);
    }

    /**
     * Show the form for editing the specified company.
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
            ['company_policies_company_id' => $company->company_id],
            $validatedPolicies
        );

        return redirect()->route('companies.edit', $company)
            ->with('success', __('companies/messages.updated'));
    }

    /**
     * Remove the specified company from storage.
     */
    public function destroy(Company $company): RedirectResponse {
        $company->delete();

        // ALTERADO: Mensagem traduzida
        return redirect()->route('companies.index')
            ->with('success', __('companies/messages.deleted'));
    }

    /**
     * Private helper to validate company data from a request.
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
     */
    private function getOwnerList(): Collection {
        return User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('dotp_contacts.contact_first_name')
            ->orderBy('dotp_contacts.contact_last_name')
            ->pluck(DB::raw("CONCAT(contact_first_name, ' ', contact_last_name)"), 'dotp_users.user_id');
    }

    /**
     * Private helper to get company types from a system value setting.
     * @noinspection DuplicatedCode
     */
    private function getCompanyTypes(): array {
        $getTypes = DB::table('dotp_sysvals')
            ->where('sysval_title', 'CompanyType')
            ->pluck('sysval_value');

        $data = $getTypes->first();
        if (!$data) {
            return [];
        }

        preg_match_all('/(\d+)\|(\D+)/', $data, $matches);

        $types = [];
        if (!empty($matches[1]) && !empty($matches[2])) {
            $types = array_combine($matches[1], array_map('trim', $matches[2]));
        }

        return $types;
    }

    /** @noinspection DuplicatedCode */
    private function getProjectStatus(): array {
        $getStatus = DB::table('dotp_sysvals')
            ->where('sysval_title', 'ProjectStatus')
            ->pluck('sysval_value');

        $data = $getStatus->first();
        if (!$data) {
            return [];
        }

        preg_match_all('/(\d+)\|(\D+)/', $data, $matches);

        $status = [];
        if (!empty($matches[1]) && !empty($matches[2])) {
            $status = array_combine($matches[1], array_map('trim', $matches[2]));
        }

        return $status;
    }
}
