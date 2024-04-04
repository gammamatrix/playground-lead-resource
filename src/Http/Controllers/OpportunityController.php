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
use Playground\Lead\Models\Opportunity;
use Playground\Lead\Resource\Http\Requests\Opportunity\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\EditRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\LockRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Opportunity\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Opportunity as OpportunityResource;
use Playground\Lead\Resource\Http\Resources\OpportunityCollection;

/**
 * \Playground\Lead\Resource\Http\Controllers\OpportunityController
 */
class OpportunityController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Opportunity',
        'model_label_plural' => 'Opportunities',
        'model_route' => 'playground.lead.resource.opportunities',
        'model_slug' => 'opportunity',
        'model_slug_plural' => 'opportunities',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:opportunity',
        'table' => 'lead_opportunities',
        'view' => 'playground-lead-resource::opportunity',
    ];

    /**
     * Create information or form for the Opportunity resource in storage.
     *
     * @route GET /resource/lead/opportunities/create playground.lead.resource.opportunities.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|OpportunityResource|View {

        $validated = $request->validated();

        $opportunity = new Opportunity($validated);

        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $opportunity = new Opportunity($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $opportunity,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $opportunity->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view($this->getViewPath('opportunity', 'form'), $data);
    }

    /**
     * Edit information for the Opportunity resource in storage.
     *
     * @route GET /resource/lead/opportunities/edit playground.lead.resource.opportunities.edit
     */
    public function edit(
        Opportunity $opportunity,
        EditRequest $request
    ): JsonResponse|OpportunityResource|View {

        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $opportunity->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $opportunity,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $opportunity->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::opportunity/form',
            $data
        );
    }

    /**
     * Remove the Opportunity resource from storage.
     *
     * @route DELETE /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.destroy
     */
    public function destroy(
        Opportunity $opportunity,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

        if (empty($validated['force'])) {
            $opportunity->delete();
        } else {
            $opportunity->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.opportunities'));
    }

    /**
     * Lock the Opportunity resource in storage.
     *
     * @route PUT /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.lock
     */
    public function lock(
        Opportunity $opportunity,
        LockRequest $request
    ): JsonResponse|RedirectResponse|OpportunityResource {

        $opportunity->setAttribute('locked', true);

        $opportunity->save();

        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.opportunities.show', ['opportunity' => $opportunity->id]));
    }

    /**
     * Display a listing of Opportunity resources.
     *
     * @route GET /resource/lead/opportunities playground.lead.resource.opportunities
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|OpportunityCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Opportunity::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new OpportunityCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::opportunity/index',
            $data
        );
    }

    /**
     * Restore the Opportunity resource from the trash.
     *
     * @route PUT /resource/lead/opportunities/restore/{opportunity} playground.lead.resource.opportunities.restore
     */
    public function restore(
        Opportunity $opportunity,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|OpportunityResource {
        $validated = $request->validated();

        $user = $request->user();

        $opportunity->restore();

        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.opportunities.show', ['opportunity' => $opportunity->id]));
    }

    /**
     * Display the Opportunity resource.
     *
     * @route GET /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.show
     */
    public function show(
        Opportunity $opportunity,
        ShowRequest $request
    ): JsonResponse|View|OpportunityResource {
        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $opportunity->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $opportunity,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::opportunity/detail',
            $data
        );
    }

    /**
     * Store a newly created Opportunity resource in storage.
     *
     * @route POST /resource/lead/opportunities playground.lead.resource.opportunities.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|OpportunityResource {
        $validated = $request->validated();

        $user = $request->user();

        $opportunity = new Opportunity($validated);

        $opportunity->created_by_id = $user?->id;

        $opportunity->save();

        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.opportunities.show', ['opportunity' => $opportunity->id]));
    }

    /**
     * Unlock the Opportunity resource in storage.
     *
     * @route DELETE /resource/lead/opportunities/lock/{opportunity} playground.lead.resource.opportunities.unlock
     */
    public function unlock(
        Opportunity $opportunity,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|OpportunityResource {

        $opportunity->setAttribute('locked', false);

        $opportunity->save();

        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.opportunities.show', ['opportunity' => $opportunity->id]));
    }

    /**
     * Update the Opportunity resource in storage.
     *
     * @route PATCH /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.patch
     */
    public function update(
        Opportunity $opportunity,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|OpportunityResource {
        $validated = $request->validated();

        $user = $request->user();

        $opportunity->modified_by_id = $user?->id;

        $opportunity->update($validated);

        if ($request->expectsJson()) {
            return (new OpportunityResource($opportunity))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.opportunities.show', ['opportunity' => $opportunity->id]));
    }
}
