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
use Playground\Lead\Models\Report;
use Playground\Lead\Resource\Http\Requests\Report\CreateRequest;
use Playground\Lead\Resource\Http\Requests\Report\DestroyRequest;
use Playground\Lead\Resource\Http\Requests\Report\EditRequest;
use Playground\Lead\Resource\Http\Requests\Report\IndexRequest;
use Playground\Lead\Resource\Http\Requests\Report\LockRequest;
use Playground\Lead\Resource\Http\Requests\Report\RestoreRequest;
use Playground\Lead\Resource\Http\Requests\Report\ShowRequest;
use Playground\Lead\Resource\Http\Requests\Report\StoreRequest;
use Playground\Lead\Resource\Http\Requests\Report\UnlockRequest;
use Playground\Lead\Resource\Http\Requests\Report\UpdateRequest;
use Playground\Lead\Resource\Http\Resources\Report as ReportResource;
use Playground\Lead\Resource\Http\Resources\ReportCollection;

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
     * Create information or form for the Report resource in storage.
     *
     * @route GET /resource/lead/reports/create playground.lead.resource.reports.create
     */
    public function create(
        CreateRequest $request
    ): JsonResponse|ReportResource|View {

        $validated = $request->validated();

        $report = new Report($validated);

        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $user = $request->user();

        $report = new Report($validated);

        $meta = [
            'session_user_id' => $user?->id,
            'id' => null,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

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

        session()->flashInput($flash);

        return view($this->getViewPath('report', 'form'), $data);
    }

    /**
     * Edit information for the Report resource in storage.
     *
     * @route GET /resource/lead/reports/edit playground.lead.resource.reports.edit
     */
    public function edit(
        Report $report,
        EditRequest $request
    ): JsonResponse|ReportResource|View {

        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $user = $request->user();

        $meta = [
            'session_user_id' => $user?->id,
            'id' => $report->id,
            'timestamp' => Carbon::now()->toJson(),
            'input' => $request->input(),
            'validated' => $validated,
            'info' => $this->packageInfo,
        ];

        $data = [
            'data' => $report,
            'meta' => $meta,
            '_method' => 'patch',
        ];

        $flash = $report->toArray();

        if (! empty($validated['_return_url'])) {
            $flash['_return_url'] = $validated['_return_url'];
            $data['_return_url'] = $validated['_return_url'];
        }

        session()->flashInput($flash);

        return view(
            'playground-lead-resource::report/form',
            $data
        );
    }

    /**
     * Remove the Report resource from storage.
     *
     * @route DELETE /resource/lead/reports/{report} playground.lead.resource.reports.destroy
     */
    public function destroy(
        Report $report,
        DestroyRequest $request
    ): Response|RedirectResponse {
        $validated = $request->validated();

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

        return redirect(route('playground.lead.resource.reports'));
    }

    /**
     * Lock the Report resource in storage.
     *
     * @route PUT /resource/lead/reports/{report} playground.lead.resource.reports.lock
     */
    public function lock(
        Report $report,
        LockRequest $request
    ): JsonResponse|RedirectResponse|ReportResource {

        $report->setAttribute('locked', true);

        $report->save();

        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.reports.show', ['report' => $report->id]));
    }

    /**
     * Display a listing of Report resources.
     *
     * @route GET /resource/lead/reports playground.lead.resource.reports
     */
    public function index(
        IndexRequest $request
    ): JsonResponse|View|ReportCollection {
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
            return (new ReportCollection($paginator))->additional(['meta' => [
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
            'playground-lead-resource::report/index',
            $data
        );
    }

    /**
     * Restore the Report resource from the trash.
     *
     * @route PUT /resource/lead/reports/restore/{report} playground.lead.resource.reports.restore
     */
    public function restore(
        Report $report,
        RestoreRequest $request
    ): JsonResponse|RedirectResponse|ReportResource {
        $validated = $request->validated();

        $user = $request->user();

        $report->restore();

        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.reports.show', ['report' => $report->id]));
    }

    /**
     * Display the Report resource.
     *
     * @route GET /resource/lead/reports/{report} playground.lead.resource.reports.show
     */
    public function show(
        Report $report,
        ShowRequest $request
    ): JsonResponse|View|ReportResource {
        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }
        $validated = $request->validated();

        $user = $request->user();

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
        ];

        return view(
            'playground-lead-resource::report/detail',
            $data
        );
    }

    /**
     * Store a newly created Report resource in storage.
     *
     * @route POST /resource/lead/reports playground.lead.resource.reports.post
     */
    public function store(
        StoreRequest $request
    ): Response|JsonResponse|RedirectResponse|ReportResource {
        $validated = $request->validated();

        $user = $request->user();

        $report = new Report($validated);

        $report->created_by_id = $user?->id;

        $report->save();

        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.reports.show', ['report' => $report->id]));
    }

    /**
     * Unlock the Report resource in storage.
     *
     * @route DELETE /resource/lead/reports/lock/{report} playground.lead.resource.reports.unlock
     */
    public function unlock(
        Report $report,
        UnlockRequest $request
    ): JsonResponse|RedirectResponse|ReportResource {

        $report->setAttribute('locked', false);

        $report->save();

        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $validated = $request->validated();

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.reports.show', ['report' => $report->id]));
    }

    /**
     * Update the Report resource in storage.
     *
     * @route PATCH /resource/lead/reports/{report} playground.lead.resource.reports.patch
     */
    public function update(
        Report $report,
        UpdateRequest $request
    ): JsonResponse|RedirectResponse|ReportResource {
        $validated = $request->validated();

        $user = $request->user();

        $report->modified_by_id = $user?->id;

        $report->update($validated);

        if ($request->expectsJson()) {
            return (new ReportResource($report))->additional(['meta' => [
                'info' => $this->packageInfo,
            ]])->response($request);
        }

        $returnUrl = $validated['_return_url'] ?? '';

        if ($returnUrl && is_string($returnUrl)) {
            return redirect($returnUrl);
        }

        return redirect(route('playground.lead.resource.reports.show', ['report' => $report->id]));
    }
}
