<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\HumanResource\HumanResourcesRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyRoleController extends Controller {
    public function create(Company $company) {
        return view('companies.roles.create_edit', [
            'company' => $company,
            'role' => new HumanResourcesRole(),
            'isEdit' => false
        ]);
    }

    public function store(Request $request, Company $company): RedirectResponse {
        $data = $request->validate([
            'human_resources_role_name' => 'required|string|max:255',
            'human_resources_role_responsability' => 'nullable|string',
            'human_resources_role_authority' => 'nullable|string',
            'human_resources_role_competence' => 'nullable|string',
        ]);

        $data['human_resources_role_company_id'] = $company->company_id;

        HumanResourcesRole::create($data);

        return redirect()->route('companies.show', $company)
            ->with('success', 'Papel criado com sucesso!');
    }

    public function edit(Company $company, HumanResourcesRole $role) {
        return view('companies.roles.create_edit', [
            'company' => $company,
            'role' => $role,
            'isEdit' => true
        ]);
    }

    public function update(Request $request, Company $company, HumanResourcesRole $role): RedirectResponse {
        $data = $request->validate([
            'human_resources_role_name' => 'required|string|max:255',
            'human_resources_role_responsability' => 'nullable|string',
            'human_resources_role_authority' => 'nullable|string',
            'human_resources_role_competence' => 'nullable|string',
        ]);

        $role->update($data);

        return redirect()->route('companies.show', $company)
            ->with('success', 'Papel atualizado com sucesso!');
    }

    public function destroy(Company $company, HumanResourcesRole $role): RedirectResponse {
        $role->delete();
        return back()->with('success', 'Papel removido com sucesso!');
    }
}
