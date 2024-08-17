@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::plan/form-info',
    'withFormStatus' => 'playground-lead-resource::plan/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::plan/form-publishing')
@endsection
