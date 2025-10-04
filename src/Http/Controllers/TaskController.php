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
use Playground\Lead\Models\Task;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

/**
 * \Playground\Lead\Resource\Http\Controllers\TaskController
 */
class TaskController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Task',
        'model_label_plural' => 'Tasks',
        'model_route' => 'playground.lead.resource.tasks',
        'model_slug' => 'task',
        'model_slug_plural' => 'tasks',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:task',
        'table' => 'lead_tasks',
        'view' => 'playground-lead-resource::task',
    ];

    /**
     * Create the Task resource in storage.
     *
     * @route GET /resource/lead/tasks/create playground.lead.resource.tasks.create
     */
    public function create(
        Requests\Task\CreateRequest $request
    ): JsonResponse|View|Resources\Task {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $task = new Task($validated);

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
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
            'data' => $task,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $task->toArray();

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
     * Edit the Task resource in storage.
     *
     * @route GET /resource/lead/tasks/edit/{task} playground.lead.resource.tasks.edit
     */
    public function edit(
        Task $task,
        Requests\Task\EditRequest $request
    ): JsonResponse|View|Resources\Task {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $flash = $task->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $task->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $task,
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
     * Remove the Task resource from storage.
     *
     * @route DELETE /resource/lead/tasks/{task} playground.lead.resource.tasks.destroy
     */
    public function destroy(
        Task $task,
        Requests\Task\DestroyRequest $request
    ): Response|RedirectResponse {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $task->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $task->delete();
        } else {
            $task->forceDelete();
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
     * Lock the Task resource in storage.
     *
     * @route PUT /resource/lead/tasks/{task} playground.lead.resource.tasks.lock
     */
    public function lock(
        Task $task,
        Requests\Task\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Task {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $task->modified_by_id = $user->id;
        }

        $task->locked = true;

        $task->save();

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
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
        ), ['task' => $task->id]));
    }

    /**
     * Display a listing of Task resources.
     *
     * @route GET /resource/lead/tasks playground.lead.resource.tasks
     */
    public function index(
        Requests\Task\IndexRequest $request
    ): JsonResponse|View|Resources\TaskCollection {

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

        $query = Task::addSelect(sprintf('%1$s.*', $packageInfo->table()));

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
            return new Resources\TaskCollection($paginator)->response($request);
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
     * Restore the Task resource from the trash.
     *
     * @route PUT /resource/lead/tasks/restore/{task} playground.lead.resource.tasks.restore
     */
    public function restore(
        Task $task,
        Requests\Task\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Task {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $task->modified_by_id = $user?->id;

        $task->restore();

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
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
        ), ['task' => $task->id]));
    }

    /**
     * Display the Task resource.
     *
     * @route GET /resource/lead/tasks/{task} playground.lead.resource.tasks.show
     */
    public function show(
        Task $task,
        Requests\Task\ShowRequest $request
    ): JsonResponse|View|Resources\Task {

        $packageInfo = $this->packageInfo();

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
                'info' => $packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $task->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $packageInfo,
        ];

        $data = [
            'data' => $task,
            'meta' => $meta,
        ];

        /**
         * @var view-string $view
         */
        $view = sprintf('%1$s/detail', $packageInfo->view());

        return view($view, $data);
    }

    /**
     * Store a newly created API Task resource in storage.
     *
     * @route POST /resource/lead/tasks playground.lead.resource.tasks.post
     */
    public function store(
        Requests\Task\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Task {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $task = new Task($validated);

        $task->created_by_id = $user?->id;

        $task->save();

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
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
        ), ['task' => $task->id]));
    }

    /**
     * Unlock the Task resource in storage.
     *
     * @route DELETE /resource/lead/tasks/lock/{task} playground.lead.resource.tasks.unlock
     */
    public function unlock(
        Task $task,
        Requests\Task\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Task {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $task->locked = false;

        $task->modified_by_id = $user?->id;

        $task->save();

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
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
        ), ['task' => $task->id]));
    }

    /**
     * Update the Task resource in storage.
     *
     * @route PATCH /resource/lead/tasks/{task} playground.lead.resource.tasks.patch
     */
    public function update(
        Task $task,
        Requests\Task\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Task {

        $packageInfo = $this->packageInfo();

        $validated = $request->validated();

        $user = $request->user();

        $task->modified_by_id = $user?->id;

        $task->update($validated);

        if ($request->expectsJson()) {
            return new Resources\Task($task)->additional(['meta' => [
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
        ), ['task' => $task->id]));
    }
}
