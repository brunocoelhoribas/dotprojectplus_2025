<?php

namespace App\Http\Controllers\Planning\Communication;

use App\Http\Controllers\Controller;
use App\Models\Planning\Communication\CommunicationIssuing;
use App\Models\Planning\Communication\CommunicationReceptor;
use App\Models\Project\Project;
use App\Models\Planning\Communication\Communication;
use App\Models\Planning\Communication\CommunicationChannel;
use App\Models\Planning\Communication\CommunicationFrequency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommunicationController extends Controller {
    private function successResponse($key): JsonResponse {
        return response()->json(['success' => true, 'message' => __($key)]);
    }


    public function store(Request $request, Project $project): JsonResponse {
        $data = $request->validate([
            'communication_title' => 'required|string|max:255',
            'communication_information' => 'nullable|string',
            'communication_channel_id' => 'nullable|integer',
            'communication_frequency_id' => 'nullable|integer',
            'communication_restrictions' => 'nullable|string',
            'communication_responsible_authorization' => 'nullable|integer',
            'issuers' => 'nullable|array',
            'issuers.*' => 'integer',
            'receptors' => 'nullable|array',
            'receptors.*' => 'integer',
        ]);

        $data['communication_project_id'] = $project->project_id;
        $data['communication_date'] = date('Y-m-d');
        $communication = Communication::create($data);

        if (!empty($request->issuers)) {
            foreach ($request->issuers as $userId) {
                CommunicationIssuing::create([
                    'communication_id' => $communication->communication_id,
                    'communication_stakeholder_id' => $userId
                ]);
            }
        }

        if (!empty($request->receptors)) {
            foreach ($request->receptors as $userId) {
                CommunicationReceptor::create([
                    'communication_id' => $communication->communication_id,
                    'communication_stakeholder_id' => $userId
                ]);
            }
        }

        return $this->successResponse('planning/messages.communication.event_created');
    }

    public function show(Project $project, Communication $communication): JsonResponse {
        $communication->load(['issuers', 'receptors']);

        $data = $communication->toArray();
        $data['issuer_ids'] = $communication->issuers->pluck('communication_stakeholder_id');
        $data['receptor_ids'] = $communication->receptors->pluck('communication_stakeholder_id');

        return response()->json($data);
    }

    public function update(Request $request, Project $project, Communication $communication): JsonResponse {
        $data = $request->validate([
            'communication_title' => 'required|string|max:255',
            'communication_information' => 'nullable|string',
            'communication_channel_id' => 'nullable|integer',
            'communication_frequency_id' => 'nullable|integer',
            'communication_restrictions' => 'nullable|string',
            'communication_responsible_authorization' => 'nullable|integer',
            'issuers' => 'nullable|array',
            'receptors' => 'nullable|array',
        ]);

        $communication->update($data);

        CommunicationIssuing::where('communication_id', $communication->communication_id)->delete();
        if (!empty($request->issuers)) {
            foreach ($request->issuers as $userId) {
                CommunicationIssuing::create([
                    'communication_id' => $communication->communication_id,
                    'communication_stakeholder_id' => $userId
                ]);
            }
        }

        CommunicationReceptor::where('communication_id', $communication->communication_id)->delete();
        if (!empty($request->receptors)) {
            foreach ($request->receptors as $userId) {
                CommunicationReceptor::create([
                    'communication_id' => $communication->communication_id,
                    'communication_stakeholder_id' => $userId
                ]);
            }
        }

        return $this->successResponse('planning/messages.communication.event_updated');
    }

    public function destroy(Project $project, Communication $communication): JsonResponse {
        $communication->delete();
        return $this->successResponse('planning/messages.communication.event_deleted');
    }


    public function storeChannel(Request $request, Project $project): JsonResponse {
        $request->validate(['communication_channel' => 'required|string|max:255']);
        CommunicationChannel::create($request->only('communication_channel'));
        return $this->successResponse('planning/messages.communication.channel_created');
    }

    public function destroyChannel(Request $request, Project $project): JsonResponse {
        $request->validate([
            'delete_channel_id' => 'required|integer|exists:dotp_communication_channel,communication_channel_id'
        ]);

        $channelId = $request->input('delete_channel_id');

        $isInUse = Communication::where('communication_channel_id', $channelId)->exists();
        if ($isInUse) {
            return response()->json([
                'success' => false,
                'message' => __('planning/messages.communication.channel_in_use')
            ], 422);
        }

        CommunicationChannel::where('communication_channel_id', $channelId)->delete();

        return $this->successResponse('planning/messages.communication.channel_deleted');
    }

    public function storeFrequency(Request $request, Project $project): JsonResponse {
        $request->validate(['communication_frequency' => 'required|string|max:255']);

        $hasDate = $request->has('communication_frequency_hasdate') ? 1 : 0;

        CommunicationFrequency::create([
            'communication_frequency' => $request->input('communication_frequency'),
            'communication_frequency_hasdate' => $hasDate
        ]);

        return $this->successResponse('planning/messages.communication.frequency_created');
    }

    public function destroyFrequency(Request $request, Project $project): JsonResponse {
        $request->validate([
            'delete_frequency_id' => 'required|integer|exists:dotp_communication_frequency,communication_frequency_id'
        ]);

        $freqId = $request->input('delete_frequency_id');

        $isInUse = Communication::where('communication_frequency_id', $freqId)->exists();
        if ($isInUse) {
            return response()->json([
                'success' => false,
                'message' => __('planning/messages.communication.frequency_in_use')
            ], 422);
        }

        CommunicationFrequency::where('communication_frequency_id', $freqId)->delete();

        return $this->successResponse('planning/messages.communication.frequency_deleted');
    }
}
