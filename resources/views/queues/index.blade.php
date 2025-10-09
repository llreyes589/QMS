@extends('layouts.app')
@section('content')


<queue-table :queues="{{$queues}}"></queue-table>

@endsection