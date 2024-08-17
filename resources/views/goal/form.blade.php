@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::goal/form-info',
    'withFormStatus' => 'playground-lead-resource::goal/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::goal/form-publishing')
@endsection
