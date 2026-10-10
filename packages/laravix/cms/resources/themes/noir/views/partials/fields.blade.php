@if ($fields->isNotEmpty())
    <dl class="noir-fields">
        @foreach ($fields as $field)
            <div class="noir-fields__row">
                <dt class="noir-fields__key">{{ $field->key }}</dt>
                <dd class="noir-fields__value">{{ $field->value }}</dd>
            </div>
        @endforeach
    </dl>
@endif
