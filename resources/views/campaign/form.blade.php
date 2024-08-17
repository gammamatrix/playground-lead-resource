@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::campaign/form-info',
    'withFormStatus' => 'playground-lead-resource::campaign/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::campaign/form-publishing')
@endsection
