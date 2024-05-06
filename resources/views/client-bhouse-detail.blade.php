@extends('layouts.main-navbar')

@section('extracss')
    <link rel="stylesheet" href="{{ asset('/css/leaflet.css ') }}">

    <link rel="stylesheet" href="{{ asset('/css/leaflet-routing-machine.css ') }}">
@endsection

@section('extrajs')
    <script src="{{ asset('/js/leaflet.js') }}"></script>

    <script src="{{ asset('/js/leaflet-routing-machine.js') }}"></script>
@endsection


@section('content')


    <client-bhouse-detail prop-bhouse-id="{{ $id }}"></client-bhouse-detail>

@endsection
