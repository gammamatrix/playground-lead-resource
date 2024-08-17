@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::region/form-info',
    'withFormStatus' => 'playground-lead-resource::region/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::region/form-publishing')
@endsection
