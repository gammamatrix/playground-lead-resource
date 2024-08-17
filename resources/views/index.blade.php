@extends('playground::layouts.resource.layout')

@section('title', 'Lead')

@section('breadcrumbs')
<div class="container-fluid mt-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('playground.lead.resource') }}">Lead</a></li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card my-1">
                <div class="card-header">
                    <h1>Lead</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Campaigns</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage campaigns</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.campaigns') }}">View Campaigns</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Goals</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage goals</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.goals') }}">View Goals</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Leads</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage leads</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.leads') }}">View Leads</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Opportunities</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage opportunities</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.opportunities') }}">View Opportunities</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Plans</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage plans</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.plans') }}">View Plans</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Regions</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage regions</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.regions') }}">View Regions</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Reports</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage reports</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.reports') }}">View Reports</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Sources</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage sources</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.sources') }}">View Sources</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Tasks</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage tasks</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.tasks') }}">View Tasks</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Teams</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage teams</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.teams') }}">View Teams</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">Teammates</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage teammates</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('playground.lead.resource.teammates') }}">View Teammates</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
