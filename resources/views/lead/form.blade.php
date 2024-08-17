@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::lead/form-info',
    'withFormStatus' => 'playground-lead-resource::lead/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::lead/form-publishing')
@endsection
