@extends('layouts.app')
@section('content')

<queue-table :queues="{{$queues}}" :types="{{$types}}" :rooms="{{$rooms}}"></queue-table>

@endsection