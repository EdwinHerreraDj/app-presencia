<!DOCTYPE html>
<html lang="en" @yield('html-attribute')>

<head>
    @include('layouts.partials/title-meta')

    @include('layouts.partials/head-css')
    @livewireStyles
    {{-- DNS para los mapas --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    {{-- Notyf para notificaciones --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>


</head>

<body>


    <div class="app-wrapper">

        @include('layouts.partials/sidebar')

        @include('layouts.partials/topbar')

        <div class="page-content">

            <div class="container-fluid">

                @yield('content')

            </div>

            @include('layouts.partials/footer')
        </div>

    </div>

    <script>
        const notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top',
            },
            dismissible: true,
        });

        window.addEventListener('notify', event => {
            const detail = event.detail || {};

            if (detail.type === 'success') {
                notyf.success(detail.message || 'Operación realizada correctamente');
            } else if (detail.type === 'error' || detail.type === 'danger') {
                notyf.error(detail.message || 'Ha ocurrido un error');
            } else {
                notyf.open({
                    type: detail.type || 'info',
                    message: detail.message || ''
                });
            }
        });

        // Mantener sesión viva cada 5 minutos
        setInterval(() => {
            if (window.Livewire) {
                Livewire.dispatch('ping-session');
            }
        }, 5 * 60 * 1000);
    </script>





    @include('layouts.partials/vendor-scripts')

    @livewireScripts

    <script>
        let soundOk = null;
        let soundError = null;
        let soundsReady = false;

        function initSounds() {
            if (soundsReady) return;

            soundOk = new Audio('/sounds/ok.mp3');
            soundError = new Audio('/sounds/error.mp3');

            // Forzar carga
            soundOk.load();
            soundError.load();

            soundsReady = true;
        }

        // Se inicializan con la PRIMERA interacción del usuario
        document.addEventListener('click', initSounds, {
            once: true
        });

        window.addEventListener('terminal-sound', event => {
            if (!soundsReady) return;

            if (event.detail?.type === 'ok') {
                soundOk.currentTime = 0;
                soundOk.play();
            }

            if (event.detail?.type === 'error') {
                soundError.currentTime = 0;
                soundError.play();
            }
        });
    </script>


</body>

</html>
