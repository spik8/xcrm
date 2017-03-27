@extends('main')
@section('content')


    <form  action="/search" method="get">
        <input type="text"  name="q" placeholder="Search.."/>
        <button type="submit">Search</button>
    </form>
@endsection