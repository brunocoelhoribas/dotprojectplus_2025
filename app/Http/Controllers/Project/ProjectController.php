<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Initiating\Initiating;
use App\Models\Project\Project;
use App\Models\Project\ProjectMinute;
use App\Models\Project\ProjectTraining;
use App\Models\Project\ProjectWbsItem;
use App\Models\Project\Task\Task;
use App\Models\User\User;
use App\Models\User\UserContact;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Manages Project CRUD operations and related business logic.
 * (Based on the original dotProject module)
 */
class ProjectController extends Controller {

    public function index(Request $request): View {
        $filterStatus = $request->query('status', 'all');
        $filterOwner = $request->query('owner');
        $filterCompany = $request->query('company');

        $query = Project::with(['company', 'owner.contact']);

        if ($filterStatus !== 'all') {
            $query->where('project_status', $filterStatus);
        }

        if ($filterOwner) {
            $query->where('project_owner', $filterOwner);
        }

        if ($filterCompany) {
            $query->where('project_company', $filterCompany);
        }

        $projects = $query->orderBy('project_end_date', 'desc')
            ->paginate(20)
            ->appends($request->query());

        return view('projects.index', [
            'projects' => $projects,
            'statuses' => $this->getProjectStatus(),
            'users' => $this->getOwnerList(),
            'companies' => $this->getCompanyList(),
            'filterStatus' => $filterStatus,
            'filterOwner' => $filterOwner,
            'filterCompany' => $filterCompany,
        ]);
    }

    public function create(Request $request): View {
        $companyId = $request->query('company_id');

        return view('projects.create', [
            'project' => new Project(),
            'companyId' => $companyId,
            'companies' => $this->getCompanyList(),
            'users' => $this->getOwnerList(),
            'statuses' => $this->getProjectStatus(),
            'priorities' => $this->getProjectPriorities(),
        ]);
    }

    /**
     * Store a newly created project in the database.
     */
    public function store(Request $request): RedirectResponse {
        $validatedData = $this->validateProject($request);

        $m2mDepartments = $this->parseM2MInput($request->input('project_departments'));
        $m2mContacts = $this->parseM2MInput($request->input('project_contacts'));

        $projectData = Arr::except($validatedData, ['project_departments', 'project_contacts']);
        $projectData['project_creator'] = auth()->id();

        $project = Project::create($projectData);

        if (!empty($m2mDepartments)) {
            $project->departments()->sync($m2mDepartments);
        }
        if (!empty($m2mContacts)) {
            $project->contacts()->sync($m2mContacts);
        }

        return redirect()->route('projects.show', $project->project_id)
            ->with('success', __('projects/messages.success.created'));
    }

    public function show(Project $project): View {
        $project->loadMissing([
            'company',
            'owner.contact',
            'departments',
            'contacts.contact',
            'initiating.stakeholders.contact'
        ]);

        $statuses = $this->getProjectStatus();
        $priorities = $this->getProjectPriorities();
        $contacts = UserContact::orderBy('contact_first_name')->get();

        $wbsItems = ProjectWbsItem::with(['tasks.owner.contact', 'tasks.estimation'])
            ->where('project_id', $project->project_id)
            ->orderBy('sort_order')
            ->get();

        $executionTasks = Task::where('task_project', $project->project_id)
            ->orderBy('task_start_date')
            ->get();
        $allTasks = $executionTasks->pluck('task_name', 'task_id');

        $percentComplete = $project->project_percent_complete;
        $workedHours = 0;
        $totalHours = 0;
        $minutes = ProjectMinute::where('project_id', $project->project_id)
            ->orderBy('minute_date', 'desc')
            ->get();

        $training = ProjectTraining::where('project_id', $project->project_id)->first();

        return view('projects.show', [
            'project' => $project,
            'initiating' => $project->initiating,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'users' => $this->getOwnerList(),
            'contacts' => $contacts,
            'wbsItems' => $wbsItems,
            'executionTasks' => $executionTasks,
            'allTasks' => $allTasks,
            'percentComplete' => $percentComplete,
            'workedHours' => $workedHours,
            'totalHours' => $totalHours,
            'minutes' => $minutes,
            'training' => $training,
        ]);
    }

