@extends('layouts.app')

@section('content')
  <tasks-component :tasks="{{ $tasks }}"></tasks-component>
@endsection
