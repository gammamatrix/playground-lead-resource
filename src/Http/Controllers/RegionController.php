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
use Playground\Lead\Models\Region;
use Playground\Lead\Resource\Http\Requests\Region\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Region\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Region\EditRequest;
use Playground\Lead\Resource\Http\Requests\Region\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Region\LockRequest;
use Playground\Lead\Resource\Http\Requests\Region\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Region\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Region\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Region\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Region\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Region as RegionResource;
use Playground\Lead\Resource\Http\Resources\RegionCollection;

/**
 * \Playground\Lead\Resource\Http\Controllers\RegionController
 */
class RegionController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Region',
        'model_label_plural' => 'Regions',
        'model_route' => 'playground.lead.resource.regions',
        'model_slug' => 'region',
        'model_slug_plural' => 'regions',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:region',
        'table' => 'lead_regions',
        'view' => 'playground-lead-resource::region',
    ];

    /**
     * Create information or form for the Region resource in storage.
     *
     * @route GET /resource/lead/regions/create playground.lead.resource.regions.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|RegionResource|View {

        $validated = $request->validated();

        $region = new Region($validated);

        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $region = new Region($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $region,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $region->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view($this->getViewPath('region', 'form'), $data);
    }

    /**
     * Edit information for the Region resource in storage.
     *
     * @route GET /resource/lead/regions/edit playground.lead.resource.regions.edit
     */
    public function edit(
        Region $region,
        EditRequest $request
    ): JsonResponse|RegionResource|View {

        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $region->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $region,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $region->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::region/form',
            $data
        );
    }

    /**
     * Remove the Region resource from storage.
     *
     * @route DELETE /resource/lead/regions/{region} playground.lead.resource.regions.destroy
     */
    public function destroy(
        Region $region,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

        if (empty($validated['force'])) {
            $region->delete();
        } else {
            $region->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.regions'));
    }

    /**
     * Lock the Region resource in storage.
     *
     * @route PUT /resource/lead/regions/{region} playground.lead.resource.regions.lock
     */
    public function lock(
        Region $region,
        LockRequest $request
    ): JsonResponse|RedirectResponse|RegionResource {

        $region->setAttribute('locked', true);

        $region->save();

        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.regions.show', ['region' => $region->id]));
    }

    /**
     * Display a listing of Region resources.
     *
     * @route GET /resource/lead/regions playground.lead.resource.regions
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|RegionCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Region::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new RegionCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::region/index',
            $data
        );
    }

    /**
     * Restore the Region resource from the trash.
     *
     * @route PUT /resource/lead/regions/restore/{region} playground.lead.resource.regions.restore
     */
    public function restore(
        Region $region,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|RegionResource {
        $validated = $request->validated();

        $user = $request->user();

        $region->restore();

        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.regions.show', ['region' => $region->id]));
    }

    /**
     * Display the Region resource.
     *
     * @route GET /resource/lead/regions/{region} playground.lead.resource.regions.show
     */
    public function show(
        Region $region,
        ShowRequest $request
    ): JsonResponse|View|RegionResource {
        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $region->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $region,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::region/detail',
            $data
        );
    }

    /**
     * Store a newly created Region resource in storage.
     *
     * @route POST /resource/lead/regions playground.lead.resource.regions.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|RegionResource {
        $validated = $request->validated();

        $user = $request->user();

        $region = new Region($validated);

        $region->created_by_id = $user?->id;

        $region->save();

        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.regions.show', ['region' => $region->id]));
    }

    /**
     * Unlock the Region resource in storage.
     *
     * @route DELETE /resource/lead/regions/lock/{region} playground.lead.resource.regions.unlock
     */
    public function unlock(
        Region $region,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|RegionResource {

        $region->setAttribute('locked', false);

        $region->save();

        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.regions.show', ['region' => $region->id]));
    }

    /**
     * Update the Region resource in storage.
     *
     * @route PATCH /resource/lead/regions/{region} playground.lead.resource.regions.patch
     */
    public function update(
        Region $region,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|RegionResource {
        $validated = $request->validated();

        $user = $request->user();

        $region->modified_by_id = $user?->id;

        $region->update($validated);

        if ($request->expectsJson()) {
            return (new RegionResource($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.regions.show', ['region' => $region->id]));
    }
}
