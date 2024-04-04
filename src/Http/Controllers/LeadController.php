<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Lead\Resource\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Playground\Lead\Models\Lead;
use Playground\Lead\Resource\Http\Requests\Lead\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Lead\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Lead\EditRequest;
use Playground\Lead\Resource\Http\Requests\Lead\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Lead\LockRequest;
use Playground\Lead\Resource\Http\Requests\Lead\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Lead\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Lead\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Lead\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Lead\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Lead as LeadResource;
use Playground\Lead\Resource\Http\Resources\LeadCollection;

/**
 * \Playground\Lead\Resource\Http\Controllers\LeadController
 */
class LeadController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Lead',
        'model_label_plural' => 'Leads',
        'model_route' => 'playground.lead.resource.leads',
        'model_slug' => 'lead',
        'model_slug_plural' => 'leads',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:lead',
        'table' => 'lead_leads',
        'view' => 'playground-lead-resource::lead',
    ];

    /**
     * Create information or form for the Lead resource in storage.
     *
     * @route GET /resource/lead/leads/create playground.lead.resource.leads.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|LeadResource|View {

        $validated = $request->validated();

        $lead = new Lead($validated);

        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $lead = new Lead($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $lead,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $lead->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view($this->getViewPath('lead', 'form'), $data);
    }

    /**
     * Edit information for the Lead resource in storage.
     *
     * @route GET /resource/lead/leads/edit playground.lead.resource.leads.edit
     */
    public function edit(
        Lead $lead,
        EditRequest $request
    ): JsonResponse|LeadResource|View {

        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $lead->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $lead,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $lead->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::lead/form',
            $data
        );
    }

    /**
     * Remove the Lead resource from storage.
     *
     * @route DELETE /resource/lead/leads/{lead} playground.lead.resource.leads.destroy
     */
    public function destroy(
        Lead $lead,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

        if (empty($validated['force'])) {
            $lead->delete();
        } else {
            $lead->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.leads'));
    }

    /**
     * Lock the Lead resource in storage.
     *
     * @route PUT /resource/lead/leads/{lead} playground.lead.resource.leads.lock
     */
    public function lock(
        Lead $lead,
        LockRequest $request
    ): JsonResponse|RedirectResponse|LeadResource {

        $lead->setAttribute('locked', true);

        $lead->save();

        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.leads.show', ['lead' => $lead->id]));
    }

    /**
     * Display a listing of Lead resources.
     *
     * @route GET /resource/lead/leads playground.lead.resource.leads
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|LeadCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Lead::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

        $query->sort($validated['sort'] ?? null);

        if (! empty($validated['filter']) && is_array($validated['filter'])) {
            $query->filterTrash($validated['filter']['trash'] ?? null);

            $query->filterIds(
                $request->getPaginationIds(),
                $validated
            );

            $query->filterFlags(
                $request->getPaginationFlags(),
                $validated
            );

            $query->filterDates(
                $request->getPaginationDates(),
                $validated
            );

            $query->filterColumns(
                $request->getPaginationColumns(),
                $validated
            );
        }

        $perPage = ! empty($validated['perPage']) && is_int($validated['perPage']) ? $validated['perPage'] : null;
        $paginator = $query->paginate($perPage);

        $paginator->appends($validated);

        if ($request->expectsJson()) {
            return (new LeadCollection($paginator))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta = [
            'session_user_id' => $user?->id,
            'columns' => $request->getPaginationColumns(),
            'dates' => $request->getPaginationDates(),
            'flags' => $request->getPaginationFlags(),
            'ids' => $request->getPaginationIds(),
            'rules' => $request->rules(),
            'sortable' => $request->getSortable(),
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'paginator' => $paginator,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::lead/index',
            $data
        );
    }

    /**
     * Restore the Lead resource from the trash.
     *
     * @route PUT /resource/lead/leads/restore/{lead} playground.lead.resource.leads.restore
     */
    public function restore(
        Lead $lead,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|LeadResource {
        $validated = $request->validated();

        $user = $request->user();

        $lead->restore();

        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.leads.show', ['lead' => $lead->id]));
    }

    /**
     * Display the Lead resource.
     *
     * @route GET /resource/lead/leads/{lead} playground.lead.resource.leads.show
     */
    public function show(
        Lead $lead,
        ShowRequest $request
    ): JsonResponse|View|LeadResource {
        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $lead->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $lead,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::lead/detail',
            $data
        );
    }

    /**
     * Store a newly created Lead resource in storage.
     *
     * @route POST /resource/lead/leads playground.lead.resource.leads.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|LeadResource {
        $validated = $request->validated();

        $user = $request->user();

        $lead = new Lead($validated);

        $lead->created_by_id = $user?->id;

        $lead->save();

        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.leads.show', ['lead' => $lead->id]));
    }

    /**
     * Unlock the Lead resource in storage.
     *
     * @route DELETE /resource/lead/leads/lock/{lead} playground.lead.resource.leads.unlock
     */
    public function unlock(
        Lead $lead,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|LeadResource {

        $lead->setAttribute('locked', false);

        $lead->save();

        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.leads.show', ['lead' => $lead->id]));
    }

    /**
     * Update the Lead resource in storage.
     *
     * @route PATCH /resource/lead/leads/{lead} playground.lead.resource.leads.patch
     */
    public function update(
        Lead $lead,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|LeadResource {
        $validated = $request->validated();

        $user = $request->user();

        $lead->modified_by_id = $user?->id;

        $lead->update($validated);

        if ($request->expectsJson()) {
            return (new LeadResource($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.leads.show', ['lead' => $lead->id]));
    }
}
