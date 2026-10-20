@props(['label', 'value', 'detail' => ''])
<article class="stat">
    <strong>{{ $label }}</strong>
    <p class="value">{{ $value }}</p>
    <p>{{ $detail }}</p>
</article>
