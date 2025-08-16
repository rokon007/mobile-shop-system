@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger small mt-1 mb-0']) }}>
        @foreach ((array) $messages as $message)
            <li><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</li>
        @endforeach
    </ul>
@endif
