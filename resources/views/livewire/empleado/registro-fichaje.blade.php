<div>

    {{-- TARJETA SUPERIOR --}}
    <div class="card text-center shadow-sm">
        <h1 class="mt-3">{{ session('user_name') }}</h1>
        <div id="reloj" class="fs-1 fw-bold text-primary my-3"></div>
    </div>

    {{-- TARJETA PRINCIPAL --}}
    <div class="card mt-3 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Registro de presencia</h5>
        </div>

        <div class="card-body">

            {{-- EMPRESA --}}
            <label class="form-label fw-semibold">Empresa</label>
            <select class="form-select mb-3" wire:model="empresa_id" @if ($showModal) disabled @endif>
                <option value="">-- Selecciona --</option>
                @foreach ($empresas as $e)
                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                @endforeach
            </select>

            {{-- BOTONES --}}
            <div class="d-flex gap-3">
                <button class="btn btn-success w-50" onclick="obtenerUbicacion('entrada')"
                    @if ($showModal) disabled @endif>
                    Entrada
                </button>

                <button class="btn btn-danger w-50" onclick="obtenerUbicacion('salida')"
                    @if ($showModal) disabled @endif>
                    Salida
                </button>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.55)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- Header minimal --}}
                    <div class="px-4 pt-4 pb-2 text-center">
                        @if ($modalEstado === 'loading')
                            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                                Procesando
                            </span>
                        @endif

                        @if ($modalEstado === 'success')
                            <span class="badge rounded-pill text-bg-success px-3 py-2">
                                Correcto
                            </span>
                        @endif

                        @if ($modalEstado === 'error')
                            <span class="badge rounded-pill text-bg-danger px-3 py-2">
                                Error
                            </span>
                        @endif
                    </div>

                    <div class="modal-body text-center px-4 pb-4 pt-2">

                        {{-- ICONO --}}
                        @if ($modalEstado === 'loading')
                            <div class="d-flex justify-content-center mb-3">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                    role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">Procesando…</h5>
                            <p class="text-muted mb-0">{{ $modalMensaje }}</p>
                        @endif

                        @if ($modalEstado === 'success')
                            <div class="d-flex justify-content-center mb-3">
                                <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <span class="fs-2 fw-bold">✓</span>
                                </div>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">Correcto</h5>
                            <p class="text-muted mb-0">{{ $modalMensaje }}</p>
                        @endif

                        @if ($modalEstado === 'error')
                            <div class="d-flex justify-content-center mb-3">
                                <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center"
                                    style="width: 64px; height: 64px;">
                                    <span class="fs-2 fw-bold">✕</span>
                                </div>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">Ha ocurrido un error</h5>
                            <p class="text-muted mb-0">{{ $modalMensaje }}</p>
                        @endif

                    </div>

                    @if ($modalEstado !== 'loading')
                        <div class="modal-footer border-0 justify-content-center pb-4">
                            <button type="button" class="btn btn-outline-secondary px-4" wire:click="cerrarModal">
                                Cerrar
                            </button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif





    {{-- SCRIPT GEOLOCALIZACIÓN --}}
    <script>
        function obtenerUbicacion(tipo) {
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const component = Livewire.find('{{ $this->getId() }}');
                    component.set('latitud', pos.coords.latitude);
                    component.set('longitud', pos.coords.longitude);
                    component.call('registrar', tipo);
                },
                () => {
                    Livewire.find('{{ $this->getId() }}')
                        .call('setError', 'Activa la ubicación para fichar.');
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        setInterval(() => {
            const r = document.getElementById('reloj');
            if (r) r.innerText = new Date().toLocaleTimeString();
        }, 1000);
    </script>

</div>
