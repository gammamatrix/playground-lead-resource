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
use Playground\Lead\Models\Report;
use Playground\Lead\Resource\Http\Requests;
use Playground\Lead\Resource\Http\Resources;

/**
 * \Playground\Lead\Resource\Http\Controllers\ReportController
 */
class ReportController extends Controller
{
    /**
     * @var array<string, string>
     */
    public array $packageInfo = [
        'model_attribute' => 'title',
        'model_label' => 'Report',
        'model_label_plural' => 'Reports',
        'model_route' => 'playground.lead.resource.reports',
        'model_slug' => 'report',
        'model_slug_plural' => 'reports',
        'module_label' => 'Lead',
        'module_label_plural' => 'Leads',
        'module_route' => 'playground.lead.resource',
        'module_slug' => 'lead',
        'privilege' => 'playground-lead-resource:report',
        'table' => 'lead_reports',
        'view' => 'playground-lead-resource::report',
    ];

    /**
     * Create the Report resource in storage.
     *
     * @route GET /resource/lead/reports/create playground.lead.resource.reports.create
     */
    public function create(
        Requests\Report\CreateRequest $request
    ): JsonResponse|View|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        $report = new Report($validated);

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
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
            'data' => $report,
            'meta' => $meta,
            '_method' => 'post',
        ];

        $flash = $report->toArray();

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
     * Edit the Report resource in storage.
     *
     * @route GET /resource/lead/reports/edit/{report} playground.lead.resource.reports.edit
     */
    public function edit(
        Report $report,
        Requests\Report\EditRequest $request
    ): JsonResponse|View|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $flash = $report->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $report->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $report,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        session()->flashInput($flash);

        return view(sprintf('%1$s/form', $this->packageInfo['view']), $data);
    }

    /**
     * Remove the Report resource from storage.
     *
     * @route DELETE /resource/lead/reports/{report} playground.lead.resource.reports.destroy
     */
    public function destroy(
        Report $report,
        Requests\Report\DestroyRequest $request
    ): Response|RedirectResponse {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $report->modified_by_id = $user->id;
        }

        if (empty($validated['force'])) {
            $report->delete();
        } else {
            $report->forceDelete();
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
     * Lock the Report resource in storage.
     *
     * @route PUT /resource/lead/reports/{report} playground.lead.resource.reports.lock
     */
    public function lock(
        Report $report,
        Requests\Report\LockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $report->modified_by_id = $user->id;
        }

        $report->locked = true;

        $report->save();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $report->id,
            'timestamp' => Carbon::now()->toJson(),
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
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
        ), ['report' => $report->id]));
    }

    /**
     * Display a listing of Report resources.
     *
     * @route GET /resource/lead/reports playground.lead.resource.reports
     */
    public function index(
        Requests\Report\IndexRequest $request
    ): JsonResponse|View|Resources\ReportCollection {

        $user = $request->user();

        $validated = $request->validated();

        $query = Report::addSelect(sprintf('%1$s.*', $this->packageInfo['table']));

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
            return (new Resources\ReportCollection($paginator))->response($request);
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
     * Restore the Report resource from the trash.
     *
     * @route PUT /resource/lead/reports/restore/{report} playground.lead.resource.reports.restore
     */
    public function restore(
        Report $report,
        Requests\Report\RestoreRequest $request
    ): JsonResponse|RedirectResponse|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        if ($user?->id) {
            $report->modified_by_id = $user->id;
        }

        $report->restore();

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
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
        ), ['report' => $report->id]));
    }

    /**
     * Display the Report resource.
     *
     * @route GET /resource/lead/reports/{report} playground.lead.resource.reports.show
     */
    public function show(
        Report $report,
        Requests\Report\ShowRequest $request
    ): JsonResponse|View|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $report->id,
            'timestamp' => Carbon::now()->toJson(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $meta['input'] = $request->input();
        $meta['validated'] = $request->validated();

        $data = [
            'data' => $report,
            'meta' => $meta,
        ];

        return view(sprintf('%1$s/detail', $this->packageInfo['view']), $data);
    }

    /**
     * Store a newly created API Report resource in storage.
     *
     * @route POST /resource/lead/reports playground.lead.resource.reports.post
     */
    public function store(
        Requests\Report\StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        $report = new Report($validated);

        if ($user?->id) {
            $report->created_by_id = $user->id;
        }

        $report->save();

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
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
        ), ['report' => $report->id]));
    }

    /**
     * Unlock the Report resource in storage.
     *
     * @route DELETE /resource/lead/reports/lock/{report} playground.lead.resource.reports.unlock
     */
    public function unlock(
        Report $report,
        Requests\Report\UnlockRequest $request
    ): JsonResponse|RedirectResponse|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        $report->locked = false;

        if ($user?->id) {
            $report->modified_by_id = $user->id;
        }

        $report->save();

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
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
        ), ['report' => $report->id]));
    }

    /**
     * Update the Report resource in storage.
     *
     * @route PATCH /resource/lead/reports/{report} playground.lead.resource.reports.patch
     */
    public function update(
        Report $report,
        Requests\Report\UpdateRequest $request
    ): JsonResponse|RedirectResponse|Resources\Report {

        $validated = $request->validated();

        $user = $request->user();

        $report->update($validated);

        if ($user?->id) {
            $report->modified_by_id = $user->id;
        }

        if ($request->expectsJson()) {
            return (new Resources\Report($report))->additional(['meta' => [
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
        ), ['report' => $report->id]));
    }
}
