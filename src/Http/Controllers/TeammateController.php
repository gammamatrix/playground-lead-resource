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
use Playground\Lead\Models\Teammate;
use Playground\Lead\Resource\Http\Requests\Teammate\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\EditRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\LockRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Teammate\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Teammate as TeammateResource;
use Playground\Lead\Resource\Http\Resources\TeammateCollection;

/**
 * \Playground\Lead\Resource\Http\Controllers\TeammateController
 */
class TeammateController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Teammate',
        'model_label_plural' => 'Teammates',
        'model_route' => 'playground.lead.resource.teammates',
        'model_slug' => 'teammate',
        'model_slug_plural' => 'teammates',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:teammate',
        'table' => 'lead_teammates',
        'view' => 'playground-lead-resource::teammate',
    ];

    /**
     * Create information or form for the Teammate resource in storage.
     *
     * @route GET /resource/lead/teammates/create playground.lead.resource.teammates.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|TeammateResource|View {

        $validated = $request->validated();

        $teammate = new Teammate($validated);

        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $teammate = new Teammate($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $teammate,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $teammate->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view($this->getViewPath('teammate', 'form'), $data);
    }

    /**
     * Edit information for the Teammate resource in storage.
     *
     * @route GET /resource/lead/teammates/edit playground.lead.resource.teammates.edit
     */
    public function edit(
        Teammate $teammate,
        EditRequest $request
    ): JsonResponse|TeammateResource|View {

        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $teammate->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $teammate,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $teammate->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::teammate/form',
            $data
        );
    }

    /**
     * Remove the Teammate resource from storage.
     *
     * @route DELETE /resource/lead/teammates/{teammate} playground.lead.resource.teammates.destroy
     */
    public function destroy(
        Teammate $teammate,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

        if (empty($validated['force'])) {
            $teammate->delete();
        } else {
            $teammate->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teammates'));
    }

    /**
     * Lock the Teammate resource in storage.
     *
     * @route PUT /resource/lead/teammates/{teammate} playground.lead.resource.teammates.lock
     */
    public function lock(
        Teammate $teammate,
        LockRequest $request
    ): JsonResponse|RedirectResponse|TeammateResource {

        $teammate->setAttribute('locked', true);

        $teammate->save();

        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teammates.show', ['teammate' => $teammate->id]));
    }

    /**
     * Display a listing of Teammate resources.
     *
     * @route GET /resource/lead/teammates playground.lead.resource.teammates
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|TeammateCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Teammate::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new TeammateCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::teammate/index',
            $data
        );
    }

    /**
     * Restore the Teammate resource from the trash.
     *
     * @route PUT /resource/lead/teammates/restore/{teammate} playground.lead.resource.teammates.restore
     */
    public function restore(
        Teammate $teammate,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|TeammateResource {
        $validated = $request->validated();

        $user = $request->user();

        $teammate->restore();

        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teammates.show', ['teammate' => $teammate->id]));
    }

    /**
     * Display the Teammate resource.
     *
     * @route GET /resource/lead/teammates/{teammate} playground.lead.resource.teammates.show
     */
    public function show(
        Teammate $teammate,
        ShowRequest $request
    ): JsonResponse|View|TeammateResource {
        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $teammate->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $teammate,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::teammate/detail',
            $data
        );
    }

    /**
     * Store a newly created Teammate resource in storage.
     *
     * @route POST /resource/lead/teammates playground.lead.resource.teammates.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|TeammateResource {
        $validated = $request->validated();

        $user = $request->user();

        $teammate = new Teammate($validated);

        $teammate->created_by_id = $user?->id;

        $teammate->save();

        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teammates.show', ['teammate' => $teammate->id]));
    }

    /**
     * Unlock the Teammate resource in storage.
     *
     * @route DELETE /resource/lead/teammates/lock/{teammate} playground.lead.resource.teammates.unlock
     */
    public function unlock(
        Teammate $teammate,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|TeammateResource {

        $teammate->setAttribute('locked', false);

        $teammate->save();

        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teammates.show', ['teammate' => $teammate->id]));
    }

    /**
     * Update the Teammate resource in storage.
     *
     * @route PATCH /resource/lead/teammates/{teammate} playground.lead.resource.teammates.patch
     */
    public function update(
        Teammate $teammate,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|TeammateResource {
        $validated = $request->validated();

        $user = $request->user();

        $teammate->modified_by_id = $user?->id;

        $teammate->update($validated);

        if ($request->expectsJson()) {
            return (new TeammateResource($teammate))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teammates.show', ['teammate' => $teammate->id]));
    }
}
