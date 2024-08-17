<?php
$user = \Illuminate\Support\Facades\Auth::user();

$viewCampaigns = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:campaign:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewGoals = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:goal:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewLeads = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:lead:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewOpportunities = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:opportunity:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewPlans = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:plan:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewRegions = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:region:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewReports = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:report:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewSources = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:source:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewTasks = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:task:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewTeams = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:team:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();

$viewTeammates = \Playground\Auth\Facades\Can::access($user, [
    'allow' => false,
    'any' => true,
    'privilege' => 'playground-lead-resource:teammate:viewAny',
    'roles' => ['admin', 'manager', 'publisher'],
])->allowed();


if (!$viewCampaigns && !$viewGoals && !$viewLeads && !$viewOpportunities && !$viewPlans && !$viewRegions && !$viewReports && !$viewSources && !$viewTasks && !$viewTeams && !$viewTeammates) {
    return;
}
?>
<div class="card my-1">
    <div class="card-body">

        <h2>Lead</h2>

        <div class="row">

            <div class="col-sm-6 mb-3">
                <div class="card">
                    <div class="card-header">
                    Lead Index
                    <small class="text-muted">campaigns, goals, leads, opportunities, plans, regions, reports, sources, tasks, teams and teammates</small>
                    </div>
                    <ul class="list-group list-group-flush">

                        @if ($viewCampaigns)
                        <a href="{{ route('playground.lead.resource.campaigns') }}" class="list-group-item list-group-item-action">
                            Campaigns
                        </a>
                        @endif

                        @if ($viewGoals)
                        <a href="{{ route('playground.lead.resource.goals') }}" class="list-group-item list-group-item-action">
                            Goals
                        </a>
                        @endif

                        @if ($viewLeads)
                        <a href="{{ route('playground.lead.resource.leads') }}" class="list-group-item list-group-item-action">
                            Leads
                        </a>
                        @endif

                        @if ($viewOpportunities)
                        <a href="{{ route('playground.lead.resource.opportunities') }}" class="list-group-item list-group-item-action">
                            Opportunities
                        </a>
                        @endif

                        @if ($viewPlans)
                        <a href="{{ route('playground.lead.resource.plans') }}" class="list-group-item list-group-item-action">
                            Plans
                        </a>
                        @endif

                        @if ($viewRegions)
                        <a href="{{ route('playground.lead.resource.regions') }}" class="list-group-item list-group-item-action">
                            Regions
                        </a>
                        @endif

                        @if ($viewReports)
                        <a href="{{ route('playground.lead.resource.reports') }}" class="list-group-item list-group-item-action">
                            Reports
                        </a>
                        @endif

                        @if ($viewSources)
                        <a href="{{ route('playground.lead.resource.sources') }}" class="list-group-item list-group-item-action">
                            Sources
                        </a>
                        @endif

                        @if ($viewTasks)
                        <a href="{{ route('playground.lead.resource.tasks') }}" class="list-group-item list-group-item-action">
                            Tasks
                        </a>
                        @endif

                        @if ($viewTeams)
                        <a href="{{ route('playground.lead.resource.teams') }}" class="list-group-item list-group-item-action">
                            Teams
                        </a>
                        @endif

                        @if ($viewTeammates)
                        <a href="{{ route('playground.lead.resource.teammates') }}" class="list-group-item list-group-item-action">
                            Teammates
                        </a>
                        @endif

                    </ul>
                </div>
            </div>

        </div>

    </div>
</div>
