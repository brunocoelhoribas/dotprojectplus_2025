<?php

namespace App\Http\Controllers;

use App\Models\Company\Company;
use App\Models\Initiating\Initiating;
use App\Models\Project\Project;
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
    /**
     * Display a paginated list of projects with filtering.
     * (Based on 'index.php' and 'projects.class.php::projects_list_data()')
     *
     * @param Request $request
     * @return View
     */
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

    /**
     * Show the form for creating a new project.
     * (Based on 'addedit.php')
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View {
        // Get company_id from URL to pre-select the dropdown
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
     * (Based on 'do_project_aed.php' and 'projects.class.php::store()')
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse {
        $validatedData = $this->validateProject($request);

        // Parse M2M relationships (which are not columns in 'dotp_projects')
        $m2mDepartments = $this->parseM2MInput($request->input('project_departments'));
        $m2mContacts = $this->parseM2MInput($request->input('project_contacts'));

        // Prepare data for the main project table
        $projectData = Arr::except($validatedData, ['project_departments', 'project_contacts']);
        $projectData['project_creator'] = auth()->id();

        // Create the project
        $project = Project::create($projectData);

        // Sync M2M relationships
        if (!empty($m2mDepartments)) {
            $project->departments()->sync($m2mDepartments);
        }
        if (!empty($m2mContacts)) {
            $project->contacts()->sync($m2mContacts);
        }

        return redirect()->route('projects.show', $project->project_id)
            ->with('success', 'Projeto criado com sucesso.');
    }

    /**
     * Display the specified project details.
     * (Based on 'view.php')
     *
     * @param Project $project Route-model binding
     * @return View
     */
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
        $users = $this->getOwnerList();
        $initiating = $project->initiating ?? new Initiating();
        $contacts = UserContact::orderBy('contact_first_name')->get();

        $wbsItems = ProjectWbsItem::with(['tasks.owner.contact', 'tasks.estimation'])
            ->where('project_id', $project->project_id)
            ->orderBy('sort_order')
            ->get();

        $executionTasks = Task::where('task_project', $project->project_id)
            ->orderBy('task_start_date')
            ->get();

        $percentComplete = $project->project_percent_complete;
        $workedHours = 0;
        $totalHours = 0;

        return view('projects.show', [
            'project' => $project,
            'initiating' => $initiating,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'users' => $users,
            'contacts' => $contacts,
            'wbsItems' => $wbsItems,
            'executionTasks' => $executionTasks,
            'percentComplete' => $percentComplete,
            'workedHours' => $workedHours,
            'totalHours' => $totalHours,
        ]);
    }

    /**
     * Show the form for editing the specified project.
     * (Based on 'addedit.php')
     *
     * @param Project $project Route-model binding
     * @return View
     */
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
     * (Based on 'do_project_aed.php' and 'projects.class.php::store()')
     *
     * @param Request $request
     * @param Project $project Route-model binding
     * @return RedirectResponse
     */
    public function update(Request $request, Project $project): RedirectResponse {
        $validatedData = $this->validateProject($request);

        // Parse M2M relationships
        $m2mDepartments = $this->parseM2MInput($request->input('project_departments'));
        $m2mContacts = $this->parseM2MInput($request->input('project_contacts'));

        // Get data for the main project table
        $projectData = Arr::except($validatedData, ['project_departments', 'project_contacts']);

        // Update the project
        $project->update($projectData);

        // Re-sync M2M relationships
        $project->departments()->sync($m2mDepartments);
        $project->contacts()->sync($m2mContacts);

        return redirect()->route('projects.show', $project->project_id)
            ->with('success', 'Projeto atualizado com sucesso.');
    }

    /**
     * Remove the specified project from the database.
     * (Based on 'do_project_aed.php' and 'projects.class.php::delete()')
     *
     * @param Project $project Route-model binding
     * @return RedirectResponse
     */
    public function destroy(Project $project): RedirectResponse {
        // TODO: Implement cascading delete logic from 'projects.class.php::delete()'
        // This should delete related tasks, task_logs, user_tasks, dependencies, etc.
        // This is best handled by Model Observers (on the 'deleting' event).

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projeto excluído com sucesso.');
    }

    /**
     * Update the status of multiple projects at once.
     * (Based on 'index.php' batch update logic)
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function batchUpdate(Request $request): RedirectResponse {
        $request->validate([
            'project_ids' => 'required|array',
            'project_ids.*' => 'integer|exists:dotp_projects,project_id',
            'new_status' => 'required|integer',
        ]);

        $projectIds = $request->input('project_ids');
        $newStatus = $request->input('new_status');

        // TODO: Add permission check for each project ID

        Project::whereIn('project_id', $projectIds)
            ->update(['project_status' => $newStatus]);

        return redirect()->back()->with('success', 'Status dos projetos atualizado com sucesso.');
    }

    // --- PRIVATE HELPER METHODS ---

    /**
     * Validate the project data from the request.
     * (Based on 'addedit.php' required fields)
     *
     * @param Request $request
     * @return array The validated data.
     */
    private function validateProject(Request $request): array {
        // These keys must match the form 'name' attributes and DB columns
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
            'project_departments' => 'nullable|string', // Comma-separated IDs
            'project_contacts' => 'nullable|string',    // Comma-separated IDs
        ]);
    }

    /**
     * Helper function to get a list of companies for dropdowns.
     *
     * @return Collection
     */
    private function getCompanyList(): Collection {
        // TODO: Implement 'getAllowedRecords' logic if necessary
        return Company::orderBy('company_name')->pluck('company_name', 'company_id');
    }

    /**
     * Helper function to get a list of owners (Users) for dropdowns.
     * (Replicates logic from CompanyController)
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
     * Helper function to get the project status list from sysvals.
     *
     * @return array
     */
    private function getProjectStatus(): array {
        return $this->getSysVal('ProjectStatus');
    }

    /**
     * Helper function to get the project priority list from sysvals.
     *
     * @return array
     */
    private function getProjectPriorities(): array {
        return $this->getSysVal('ProjectPriority');
    }

    /**
     * Generic helper to parse dotProject sysvals from the database.
     *
     * @param string $title The 'sysval_title' (e.g., 'ProjectStatus')
     * @return array
     */
    private function getSysVal(string $title): array {
        $getStatus = DB::table('dotp_sysvals')
            ->where('sysval_title', $title)
            ->pluck('sysval_value');

        $data = $getStatus->first();
        if (!$data) {
            return [];
        }

        // Regex to parse the "key|Value\nkey|Value" format
        preg_match_all('/(-?\d+)\|(\D+)/', $data, $matches);

        $status = [];
        if (!empty($matches[1]) && !empty($matches[2])) {
            $status = array_combine($matches[1], array_map('trim', $matches[2]));
        }

        return $status;
    }

    /**
     * Helper function to parse a comma-separated string of IDs into an array.
     * (Used for project_departments and project_contacts)
     *
     * @param string|null $input (e.g., "1,2,3")
     * @return array (e.g., [1, 2, 3])
     */
    private function parseM2MInput($input): array {
        if (empty($input)) {
            return [];
        }
        // Filter out empty values and ensure integers
        return array_filter(array_map('intval', explode(',', $input)));
    }
}
