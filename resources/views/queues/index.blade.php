@extends('layouts.app')
@section('content')

<queue-table :queues="{{$queues}}" :types="{{$types}}" :rooms="{{$rooms}}" :time_interval="{{$time_interval}}"></queue-table>

@endsection