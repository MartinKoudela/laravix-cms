@if ($taxonomies->isNotEmpty())
    <ul class="noir-tags">
        @foreach ($taxonomies as $taxonomy)
            <li class="noir-tag">{{ $taxonomy->name }}</li>
        @endforeach
    </ul>
@endif
