@extends('layouts.app')@section('title','Add User')@section('page_title','Add User')
@section('content')<div class="card p-4 border-0 shadow-sm"><form method="POST" action="{{ route('users.store') }}">@csrf @include('users._form')<button class="btn btn-primary">Save</button></form></div>@endsection
