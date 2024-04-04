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
use Playground\Lead\Models\Team;
use Playground\Lead\Resource\Http\Requests\Team\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Team\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Team\EditRequest;
use Playground\Lead\Resource\Http\Requests\Team\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Team\LockRequest;
use Playground\Lead\Resource\Http\Requests\Team\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Team\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Team\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Team\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Team\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Team as TeamResource;
use Playground\Lead\Resource\Http\Resources\TeamCollection;

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
     * Create information or form for the Team resource in storage.
     *
     * @route GET /resource/lead/teams/create playground.lead.resource.teams.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|TeamResource|View {

        $validated = $request->validated();

        $team = new Team($validated);

        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $team = new Team($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

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

        session()->flashInput($flash);

        return view($this->getViewPath('team', 'form'), $data);
    }

    /**
     * Edit information for the Team resource in storage.
     *
     * @route GET /resource/lead/teams/edit playground.lead.resource.teams.edit
     */
    public function edit(
        Team $team,
        EditRequest $request
    ): JsonResponse|TeamResource|View {

        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $team->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $team,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $team->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::team/form',
            $data
        );
    }

    /**
     * Remove the Team resource from storage.
     *
     * @route DELETE /resource/lead/teams/{team} playground.lead.resource.teams.destroy
     */
    public function destroy(
        Team $team,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

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

        return redirect(route('playground.lead.resource.teams'));
    }

    /**
     * Lock the Team resource in storage.
     *
     * @route PUT /resource/lead/teams/{team} playground.lead.resource.teams.lock
     */
    public function lock(
        Team $team,
        LockRequest $request
    ): JsonResponse|RedirectResponse|TeamResource {

        $team->setAttribute('locked', true);

        $team->save();

        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teams.show', ['team' => $team->id]));
    }

    /**
     * Display a listing of Team resources.
     *
     * @route GET /resource/lead/teams playground.lead.resource.teams
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|TeamCollection {
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
            return (new TeamCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::team/index',
            $data
        );
    }

    /**
     * Restore the Team resource from the trash.
     *
     * @route PUT /resource/lead/teams/restore/{team} playground.lead.resource.teams.restore
     */
    public function restore(
        Team $team,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|TeamResource {
        $validated = $request->validated();

        $user = $request->user();

        $team->restore();

        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teams.show', ['team' => $team->id]));
    }

    /**
     * Display the Team resource.
     *
     * @route GET /resource/lead/teams/{team} playground.lead.resource.teams.show
     */
    public function show(
        Team $team,
        ShowRequest $request
    ): JsonResponse|View|TeamResource {
        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

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
        ];

        return view(
            'playground-lead-resource::team/detail',
            $data
        );
    }

    /**
     * Store a newly created Team resource in storage.
     *
     * @route POST /resource/lead/teams playground.lead.resource.teams.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|TeamResource {
        $validated = $request->validated();

        $user = $request->user();

        $team = new Team($validated);

        $team->created_by_id = $user?->id;

        $team->save();

        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teams.show', ['team' => $team->id]));
    }

    /**
     * Unlock the Team resource in storage.
     *
     * @route DELETE /resource/lead/teams/lock/{team} playground.lead.resource.teams.unlock
     */
    public function unlock(
        Team $team,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|TeamResource {

        $team->setAttribute('locked', false);

        $team->save();

        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teams.show', ['team' => $team->id]));
    }

    /**
     * Update the Team resource in storage.
     *
     * @route PATCH /resource/lead/teams/{team} playground.lead.resource.teams.patch
     */
    public function update(
        Team $team,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|TeamResource {
        $validated = $request->validated();

        $user = $request->user();

        $team->modified_by_id = $user?->id;

        $team->update($validated);

        if ($request->expectsJson()) {
            return (new TeamResource($team))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.teams.show', ['team' => $team->id]));
    }
}
