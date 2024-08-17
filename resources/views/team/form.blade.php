@extends('playground::layouts.resource.form', [
    'withFormInfo' => 'playground-lead-resource::team/form-info',
    'withFormStatus' => 'playground-lead-resource::team/form-status',
])

@section('form-tertiary')
@include('playground-lead-resource::team/form-publishing')
@endsection
