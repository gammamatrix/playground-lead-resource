<fieldset class="mb-3">

    <legend>Information</legend>

    <x-playground::forms.column column="title" label="Title" :autocomplete="false" :rules="[
        'required' => true,
        'maxlength' => 255,
    ]">
        You should provide a <strong>title.</strong>
    </x-playground::forms.column>

    <x-playground::forms.column column="label" label="Label" :autocomplete="false" :rules="[
        'required' => false,
        'maxlength' => 255,
    ]">
    </x-playground::forms.column>

    <x-playground::forms.column column="slug" label="SLUG" :autocomplete="false" :rules="[
        'required' => !empty($_method) && 'patch' === $_method,
        'maxlength' => 255,
    ]" />

    <x-playground::forms.column column="report_type" label="Report Type" :rules="['maxlength' => 255]" />

    @if (!empty($parents))
    <x-playground::forms.column-select column="parent_id" key="label" label="Parent Report" :records="$parents"/>
    @endif

</fieldset>
