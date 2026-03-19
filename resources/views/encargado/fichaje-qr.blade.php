@extends('layouts.vertical', ['subtitle' => 'Terminal QR'])

@section('content')
    @include('layouts.partials/page-title', ['title' => 'Darkone', 'subtitle' => 'Terminal QR'])
    <livewire:encargado.fichaje-qr />
@endsection

@section('scripts')
@endsection
