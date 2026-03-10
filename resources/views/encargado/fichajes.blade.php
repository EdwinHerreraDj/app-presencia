@extends('layouts.vertical', ['subtitle' => 'Fichaje Manual'])

@section('content')
    @include('layouts.partials/page-title', ['title' => 'Darkone', 'subtitle' => 'Fichaje Manual'])

    <livewire:encargado.fichaje-manual />
@endsection

@section('scripts')
@endsection
