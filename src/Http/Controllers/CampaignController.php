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
use Playground\Lead\Models\Campaign;
use Playground\Lead\Resource\Http\Requests\Campaign\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\EditRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\LockRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Campaign\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Campaign as CampaignResource;
use Playground\Lead\Resource\Http\Resources\CampaignCollection;

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
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:campaign',
        'table' => 'lead_campaigns',
        'view' => 'playground-lead-resource::campaign',
    ];

    /**
     * Create information or form for the Campaign resource in storage.
     *
     * @route GET /resource/lead/campaigns/create playground.lead.resource.campaigns.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|CampaignResource|View {

        $validated = $request->validated();

        $campaign = new Campaign($validated);

        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $campaign = new Campaign($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
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

        session()->flashInput($flash);

        return view($this->getViewPath('campaign', 'form'), $data);
    }

    /**
     * Edit information for the Campaign resource in storage.
     *
     * @route GET /resource/lead/campaigns/edit playground.lead.resource.campaigns.edit
     */
    public function edit(
        Campaign $campaign,
        EditRequest $request
    ): JsonResponse|CampaignResource|View {

        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $campaign->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $campaign,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $campaign->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::campaign/form',
            $data
        );
    }

    /**
     * Remove the Campaign resource from storage.
     *
     * @route DELETE /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.destroy
     */
    public function destroy(
        Campaign $campaign,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

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

        return redirect(route('playground.lead.resource.campaigns'));
    }

    /**
     * Lock the Campaign resource in storage.
     *
     * @route PUT /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.lock
     */
    public function lock(
        Campaign $campaign,
        LockRequest $request
    ): JsonResponse|RedirectResponse|CampaignResource {

        $campaign->setAttribute('locked', true);

        $campaign->save();

        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.campaigns.show', ['campaign' => $campaign->id]));
    }

    /**
     * Display a listing of Campaign resources.
     *
     * @route GET /resource/lead/campaigns playground.lead.resource.campaigns
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|CampaignCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Campaign::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new CampaignCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::campaign/index',
            $data
        );
    }

    /**
     * Restore the Campaign resource from the trash.
     *
     * @route PUT /resource/lead/campaigns/restore/{campaign} playground.lead.resource.campaigns.restore
     */
    public function restore(
        Campaign $campaign,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|CampaignResource {
        $validated = $request->validated();

        $user = $request->user();

        $campaign->restore();

        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.campaigns.show', ['campaign' => $campaign->id]));
    }

    /**
     * Display the Campaign resource.
     *
     * @route GET /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.show
     */
    public function show(
        Campaign $campaign,
        ShowRequest $request
    ): JsonResponse|View|CampaignResource {
        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $campaign->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $campaign,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::campaign/detail',
            $data
        );
    }

    /**
     * Store a newly created Campaign resource in storage.
     *
     * @route POST /resource/lead/campaigns playground.lead.resource.campaigns.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|CampaignResource {
        $validated = $request->validated();

        $user = $request->user();

        $campaign = new Campaign($validated);

        $campaign->created_by_id = $user?->id;

        $campaign->save();

        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.campaigns.show', ['campaign' => $campaign->id]));
    }

    /**
     * Unlock the Campaign resource in storage.
     *
     * @route DELETE /resource/lead/campaigns/lock/{campaign} playground.lead.resource.campaigns.unlock
     */
    public function unlock(
        Campaign $campaign,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|CampaignResource {

        $campaign->setAttribute('locked', false);

        $campaign->save();

        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.campaigns.show', ['campaign' => $campaign->id]));
    }

    /**
     * Update the Campaign resource in storage.
     *
     * @route PATCH /resource/lead/campaigns/{campaign} playground.lead.resource.campaigns.patch
     */
    public function update(
        Campaign $campaign,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|CampaignResource {
        $validated = $request->validated();

        $user = $request->user();

        $campaign->modified_by_id = $user?->id;

        $campaign->update($validated);

        if ($request->expectsJson()) {
            return (new CampaignResource($campaign))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.campaigns.show', ['campaign' => $campaign->id]));
    }
}
