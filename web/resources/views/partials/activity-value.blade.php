@if(is_null($value))
    <span class="badge bg-secondary-subtle text-secondary-emphasis">null</span>
@elseif(is_bool($value))
    <span class="badge {{ $value ? 'bg-success' : 'bg-secondary' }}">
        {{ $value ? 'true' : 'false' }}
    </span>
@elseif(is_scalar($value))
    <span>{{ $value }}</span>
@else
    @php
        $items = $value instanceof \Illuminate\Support\Collection ? $value->toArray() : (array) $value;
    @endphp

    @if(empty($items))
        <span class="text-muted fst-italic">[]</span>
    @else
        <ul class="list-unstyled ps-3 mb-0 border-start border-2">
            @foreach($items as $subKey => $subVal)
                <li class="my-1">
                    @if(!is_int($subKey))
                        <code class="text-body-secondary">{{ $subKey }}</code>:
                    @endif
                    @include('partials.activity-value', ['value' => $subVal])
                </li>
            @endforeach
        </ul>
    @endif
@endif