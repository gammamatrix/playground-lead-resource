<?php

declare(strict_types=1);
/**
 * Playground
 */
namespace Playground\Lead\Resource\Http\Requests;

use Playground\Http\Requests\IndexRequest as BaseIndexRequest;

/**
 * \Playground\Lead\Resource\Http\Requests\IndexRequest
 */
class IndexRequest extends BaseIndexRequest
{
    /**
     * @var array<string, mixed>
     */
    protected array $paginationDates = [
        'created_at' => ['column' => 'created_at', 'label' => 'Created At', 'nullable' => true],
        'updated_at' => ['column' => 'updated_at', 'label' => 'Updated At', 'nullable' => true],
        'deleted_at' => ['column' => 'deleted_at', 'label' => 'Deleted At', 'nullable' => true],
        'start_at' => ['column' => 'start_at', 'label' => 'Start At', 'nullable' => true],
        'planned_start_at' => ['column' => 'planned_start_at', 'label' => 'Planned Start At', 'nullable' => true],
        'end_at' => ['column' => 'end_at', 'label' => 'End At', 'nullable' => true],
        'planned_end_at' => ['column' => 'planned_end_at', 'label' => 'Planned End At', 'nullable' => true],
        'calculated_at' => ['column' => 'calculated_at', 'label' => 'Calculated At', 'nullable' => true],
        'canceled_at' => ['column' => 'canceled_at', 'label' => 'Canceled At', 'nullable' => true],
        'closed_at' => ['column' => 'closed_at', 'label' => 'Closed At', 'nullable' => true],
        'embargo_at' => ['column' => 'embargo_at', 'label' => 'Embargo At', 'nullable' => true],
        'fixed_at' => ['column' => 'fixed_at', 'label' => 'Fixed At', 'nullable' => true],
        'postponed_at' => ['column' => 'postponed_at', 'label' => 'Postponed At', 'nullable' => true],
        'published_at' => ['column' => 'published_at', 'label' => 'Published At', 'nullable' => true],
        'released_at' => ['column' => 'released_at', 'label' => 'Released At', 'nullable' => true],
        'reported_at' => ['column' => 'reported_at', 'label' => 'Reported At', 'nullable' => true],
        'resolved_at' => ['column' => 'resolved_at', 'label' => 'Resolved At', 'nullable' => true],
        'resumed_at' => ['column' => 'resumed_at', 'label' => 'Resumed At', 'nullable' => true],
        'suspended_at' => ['column' => 'suspended_at', 'label' => 'Suspended At', 'nullable' => true],
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $paginationFlags = [
        'active' => ['column' => 'active', 'label' => 'Active', 'icon' => 'fa-solid fa-person-running'],
        'canceled' => ['column' => 'canceled', 'label' => 'Canceled', 'icon' => 'fa-solid fa-ban text-warning'],
        'closed' => ['column' => 'closed', 'label' => 'Closed', 'icon' => 'fa-solid fa-xmark'],
        'completed' => ['column' => 'completed', 'label' => 'Completed', 'icon' => 'fa-solid fa-check'],
        'duplicate' => ['column' => 'duplicate', 'label' => 'Duplicate', 'icon' => 'fa-solid fa-clone'],
        'featured' => ['column' => 'featured', 'label' => 'Featured', 'icon' => 'fa-solid fa-star'],
        'fixed' => ['column' => 'fixed', 'label' => 'Fixed', 'icon' => 'fa-solid fa-wrench'],
        'flagged' => ['column' => 'flagged', 'label' => 'Flagged', 'icon' => 'fa-solid fa-flag'],
        'internal' => ['column' => 'internal', 'label' => 'Internal', 'icon' => 'fa-solid fa-server'],
        'locked' => ['column' => 'locked', 'label' => 'Locked', 'icon' => 'fa-solid fa-lock text-warning'],
        'pending' => ['column' => 'pending', 'label' => 'Pending', 'icon' => 'fa-solid fa-circle-pause text-warning'],
        'planned' => ['column' => 'planned', 'label' => 'Planned', 'icon' => 'fa-solid fa-circle-pause text-success'],
        'prioritized' => ['column' => 'prioritized', 'label' => 'Prioritized', 'icon' => 'fa-regular fa-calendar-check'],
        'problem' => ['column' => 'problem', 'label' => 'Problem', 'icon' => 'fa-solid fa-triangle-exclamation text-danger'],
        'published' => ['column' => 'published', 'label' => 'Published', 'icon' => 'fa-solid fa-book'],
        'released' => ['column' => 'released', 'label' => 'Released', 'icon' => 'fa-solid fa-dove'],
        'retired' => ['column' => 'retired', 'label' => 'Retired', 'icon' => 'fa-solid fa-chair text-success'],
        'resolved' => ['column' => 'resolved', 'label' => 'Resolved', 'icon' => 'fa-solid fa-check-double text-success'],
        'special' => ['column' => 'featured', 'label' => 'Featured', 'icon' => 'fa-solid fa-meteor'],
        'suspended' => ['column' => 'suspended', 'label' => 'Suspended', 'icon' => 'fa-solid fa-hand text-danger'],
        'unknown' => ['column' => 'unknown', 'label' => 'Unknown', 'icon' => 'fa-solid fa-question text-warning'],
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $paginationIds = [
        'id' => ['column' => 'id', 'label' => 'Id', 'type' => 'uuid', 'nullable' => false],
        'created_by_id' => ['column' => 'created_by_id', 'label' => 'Created By Id', 'type' => 'uuid', 'nullable' => true],
        'modified_by_id' => ['column' => 'modified_by_id', 'label' => 'Modified By Id', 'type' => 'uuid', 'nullable' => true],
        'owned_by_id' => ['column' => 'owned_by_id', 'label' => 'Owned By Id', 'type' => 'uuid', 'nullable' => true],
        'parent_id' => ['column' => 'parent_id', 'label' => 'Parent Id', 'type' => 'uuid', 'nullable' => true],
        'model_type' => ['column' => 'model_type', 'label' => 'Model Type', 'type' => 'string', 'nullable' => true],
        'campaign_id' => ['column' => 'campaign_id', 'label' => 'Campaign Id', 'type' => 'uuid', 'nullable' => true],
        'goal_id' => ['column' => 'goal_id', 'label' => 'Goal Id', 'type' => 'uuid', 'nullable' => true],
        'lead_id' => ['column' => 'lead_id', 'label' => 'Lead Id', 'type' => 'uuid', 'nullable' => true],
        'opportunity_id' => ['column' => 'opportunity_id', 'label' => 'Opportunity Id', 'type' => 'uuid', 'nullable' => true],
        'plan_id' => ['column' => 'plan_id', 'label' => 'Plan Id', 'type' => 'uuid', 'nullable' => true],
        'region_id' => ['column' => 'region_id', 'label' => 'Region Id', 'type' => 'uuid', 'nullable' => true],
        'report_id' => ['column' => 'report_id', 'label' => 'Report Id', 'type' => 'uuid', 'nullable' => true],
        'source_id' => ['column' => 'source_id', 'label' => 'Source Id', 'type' => 'uuid', 'nullable' => true],
        'task_id' => ['column' => 'task_id', 'label' => 'Task Id', 'type' => 'uuid', 'nullable' => true],
        'team_id' => ['column' => 'team_id', 'label' => 'Team Id', 'type' => 'uuid', 'nullable' => true],
        'teammate_id' => ['column' => 'teammate_id', 'label' => 'Teammate Id', 'type' => 'uuid', 'nullable' => true],
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $paginationColumns = [
        'label' => ['column' => 'label', 'label' => 'Label', 'type' => 'string', 'nullable' => false],
        'title' => ['column' => 'title', 'label' => 'Title', 'type' => 'string', 'nullable' => false],
        'byline' => ['column' => 'byline', 'label' => 'Byline', 'type' => 'string', 'nullable' => false],
        'slug' => ['column' => 'slug', 'label' => 'Slug', 'type' => 'string', 'nullable' => true],
        'url' => ['column' => 'url', 'label' => 'Url', 'type' => 'string', 'nullable' => false],
        'description' => ['column' => 'description', 'label' => 'Description', 'type' => 'string', 'nullable' => false],
        'introduction' => ['column' => 'introduction', 'label' => 'Introduction', 'type' => 'string', 'nullable' => false],
        'content' => ['column' => 'content', 'label' => 'Content', 'type' => 'mediumText', 'nullable' => true],
        'summary' => ['column' => 'summary', 'label' => 'Summary', 'type' => 'mediumText', 'nullable' => true],
        'locale' => ['column' => 'locale', 'label' => 'Locale', 'type' => 'string', 'nullable' => false],
        'currency' => ['column' => 'currency', 'label' => 'Currency', 'type' => 'string', 'nullable' => false],
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $sortable = [
        'id' => ['column' => 'id', 'label' => 'Id', 'type' => 'string'],
        'created_by_id' => ['column' => 'created_by_id', 'label' => 'Created By Id', 'type' => 'string'],
        'modified_by_id' => ['column' => 'modified_by_id', 'label' => 'Modified By Id', 'type' => 'string'],
        'owned_by_id' => ['column' => 'owned_by_id', 'label' => 'Owned By Id', 'type' => 'string'],
        'parent_id' => ['column' => 'parent_id', 'label' => 'Parent Id', 'type' => 'string'],
        'model_type' => ['column' => 'model_type', 'label' => 'Model Type', 'type' => 'string'],
        'campaign_id' => ['column' => 'campaign_id', 'label' => 'Campaign Id', 'type' => 'string'],
        'goal_id' => ['column' => 'goal_id', 'label' => 'Goal Id', 'type' => 'string'],
        'lead_id' => ['column' => 'lead_id', 'label' => 'Lead Id', 'type' => 'string'],
        'opportunity_id' => ['column' => 'opportunity_id', 'label' => 'Opportunity Id', 'type' => 'string'],
        'plan_id' => ['column' => 'plan_id', 'label' => 'Plan Id', 'type' => 'string'],
        'region_id' => ['column' => 'region_id', 'label' => 'Region Id', 'type' => 'string'],
        'report_id' => ['column' => 'report_id', 'label' => 'Report Id', 'type' => 'string'],
        'source_id' => ['column' => 'source_id', 'label' => 'Source Id', 'type' => 'string'],
        'task_id' => ['column' => 'task_id', 'label' => 'Task Id', 'type' => 'string'],
        'team_id' => ['column' => 'team_id', 'label' => 'Team Id', 'type' => 'string'],
        'teammate_id' => ['column' => 'teammate_id', 'label' => 'Teammate Id', 'type' => 'string'],
        'version_id' => ['column' => 'version_id', 'label' => 'Version Id', 'type' => 'string'],
        'created_at' => ['column' => 'created_at', 'label' => 'Created At', 'type' => 'string'],
        'updated_at' => ['column' => 'updated_at', 'label' => 'Updated At', 'type' => 'string'],
        'deleted_at' => ['column' => 'deleted_at', 'label' => 'Deleted At', 'type' => 'string'],
        'start_at' => ['column' => 'start_at', 'label' => 'Start At', 'type' => 'string'],
        'planned_start_at' => ['column' => 'planned_start_at', 'label' => 'Planned Start At', 'type' => 'string'],
        'end_at' => ['column' => 'end_at', 'label' => 'End At', 'type' => 'string'],
        'planned_end_at' => ['column' => 'planned_end_at', 'label' => 'Planned End At', 'type' => 'string'],
        'calculated_at' => ['column' => 'calculated_at', 'label' => 'Calculated At', 'type' => 'string'],
        'canceled_at' => ['column' => 'canceled_at', 'label' => 'Canceled At', 'type' => 'string'],
        'closed_at' => ['column' => 'closed_at', 'label' => 'Closed At', 'type' => 'string'],
        'embargo_at' => ['column' => 'embargo_at', 'label' => 'Embargo At', 'type' => 'string'],
        'fixed_at' => ['column' => 'fixed_at', 'label' => 'Fixed At', 'type' => 'string'],
        'postponed_at' => ['column' => 'postponed_at', 'label' => 'Postponed At', 'type' => 'string'],
        'published_at' => ['column' => 'published_at', 'label' => 'Published At', 'type' => 'string'],
        'released_at' => ['column' => 'released_at', 'label' => 'Released At', 'type' => 'string'],
        'reported_at' => ['column' => 'reported_at', 'label' => 'Reported At', 'type' => 'string'],
        'resolved_at' => ['column' => 'resolved_at', 'label' => 'Resolved At', 'type' => 'string'],
        'resumed_at' => ['column' => 'resumed_at', 'label' => 'Resumed At', 'type' => 'string'],
        'suspended_at' => ['column' => 'suspended_at', 'label' => 'Suspended At', 'type' => 'string'],
        'gids' => ['column' => 'gids', 'label' => 'Gids', 'type' => 'integer'],
        'po' => ['column' => 'po', 'label' => 'Po', 'type' => 'integer'],
        'pg' => ['column' => 'pg', 'label' => 'Pg', 'type' => 'integer'],
        'pw' => ['column' => 'pw', 'label' => 'Pw', 'type' => 'integer'],
        'status' => ['column' => 'status', 'label' => 'Status', 'type' => 'integer'],
        'rank' => ['column' => 'rank', 'label' => 'Rank', 'type' => 'integer'],
        'size' => ['column' => 'size', 'label' => 'Size', 'type' => 'integer'],
        'active' => ['column' => 'active', 'label' => 'Active', 'type' => 'boolean'],
        'canceled' => ['column' => 'canceled', 'label' => 'Canceled', 'type' => 'boolean'],
        'closed' => ['column' => 'closed', 'label' => 'Closed', 'type' => 'boolean'],
        'completed' => ['column' => 'completed', 'label' => 'Completed', 'type' => 'boolean'],
        'duplicate' => ['column' => 'duplicate', 'label' => 'Duplicate', 'type' => 'boolean'],
        'featured' => ['column' => 'featured', 'label' => 'Featured', 'type' => 'boolean'],
        'fixed' => ['column' => 'fixed', 'label' => 'Fixed', 'type' => 'boolean'],
        'flagged' => ['column' => 'flagged', 'label' => 'Flagged', 'type' => 'boolean'],
        'internal' => ['column' => 'internal', 'label' => 'Internal', 'type' => 'boolean'],
        'locked' => ['column' => 'locked', 'label' => 'Locked', 'type' => 'boolean'],
        'pending' => ['column' => 'pending', 'label' => 'Pending', 'type' => 'boolean'],
        'planned' => ['column' => 'planned', 'label' => 'Planned', 'type' => 'boolean'],
        'prioritized' => ['column' => 'prioritized', 'label' => 'Prioritized', 'type' => 'boolean'],
        'problem' => ['column' => 'problem', 'label' => 'Problem', 'type' => 'boolean'],
        'published' => ['column' => 'published', 'label' => 'Published', 'type' => 'boolean'],
        'released' => ['column' => 'released', 'label' => 'Released', 'type' => 'boolean'],
        'retired' => ['column' => 'retired', 'label' => 'Retired', 'type' => 'boolean'],
        'resolved' => ['column' => 'resolved', 'label' => 'Resolved', 'type' => 'boolean'],
        'special' => ['column' => 'special', 'label' => 'Special', 'type' => 'boolean'],
        'suspended' => ['column' => 'suspended', 'label' => 'Suspended', 'type' => 'boolean'],
        'unknown' => ['column' => 'unknown', 'label' => 'Unknown', 'type' => 'boolean'],
        'label' => ['column' => 'label', 'label' => 'Label', 'type' => 'string'],
        'title' => ['column' => 'title', 'label' => 'Title', 'type' => 'string'],
        'byline' => ['column' => 'byline', 'label' => 'Byline', 'type' => 'string'],
        'slug' => ['column' => 'slug', 'label' => 'Slug', 'type' => 'string'],
        'url' => ['column' => 'url', 'label' => 'Url', 'type' => 'string'],
        'description' => ['column' => 'description', 'label' => 'Description', 'type' => 'string'],
        'introduction' => ['column' => 'introduction', 'label' => 'Introduction', 'type' => 'string'],
        'content' => ['column' => 'content', 'label' => 'Content', 'type' => 'string'],
        'summary' => ['column' => 'summary', 'label' => 'Summary', 'type' => 'string'],
        'icon' => ['column' => 'icon', 'label' => 'Icon', 'type' => 'string'],
        'image' => ['column' => 'image', 'label' => 'Image', 'type' => 'string'],
        'avatar' => ['column' => 'avatar', 'label' => 'Avatar', 'type' => 'string'],
        'locale' => ['column' => 'locale', 'label' => 'Locale', 'type' => 'string'],
    ];
}
