@extends('layouts.app') {{-- Ana layout dosyanızın yolunu belirtin --}}

@section('content')
    <div class="container">
        <h1>Yeni Demirbaş Ekle</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('fixtures.store') }}" method="POST">
            @include('fixtures._form')
        </form>
    </div>
@endsection