    // ... (O método edit permanece inalterado) ...
    public function edit(Project $project): View {
        $project->loadMissing(['departments', 'contacts']);

        return view('projects.edit', [
            'project' => $project,
            'companyId' => $project->project_company,
            'companies' => $this->getCompanyList(),
            'users' => $this->getOwnerList(),
            'statuses' => $this->getProjectStatus(),
            'priorities' => $this->getProjectPriorities(),
        ]);
    }

    /**
     * Update the specified project in the database.
     */
    public function update(Request $request, Project $project): RedirectResponse {
        $validatedData = $this->validateProject($request);

        $m2mDepartments = $this->parseM2MInput($request->input('project_departments'));
        $m2mContacts = $this->parseM2MInput($request->input('project_contacts'));

        $projectData = Arr::except($validatedData, ['project_departments', 'project_contacts']);

        $project->update($projectData);

        $project->departments()->sync($m2mDepartments);
        $project->contacts()->sync($m2mContacts);

        return redirect()->route('projects.show', $project->project_id)
            ->with('success', __('projects/messages.success.updated'));
    }

    /**
     * Remove the specified project from the database.
     */
    public function destroy(Project $project): RedirectResponse {
        // TODO: Implement cascading delete logic via Observers
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', __('projects/messages.success.deleted'));
    }

    /**
     * Update the status of multiple projects at once.
     */
    public function batchUpdate(Request $request): RedirectResponse {
        $request->validate([
            'project_ids' => 'required|array',
            'project_ids.*' => 'integer|exists:dotp_projects,project_id',
            'new_status' => 'required|integer',
        ]);

        $projectIds = $request->input('project_ids');
        $newStatus = $request->input('new_status');

        Project::whereIn('project_id', $projectIds)
            ->update(['project_status' => $newStatus]);

        return redirect()->route('projects.index')
            ->with('success', __('projects/messages.success.batch_updated'));
    }

    private function validateProject(Request $request): array {
        return $request->validate([
            'project_name' => 'required|string|max:255',
            'project_short_name' => 'nullable|string|max:10',
            'project_company' => 'required|integer|exists:dotp_companies,company_id',
            'project_owner' => 'required|integer|exists:dotp_users,user_id',
            'project_start_date' => 'nullable|date',
            'project_end_date' => 'nullable|date|after_or_equal:project_start_date',
            'project_status' => 'required|integer',
            'project_priority' => 'required|integer',
            'project_target_budget' => 'nullable|numeric|min:0',
            'project_description' => 'nullable|string',
            'project_color_identifier' => 'nullable|string|max:6',
            'project_departments' => 'nullable|string',
            'project_contacts' => 'nullable|string',
        ]);
    }

    private function getCompanyList(): Collection {
        return Company::orderBy('company_name')->pluck('company_name', 'company_id');
    }

    private function getOwnerList(): Collection {
        return User::join('dotp_contacts', 'dotp_users.user_contact', '=', 'dotp_contacts.contact_id')
            ->orderBy('dotp_contacts.contact_first_name')
            ->orderBy('dotp_contacts.contact_last_name')
            ->pluck(DB::raw("CONCAT(contact_first_name, ' ', contact_last_name)"), 'dotp_users.user_id');
    }

    private function getProjectStatus(): array {
        return $this->getSysVal('ProjectStatus');
    }

    private function getProjectPriorities(): array {
        return $this->getSysVal('ProjectPriority');
    }

    private function getSysVal(string $title): array {
        $getStatus = DB::table('dotp_sysvals')
            ->where('sysval_title', $title)
            ->pluck('sysval_value');

        $data = $getStatus->first();
        if (!$data) {
            return [];
        }

        preg_match_all('/(-?\d+)\|(\D+)/', $data, $matches);

        $status = [];
        if (!empty($matches[1]) && !empty($matches[2])) {
            $status = array_combine($matches[1], array_map('trim', $matches[2]));
        }

        return $status;
    }

    private function parseM2MInput($input): array {
        if (empty($input)) {
            return [];
        }
        return array_filter(array_map('intval', explode(',', $input)));
    }
}
