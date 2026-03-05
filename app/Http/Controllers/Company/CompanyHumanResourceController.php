<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\User\UserContact;
use App\Models\User\User;

// Ajuste se o seu model User ficar em outro namespace, ex: App\Models\User
use App\Models\HumanResource\HumanResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CompanyHumanResourceController extends Controller {
    /**
     * @throws Throwable
     */
    public function store(Request $request, Company $company): JsonResponse {
        $request->validate([
            'creation_type' => 'required|in:existing,new',
            'user_id' => 'required_if:creation_type,existing|nullable|integer|exists:users,user_id',
            'first_name' => 'required_if:creation_type,new|nullable|string|max:100',
            'last_name' => 'required_if:creation_type,new|nullable|string|max:100',
            'roles' => 'nullable|array',
            'human_resource_lattes_url' => 'nullable|url',
            'human_resource_sun' => 'nullable|integer|min:0|max:24',
            'human_resource_mon' => 'nullable|integer|min:0|max:24',
            'human_resource_tue' => 'nullable|integer|min:0|max:24',
            'human_resource_wed' => 'nullable|integer|min:0|max:24',
            'human_resource_thu' => 'nullable|integer|min:0|max:24',
            'human_resource_fri' => 'nullable|integer|min:0|max:24',
            'human_resource_sat' => 'nullable|integer|min:0|max:24',
        ]);

        try {
            DB::beginTransaction();

            $userId = $request->user_id;

            if ($request->creation_type === 'new') {
                $contact = UserContact::create([
                    'contact_first_name' => $request->first_name,
                    'contact_last_name' => $request->last_name,
                    'contact_company' => $company->company_id,
                ]);

                $username = strtolower($request->first_name . '.' . $request->last_name);

                $user = User::create([
                    'user_username' => $username,
                    'user_password' => md5('123456'),
                    'user_contact' => $contact->contact_id,
                    'user_company' => $company->company_id,
                    'user_type' => 0,
                    'user_parent' => 0,
                    'user_department' => 0,
                    'user_owner' => 0,
                ]);

                $userId = $user->user_id;
            }

            $exists = HumanResource::where('human_resource_user_id', $userId)->exists();
            if ($exists) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Este usuário já possui um recurso humano configurado.'], 400);
            }

            $hr = HumanResource::create([
                'human_resource_user_id' => $userId,
                'human_resource_lattes_url' => $request->human_resource_lattes_url,
                'human_resource_sun' => $request->human_resource_sun ?? 0,
                'human_resource_mon' => $request->human_resource_mon ?? 0,
                'human_resource_tue' => $request->human_resource_tue ?? 0,
                'human_resource_wed' => $request->human_resource_wed ?? 0,
                'human_resource_thu' => $request->human_resource_thu ?? 0,
                'human_resource_fri' => $request->human_resource_fri ?? 0,
                'human_resource_sat' => $request->human_resource_sat ?? 0,
            ]);

            if ($request->has('roles')) {
                $hr->roles()->sync($request->roles);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('companies/view.hr.messages.created_success') ?? 'Criado com sucesso!'
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erro ao criar: ' . $e->getMessage()], 500);
        }
    }

    public function show(Company $company, $humanResourceId) {
        $hr = HumanResource::with(['user.contact', 'roles'])->findOrFail($humanResourceId);
        $availableRoles = $company->roles()->orderBy('human_resources_role_name')->get();

        return view('companies.human-resources.show', compact('company', 'hr', 'availableRoles'));
    }

    public function update(Request $request, Company $company, $humanResourceId): JsonResponse {
        $request->validate([
            'human_resource_lattes_url' => 'nullable|url',
            'human_resource_sun' => 'nullable|integer|min:0|max:24',
            'human_resource_mon' => 'nullable|integer|min:0|max:24',
            'human_resource_tue' => 'nullable|integer|min:0|max:24',
            'human_resource_wed' => 'nullable|integer|min:0|max:24',
            'human_resource_thu' => 'nullable|integer|min:0|max:24',
            'human_resource_fri' => 'nullable|integer|min:0|max:24',
            'human_resource_sat' => 'nullable|integer|min:0|max:24',
        ]);

        try {
            $hr = HumanResource::findOrFail($humanResourceId);

            $hr->update($request->only([
                'human_resource_lattes_url',
                'human_resource_sun',
                'human_resource_mon',
                'human_resource_tue',
                'human_resource_wed',
                'human_resource_thu',
                'human_resource_fri',
                'human_resource_sat',
            ]));

            return response()->json([
                'success' => true,
                'message' => __('companies/view.hr.messages.updated_success'),
                'data' => $hr
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Company $company, $humanResourceId): JsonResponse {
        try {
            $hr = HumanResource::findOrFail($humanResourceId);

            if (!$hr->can_delete) {
                return response()->json([
                    'success' => false,
                    'message' => __('companies/view.hr.messages.cannot_delete')
                ], 403);
            }

            $hr->delete();

            return response()->json([
                'success' => true,
                'message' => __('companies/view.hr.messages.deleted_success')
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir: ' . $e->getMessage()
            ], 500);
        }
    }
}
