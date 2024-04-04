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
use Playground\Lead\Models\Task;
use Playground\Lead\Resource\Http\Requests\Task\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Task\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Task\EditRequest;
use Playground\Lead\Resource\Http\Requests\Task\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Task\LockRequest;
use Playground\Lead\Resource\Http\Requests\Task\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Task\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Task\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Task\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Task\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Task as TaskResource;
use Playground\Lead\Resource\Http\Resources\TaskCollection;

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
     * Create information or form for the Task resource in storage.
     *
     * @route GET /resource/lead/tasks/create playground.lead.resource.tasks.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|TaskResource|View {

        $validated = $request->validated();

        $task = new Task($validated);

        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $task = new Task($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
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

        session()->flashInput($flash);

        return view($this->getViewPath('task', 'form'), $data);
    }

    /**
     * Edit information for the Task resource in storage.
     *
     * @route GET /resource/lead/tasks/edit playground.lead.resource.tasks.edit
     */
    public function edit(
        Task $task,
        EditRequest $request
    ): JsonResponse|TaskResource|View {

        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $task->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $task,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $task->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::task/form',
            $data
        );
    }

    /**
     * Remove the Task resource from storage.
     *
     * @route DELETE /resource/lead/tasks/{task} playground.lead.resource.tasks.destroy
     */
    public function destroy(
        Task $task,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

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

        return redirect(route('playground.lead.resource.tasks'));
    }

    /**
     * Lock the Task resource in storage.
     *
     * @route PUT /resource/lead/tasks/{task} playground.lead.resource.tasks.lock
     */
    public function lock(
        Task $task,
        LockRequest $request
    ): JsonResponse|RedirectResponse|TaskResource {

        $task->setAttribute('locked', true);

        $task->save();

        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.tasks.show', ['task' => $task->id]));
    }

    /**
     * Display a listing of Task resources.
     *
     * @route GET /resource/lead/tasks playground.lead.resource.tasks
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|TaskCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Task::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new TaskCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::task/index',
            $data
        );
    }

    /**
     * Restore the Task resource from the trash.
     *
     * @route PUT /resource/lead/tasks/restore/{task} playground.lead.resource.tasks.restore
     */
    public function restore(
        Task $task,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|TaskResource {
        $validated = $request->validated();

        $user = $request->user();

        $task->restore();

        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.tasks.show', ['task' => $task->id]));
    }

    /**
     * Display the Task resource.
     *
     * @route GET /resource/lead/tasks/{task} playground.lead.resource.tasks.show
     */
    public function show(
        Task $task,
        ShowRequest $request
    ): JsonResponse|View|TaskResource {
        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $task->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $task,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::task/detail',
            $data
        );
    }

    /**
     * Store a newly created Task resource in storage.
     *
     * @route POST /resource/lead/tasks playground.lead.resource.tasks.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|TaskResource {
        $validated = $request->validated();

        $user = $request->user();

        $task = new Task($validated);

        $task->created_by_id = $user?->id;

        $task->save();

        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.tasks.show', ['task' => $task->id]));
    }

    /**
     * Unlock the Task resource in storage.
     *
     * @route DELETE /resource/lead/tasks/lock/{task} playground.lead.resource.tasks.unlock
     */
    public function unlock(
        Task $task,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|TaskResource {

        $task->setAttribute('locked', false);

        $task->save();

        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.tasks.show', ['task' => $task->id]));
    }

    /**
     * Update the Task resource in storage.
     *
     * @route PATCH /resource/lead/tasks/{task} playground.lead.resource.tasks.patch
     */
    public function update(
        Task $task,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|TaskResource {
        $validated = $request->validated();

        $user = $request->user();

        $task->modified_by_id = $user?->id;

        $task->update($validated);

        if ($request->expectsJson()) {
            return (new TaskResource($task))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.tasks.show', ['task' => $task->id]));
    }
}
