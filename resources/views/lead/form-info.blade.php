<fieldset class="mb-3">
    <legend>Information</legend>

    <x-playground::forms.column
        column="title"
        label="Title"
        :autocomplete="false"
        :rules="[
            'required' => true,
            'maxlength' => 255,
        ]"
    >
        You should provide a
        <strong>title.</strong>
    </x-playground::forms.column>

    <x-playground::forms.column
        column="label"
        label="Label"
        :autocomplete="false"
        :rules="[
            'required' => false,
            'maxlength' => 255,
        ]"
    ></x-playground::forms.column>

    <x-playground::forms.column
        column="slug"
        label="SLUG"
        :autocomplete="false"
        :rules="[
            'required' => !empty($_method) && 'patch' === $_method,
            'maxlength' => 255,
        ]"
    />

    <x-playground::forms.column
        column="lead_type"
        label="Lead Type"
        :rules="['maxlength' => 255]"
    />

    @php
        if ("patch" === $_method) {
            $parents = Playground\Lead\Models\Lead::where("id", "!=", $data->id)->get();
        } else {
            $parents = Playground\Lead\Models\Lead::isNotClosed()
                ->isActive()
                ->get();
        }
    @endphp

    @if ($parents->isEmpty())
        <input type="hidden" name="parent_id" value="" />
    @else
        <x-playground::forms.column-select
            column="parent_id"
            key="label"
            label="Lead"
            :records="$parents->toArray()"
        />
    @endif
</fieldset>
