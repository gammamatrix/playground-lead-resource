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
use Playground\Lead\Models\Goal;
use Playground\Lead\Resource\Http\Requests\Goal\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Goal\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Goal\EditRequest;
use Playground\Lead\Resource\Http\Requests\Goal\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Goal\LockRequest;
use Playground\Lead\Resource\Http\Requests\Goal\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Goal\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Goal\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Goal\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Goal\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Goal as GoalResource;
use Playground\Lead\Resource\Http\Resources\GoalCollection;

/**
 * \Playground\Lead\Resource\Http\Controllers\GoalController
 */
class GoalController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Goal',
        'model_label_plural' => 'Goals',
        'model_route' => 'playground.lead.resource.goals',
        'model_slug' => 'goal',
        'model_slug_plural' => 'goals',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:goal',
        'table' => 'lead_goals',
        'view' => 'playground-lead-resource::goal',
    ];

    /**
     * Create information or form for the Goal resource in storage.
     *
     * @route GET /resource/lead/goals/create playground.lead.resource.goals.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|GoalResource|View {

        $validated = $request->validated();

        $goal = new Goal($validated);

        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $goal = new Goal($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $goal,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $goal->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view($this->getViewPath('goal', 'form'), $data);
    }

    /**
     * Edit information for the Goal resource in storage.
     *
     * @route GET /resource/lead/goals/edit playground.lead.resource.goals.edit
     */
    public function edit(
        Goal $goal,
        EditRequest $request
    ): JsonResponse|GoalResource|View {

        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $goal->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $goal,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $goal->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::goal/form',
            $data
        );
    }

    /**
     * Remove the Goal resource from storage.
     *
     * @route DELETE /resource/lead/goals/{goal} playground.lead.resource.goals.destroy
     */
    public function destroy(
        Goal $goal,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

        if (empty($validated['force'])) {
            $goal->delete();
        } else {
            $goal->forceDelete();
        }

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.goals'));
    }

    /**
     * Lock the Goal resource in storage.
     *
     * @route PUT /resource/lead/goals/{goal} playground.lead.resource.goals.lock
     */
    public function lock(
        Goal $goal,
        LockRequest $request
    ): JsonResponse|RedirectResponse|GoalResource {

        $goal->setAttribute('locked', true);

        $goal->save();

        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.goals.show', ['goal' => $goal->id]));
    }

    /**
     * Display a listing of Goal resources.
     *
     * @route GET /resource/lead/goals playground.lead.resource.goals
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|GoalCollection {
        $user = $request->user();

        $validated = $request->validated();

        $query = Goal::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new GoalCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::goal/index',
            $data
        );
    }

    /**
     * Restore the Goal resource from the trash.
     *
     * @route PUT /resource/lead/goals/restore/{goal} playground.lead.resource.goals.restore
     */
    public function restore(
        Goal $goal,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|GoalResource {
        $validated = $request->validated();

        $user = $request->user();

        $goal->restore();

        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.goals.show', ['goal' => $goal->id]));
    }

    /**
     * Display the Goal resource.
     *
     * @route GET /resource/lead/goals/{goal} playground.lead.resource.goals.show
     */
    public function show(
        Goal $goal,
        ShowRequest $request
    ): JsonResponse|View|GoalResource {
        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $goal->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $goal,
            'meta' => $meta,
        ];

        return view(
            'playground-lead-resource::goal/detail',
            $data
        );
    }

    /**
     * Store a newly created Goal resource in storage.
     *
     * @route POST /resource/lead/goals playground.lead.resource.goals.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|GoalResource {
        $validated = $request->validated();

        $user = $request->user();

        $goal = new Goal($validated);

        $goal->created_by_id = $user?->id;

        $goal->save();

        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.goals.show', ['goal' => $goal->id]));
    }

    /**
     * Unlock the Goal resource in storage.
     *
     * @route DELETE /resource/lead/goals/lock/{goal} playground.lead.resource.goals.unlock
     */
    public function unlock(
        Goal $goal,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|GoalResource {

        $goal->setAttribute('locked', false);

        $goal->save();

        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.goals.show', ['goal' => $goal->id]));
    }

    /**
     * Update the Goal resource in storage.
     *
     * @route PATCH /resource/lead/goals/{goal} playground.lead.resource.goals.patch
     */
    public function update(
        Goal $goal,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|GoalResource {
        $validated = $request->validated();

        $user = $request->user();

        $goal->modified_by_id = $user?->id;

        $goal->update($validated);

        if ($request->expectsJson()) {
            return (new GoalResource($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.goals.show', ['goal' => $goal->id]));
    }
}
