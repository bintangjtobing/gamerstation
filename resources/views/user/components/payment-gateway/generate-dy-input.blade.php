@isset($input_fields)
    @foreach ($input_fields ?? [] as $item)
        @if ($item->type == "text")
            @include('admin.components.form.input',[
                'label'     => $item->label,
                'name'      => $item->name,
                'attribute' => ($item->required == true) ? "required=true" : "",
                'class'     => "mb-3",
                'value'     => old($item->name),
            ])
        @elseif ($item->type == "file")
            @include('admin.components.form.input',[
                'label'     => $item->label,
                'type'      => "file",
                'name'      => $item->name,
                'attribute' => ($item->required == true) ? "required=true" : "",
                'class'     => "mb-3",
            ])
        @elseif ($item->type == "textarea")
            @include('admin.components.form.textarea',[
                'label'     => $item->label,
                'name'      => $item->name,
                'attribute' => ($item->required == true) ? "required=true" : "",
                'class'     => "form--control mb-3",
                'value'     => old($item->name),
            ])
        @endif
    @endforeach
@endisset