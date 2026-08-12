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
use Playground\Lead\Models\Campaign;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

/**
 * \Playground\Lead\Resource\Http\Controllers\CampaignController
 */
class CampaignController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Campaign',
        'model_label_plural' => 'Campaigns',
        'model_route' => 'playground.lead.resource.campaigns',
        'model_slug' => 'campaign',
        'model_slug_plural' => 'campaigns',
        'model_variable' => 'campaign',
        'model_variable_plural' => 'campaigns',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:campaign',
        'table' => 'lead_campaigns',
        'view' => 'playground-lead-resource::campaign',
    ];

    /**
     * Create the Campaign resource in storage.
     *
     * @route GET /resource/lead/campaigns/create playground.lead.resource.campaigns.create
     */
    public function create(
        Requests\Campaign\CreateRequest $request
    ): JsonResponse|View|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $campaign = new Campaign($validated);

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
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
            'data' => $campaign,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $campaign->toArray();

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
     * Edit the Campaign resource in storage.
     *
     * @route GET /resource/lead/campaigns/edit/{campaign} playground.lead.resource.campaigns.edit
     */
    public function edit(
        Campaign $campaign,
        Requests\Campaign\EditRequest $request
    ): JsonResponse|View|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $flash = $campaign->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $campaign->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $campaign,
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
     * Remove the Campaign resource from storage.
     *
     * @route DELETE /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.destroy
     */
    public function destroy(
        Campaign $campaign,
        Requests\Campaign\DestroyRequest $request
    ): Response|RedirectResponse {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $campaign->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $campaign->delete();
        } else {
            $campaign->forceDelete();
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
     * Lock the Campaign resource in storage.
     *
     * @route PUT /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.lock
     */
    public function lock(
        Campaign $campaign,
        Requests\Campaign\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $campaign->modified_by_id = $user->id;
        }

        $campaign->locked = true;

        $campaign->save();

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
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
        ), ['campaign' => $campaign->id]));
    }

    /**
     * Display a listing of Campaign resources.
     *
     * @route GET /resource/lead/campaigns playground.lead.resource.campaigns
     */
    public function index(
        Requests\Campaign\IndexRequest $request
    ): JsonResponse|View|Resources\CampaignCollection {

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

        $query = Campaign::addSelect(sprintf('%1$s.*', $packageInfo->table()));

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
            return new Resources\CampaignCollection($paginator)->response($request);
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
     * Restore the Campaign resource from the trash.
     *
     * @route PUT /resource/lead/campaigns/restore/{campaign} playground.lead.resource.campaigns.restore
     */
    public function restore(
        Campaign $campaign,
        Requests\Campaign\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $campaign->modified_by_id = $user?->id;

        $campaign->restore();

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
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
        ), ['campaign' => $campaign->id]));
    }

    /**
     * Display the Campaign resource.
     *
     * @route GET /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.show
     */
    public function show(
        Campaign $campaign,
        Requests\Campaign\ShowRequest $request
    ): JsonResponse|View|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $campaign->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $campaign,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/detail', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Store a newly created API Campaign resource in storage.
     *
     * @route POST /resource/lead/campaigns playground.lead.resource.campaigns.post
     */
    public function store(
        Requests\Campaign\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $campaign = new Campaign($validated);

        $campaign->created_by_id = $user?->id;

        $campaign->save();

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
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
        ), ['campaign' => $campaign->id]));
    }

    /**
     * Unlock the Campaign resource in storage.
     *
     * @route DELETE /resource/lead/campaigns/lock/{campaign} playground.lead.resource.campaigns.unlock
     */
    public function unlock(
        Campaign $campaign,
        Requests\Campaign\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $campaign->locked = false;

        $campaign->modified_by_id = $user?->id;

        $campaign->save();

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
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
        ), ['campaign' => $campaign->id]));
    }

    /**
     * Update the Campaign resource in storage.
     *
     * @route PATCH /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.patch
     */
    public function update(
        Campaign $campaign,
        Requests\Campaign\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Campaign {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $campaign->modified_by_id = $user?->id;

        $campaign->update($validated);

        if ($request->expectsJson()) {
            return new Resources\Campaign($campaign)->additional(['meta' => [
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
        ), ['campaign' => $campaign->id]));
    }
}
