@extends('layouts.landowner-app')

@section('content')
    <landowner-dashboard prop-user="{{ auth()->user() }}"></landowner-dashboard>
@endsection

