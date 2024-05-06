@extends('layouts.main-navbar')


@section('content')

    <client-bh-rooms prop-data='@json($data)'></client-bh-rooms>


@endsection
