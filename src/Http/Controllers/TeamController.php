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
use Playground\Lead\Models\Team;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

/**
 * \Playground\Lead\Resource\Http\Controllers\TeamController
 */
class TeamController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Team',
        'model_label_plural' => 'Teams',
        'model_route' => 'playground.lead.resource.teams',
        'model_slug' => 'team',
        'model_slug_plural' => 'teams',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:team',
        'table' => 'lead_teams',
        'view' => 'playground-lead-resource::team',
    ];

    /**
     * Create the Team resource in storage.
     *
     * @route GET /resource/lead/teams/create playground.lead.resource.teams.create
     */
    public function create(
        Requests\Team\CreateRequest $request
    ): JsonResponse|View|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        $team = new Team($validated);

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
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
            'data' => $team,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $team->toArray();

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
     * Edit the Team resource in storage.
     *
     * @route GET /resource/lead/teams/edit/{team} playground.lead.resource.teams.edit
     */
    public function edit(
        Team $team,
        Requests\Team\EditRequest $request
    ): JsonResponse|View|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $team->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $team->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $team,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Team resource from storage.
     *
     * @route DELETE /resource/lead/teams/{team} playground.lead.resource.teams.destroy
     */
    public function destroy(
        Team $team,
        Requests\Team\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $team->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $team->delete();
        } else {
            $team->forceDelete();
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
     * Lock the Team resource in storage.
     *
     * @route PUT /resource/lead/teams/{team} playground.lead.resource.teams.lock
     */
    public function lock(
        Team $team,
        Requests\Team\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $team->modified_by_id = $user->id;
        }

        $team->locked = true;

        $team->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $team->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
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
        ), ['team' => $team->id]));
    }

    /**
     * Display a listing of Team resources.
     *
     * @route GET /resource/lead/teams playground.lead.resource.teams
     */
    public function index(
        Requests\Team\IndexRequest $request
    ): JsonResponse|View|Resources\TeamCollection {

        $user = $request->user();

        $validated = $request->validated();

        $query = Team::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new Resources\TeamCollection($paginator))->response($request);
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
     * Restore the Team resource from the trash.
     *
     * @route PUT /resource/lead/teams/restore/{team} playground.lead.resource.teams.restore
     */
    public function restore(
        Team $team,
        Requests\Team\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $team->modified_by_id = $user->id;
        }

        $team->restore();

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
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
        ), ['team' => $team->id]));
    }

    /**
     * Display the Team resource.
     *
     * @route GET /resource/lead/teams/{team} playground.lead.resource.teams.show
     */
    public function show(
        Team $team,
        Requests\Team\ShowRequest $request
    ): JsonResponse|View|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $team->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $team,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Team resource in storage.
     *
     * @route POST /resource/lead/teams playground.lead.resource.teams.post
     */
    public function store(
        Requests\Team\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        $team = new Team($validated);

        if ($user?->id) {
            $team->created_by_id = $user->id;
        }

        $team->save();

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
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
        ), ['team' => $team->id]));
    }

    /**
     * Unlock the Team resource in storage.
     *
     * @route DELETE /resource/lead/teams/lock/{team} playground.lead.resource.teams.unlock
     */
    public function unlock(
        Team $team,
        Requests\Team\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        $team->locked = false;

        if ($user?->id) {
            $team->modified_by_id = $user->id;
        }

        $team->save();

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
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
        ), ['team' => $team->id]));
    }

    /**
     * Update the Team resource in storage.
     *
     * @route PATCH /resource/lead/teams/{team} playground.lead.resource.teams.patch
     */
    public function update(
        Team $team,
        Requests\Team\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Team {

        $validated = $request->validated();

        $user = $request->user();

        $team->update($validated);

        if ($user?->id) {
            $team->modified_by_id = $user->id;
        }

        if ($request->expectsJson()) {
            return (new Resources\Team($team))->additional(['meta' => [
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
        ), ['team' => $team->id]));
    }
}
