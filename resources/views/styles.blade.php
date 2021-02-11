@if (!isset($styles))
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.css" rel="stylesheet" />
@else
    @foreach ($styles as $link)
        <link href="{{ $link }}" rel="stylesheet" />
    @endforeach
@endif