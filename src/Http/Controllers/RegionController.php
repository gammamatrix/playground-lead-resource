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
use Playground\Lead\Models\Region;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

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
     * Create the Region resource in storage.
     *
     * @route GET /resource/lead/regions/create playground.lead.resource.regions.create
     */
    public function create(
        Requests\Region\CreateRequest $request
    ): JsonResponse|View|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        $region = new Region($validated);

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
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
            'data' => $region,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $region->toArray();

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
     * Edit the Region resource in storage.
     *
     * @route GET /resource/lead/regions/edit/{region} playground.lead.resource.regions.edit
     */
    public function edit(
        Region $region,
        Requests\Region\EditRequest $request
    ): JsonResponse|View|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $region->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

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
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Region resource from storage.
     *
     * @route DELETE /resource/lead/regions/{region} playground.lead.resource.regions.destroy
     */
    public function destroy(
        Region $region,
        Requests\Region\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $region->modified_by_id = $user->id;
        }

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

        return redirect(route($this->packageInfo['model_route']));
    }

    /**
     * Lock the Region resource in storage.
     *
     * @route PUT /resource/lead/regions/{region} playground.lead.resource.regions.lock
     */
    public function lock(
        Region $region,
        Requests\Region\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $region->modified_by_id = $user->id;
        }

        $region->locked = true;

        $region->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $region->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
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
        ), ['region' => $region->id]));
    }

    /**
     * Display a listing of Region resources.
     *
     * @route GET /resource/lead/regions playground.lead.resource.regions
     */
    public function index(
        Requests\Region\IndexRequest $request
    ): JsonResponse|View|Resources\RegionCollection {

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
            return (new Resources\RegionCollection($paginator))->response($request);
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
     * Restore the Region resource from the trash.
     *
     * @route PUT /resource/lead/regions/restore/{region} playground.lead.resource.regions.restore
     */
    public function restore(
        Region $region,
        Requests\Region\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $region->modified_by_id = $user->id;
        }

        $region->restore();

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
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
        ), ['region' => $region->id]));
    }

    /**
     * Display the Region resource.
     *
     * @route GET /resource/lead/regions/{region} playground.lead.resource.regions.show
     */
    public function show(
        Region $region,
        Requests\Region\ShowRequest $request
    ): JsonResponse|View|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $region->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $region,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Region resource in storage.
     *
     * @route POST /resource/lead/regions playground.lead.resource.regions.post
     */
    public function store(
        Requests\Region\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        $region = new Region($validated);

        if ($user?->id) {
            $region->created_by_id = $user->id;
        }

        $region->save();

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
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
        ), ['region' => $region->id]));
    }

    /**
     * Unlock the Region resource in storage.
     *
     * @route DELETE /resource/lead/regions/lock/{region} playground.lead.resource.regions.unlock
     */
    public function unlock(
        Region $region,
        Requests\Region\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        $region->locked = false;

        if ($user?->id) {
            $region->modified_by_id = $user->id;
        }

        $region->save();

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
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
        ), ['region' => $region->id]));
    }

    /**
     * Update the Region resource in storage.
     *
     * @route PATCH /resource/lead/regions/{region} playground.lead.resource.regions.patch
     */
    public function update(
        Region $region,
        Requests\Region\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Region {

        $validated = $request->validated();

        $user = $request->user();

        $region->update($validated);

        if ($user?->id) {
            $region->modified_by_id = $user->id;
        }

        if ($request->expectsJson()) {
            return (new Resources\Region($region))->additional(['meta' => [
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
        ), ['region' => $region->id]));
    }
}
