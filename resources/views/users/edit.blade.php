@extends('layouts.app')@section('title','Edit User')@section('page_title','Edit User')
@section('content')<div class="card p-4 border-0 shadow-sm"><form method="POST" action="{{ route('users.update',$user) }}">@csrf @method('PUT') @include('users._form')<button class="btn btn-primary">Update</button></form></div>@endsection
