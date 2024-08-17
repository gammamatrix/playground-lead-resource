@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::source/form-info',
    'withFormStatus' => 'playground-lead-resource::source/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::source/form-publishing')
@endsection
