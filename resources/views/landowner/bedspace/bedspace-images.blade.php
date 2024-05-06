@extends('layouts.landowner-app')

@section('content')

    <bedspace-images prop-bh-id='{{ $bhId }}' prop-bh-room-id='{{ $roomId }}' prop-bedspace-id='{{ $bedspaceId }}'></bedspace-images>

@endsection
