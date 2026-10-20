@foreach (['success', 'warning', 'error'] as $key)
    @if (session($key))
        <div class="notice" data-type="{{ $key }}" role="status">{{ session($key) }}</div>
    @endif
@endforeach
