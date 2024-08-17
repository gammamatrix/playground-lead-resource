@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::opportunity/form-info',
    'withFormStatus' => 'playground-lead-resource::opportunity/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::opportunity/form-publishing')
@endsection
