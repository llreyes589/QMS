@extends('layouts.app')
@section('content')

<queue-table :queues="{{$queues}}" :types="{{$types}}"></queue-table>

@endsection