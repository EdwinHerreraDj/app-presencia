<div style="min-height:100vh;background:#0a0c10;padding:24px 16px;font-family:'Sora',sans-serif;position:relative;"
    x-data>

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

        .fq-bg::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .015) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .fq-wrap {
            position: relative;
            z-index: 1;
            max-width: 560px;
            margin: 0 auto;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
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
            animation: fadeUp .45s .08s ease both;
        }

        .fu3 {
            animation: fadeUp .45s .16s ease both;
        }

        .fq-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .fq-card-body {
            padding: 20px 24px;
        }

        .label-xs {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 10px;
            display: block;
        }

        .fq-select {
            background: #0f1117;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 12px;
            padding: 11px 40px 11px 16px;
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

        .fq-select:focus {
            border-color: #3b82f6;
        }

        .fq-select option {
            background: #111318;
        }

        /* Toggle tipo */
        .tipo-toggle {
            display: flex;
            background: #0f1117;
            border: 1px solid #1e2330;
            border-radius: 12px;
            padding: 4px;
            gap: 4px;
        }

        .tipo-btn {
            flex: 1;
            padding: 9px 0;
            border: none;
            border-radius: 9px;
            font-family: 'Sora', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
            background: transparent;
            color: #475569;
        }

        .tipo-btn.active-entrada {
            background: rgba(52, 211, 153, .15);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, .3);
        }

        .tipo-btn.active-salida {
            background: rgba(248, 113, 113, .15);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, .3);
        }

        /* Botones */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            transition: all .18s;
            white-space: nowrap;
            border-radius: 10px;
            font-size: 14px;
            padding: 11px 20px;
            width: 100%;
        }

        .btn-iniciar {
            background: #3b82f6;
            color: #fff;
        }

        .btn-iniciar:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, .3);
        }

        .btn-detener {
            background: rgba(248, 113, 113, .13);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, .28);
        }

        .btn-detener:hover {
            background: rgba(248, 113, 113, .22);
        }

        /* Visor cámara */
        #qr-reader {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            background: #0a0c10;
        }

        #qr-reader video {
            border-radius: 12px;
        }

        /* Resultado */
        .resultado-ok {
            background: rgba(52, 211, 153, .08);
            border: 1px solid rgba(52, 211, 153, .25);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .resultado-error {
            background: rgba(248, 113, 113, .08);
            border: 1px solid rgba(248, 113, 113, .25);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .resultado-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .avatar-ok {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }

        .avatar-error {
            background: rgba(248, 113, 113, .2);
            color: #f87171;
        }

        /* Dot pulse */
        @keyframes pulse-dot {

            0%,
            100% {
                box-shadow: 0 0 5px rgba(52, 211, 153, .5)
            }

            50% {
                box-shadow: 0 0 12px rgba(52, 211, 153, .9)
            }
        }

        .dot-scan {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #34d399;
            animation: pulse-dot 1.4s infinite;
            display: inline-block;
        }

        /* ---- MODAL ---- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .85);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 24px;
        }

        .modal-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 28px;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, .6);
            animation: fadeUp .3s ease;
        }

        .modal-ok-header {
            background: linear-gradient(135deg, #064e3b, #065f46);
            padding: 40px 36px 36px;
            text-align: center;
            border-bottom: 1px solid rgba(52, 211, 153, .15);
        }

        .modal-err-header {
            background: linear-gradient(135deg, #450a0a, #7f1d1d);
            padding: 40px 36px 36px;
            text-align: center;
            border-bottom: 1px solid rgba(248, 113, 113, .15);
        }

        .modal-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .modal-icon-ok {
            background: rgba(52, 211, 153, .15);
            border: 2px solid rgba(52, 211, 153, .3);
        }

        .modal-icon-err {
            background: rgba(248, 113, 113, .15);
            border: 2px solid rgba(248, 113, 113, .3);
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
            font-size: 15px;
            color: #64748b;
            line-height: 1.5;
        }

        .modal-footer {
            padding: 0 36px 36px;
        }

        .btn-aceptar-ok {
            width: 100%;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-size: 17px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-aceptar-ok:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, .3);
        }

        .btn-aceptar-err {
            width: 100%;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 14px;
            padding: 18px;
            font-size: 17px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-aceptar-err:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, .3);
        }
    </style>

    <div class="fq-bg" style="position:fixed;inset:0;pointer-events:none;"></div>

    <div class="fq-wrap">

        {{-- HEADER --}}
        <div class="fu" style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
            <div
                style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1" />
                    <rect x="14" y="3" width="7" height="7" rx="1" />
                    <rect x="3" y="14" width="7" height="7" rx="1" />
                    <path d="M14 14h2v2h-2zM18 14h3M14 18v3M18 18h3v3h-3z" />
                </svg>
            </div>
            <div>
                <h1 style="font-size:20px;font-weight:700;color:#f1f5f9;line-height:1.2;">Terminal QR</h1>
                <p style="font-size:12px;color:#475569;">Escanea el QR del empleado para fichar</p>
            </div>
        </div>

        {{-- CONFIGURACIÓN --}}
        <div class="fq-card fu2">
            <div class="fq-card-body">

                {{-- Empresa --}}
                <label class="label-xs">Empresa</label>
                <select wire:model.live="empresaId" class="fq-select" style="margin-bottom:18px;"
                    @if ($escaneando) disabled @endif>
                    <option value="">Selecciona una empresa...</option>
                    @foreach ($empresas as $empresa)
                        <option value="{{ $empresa['id'] }}">{{ $empresa['nombre'] }}</option>
                    @endforeach
                </select>

                {{-- Tipo --}}
                <label class="label-xs">Tipo de fichaje</label>
                <div class="tipo-toggle" style="margin-bottom:18px;">
                    <button type="button" class="tipo-btn {{ $tipo === 'entrada' ? 'active-entrada' : '' }}"
                        wire:click="$set('tipo','entrada')" @if ($escaneando) disabled @endif>
                        ↑ Entrada
                    </button>
                    <button type="button" class="tipo-btn {{ $tipo === 'salida' ? 'active-salida' : '' }}"
                        wire:click="$set('tipo','salida')" @if ($escaneando) disabled @endif>
                        ↓ Salida
                    </button>
                </div>

                {{-- Botón iniciar / detener --}}
                @if (!$escaneando)
                    <button wire:click="iniciarEscaneo" class="btn btn-iniciar">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <polygon points="5 3 19 12 5 21 5 3" />
                        </svg>
                        Iniciar escaneo
                    </button>
                @else
                    <button wire:click="detenerEscaneo" class="btn btn-detener">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                        </svg>
                        Detener escaneo
                    </button>
                @endif

            </div>
        </div>

        {{-- VISOR CÁMARA --}}
        {{-- VISOR CÁMARA --}}
        <div class="fq-card fu3" style="{{ $escaneando ? '' : 'display:none;' }}">
            <div class="fq-card-body">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                    <span class="dot-scan"></span>
                    <span style="font-size:12px;color:#34d399;font-weight:600;">Cámara activa — apunta al QR del
                        empleado</span>
                </div>
                <div id="qr-reader"></div>
            </div>
        </div>

        {{-- RESULTADO ÚLTIMO FICHAJE --}}
        @if ($ultimoNombre)
            <div class="fu3" style="margin-bottom:20px;">
                <div class="resultado-ok">
                    <div class="resultado-avatar avatar-ok">
                        {{ strtoupper(substr($ultimoNombre, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:15px;font-weight:700;color:#f1f5f9;">{{ $ultimoNombre }}</div>
                        <div style="font-size:12px;color:#34d399;margin-top:2px;">
                            {{ $ultimoTipo === 'entrada' ? '↑ Entrada' : '↓ Salida' }} registrada · {{ $ultimaHora }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($ultimoError)
            <div class="fu3" style="margin-bottom:20px;">
                <div class="resultado-error">
                    <div class="resultado-avatar avatar-error">!</div>
                    <div>
                        <div style="font-size:13px;color:#f87171;">{{ $ultimoError }}</div>
                    </div>
                </div>
            </div>
        @endif

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
                        <button wire:click="cerrarModal" class="btn-aceptar-ok">
                            ✓ Aceptar y seguir escaneando
                        </button>
                    @else
                        <button wire:click="cerrarModal" class="btn-aceptar-err">
                            Entendido
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let qrScanner = null;
        let ctxAudio = null;

        function getAudioCtx() {
            if (!ctxAudio) {
                ctxAudio = new(window.AudioContext || window.webkitAudioContext)();
            }
            return ctxAudio;
        }

        function playSound(type) {
            try {
                const ctx = getAudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                if (type === 'ok') {
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.setValueAtTime(1100, ctx.currentTime + 0.1);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.4);
                } else {
                    osc.frequency.setValueAtTime(300, ctx.currentTime);
                    osc.frequency.setValueAtTime(200, ctx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.4, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.5);
                }
            } catch (e) {
                console.warn('Audio no disponible:', e);
            }
        }

        function detenerEscanerFisico() {
            return new Promise(resolve => {
                if (qrScanner) {
                    qrScanner.stop()
                        .catch(() => {})
                        .finally(() => resolve());
                } else {
                    resolve();
                }
            });
        }

        function arrancarCamara() {
            const el = document.getElementById('qr-reader');
            if (!el) {
                setTimeout(arrancarCamara, 150);
                return;
            }

            // Limpia instancia anterior si existe
            if (qrScanner) {
                qrScanner.stop().catch(() => {}).finally(() => {
                    qrScanner = null;
                    setTimeout(arrancarCamara, 200);
                });
                return;
            }

            qrScanner = new Html5Qrcode("qr-reader");

            qrScanner.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                (decodedText) => {
                    console.log('QR detectado:', decodedText);

                    // Para físicamente el escáner al detectar
                    detenerEscanerFisico().then(() => {
                        qrScanner = null;
                        Livewire.dispatch('qr-escaneado', {
                            contenido: decodedText
                        });
                    });
                }
            ).catch(err => {
                qrScanner = null;
                console.error('Error al iniciar cámara:', err);
                alert('No se pudo acceder a la cámara: ' + err);
            });
        }

        document.addEventListener('livewire:init', () => {

            const notyf = new Notyf({
                duration: 4000,
                dismissible: true,
                position: {
                    x: 'right',
                    y: 'top'
                },
                types: [{
                        type: 'success',
                        background: '#111318',
                        icon: false
                    },
                    {
                        type: 'error',
                        background: '#111318',
                        icon: false
                    }
                ]
            });

            Livewire.on('notyf-success', e => notyf.success(e.message));
            Livewire.on('notyf-error', e => notyf.error(e.message));
            Livewire.on('terminal-sound', e => playSound(e.type));

            Livewire.on('iniciar-camara', () => {
                setTimeout(arrancarCamara, 400);
            });

            // Se dispara cuando el usuario pulsa Aceptar en el modal
            // Vuelve a arrancar la cámara para el siguiente empleado
            Livewire.on('fichaje-ok', () => {
                setTimeout(arrancarCamara, 500);
            });

            Livewire.on('detener-camara', () => {
                detenerEscanerFisico().then(() => {
                    qrScanner = null;
                });
            });
        });
    </script>
</div>
