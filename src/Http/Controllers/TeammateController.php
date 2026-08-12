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
use Playground\Lead\Models\Teammate;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

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
        'model_variable' => 'teammate',
        'model_variable_plural' => 'teammates',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:teammate',
        'table' => 'lead_teammates',
        'view' => 'playground-lead-resource::teammate',
    ];

    /**
     * Create the Teammate resource in storage.
     *
     * @route GET /resource/lead/teammates/create playground.lead.resource.teammates.create
     */
    public function create(
        Requests\Teammate\CreateRequest $request
    ): JsonResponse|View|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $teammate = new Teammate($validated);

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
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
            'data' => $teammate,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $teammate->toArray();

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
     * Edit the Teammate resource in storage.
     *
     * @route GET /resource/lead/teammates/edit/{teammate} playground.lead.resource.teammates.edit
     */
    public function edit(
        Teammate $teammate,
        Requests\Teammate\EditRequest $request
    ): JsonResponse|View|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $flash = $teammate->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $teammate->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $teammate,
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
     * Remove the Teammate resource from storage.
     *
     * @route DELETE /resource/lead/teammates/{teammate} playground.lead.resource.teammates.destroy
     */
    public function destroy(
        Teammate $teammate,
        Requests\Teammate\DestroyRequest $request
    ): Response|RedirectResponse {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $teammate->modified_by_id = $user->id;
        }

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

        return redirect(route($packageInfo->model_route()));
    }

    /**
     * Lock the Teammate resource in storage.
     *
     * @route PUT /resource/lead/teammates/{teammate} playground.lead.resource.teammates.lock
     */
    public function lock(
        Teammate $teammate,
        Requests\Teammate\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $teammate->modified_by_id = $user->id;
        }

        $teammate->locked = true;

        $teammate->save();

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
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
        ), ['teammate' => $teammate->id]));
    }

    /**
     * Display a listing of Teammate resources.
     *
     * @route GET /resource/lead/teammates playground.lead.resource.teammates
     */
    public function index(
        Requests\Teammate\IndexRequest $request
    ): JsonResponse|View|Resources\TeammateCollection {

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

        $query = Teammate::addSelect(sprintf('%1$s.*', $packageInfo->table()));

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
            return new Resources\TeammateCollection($paginator)->response($request);
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
     * Restore the Teammate resource from the trash.
     *
     * @route PUT /resource/lead/teammates/restore/{teammate} playground.lead.resource.teammates.restore
     */
    public function restore(
        Teammate $teammate,
        Requests\Teammate\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $teammate->modified_by_id = $user?->id;

        $teammate->restore();

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
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
        ), ['teammate' => $teammate->id]));
    }

    /**
     * Display the Teammate resource.
     *
     * @route GET /resource/lead/teammates/{teammate} playground.lead.resource.teammates.show
     */
    public function show(
        Teammate $teammate,
        Requests\Teammate\ShowRequest $request
    ): JsonResponse|View|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $teammate->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $teammate,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/detail', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Store a newly created API Teammate resource in storage.
     *
     * @route POST /resource/lead/teammates playground.lead.resource.teammates.post
     */
    public function store(
        Requests\Teammate\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $teammate = new Teammate($validated);

        $teammate->created_by_id = $user?->id;

        $teammate->save();

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
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
        ), ['teammate' => $teammate->id]));
    }

    /**
     * Unlock the Teammate resource in storage.
     *
     * @route DELETE /resource/lead/teammates/lock/{teammate} playground.lead.resource.teammates.unlock
     */
    public function unlock(
        Teammate $teammate,
        Requests\Teammate\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $teammate->locked = false;

        $teammate->modified_by_id = $user?->id;

        $teammate->save();

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
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
        ), ['teammate' => $teammate->id]));
    }

    /**
     * Update the Teammate resource in storage.
     *
     * @route PATCH /resource/lead/teammates/{teammate} playground.lead.resource.teammates.patch
     */
    public function update(
        Teammate $teammate,
        Requests\Teammate\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Teammate {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $teammate->modified_by_id = $user?->id;

        $teammate->update($validated);

        if ($request->expectsJson()) {
            return new Resources\Teammate($teammate)->additional(['meta' => [
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
        ), ['teammate' => $teammate->id]));
    }
}
