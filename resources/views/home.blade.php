@extends('layouts.app')

@section('content')
    <app-home appname=" {{config('app.name', 'Jobs Tracker')}}"></app-home>
@endsection
