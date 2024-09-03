@extends('administrator::layouts.master')

@section('content')
<h1>Hello World</h1>

<p>Module: {!! config('administrator.name') !!}</p>
@endsection