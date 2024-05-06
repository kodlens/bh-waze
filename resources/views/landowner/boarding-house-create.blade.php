@extends('layouts.landowner-app')

@section('extracss')
    <link rel="stylesheet" href="{{ asset('/css/leaflet.css ') }}">
@endsection

@section('extrajs')
    <script src="{{ asset('/js/leaflet.js') }}"></script>
@endsection


@section('content')



    @if (isset($id))
        <boarding-house-create prop-data-id='{{ $id }}' prop-data='@json($bhouse)'></boarding-house-create>
    @else
        <boarding-house-create prop-data-id='0' prop-data=''></boarding-house-create>
    @endif


@endsection




