@extends('layouts.app')
@section('content')

<new-queue :rooms='{{$rooms}}' :queue="{{$queue}}"></new-queue>
@endsection