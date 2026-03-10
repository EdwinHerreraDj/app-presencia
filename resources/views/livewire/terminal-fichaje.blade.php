<div x-data x-ref="terminal"
    style="min-height:100vh;background:#0a0c10;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;font-family:'Sora',sans-serif;position:relative;overflow:hidden;">

    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* Fondo con grid sutil */
        .terminal-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Glow ambiental */
        .terminal-bg::after {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .terminal-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
        }

        /* Animación de entrada */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.5s ease forwards;
        }

        .fade-up-2 {
            animation: fadeUp 0.5s 0.1s ease both;
        }

        .fade-up-3 {
            animation: fadeUp 0.5s 0.2s ease both;
        }

        /* Card principal */
        .main-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.03), 0 32px 64px rgba(0, 0, 0, 0.5);
        }

        /* Header de la card */
        .card-header-strip {
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            padding: 5px 0;
        }

        .card-body {
            padding: 36px;
        }

        /* Empresa activa */
        .empresa-badge {
            background: rgba(14, 165, 233, 0.1);
            border: 1px solid rgba(14, 165, 233, 0.2);
            border-radius: 14px;
            padding: 14px 20px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .empresa-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #0ea5e9;
            box-shadow: 0 0 8px rgba(14, 165, 233, 0.7);
            flex-shrink: 0;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                box-shadow: 0 0 6px rgba(14, 165, 233, 0.7);
            }

            50% {
                box-shadow: 0 0 14px rgba(14, 165, 233, 1);
            }
        }

        /* Select */
        .custom-select {
            width: 100%;
            background: #0a0c10;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 14px;
            padding: 16px 20px;
            font-size: 16px;
            font-family: 'Sora', sans-serif;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2338bdf8' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .custom-select:focus {
            border-color: #0ea5e9;
        }

        .custom-select option {
            background: #111318;
        }

        /* Input DNI */
        .dni-input {
            width: 100%;
            background: #0a0c10;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 14px;
            padding: 18px 24px;
            font-size: 26px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-align: center;
            text-transform: uppercase;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .dni-input::placeholder {
            color: #2a3040;
            font-weight: 400;
            letter-spacing: 0.05em;
            font-size: 18px;
        }

        .dni-input:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12);
        }

        /* Botón registrar */
        .btn-registrar {
            width: 100%;
            background: #0ea5e9;
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-size: 17px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.01em;
        }

        .btn-registrar:hover:not(:disabled) {
            background: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.3);
        }

        .btn-registrar:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Labels */
        .field-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #475569;
            display: block;
            margin-bottom: 10px;
        }

        /* Nota footer */
        .note {
            font-size: 12px;
            color: #334155;
            text-align: center;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* ---- MODAL ---- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 24px;
        }

        .modal-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 28px;
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
            animation: fadeUp 0.3s ease;
        }

        .modal-ok-header {
            background: linear-gradient(135deg, #064e3b, #065f46);
            padding: 40px 36px 36px;
            text-align: center;
            border-bottom: 1px solid rgba(52, 211, 153, 0.15);
        }

        .modal-err-header {
            background: linear-gradient(135deg, #450a0a, #7f1d1d);
            padding: 40px 36px 36px;
            text-align: center;
            border-bottom: 1px solid rgba(248, 113, 113, 0.15);
        }

        .modal-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
        }

        .modal-icon-ok {
            background: rgba(52, 211, 153, 0.15);
            border: 2px solid rgba(52, 211, 153, 0.3);
        }

        .modal-icon-err {
            background: rgba(248, 113, 113, 0.15);
            border: 2px solid rgba(248, 113, 113, 0.3);
        }

        .modal-body {
            padding: 32px 36px;
            text-align: center;
        }

        .modal-name {
            font-size: 28px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .modal-msg {
            font-size: 16px;
            color: #64748b;
            line-height: 1.5;
        }

        .modal-footer {
            padding: 0 36px 36px;
        }

        .btn-aceptar-ok {
            width: 100%;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-size: 17px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-aceptar-ok:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        }

        .btn-aceptar-err {
            width: 100%;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-size: 17px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-aceptar-err:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
        }

        /* Reloj */
        .clock {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #334155;
            text-align: center;
            margin-bottom: 28px;
            letter-spacing: 0.05em;
        }
    </style>

    <div class="terminal-bg" style="position:fixed;inset:0;pointer-events:none;"></div>

    <div class="terminal-wrap">

        {{-- Logo / título --}}
        <div class="fade-up" style="text-align:center;margin-bottom:32px;">
            <div style="display:inline-flex;align-items:center;gap:10px;margin-bottom:10px;">
                <div
                    style="width:36px;height:36px;border-radius:10px;background:#0ea5e9;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <span style="font-size:20px;font-weight:700;color:#f1f5f9;letter-spacing:-0.02em;">Terminal de
                    Fichaje</span>
            </div>

            {{-- Reloj en vivo --}}
            <div class="clock" id="live-clock">--:--:--</div>
        </div>

        {{-- Card --}}
        <div class="main-card fade-up-2">
            <div class="card-header-strip"></div>
            <div class="card-body">

                {{-- Selección empresa --}}
                @if (!$empresa_id)
                    <div style="margin-bottom:28px;">
                        <span class="field-label">Punto de fichaje</span>
                        <select wire:model.live="empresa_id" class="custom-select">
                            <option value="">Selecciona un punto...</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                        <p style="font-size:12px;color:#334155;text-align:center;margin-top:10px;">
                            Si no aparece tu punto, verifica que esté activo.
                        </p>
                    </div>
                @else
                    <div class="empresa-badge fade-up">
                        <span class="empresa-dot"></span>
                        <div>
                            <div
                                style="font-size:10px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:#0ea5e9;margin-bottom:2px;">
                                Punto activo</div>
                            <div style="font-size:16px;font-weight:600;color:#f1f5f9;">
                                {{ $empresas->firstWhere('id', $empresa_id)?->nombre }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Formulario DNI --}}
                @if ($empresa_id)
                    <form wire:submit.prevent="registrar">

                        <div style="margin-bottom:20px;">
                            <span class="field-label">DNI / NIE</span>
                            <input type="text" wire:model.defer="dni" x-ref="dni" autofocus
                                placeholder="12345678A" autocomplete="off" spellcheck="false" class="dni-input">
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="btn-registrar">
                            <span wire:loading.remove style="display:flex;align-items:center;gap:8px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor"
                                    stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Registrar fichaje
                            </span>
                            <span wire:loading style="display:flex;align-items:center;gap:8px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite;">
                                    <path
                                        d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                                </svg>
                                Procesando…
                            </span>
                        </button>

                        <p class="note" style="margin-top:16px;">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            El sistema detectará automáticamente entrada o salida
                        </p>

                    </form>
                @endif

            </div>
        </div>

        {{-- Nota GPS --}}
        <p class="note fade-up-3" style="margin-top:16px;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                <circle cx="12" cy="10" r="3" />
            </svg>
            Si el punto requiere ubicación, permite el acceso al GPS
        </p>

    </div>

    {{-- MODAL RESULTADO --}}
    @if ($showModal)
        <div class="modal-overlay">
            <div class="modal-card">

                @if ($estado === 'ok')
                    <div class="modal-ok-header">
                        <div class="modal-icon modal-icon-ok">
                            <svg width="34" height="34" fill="none" stroke="#34d399" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                        <div style="font-size:22px;font-weight:700;color:#d1fae5;margin-bottom:4px;">Fichaje registrado
                        </div>
                        <div style="font-size:14px;color:#6ee7b7;">Registro confirmado correctamente</div>
                    </div>
                @else
                    <div class="modal-err-header">
                        <div class="modal-icon modal-icon-err">
                            <svg width="34" height="34" fill="none" stroke="#f87171" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </div>
                        <div style="font-size:22px;font-weight:700;color:#fee2e2;margin-bottom:4px;">Error en el
                            fichaje</div>
                        <div style="font-size:14px;color:#fca5a5;">Revisa el mensaje a continuación</div>
                    </div>
                @endif

                <div class="modal-body">
                    @if ($estado === 'ok')
                        <div class="modal-name">{{ $nombreEmpleado }}</div>
                        <div class="modal-msg">{{ $mensaje }}</div>
                    @else
                        <div class="modal-msg" style="color:#f1f5f9;font-size:18px;">{{ $mensaje }}</div>
                    @endif
                </div>

                <div class="modal-footer">
                    @if ($estado === 'ok')
                        <button wire:click="cerrarModal" class="btn-aceptar-ok">Aceptar</button>
                    @else
                        <button wire:click="cerrarModal" class="btn-aceptar-err">Entendido</button>
                    @endif
                </div>

            </div>
        </div>
    @endif

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        // Reloj en vivo
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            const el = document.getElementById('live-clock');
            if (el) el.textContent = `${h}:${m}:${s}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

</div>
