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
use Playground\Lead\Models\Source;
use Playground\Lead\Resource\Http\Requests\Source\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Source\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Source\EditRequest;
use Playground\Lead\Resource\Http\Requests\Source\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Source\LockRequest;
use Playground\Lead\Resource\Http\Requests\Source\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Source\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Source\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Source\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Source\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Source as SourceResource;
use Playground\Lead\Resource\Http\Resources\SourceCollection;

/**
 * \Playground\Lead\Resource\Http\Controllers\SourceController
 */
class SourceController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Source',
        'model_label_plural' => 'Sources',
        'model_route' => 'playground.lead.resource.sources',
        'model_slug' => 'source',
        'model_slug_plural' => 'sources',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:source',
        'table' => 'lead_sources',
        'view' => 'playground-lead-resource::source',
    ];

    /**
     * Create information or form for the Source resource in storage.
     *
     * @route GET /resource/lead/sources/create playground.lead.resource.sources.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|SourceResource|View {

        $validated = $request->validated();

        $source = new Source($validated);

        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $source = new Source($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $source,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $source->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view($this->getViewPath('source', 'form'), $data);
    }

    /**
     * Edit information for the Source resource in storage.
     *
     * @route GET /resource/lead/sources/edit playground.lead.resource.sources.edit
     */
    public function edit(
        Source $source,
        EditRequest $request
    ): JsonResponse|SourceResource|View {

        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $source->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $source,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $source->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::source/form',
            $data
        );
    }

    /**
     * Remove the Source resource from storage.
     *
     * @route DELETE /resource/lead/sources/{source} playground.lead.resource.sources.destroy
     */
    public function destroy(
        Source $source,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

        if (empty($validated['force'])) {
            $source->delete();
        } else {
            $source->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.sources'));
    }

    /**
     * Lock the Source resource in storage.
     *
     * @route PUT /resource/lead/sources/{source} playground.lead.resource.sources.lock
     */
    public function lock(
        Source $source,
        LockRequest $request
    ): JsonResponse|RedirectResponse|SourceResource {

        $source->setAttribute('locked', true);

        $source->save();

        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.sources.show', ['source' => $source->id]));
    }

    /**
     * Display a listing of Source resources.
     *
     * @route GET /resource/lead/sources playground.lead.resource.sources
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|SourceCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Source::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new SourceCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::source/index',
            $data
        );
    }

    /**
     * Restore the Source resource from the trash.
     *
     * @route PUT /resource/lead/sources/restore/{source} playground.lead.resource.sources.restore
     */
    public function restore(
        Source $source,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|SourceResource {
        $validated = $request->validated();

        $user = $request->user();

        $source->restore();

        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.sources.show', ['source' => $source->id]));
    }

    /**
     * Display the Source resource.
     *
     * @route GET /resource/lead/sources/{source} playground.lead.resource.sources.show
     */
    public function show(
        Source $source,
        ShowRequest $request
    ): JsonResponse|View|SourceResource {
        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $source->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $source,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::source/detail',
            $data
        );
    }

    /**
     * Store a newly created Source resource in storage.
     *
     * @route POST /resource/lead/sources playground.lead.resource.sources.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|SourceResource {
        $validated = $request->validated();

        $user = $request->user();

        $source = new Source($validated);

        $source->created_by_id = $user?->id;

        $source->save();

        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.sources.show', ['source' => $source->id]));
    }

    /**
     * Unlock the Source resource in storage.
     *
     * @route DELETE /resource/lead/sources/lock/{source} playground.lead.resource.sources.unlock
     */
    public function unlock(
        Source $source,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|SourceResource {

        $source->setAttribute('locked', false);

        $source->save();

        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.sources.show', ['source' => $source->id]));
    }

    /**
     * Update the Source resource in storage.
     *
     * @route PATCH /resource/lead/sources/{source} playground.lead.resource.sources.patch
     */
    public function update(
        Source $source,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|SourceResource {
        $validated = $request->validated();

        $user = $request->user();

        $source->modified_by_id = $user?->id;

        $source->update($validated);

        if ($request->expectsJson()) {
            return (new SourceResource($source))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.sources.show', ['source' => $source->id]));
    }
}
