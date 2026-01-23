@extends('layouts.vertical', ['subtitle' => 'Terminal Fichaje'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection


@section('content')
    @livewire('terminal-fichaje')
@endsection
