<div
    style="min-height:100vh;background:#0a0c10;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:20px 16px;font-family:'Sora',sans-serif;position:relative;">

    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Fondo grid */
        .pres-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .pres-bg::after {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fu {
            animation: fadeUp .45s ease both;
        }

        .fu2 {
            animation: fadeUp .45s .1s ease both;
        }

        .fu3 {
            animation: fadeUp .45s .2s ease both;
        }

        /* Wrap centrado */
        .pres-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Card base */
        .pres-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 22px;
            overflow: hidden;
        }

        /* Franja de acento superior */
        .card-accent {
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
        }

        /* Select */
        .fm-select {
            background: #0f1117;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 12px;
            padding: 13px 40px 13px 16px;
            font-size: 14px;
            font-family: 'Sora', sans-serif;
            width: 100%;
            outline: none;
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2338bdf8' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            transition: border-color .2s;
        }

        .fm-select:focus {
            border-color: #3b82f6;
        }

        .fm-select option {
            background: #111318;
        }

        .fm-select:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* Label xs */
        .label-xs {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #475569;
            display: block;
            margin-bottom: 8px;
        }

        /* Botones de acción grandes */
        .btn-action {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            border-radius: 14px;
            padding: 18px 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: all .2s;
            min-height: 62px;
        }

        .btn-action:disabled {
            opacity: .35;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-entrada {
            background: rgba(52, 211, 153, .13);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, .3);
        }

        .btn-entrada:not(:disabled):hover {
            background: rgba(52, 211, 153, .22);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(52, 211, 153, .18);
        }

        .btn-salida {
            background: rgba(248, 113, 113, .13);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, .3);
        }

        .btn-salida:not(:disabled):hover {
            background: rgba(248, 113, 113, .22);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(248, 113, 113, .18);
        }

        /* Modal overlay */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .8);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }

        .modal-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 26px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, .7);
            animation: fadeUp .3s ease;
            overflow: hidden;
        }

        /* Header del modal */
        .modal-header-loading {
            background: linear-gradient(135deg, #1e2130, #1a1d27);
            padding: 36px 32px 28px;
            text-align: center;
        }

        .modal-header-success {
            background: linear-gradient(135deg, #052e16, #064e3b);
            padding: 36px 32px 28px;
            text-align: center;
            border-bottom: 1px solid rgba(52, 211, 153, .15);
        }

        .modal-header-error {
            background: linear-gradient(135deg, #2d0a0a, #450a0a);
            padding: 36px 32px 28px;
            text-align: center;
            border-bottom: 1px solid rgba(248, 113, 113, .15);
        }

        .modal-icon {
            width: 66px;
            height: 66px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .modal-icon-loading {
            background: rgba(59, 130, 246, .12);
            border: 1px solid rgba(59, 130, 246, .25);
        }

        .modal-icon-success {
            background: rgba(52, 211, 153, .12);
            border: 1px solid rgba(52, 211, 153, .25);
        }

        .modal-icon-error {
            background: rgba(248, 113, 113, .12);
            border: 1px solid rgba(248, 113, 113, .25);
        }

        .modal-body {
            padding: 24px 32px;
            text-align: center;
        }

        .modal-footer {
            padding: 0 32px 28px;
        }

        /* Spinner */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            width: 28px;
            height: 28px;
            border: 2.5px solid rgba(59, 130, 246, .2);
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        /* Botón cerrar modal */
        .btn-close-modal {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-close-success {
            background: #10b981;
            color: #fff;
        }

        .btn-close-success:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, .3);
        }

        .btn-close-error {
            background: #ef4444;
            color: #fff;
        }

        .btn-close-error:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, .3);
        }

        @media (max-width: 400px) {
            .pres-card .card-pad {
                padding: 20px 18px;
            }

            .btn-action {
                font-size: 14px;
                padding: 16px 10px;
                min-height: 56px;
            }

            .modal-header-loading,
            .modal-header-success,
            .modal-header-error {
                padding: 28px 24px 22px;
            }

            .modal-body {
                padding: 20px 24px;
            }

            .modal-footer {
                padding: 0 24px 24px;
            }
        }
    </style>

    <div class="pres-bg" style="position:fixed;inset:0;pointer-events:none;"></div>

    <div class="pres-wrap">

        {{-- ── TARJETA USUARIO + RELOJ ── --}}
        <div class="pres-card fu">
            <div class="card-accent"></div>
            <div class="card-pad" style="padding:28px 28px 24px;text-align:center;">

                {{-- Avatar con inicial --}}
                @php $nombre = session('user_name', 'Usuario'); @endphp
                <div
                    style="width:60px;height:60px;border-radius:16px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:22px;font-weight:700;color:#fff;">
                    {{ strtoupper(substr($nombre, 0, 1)) }}
                </div>

                <h2 style="font-size:20px;font-weight:700;color:#f1f5f9;margin-bottom:4px;">
                    {{ $nombre }}
                </h2>
                <p style="font-size:12px;color:#475569;margin-bottom:20px;">Registro de presencia</p>

                {{-- Reloj --}}
                <div id="reloj"
                    style="font-family:'JetBrains Mono',monospace;font-size:38px;font-weight:600;color:#f1f5f9;letter-spacing:0.04em;line-height:1;">
                </div>

                {{-- Fecha --}}
                <p id="fecha-hoy"
                    style="font-size:12px;color:#334155;margin-top:8px;letter-spacing:.04em;text-transform:uppercase;">
                </p>
            </div>
        </div>

        {{-- ── TARJETA EMPRESA + BOTONES ── --}}
        <div class="pres-card fu2">
            <div class="card-pad" style="padding:24px 28px;">

                {{-- Empresa --}}
                <div style="margin-bottom:20px;">
                    <span class="label-xs">Punto de fichaje</span>
                    <select class="fm-select" wire:model="empresa_id" @if ($showModal) disabled @endif>
                        <option value="">Selecciona un punto...</option>
                        @foreach ($empresas as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones entrada / salida --}}
                <div style="display:flex;gap:12px;">
                    <button class="btn-action btn-entrada" onclick="obtenerUbicacion('entrada')"
                        @if ($showModal) disabled @endif>
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="12" y1="19" x2="12" y2="5" />
                            <polyline points="5 12 12 5 19 12" />
                        </svg>
                        Entrada
                    </button>
                    <button class="btn-action btn-salida" onclick="obtenerUbicacion('salida')"
                        @if ($showModal) disabled @endif>
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <polyline points="19 12 12 19 5 12" />
                        </svg>
                        Salida
                    </button>
                </div>

                <p
                    style="font-size:11px;color:#1e2330;text-align:center;margin-top:14px;display:flex;align-items:center;justify-content:center;gap:5px;">
                    <svg width="11" height="11" fill="none" stroke="#334155" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span style="color:#334155;">Si el punto requiere ubicación, permite el acceso al GPS</span>
                </p>

            </div>
        </div>

    </div>

    {{-- ── MODAL ── --}}
    @if ($showModal)
        <div class="modal-overlay">
            <div class="modal-card">

                @if ($modalEstado === 'loading')
                    <div class="modal-header-loading">
                        <div class="modal-icon modal-icon-loading">
                            <div class="spinner"></div>
                        </div>
                        <div style="font-size:18px;font-weight:700;color:#e2e8f0;margin-bottom:6px;">Procesando…</div>
                        <div style="font-size:13px;color:#475569;">Por favor espera</div>
                    </div>
                    <div class="modal-body">
                        <p style="font-size:14px;color:#64748b;line-height:1.6;">{{ $modalMensaje }}</p>
                    </div>
                @endif

                @if ($modalEstado === 'success')
                    <div class="modal-header-success">
                        <div class="modal-icon modal-icon-success">
                            <svg width="30" height="30" fill="none" stroke="#34d399" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div style="font-size:19px;font-weight:700;color:#d1fae5;margin-bottom:4px;">Fichaje registrado
                        </div>
                        <div style="font-size:13px;color:#6ee7b7;">Registro confirmado correctamente</div>
                    </div>
                    <div class="modal-body">
                        <p style="font-size:16px;color:#f1f5f9;line-height:1.6;">{{ $modalMensaje }}</p>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="cerrarModal" class="btn-close-modal btn-close-success">Aceptar</button>
                    </div>
                @endif

                @if ($modalEstado === 'error')
                    <div class="modal-header-error">
                        <div class="modal-icon modal-icon-error">
                            <svg width="30" height="30" fill="none" stroke="#f87171" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </div>
                        <div style="font-size:19px;font-weight:700;color:#fee2e2;margin-bottom:4px;">Error en el
                            fichaje</div>
                        <div style="font-size:13px;color:#fca5a5;">Revisa el mensaje</div>
                    </div>
                    <div class="modal-body">
                        <p style="font-size:15px;color:#e2e8f0;line-height:1.6;">{{ $modalMensaje }}</p>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="cerrarModal" class="btn-close-modal btn-close-error">Entendido</button>
                    </div>
                @endif

            </div>
        </div>
    @endif

    <script>
        // Reloj + fecha
        const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        const meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre',
            'noviembre', 'diciembre'
        ];

        function actualizarReloj() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            const reloj = document.getElementById('reloj');
            if (reloj) reloj.textContent = `${h}:${m}:${s}`;

            const fecha = document.getElementById('fecha-hoy');
            if (fecha) {
                const dia = diasSemana[now.getDay()];
                fecha.textContent = `${dia}, ${now.getDate()} de ${meses[now.getMonth()]} de ${now.getFullYear()}`;
            }
        }
        actualizarReloj();
        setInterval(actualizarReloj, 1000);

        // Geolocalización
        function obtenerUbicacion(tipo) {
            const component = Livewire.find('{{ $this->getId() }}');

            navigator.geolocation.getCurrentPosition(
                pos => {
                    component.set('latitud', pos.coords.latitude);
                    component.set('longitud', pos.coords.longitude);
                    component.call('registrar', tipo);
                },
                error => {
                    component.set('latitud', null);
                    component.set('longitud', null);
                    component.call('registrar', tipo);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('redirigirIncidencias', () => {
                setTimeout(() => {
                    window.location.href = '/incidencias';
                }, 4000);
            });
            Livewire.on('recargarPagina', () => {
                window.location.reload();
            });
        });
    </script>

</div>
