@extends('layouts.landowner-app')



@section('content')

    <bhouse-room prop-data-id='{{ $id }}' prop-route-back="{{url()->previous()}}"></bhouse-room>


@endsection

