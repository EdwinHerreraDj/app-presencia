<div class="container py-4" x-data x-ref="terminal">

    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7 col-xl-6">

            {{-- HEADER --}}
            <div class="text-center mb-4">
                <h1 class="fw-bold mb-1">Terminal de fichaje</h1>
                <div class="text-muted">
                    Selecciona el punto y registra con DNI/NIE
                </div>
            </div>

            {{-- CARD PRINCIPAL --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    {{-- Selección de empresa --}}
                    @if (!$empresa_id)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-center w-100">
                                Punto de fichaje
                            </label>

                            <select wire:model.live="empresa_id"
                                class="form-select form-select-lg text-center rounded-3">
                                <option value="">Selecciona punto de fichaje</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="form-text text-center mt-2">
                                Si no aparece, revisa que el punto esté activo.
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border rounded-3 text-center mb-4">
                            <div class="text-muted small">Punto de fichaje activo</div>
                            <div class="fw-bold fs-5">
                                {{ $empresas->firstWhere('id', $empresa_id)?->nombre }}
                            </div>
                        </div>
                    @endif

                    {{-- DNI --}}
                    @if ($empresa_id)
                        <form wire:submit.prevent="registrar">

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-center w-100">
                                    DNI / NIE
                                </label>

                                <input type="text" wire:model.defer="dni" x-ref="dni" autofocus
                                    placeholder="Introduce DNI o NIE"
                                    class="form-control form-control-lg text-center fw-semibold rounded-3"
                                    style="letter-spacing: .08em; font-size: 1.4rem;">
                            </div>

                            <button type="submit" wire:loading.attr="disabled"
                                class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold">

                                <span wire:loading.remove>Registrar</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true"></span>
                                    Procesando…
                                </span>
                            </button>

                            <div class="text-center text-muted small mt-3">
                                El sistema registrará automáticamente entrada o salida.
                            </div>

                        </form>
                    @endif

                </div>
            </div>

            {{-- NOTA INFERIOR --}}
            <div class="text-center text-muted small mt-3">
                Si el punto requiere ubicación, permite el acceso al GPS.
            </div>

        </div>
    </div>

    {{-- MODAL RESULTADO --}}
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.75)"
            data-bs-backdrop="static" data-bs-keyboard="false">

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="modal-header border-0 px-5 py-5 text-center d-block bg-light">
                        @if ($estado === 'ok')
                            <div class="text-success mb-3" style="font-size: 4.5rem; line-height: 1;">✔</div>
                            <h2 class="fw-bold mb-1" style="font-size: 2rem;">
                                Fichaje correcto
                            </h2>
                            <div class="text-muted" style="font-size: 1.1rem;">
                                Registro confirmado
                            </div>
                        @else
                            <div class="text-danger mb-3" style="font-size: 4.5rem; line-height: 1;">✖</div>
                            <h2 class="fw-bold mb-1" style="font-size: 2rem;">
                                Error en el fichaje
                            </h2>
                            <div class="text-muted" style="font-size: 1.1rem;">
                                Revisa el mensaje
                            </div>
                        @endif
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body px-5 py-4 text-center">
                        @if ($estado === 'ok')
                            <div class="fw-bold mb-2" style="font-size: 1.8rem;">
                                {{ $nombreEmpleado }}
                            </div>

                            <div class="text-muted" style="font-size: 1.25rem;">
                                {{ $mensaje }}
                            </div>
                        @else
                            <div class="text-dark" style="font-size: 1.25rem;">
                                {{ $mensaje }}
                            </div>
                        @endif
                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0 px-5 pb-5 pt-0">
                        <button wire:click="cerrarModal" class="btn btn-primary btn-lg w-100 rounded-3 fw-semibold py-3"
                            style="font-size: 1.25rem;">
                            Aceptar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


</div>
