@extends('layouts.app')
@section('content')

<new-queue :rooms='{{$rooms}}'></new-queue>
@endsection