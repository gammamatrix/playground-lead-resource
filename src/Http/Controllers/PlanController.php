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
use Playground\Lead\Models\Plan;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

/**
 * \Playground\Lead\Resource\Http\Controllers\PlanController
 */
class PlanController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Plan',
        'model_label_plural' => 'Plans',
        'model_route' => 'playground.lead.resource.plans',
        'model_slug' => 'plan',
        'model_slug_plural' => 'plans',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:plan',
        'table' => 'lead_plans',
        'view' => 'playground-lead-resource::plan',
    ];

    /**
     * Create the Plan resource in storage.
     *
     * @route GET /resource/lead/plans/create playground.lead.resource.plans.create
     */
    public function create(
        Requests\Plan\CreateRequest $request
    ): JsonResponse|View|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        $plan = new Plan($validated);

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
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
            'data' => $plan,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $plan->toArray();

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
     * Edit the Plan resource in storage.
     *
     * @route GET /resource/lead/plans/edit/{plan} playground.lead.resource.plans.edit
     */
    public function edit(
        Plan $plan,
        Requests\Plan\EditRequest $request
    ): JsonResponse|View|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $plan->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $plan->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $plan,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Plan resource from storage.
     *
     * @route DELETE /resource/lead/plans/{plan} playground.lead.resource.plans.destroy
     */
    public function destroy(
        Plan $plan,
        Requests\Plan\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $plan->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $plan->delete();
        } else {
            $plan->forceDelete();
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
     * Lock the Plan resource in storage.
     *
     * @route PUT /resource/lead/plans/{plan} playground.lead.resource.plans.lock
     */
    public function lock(
        Plan $plan,
        Requests\Plan\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $plan->modified_by_id = $user->id;
        }

        $plan->locked = true;

        $plan->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $plan->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
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
        ), ['plan' => $plan->id]));
    }

    /**
     * Display a listing of Plan resources.
     *
     * @route GET /resource/lead/plans playground.lead.resource.plans
     */
    public function index(
        Requests\Plan\IndexRequest $request
    ): JsonResponse|View|Resources\PlanCollection {

        $user = $request->user();

        $validated = $request->validated();

        $query = Plan::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new Resources\PlanCollection($paginator))->response($request);
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
     * Restore the Plan resource from the trash.
     *
     * @route PUT /resource/lead/plans/restore/{plan} playground.lead.resource.plans.restore
     */
    public function restore(
        Plan $plan,
        Requests\Plan\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $plan->modified_by_id = $user->id;
        }

        $plan->restore();

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
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
        ), ['plan' => $plan->id]));
    }

    /**
     * Display the Plan resource.
     *
     * @route GET /resource/lead/plans/{plan} playground.lead.resource.plans.show
     */
    public function show(
        Plan $plan,
        Requests\Plan\ShowRequest $request
    ): JsonResponse|View|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $plan->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $plan,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Plan resource in storage.
     *
     * @route POST /resource/lead/plans playground.lead.resource.plans.post
     */
    public function store(
        Requests\Plan\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        $plan = new Plan($validated);

        if ($user?->id) {
            $plan->created_by_id = $user->id;
        }

        $plan->save();

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
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
        ), ['plan' => $plan->id]));
    }

    /**
     * Unlock the Plan resource in storage.
     *
     * @route DELETE /resource/lead/plans/lock/{plan} playground.lead.resource.plans.unlock
     */
    public function unlock(
        Plan $plan,
        Requests\Plan\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        $plan->locked = false;

        if ($user?->id) {
            $plan->modified_by_id = $user->id;
        }

        $plan->save();

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
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
        ), ['plan' => $plan->id]));
    }

    /**
     * Update the Plan resource in storage.
     *
     * @route PATCH /resource/lead/plans/{plan} playground.lead.resource.plans.patch
     */
    public function update(
        Plan $plan,
        Requests\Plan\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Plan {

        $validated = $request->validated();

        $user = $request->user();

        $plan->update($validated);

        if ($user?->id) {
            $plan->modified_by_id = $user->id;
        }

        if ($request->expectsJson()) {
            return (new Resources\Plan($plan))->additional(['meta' => [
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
        ), ['plan' => $plan->id]));
    }
}
