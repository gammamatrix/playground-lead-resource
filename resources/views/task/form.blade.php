@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::task/form-info',
    'withFormStatus' => 'playground-lead-resource::task/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::task/form-publishing')
@endsection
