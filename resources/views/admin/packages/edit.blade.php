@extends('layouts.app')
@section('content')
    <h1>Paketi Düzenle: {{ $package->name }}</h1>
    <form action="{{ route('admin.packages.update', $package) }}" method="POST">
        @method('PUT')
        @include('admin.packages._form')
    </form>
@endsection
