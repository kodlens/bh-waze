@extends('layouts.landowner-app')

@section('content')

    <boarding-house-bedspace prop-bh-id='{{ $bhouse_id }}' prop-bh-room-id='{{ $bh_room_id }}'></boarding-house-bedspace>

@endsection

