@php
    $initiating = $project->initiating;
@endphp

<div id="stakeholder-list-container">
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark m-0"></h6>

            <div>
                @if($initiating && $initiating->exists)
                    <a href="{{ route('initiating.stakeholders.pdf', $initiating) }}"
                       class="btn btn-sm btn-light border shadow-sm me-1"
                       target="_blank"
                       title="{{ __('projects/views.show.stakeholder.generate_pdf') }}">
                        <i class="bi bi-file-earmark-pdf text-danger"></i> PDF
                    </a>
                @endif

                <button type="button" class="btn btn-sm btn-light border shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#createStakeholderModal">
                    <i class="bi bi-plus-lg me-1"></i> {{ __('projects/views.show.stakeholder.new_btn') }}
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle mb-0 small" style="font-size: 0.85rem;">
                <thead class="bg-warning text-dark">
                <tr class="text-center align-middle">
                    <th width="20%">{{ __('projects/views.show.stakeholder.table.name') }}</th>
                    <th width="30%">{{ __('projects/views.show.stakeholder.table.responsibilities') }}</th>
                    <th width="10%">{{ __('projects/views.show.stakeholder.table.interest') }}</th>
                    <th width="10%">{{ __('projects/views.show.stakeholder.table.power') }}</th>
                    <th width="25%">{{ __('projects/views.show.stakeholder.table.strategy') }}</th>
                    <th width="50px"></th>
                </tr>
                </thead>
                <tbody class="bg-white">
                @if($initiating)
                    @forelse ($initiating->stakeholders as $stakeholder)
                        <tr>
                            <td class="fw-bold text-dark">
                                {{ $stakeholder->contact->full_name ?? 'N/A' }}
                            </td>
                            <td>{{ $stakeholder->stakeholder_responsibility }}</td>

                            <td class="text-center">
                                @if(in_array($stakeholder->stakeholder_interest, ['Alto', 'High', 'High', 'Elevado'], true))
                                    <span class="badge bg-warning text-dark">{{ $stakeholder->stakeholder_interest }}</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $stakeholder->stakeholder_interest }}</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(in_array($stakeholder->stakeholder_power, ['Alto', 'High', 'High', 'Elevado'], true))
                                    <span class="badge bg-danger">{{ $stakeholder->stakeholder_power }}</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $stakeholder->stakeholder_power }}</span>
                                @endif
                            </td>

                            <td>{{ $stakeholder->stakeholder_strategy }}</td>

                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-link text-dark p-0 me-2"
                                        onclick="openEditModal({{ $stakeholder }})"
                                        title="{{ __('projects/views.show.stakeholder.edit') }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <button type="button" class="btn btn-xs btn-link text-danger p-0 border-0"
                                        onclick="openDeleteModal({{ $stakeholder }})"
                                        title="{{ __('projects/views.show.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox display-6 d-block mb-2 opacity-25"></i>
                                {{ __('projects/views.show.stakeholder.table.empty') }}
                            </td>
                        </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            {{ __('projects/views.show.stakeholder.table.save_charter_hint') }}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

