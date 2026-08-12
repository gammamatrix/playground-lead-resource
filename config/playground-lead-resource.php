<?php

/**
 * Playground
 */

declare(strict_types=1);
use Illuminate\Database\Eloquent\Model;
use Playground\Auth\Policies\Policy;
use Playground\Lead\Models\Campaign;
use Playground\Lead\Models\Goal;
use Playground\Lead\Models\Lead;
use Playground\Lead\Models\Opportunity;
use Playground\Lead\Models\Plan;
use Playground\Lead\Models\Region;
use Playground\Lead\Models\Report;
use Playground\Lead\Models\Source;
use Playground\Lead\Models\Task;
use Playground\Lead\Models\Team;
use Playground\Lead\Models\Teammate;
use Playground\Lead\Resource\Policies\CampaignPolicy;
use Playground\Lead\Resource\Policies\GoalPolicy;
use Playground\Lead\Resource\Policies\LeadPolicy;
use Playground\Lead\Resource\Policies\OpportunityPolicy;
use Playground\Lead\Resource\Policies\PlanPolicy;
use Playground\Lead\Resource\Policies\RegionPolicy;
use Playground\Lead\Resource\Policies\ReportPolicy;
use Playground\Lead\Resource\Policies\SourcePolicy;
use Playground\Lead\Resource\Policies\TaskPolicy;
use Playground\Lead\Resource\Policies\TeammatePolicy;
use Playground\Lead\Resource\Policies\TeamPolicy;

/**
 * Playground: Lead Resource Configuration and Environment Variables
 *
 * @return array{
 *       about: bool,
 *       layout: string,
 *       load: array{
 *           policies: bool,
 *           routes: bool,
 *           translations: bool,
 *           views: bool
 *       },
 *       middleware: array{
 *           default: string|string[],
 *           auth: string|string[],
 *           guest: string|string[]
 *       },
 *       policies: array<
 *           class-string<Model>,
 *           class-string<Policy>
 *       >,
 *       routes: array{
 *           lead: bool,
 *           campaigns: bool,
 *           goals: bool,
 *           leads: bool,
 *           opportunities: bool,
 *           plans: bool,
 *           regions: bool,
 *           reports: bool,
 *           sources: bool,
 *           tasks: bool,
 *           teams: bool,
 *           teammates: bool,
 *       },
 *       blade: string,
 *       abilities: array<string, string[]>,
 *       sitemap: array{
 *            enable: bool,
 *            guest: bool,
 *            user: bool,
 *            view: string
 *       }
 *   }
 */
return [

    /*
    |--------------------------------------------------------------------------
    | About Information
    |--------------------------------------------------------------------------
    |
    | By default, information will be displayed about this package when using:
    |
    | `artisan about`
    |
    */

    'about' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ABOUT', true),

    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    |
    | By default, translations and views are loaded.
    |
    */

    'load' => [
        'policies' => (bool) env('PLAYGROUND_LEAD_RESOURCE_LOAD_POLICIES', true),
        'routes' => (bool) env('PLAYGROUND_LEAD_RESOURCE_LOAD_ROUTES', true),
        'translations' => (bool) env('PLAYGROUND_LEAD_RESOURCE_LOAD_TRANSLATIONS', true),
        'views' => (bool) env('PLAYGROUND_LEAD_RESOURCE_LOAD_VIEWS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    |
    */

    'middleware' => [
        'default' => env('PLAYGROUND_LEAD_RESOURCE_MIDDLEWARE_DEFAULT', ['web']),
        'auth' => env('PLAYGROUND_LEAD_RESOURCE_MIDDLEWARE_AUTH', ['web', 'auth']),
        'guest' => env('PLAYGROUND_LEAD_RESOURCE_MIDDLEWARE_GUEST', ['web']),
    ],

    /*
    |--------------------------------------------------------------------------
    | Policies
    |--------------------------------------------------------------------------
    |
    |
    */

    'policies' => [
        Campaign::class => CampaignPolicy::class,
        Goal::class => GoalPolicy::class,
        Lead::class => LeadPolicy::class,
        Opportunity::class => OpportunityPolicy::class,
        Plan::class => PlanPolicy::class,
        Region::class => RegionPolicy::class,
        Report::class => ReportPolicy::class,
        Source::class => SourcePolicy::class,
        Task::class => TaskPolicy::class,
        Team::class => TeamPolicy::class,
        Teammate::class => TeammatePolicy::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    |
    */

    'routes' => [
        'lead' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_LEAD', true),
        'campaigns' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_CAMPAIGNS', true),
        'goals' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_GOALS', true),
        'leads' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_LEADS', true),
        'opportunities' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_OPPORTUNITIES', true),
        'plans' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_PLANS', true),
        'regions' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_REGIONS', true),
        'reports' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_REPORTS', true),
        'sources' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_SOURCES', true),
        'tasks' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_TASKS', true),
        'teams' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_TEAMS', true),
        'teammates' => (bool) env('PLAYGROUND_LEAD_RESOURCE_ROUTES_TEAMMATES', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap
    |--------------------------------------------------------------------------
    |
    |
    */

    'sitemap' => [
        'enable' => (bool) env('PLAYGROUND_LEAD_RESOURCE_SITEMAP_ENABLE', true),
        'guest' => (bool) env('PLAYGROUND_LEAD_RESOURCE_SITEMAP_GUEST', true),
        'user' => (bool) env('PLAYGROUND_LEAD_RESOURCE_SITEMAP_USER', true),
        'view' => env('PLAYGROUND_LEAD_RESOURCE_SITEMAP_VIEW', 'playground-lead-resource::sitemap'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    |
    */

    'blade' => env('PLAYGROUND_LEAD_RESOURCE_BLADE', 'playground-lead-resource::'),

    /*
    |--------------------------------------------------------------------------
    | Abilities
    |--------------------------------------------------------------------------
    |
    |
    */

    'abilities' => [
        'admin' => [
            'playground-lead-resource:*',
        ],
        'manager' => [
            'playground-lead-resource:campaign:*',
            'playground-lead-resource:goal:*',
            'playground-lead-resource:lead:*',
            'playground-lead-resource:opportunity:*',
            'playground-lead-resource:plan:*',
            'playground-lead-resource:region:*',
            'playground-lead-resource:report:*',
            'playground-lead-resource:source:*',
            'playground-lead-resource:task:*',
            'playground-lead-resource:team:*',
            'playground-lead-resource:teammate:*',
        ],
        'user' => [
            'playground-lead-resource:campaign:view',
            'playground-lead-resource:campaign:viewAny',
            'playground-lead-resource:goal:view',
            'playground-lead-resource:goal:viewAny',
            'playground-lead-resource:lead:view',
            'playground-lead-resource:lead:viewAny',
            'playground-lead-resource:opportunity:view',
            'playground-lead-resource:opportunity:viewAny',
            'playground-lead-resource:plan:view',
            'playground-lead-resource:plan:viewAny',
            'playground-lead-resource:region:view',
            'playground-lead-resource:region:viewAny',
            'playground-lead-resource:report:view',
            'playground-lead-resource:report:viewAny',
            'playground-lead-resource:source:view',
            'playground-lead-resource:source:viewAny',
            'playground-lead-resource:task:view',
            'playground-lead-resource:task:viewAny',
            'playground-lead-resource:team:view',
            'playground-lead-resource:team:viewAny',
            'playground-lead-resource:teammate:view',
            'playground-lead-resource:teammate:viewAny',
        ],
    ],
];
