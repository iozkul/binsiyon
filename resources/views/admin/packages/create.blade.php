@extends('layouts.app')
@section('content')
    <h1>Yeni Paket Oluştur</h1>
    <form action="{{ route('admin.packages.store') }}" method="POST">
        @include('admin.packages._form')
    </form>
@endsection
