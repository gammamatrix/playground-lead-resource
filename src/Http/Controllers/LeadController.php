<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Lead\Resource\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Playground\Lead\Models\Lead;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

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
     * Create the Lead resource in storage.
     *
     * @route GET /resource/lead/leads/create playground.lead.resource.leads.create
     */
    public function create(
        Requests\Lead\CreateRequest $request
    ): JsonResponse|View|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        $lead = new Lead($validated);

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

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

        if (! $request->session()->has('errors')) {
            session()->flashInput($flash);
        }

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Edit the Lead resource in storage.
     *
     * @route GET /resource/lead/leads/edit/{lead} playground.lead.resource.leads.edit
     */
    public function edit(
        Lead $lead,
        Requests\Lead\EditRequest $request
    ): JsonResponse|View|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $lead->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

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
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Lead resource from storage.
     *
     * @route DELETE /resource/lead/leads/{lead} playground.lead.resource.leads.destroy
     */
    public function destroy(
        Lead $lead,
        Requests\Lead\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $lead->modified_by_id = $user->id;
        }

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

        return redirect(route($this->packageInfo['model_route']));
    }

    /**
     * Lock the Lead resource in storage.
     *
     * @route PUT /resource/lead/leads/{lead} playground.lead.resource.leads.lock
     */
    public function lock(
        Lead $lead,
        Requests\Lead\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $lead->modified_by_id = $user->id;
        }

        $lead->locked = true;

        $lead->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $lead->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['lead' => $lead->id]));
    }

    /**
     * Display a listing of Lead resources.
     *
     * @route GET /resource/lead/leads playground.lead.resource.leads
     */
    public function index(
        Requests\Lead\IndexRequest $request
    ): JsonResponse|View|Resources\LeadCollection {

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
            return (new Resources\LeadCollection($paginator))->response($request);
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

        return view(sprintf('%1$s/index', $this->packageInfo['view']), $data);
    }

    /**
     * Restore the Lead resource from the trash.
     *
     * @route PUT /resource/lead/leads/restore/{lead} playground.lead.resource.leads.restore
     */
    public function restore(
        Lead $lead,
        Requests\Lead\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $lead->modified_by_id = $user->id;
        }

        $lead->restore();

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['lead' => $lead->id]));
    }

    /**
     * Display the Lead resource.
     *
     * @route GET /resource/lead/leads/{lead} playground.lead.resource.leads.show
     */
    public function show(
        Lead $lead,
        Requests\Lead\ShowRequest $request
    ): JsonResponse|View|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $lead->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $lead,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Lead resource in storage.
     *
     * @route POST /resource/lead/leads playground.lead.resource.leads.post
     */
    public function store(
        Requests\Lead\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        $lead = new Lead($validated);

        if ($user?->id) {
            $lead->created_by_id = $user->id;
        }

        $lead->save();

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['lead' => $lead->id]));
    }

    /**
     * Unlock the Lead resource in storage.
     *
     * @route DELETE /resource/lead/leads/lock/{lead} playground.lead.resource.leads.unlock
     */
    public function unlock(
        Lead $lead,
        Requests\Lead\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        $lead->locked = false;

        if ($user?->id) {
            $lead->modified_by_id = $user->id;
        }

        $lead->save();

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['lead' => $lead->id]));
    }

    /**
     * Update the Lead resource in storage.
     *
     * @route PATCH /resource/lead/leads/{lead} playground.lead.resource.leads.patch
     */
    public function update(
        Lead $lead,
        Requests\Lead\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Lead {

        $validated = $request->validated();

        $user = $request->user();

        $lead->update($validated);

        if ($user?->id) {
            $lead->modified_by_id = $user->id;
        }

        if ($request->expectsJson()) {
            return (new Resources\Lead($lead))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $this->packageInfo['model_route']
        ), ['lead' => $lead->id]));
    }
}
