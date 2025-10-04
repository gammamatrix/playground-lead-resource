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
use Playground\Lead\Models\Opportunity;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

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
     * Create the Opportunity resource in storage.
     *
     * @route GET /resource/lead/opportunities/create playground.lead.resource.opportunities.create
     */
    public function create(
        Requests\Opportunity\CreateRequest $request
    ): JsonResponse|View|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $opportunity = new Opportunity($validated);

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
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

        if (! $request->session()->has('errors')) {
            session()->flashInput($flash);
        }

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/form', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Edit the Opportunity resource in storage.
     *
     * @route GET /resource/lead/opportunities/edit/{opportunity} playground.lead.resource.opportunities.edit
     */
    public function edit(
        Opportunity $opportunity,
        Requests\Opportunity\EditRequest $request
    ): JsonResponse|View|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $flash = $opportunity->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $opportunity->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $opportunity,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        if (! empty($validated['_return_url'])) {
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/form', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Remove the Opportunity resource from storage.
     *
     * @route DELETE /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.destroy
     */
    public function destroy(
        Opportunity $opportunity,
        Requests\Opportunity\DestroyRequest $request
    ): Response|RedirectResponse {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $opportunity->modified_by_id = $user->id;
        }

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

        return redirect(route($packageInfo->model_route()));
    }

    /**
     * Lock the Opportunity resource in storage.
     *
     * @route PUT /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.lock
     */
    public function lock(
        Opportunity $opportunity,
        Requests\Opportunity\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $opportunity->modified_by_id = $user->id;
        }

        $opportunity->locked = true;

        $opportunity->save();

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['opportunity' => $opportunity->id]));
    }

    /**
     * Display a listing of Opportunity resources.
     *
     * @route GET /resource/lead/opportunities playground.lead.resource.opportunities
     */
    public function index(
        Requests\Opportunity\IndexRequest $request
    ): JsonResponse|View|Resources\OpportunityCollection {

        $packageInfo = $this->packageInfo();

        /**
         * @var array{
         *     sort: string|array<mixed>,
         *     filter: array{
         *         trash: string
         *     },
         *     perPage: int
         * } $validated
         */
        $validated = $request->validated();

        $query = Opportunity::addSelect(sprintf('%1$s.*', $packageInfo->table()));

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
            return new Resources\OpportunityCollection($paginator)->response($request);
        }

        $user = $request->user();

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
            'info' => $packageInfo,
        ];

        $data = [
            'paginator' => $paginator,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/index', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Restore the Opportunity resource from the trash.
     *
     * @route PUT /resource/lead/opportunities/restore/{opportunity} playground.lead.resource.opportunities.restore
     */
    public function restore(
        Opportunity $opportunity,
        Requests\Opportunity\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $opportunity->modified_by_id = $user?->id;

        $opportunity->restore();

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['opportunity' => $opportunity->id]));
    }

    /**
     * Display the Opportunity resource.
     *
     * @route GET /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.show
     */
    public function show(
        Opportunity $opportunity,
        Requests\Opportunity\ShowRequest $request
    ): JsonResponse|View|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $opportunity->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $opportunity,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/detail', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Store a newly created API Opportunity resource in storage.
     *
     * @route POST /resource/lead/opportunities playground.lead.resource.opportunities.post
     */
    public function store(
        Requests\Opportunity\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $opportunity = new Opportunity($validated);

        $opportunity->created_by_id = $user?->id;

        $opportunity->save();

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request)->setStatusCode(201);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['opportunity' => $opportunity->id]));
    }

    /**
     * Unlock the Opportunity resource in storage.
     *
     * @route DELETE /resource/lead/opportunities/lock/{opportunity} playground.lead.resource.opportunities.unlock
     */
    public function unlock(
        Opportunity $opportunity,
        Requests\Opportunity\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $opportunity->locked = false;

        $opportunity->modified_by_id = $user?->id;

        $opportunity->save();

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['opportunity' => $opportunity->id]));
    }

    /**
     * Update the Opportunity resource in storage.
     *
     * @route PATCH /resource/lead/opportunities/{opportunity} playground.lead.resource.opportunities.patch
     */
    public function update(
        Opportunity $opportunity,
        Requests\Opportunity\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Opportunity {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $opportunity->modified_by_id = $user?->id;

        $opportunity->update($validated);

        if ($request->expectsJson()) {
            return new Resources\Opportunity($opportunity)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route(sprintf(
            '%1$s.show',
            $packageInfo->model_route()
        ), ['opportunity' => $opportunity->id]));
    }
}
