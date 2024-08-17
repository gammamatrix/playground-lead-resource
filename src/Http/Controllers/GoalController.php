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
use Playground\Lead\Models\Goal;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

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
     * Create the Goal resource in storage.
     *
     * @route GET /resource/lead/goals/create playground.lead.resource.goals.create
     */
    public function create(
        Requests\Goal\CreateRequest $request
    ): JsonResponse|View|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        $goal = new Goal($validated);

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
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
            'data' => $goal,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $goal->toArray();

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
     * Edit the Goal resource in storage.
     *
     * @route GET /resource/lead/goals/edit/{goal} playground.lead.resource.goals.edit
     */
    public function edit(
        Goal $goal,
        Requests\Goal\EditRequest $request
    ): JsonResponse|View|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $goal->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

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
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Goal resource from storage.
     *
     * @route DELETE /resource/lead/goals/{goal} playground.lead.resource.goals.destroy
     */
    public function destroy(
        Goal $goal,
        Requests\Goal\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $goal->modified_by_id = $user->id;
        }

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

        return redirect(route($this->packageInfo['model_route']));
    }

    /**
     * Lock the Goal resource in storage.
     *
     * @route PUT /resource/lead/goals/{goal} playground.lead.resource.goals.lock
     */
    public function lock(
        Goal $goal,
        Requests\Goal\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $goal->modified_by_id = $user->id;
        }

        $goal->locked = true;

        $goal->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $goal->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
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
        ), ['goal' => $goal->id]));
    }

    /**
     * Display a listing of Goal resources.
     *
     * @route GET /resource/lead/goals playground.lead.resource.goals
     */
    public function index(
        Requests\Goal\IndexRequest $request
    ): JsonResponse|View|Resources\GoalCollection {

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
            return (new Resources\GoalCollection($paginator))->response($request);
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
     * Restore the Goal resource from the trash.
     *
     * @route PUT /resource/lead/goals/restore/{goal} playground.lead.resource.goals.restore
     */
    public function restore(
        Goal $goal,
        Requests\Goal\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $goal->modified_by_id = $user->id;
        }

        $goal->restore();

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
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
        ), ['goal' => $goal->id]));
    }

    /**
     * Display the Goal resource.
     *
     * @route GET /resource/lead/goals/{goal} playground.lead.resource.goals.show
     */
    public function show(
        Goal $goal,
        Requests\Goal\ShowRequest $request
    ): JsonResponse|View|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $goal->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $goal,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Goal resource in storage.
     *
     * @route POST /resource/lead/goals playground.lead.resource.goals.post
     */
    public function store(
        Requests\Goal\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        $goal = new Goal($validated);

        if ($user?->id) {
            $goal->created_by_id = $user->id;
        }

        $goal->save();

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
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
        ), ['goal' => $goal->id]));
    }

    /**
     * Unlock the Goal resource in storage.
     *
     * @route DELETE /resource/lead/goals/lock/{goal} playground.lead.resource.goals.unlock
     */
    public function unlock(
        Goal $goal,
        Requests\Goal\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        $goal->locked = false;

        if ($user?->id) {
            $goal->modified_by_id = $user->id;
        }

        $goal->save();

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
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
        ), ['goal' => $goal->id]));
    }

    /**
     * Update the Goal resource in storage.
     *
     * @route PATCH /resource/lead/goals/{goal} playground.lead.resource.goals.patch
     */
    public function update(
        Goal $goal,
        Requests\Goal\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Goal {

        $validated = $request->validated();

        $user = $request->user();

        $goal->update($validated);

        if ($user?->id) {
            $goal->modified_by_id = $user->id;
        }

        if ($request->expectsJson()) {
            return (new Resources\Goal($goal))->additional(['meta' => [
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
        ), ['goal' => $goal->id]));
    }
}
