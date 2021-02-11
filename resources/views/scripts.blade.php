@if (!isset($scripts))
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.js"></script>
@else
    @foreach ($scripts as $link)
        <script src="{{ $link }}"></script>
    @endforeach
@endif

<script>
    @if(isset($extra))
        {!! $extra !!}
    @endif
</script>