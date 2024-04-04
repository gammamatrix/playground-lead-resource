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
use Playground\Lead\Models\Plan;
use Playground\Lead\Resource\Http\Requests\Plan\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Plan\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Plan\EditRequest;
use Playground\Lead\Resource\Http\Requests\Plan\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Plan\LockRequest;
use Playground\Lead\Resource\Http\Requests\Plan\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Plan\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Plan\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Plan\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Plan\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Plan as PlanResource;
use Playground\Lead\Resource\Http\Resources\PlanCollection;

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
     * Create information or form for the Plan resource in storage.
     *
     * @route GET /resource/lead/plans/create playground.lead.resource.plans.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|PlanResource|View {

        $validated = $request->validated();

        $plan = new Plan($validated);

        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $plan = new Plan($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

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

        session()->flashInput($flash);

        return view($this->getViewPath('plan', 'form'), $data);
    }

    /**
     * Edit information for the Plan resource in storage.
     *
     * @route GET /resource/lead/plans/edit playground.lead.resource.plans.edit
     */
    public function edit(
        Plan $plan,
        EditRequest $request
    ): JsonResponse|PlanResource|View {

        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $plan->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $plan,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $plan->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::plan/form',
            $data
        );
    }

    /**
     * Remove the Plan resource from storage.
     *
     * @route DELETE /resource/lead/plans/{plan} playground.lead.resource.plans.destroy
     */
    public function destroy(
        Plan $plan,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

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

        return redirect(route('playground.lead.resource.plans'));
    }

    /**
     * Lock the Plan resource in storage.
     *
     * @route PUT /resource/lead/plans/{plan} playground.lead.resource.plans.lock
     */
    public function lock(
        Plan $plan,
        LockRequest $request
    ): JsonResponse|RedirectResponse|PlanResource {

        $plan->setAttribute('locked', true);

        $plan->save();

        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.plans.show', ['plan' => $plan->id]));
    }

    /**
     * Display a listing of Plan resources.
     *
     * @route GET /resource/lead/plans playground.lead.resource.plans
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|PlanCollection {
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
            return (new PlanCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::plan/index',
            $data
        );
    }

    /**
     * Restore the Plan resource from the trash.
     *
     * @route PUT /resource/lead/plans/restore/{plan} playground.lead.resource.plans.restore
     */
    public function restore(
        Plan $plan,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|PlanResource {
        $validated = $request->validated();

        $user = $request->user();

        $plan->restore();

        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.plans.show', ['plan' => $plan->id]));
    }

    /**
     * Display the Plan resource.
     *
     * @route GET /resource/lead/plans/{plan} playground.lead.resource.plans.show
     */
    public function show(
        Plan $plan,
        ShowRequest $request
    ): JsonResponse|View|PlanResource {
        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

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
        ];

        return view(
            'playground-lead-resource::plan/detail',
            $data
        );
    }

    /**
     * Store a newly created Plan resource in storage.
     *
     * @route POST /resource/lead/plans playground.lead.resource.plans.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|PlanResource {
        $validated = $request->validated();

        $user = $request->user();

        $plan = new Plan($validated);

        $plan->created_by_id = $user?->id;

        $plan->save();

        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.plans.show', ['plan' => $plan->id]));
    }

    /**
     * Unlock the Plan resource in storage.
     *
     * @route DELETE /resource/lead/plans/lock/{plan} playground.lead.resource.plans.unlock
     */
    public function unlock(
        Plan $plan,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|PlanResource {

        $plan->setAttribute('locked', false);

        $plan->save();

        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.plans.show', ['plan' => $plan->id]));
    }

    /**
     * Update the Plan resource in storage.
     *
     * @route PATCH /resource/lead/plans/{plan} playground.lead.resource.plans.patch
     */
    public function update(
        Plan $plan,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|PlanResource {
        $validated = $request->validated();

        $user = $request->user();

        $plan->modified_by_id = $user?->id;

        $plan->update($validated);

        if ($request->expectsJson()) {
            return (new PlanResource($plan))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.plans.show', ['plan' => $plan->id]));
    }
}
