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
    /**
     * Função auxiliar para buscar a lista de 'donos' (usuários)
     * no formato correto (ID => Nome Completo) para os formulários.
     * Esta função é usada por create() e edit().
     */
    private function getOwnerList() {
        // Busca Users, faz join com a tabela de contatos e retorna [user_id => 'Nome Sobrenome']
        return User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('dotp_contacts.contact_first_name')
            ->orderBy('dotp_contacts.contact_last_name')
            ->pluck(DB::raw("CONCAT(contact_first_name, ' ', contact_last_name)"), 'dotp_users.user_id');
    }


    private function getCompanyTypes(): Collection {
        return DB::table('dotp_company_role') // ATENÇÃO: Verifique se o nome da sua tabela é 'sysvals'
        ->where('role_name', 'CompanyType')
            ->orderBy('id')
            ->pluck('sysval_value', 'sysval_value_id');
    }


    public function index(): Factory|View {
        $companies = Company::with('owner.contact')
            ->withCount([
                'projects AS active_projects_count' => function ($query) {
                    $query->whereNotNull('project_id');
                },
                'projects AS archived_projects_count' => function ($query) {
                    $query->where('project_status', 7);
                }
            ])
            ->orderBy('company_name')
            ->paginate(15);

        return view('companies.index', compact('companies'));
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
