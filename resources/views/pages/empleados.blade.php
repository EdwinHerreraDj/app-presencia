@extends('layouts.vertical', ['subtitle' => 'Dashboard'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection


@section('content')
    {{-- Mensaje de éxito o de error --}}
    @if (session('success'))
        <script>
            const notyf = new Notyf({
                duration: 4000,
                dismissible: true,
                position: {
                    x: 'right',
                    y: 'top',
                },
            });

            // Mostrar mensaje de éxito
            notyf.success('{{ session('success') }}');
        </script>
    @elseif (session('error'))
        <script>
            const notyf = new Notyf({
                duration: 4000,
                dismissible: true,
                position: {
                    x: 'right',
                    y: 'top',
                },
            });
            notyf.error('{{ session('error') }}');
        </script>
    @endif

    @include('layouts.partials/page-title', ['title' => 'Empleados', 'subtitle' => 'Panel'])

    <livewire:empleados.index />
@endsection

@section('scripts')
@endsection
