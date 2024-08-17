@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::teammate/form-info',
    'withFormStatus' => 'playground-lead-resource::teammate/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::teammate/form-publishing')
@endsection
