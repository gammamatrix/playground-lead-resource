@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::report/form-info',
    'withFormStatus' => 'playground-lead-resource::report/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::report/form-publishing')
@endsection
