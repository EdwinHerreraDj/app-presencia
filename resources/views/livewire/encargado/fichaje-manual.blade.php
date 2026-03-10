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

        /* --- Grid bg --- */
        .fm-bg::before {
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

        .fm-bg::after {
            content: '';
            position: fixed;
            top: -180px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.055) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .fm-wrap {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 0 auto;
        }

        /* --- Animaciones --- */
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

        /* --- Cards --- */
        .fm-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 20px;
            overflow: hidden;
        }

        .fm-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #1e2330;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* --- Botones --- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            font-family: 'Sora', sans-serif;
            font-weight: 500;
            transition: all .18s;
            white-space: nowrap;
        }

        .btn-primary {
            background: #3b82f6;
            color: #fff;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13px;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, .3);
        }

        .btn-success {
            background: rgba(52, 211, 153, .13);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, .28);
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12px;
        }

        .btn-success:hover {
            background: rgba(52, 211, 153, .22);
        }

        .btn-danger {
            background: rgba(248, 113, 113, .13);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, .28);
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12px;
        }

        .btn-danger:hover {
            background: rgba(248, 113, 113, .22);
        }

        .btn-warning {
            background: rgba(251, 191, 36, .1);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, .22);
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12px;
        }

        .btn-warning:hover {
            background: rgba(251, 191, 36, .2);
        }

        .btn-blue {
            background: rgba(59, 130, 246, .1);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, .22);
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12px;
        }

        .btn-blue:hover {
            background: rgba(59, 130, 246, .2);
        }

        .btn-ghost {
            background: rgba(100, 116, 139, .1);
            color: #94a3b8;
            border: 1px solid rgba(100, 116, 139, .18);
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 12px;
        }

        .btn-ghost:hover {
            background: rgba(100, 116, 139, .18);
        }

        .btn-icon {
            background: #1a1d27;
            border: 1px solid #1e2330;
            border-radius: 8px;
            padding: 7px 9px;
            color: #64748b;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: all .18s;
        }

        .btn-icon:hover {
            background: #1e2330;
            color: #94a3b8;
        }

        /* --- Select --- */
        .fm-select {
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

        .fm-select:focus {
            border-color: #3b82f6;
        }

        .fm-select option {
            background: #111318;
        }

        /* --- Input time --- */
        .fm-input-time {
            background: #0f1117;
            border: 1px solid #3b82f6;
            color: #f1f5f9;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 13px;
            font-family: 'JetBrains Mono', monospace;
            outline: none;
            max-width: 120px;
        }

        /* --- Etiquetas pequeñas --- */
        .label-xs {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #475569;
        }

        /* --- Badge tipo fichaje --- */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-entrada {
            background: rgba(52, 211, 153, .1);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, .2);
        }

        .badge-salida {
            background: rgba(248, 113, 113, .1);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, .2);
        }

        .badge-dentro {
            background: rgba(52, 211, 153, .1);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, .2);
        }

        .badge-fuera {
            background: rgba(100, 116, 139, .1);
            color: #64748b;
            border: 1px solid rgba(100, 116, 139, .18);
        }

        /* --- Chip hora --- */
        .time-chip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            background: #1a1d27;
            border: 1px solid #1e2330;
            border-radius: 6px;
            padding: 3px 10px;
            color: #94a3b8;
        }

        /* --- Avatar --- */
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }

        .avatar-green {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }

        .avatar-gray {
            background: linear-gradient(135deg, #334155, #475569) !important;
        }

        /* --- Dot indicador --- */
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .dot-green {
            background: #34d399;
            box-shadow: 0 0 6px rgba(52, 211, 153, .6);
        }

        .dot-gray {
            background: #475569;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                box-shadow: 0 0 5px rgba(52, 211, 153, .5);
            }

            50% {
                box-shadow: 0 0 12px rgba(52, 211, 153, .9);
            }
        }

        .dot-pulse {
            animation: pulse-dot 2s infinite;
        }

        /* ============================================================
           TABLA — desktop
        ============================================================ */
        .fm-table {
            width: 100%;
            border-collapse: collapse;
        }

        .fm-table thead tr {
            background: #0a0c10;
        }

        .fm-table th {
            padding: 11px 20px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #334155;
        }

        .fm-table th:first-child {
            text-align: left;
        }

        .fm-table th:last-child {
            text-align: right;
        }

        .fm-table td {
            padding: 13px 20px;
            vertical-align: middle;
        }

        .fm-table tbody tr {
            border-top: 1px solid #1a1d27;
            transition: background .14s;
        }

        .fm-table tbody tr:hover {
            background: rgba(255, 255, 255, .018);
        }

        /* ============================================================
           TARJETAS — móvil
        ============================================================ */
        .mobile-cards {
            display: none;
        }

        .m-card {
            border-bottom: 1px solid #1a1d27;
            padding: 16px 20px;
            transition: background .14s;
        }

        .m-card:hover {
            background: rgba(255, 255, 255, .02);
        }

        .m-card:last-child {
            border-bottom: none;
        }

        .m-card-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .m-card-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        /* ============================================================
           Modal
        ============================================================ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .8);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }

        .modal-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, .6);
            animation: fadeUp .3s ease;
            overflow: hidden;
        }

        .modal-body {
            padding: 28px 28px 0;
        }

        .modal-footer {
            padding: 20px 28px 28px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 640px) {
            .fm-table-wrap {
                display: none !important;
            }

            .mobile-cards {
                display: block !important;
            }

            .fm-card-header {
                padding: 14px 16px;
            }

            .fm-card-header .label-xs {
                font-size: 9px;
            }

            .empresa-row {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .empresa-row .btn-primary {
                width: 100%;
                justify-content: center;
            }

            .header-stats {
                display: none !important;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) and (min-width: 641px) {
            .fm-table td {
                padding: 11px 14px;
            }

            .fm-table th {
                padding: 10px 14px;
            }

            .actions-cell {
                flex-direction: column;
                gap: 4px !important;
            }
        }
    </style>

    <div class="fm-bg" style="position:fixed;inset:0;pointer-events:none;"></div>

    <div class="fm-wrap">

        {{-- ── HEADER ── --}}
        <div class="fu" style="display:flex;align-items:center;gap:12px;margin-bottom:28px;">
            <div
                style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </div>
            <div>
                <h1 style="font-size:20px;font-weight:700;color:#f1f5f9;line-height:1.2;">Fichaje Manual</h1>
                <p style="font-size:12px;color:#475569;">Gestión de entradas y salidas</p>
            </div>
        </div>

        {{-- ── SELECTOR EMPRESA ── --}}
        <div class="fm-card fu2" style="margin-bottom:20px;padding:20px 24px;">
            <p class="label-xs" style="margin-bottom:10px;">Empresa</p>
            <div class="empresa-row" style="display:flex;gap:10px;align-items:center;">
                <div style="flex:1;min-width:0;">
                    <select wire:model.live="empresaId" class="fm-select">
                        <option value="">Seleccione una empresa...</option>
                        @foreach ($empresas as $empresa)
                            <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="verFichajesHoy" class="btn btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    Ver fichajes de hoy
                </button>
            </div>
        </div>

        {{-- ── FICHAJES DE HOY ── --}}
        @if ($mostrarFichajes)
            <div class="fm-card fu3" style="margin-bottom:20px;">

                <div class="fm-card-header">
                    <div>
                        <p class="label-xs" style="margin-bottom:2px;">Fichajes de hoy</p>
                        <p style="font-size:12px;color:#64748b;">{{ now()->format('d \d\e F \d\e Y') }}</p>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <span
                            style="background:#1a1d27;border:1px solid #1e2330;border-radius:20px;padding:3px 12px;font-size:11px;color:#64748b;">
                            {{ count($fichajesHoy) }} registros
                        </span>
                        <button wire:click="$toggle('tablaFichajesVisible')" class="btn-icon"
                            title="{{ $tablaFichajesVisible ? 'Ocultar' : 'Mostrar' }}">
                            @if ($tablaFichajesVisible)
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94" />
                                    <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            @else
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            @endif
                        </button>
                    </div>
                </div>

                @if ($tablaFichajesVisible)
                    @if (count($fichajesHoy) > 0)

                        {{-- TABLA desktop --}}
                        <div class="fm-table-wrap">
                            <table class="fm-table">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;">Empleado</th>
                                        <th style="text-align:left;">Tipo</th>
                                        <th style="text-align:left;">Hora</th>
                                        <th style="text-align:right;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fichajesHoy as $fichaje)
                                        <tr>
                                            <td>
                                                <div style="display:flex;align-items:center;gap:10px;">
                                                    <div class="avatar">
                                                        {{ strtoupper(substr($fichaje->empleado->nombre, 0, 1)) }}</div>
                                                    <span
                                                        style="font-size:14px;font-weight:500;color:#e2e8f0;">{{ $fichaje->empleado->nombre }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $fichaje->tipo }}">
                                                    {{ $fichaje->tipo === 'entrada' ? '↑ Entrada' : '↓ Salida' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($editandoId === $fichaje->id)
                                                    <div style="display:flex;align-items:center;gap:6px;">
                                                        <input type="time" wire:model="horaEditada"
                                                            class="fm-input-time">
                                                        <button wire:click="guardarHora({{ $fichaje->id }})"
                                                            class="btn btn-success"
                                                            style="padding:5px 10px;">✓</button>
                                                        <button wire:click="$set('editandoId', null)"
                                                            class="btn btn-ghost" style="padding:5px 10px;">✕</button>
                                                    </div>
                                                @else
                                                    <span
                                                        class="time-chip">{{ \Carbon\Carbon::parse($fichaje->fecha_hora)->format('H:i') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="actions-cell"
                                                    style="display:flex;gap:5px;justify-content:flex-end;flex-wrap:wrap;">
                                                    <button wire:click="cambiarTipo({{ $fichaje->id }})"
                                                        class="btn btn-warning">⇄ Cambiar</button>
                                                    <button wire:click="editarHora({{ $fichaje->id }})"
                                                        class="btn btn-blue">✎ Hora</button>
                                                    <button wire:click="confirmarEliminar({{ $fichaje->id }})"
                                                        class="btn btn-danger">✕</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- TARJETAS móvil --}}
                        <div class="mobile-cards">
                            @foreach ($fichajesHoy as $fichaje)
                                <div class="m-card">
                                    <div class="m-card-row">
                                        <div style="display:flex;align-items:center;gap:10px;min-width:0;flex:1;">
                                            <div class="avatar" style="flex-shrink:0;">
                                                {{ strtoupper(substr($fichaje->empleado->nombre, 0, 1)) }}</div>
                                            <div style="min-width:0;">
                                                <div
                                                    style="font-size:14px;font-weight:600;color:#e2e8f0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {{ $fichaje->empleado->nombre }}
                                                </div>
                                                <div style="margin-top:4px;">
                                                    <span class="badge badge-{{ $fichaje->tipo }}">
                                                        {{ $fichaje->tipo === 'entrada' ? '↑ Entrada' : '↓ Salida' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="flex-shrink:0;">
                                            @if ($editandoId === $fichaje->id)
                                                <input type="time" wire:model="horaEditada" class="fm-input-time">
                                            @else
                                                <span
                                                    class="time-chip">{{ \Carbon\Carbon::parse($fichaje->fecha_hora)->format('H:i') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="m-card-actions">
                                        @if ($editandoId === $fichaje->id)
                                            <button wire:click="guardarHora({{ $fichaje->id }})"
                                                class="btn btn-success">✓ Guardar</button>
                                            <button wire:click="$set('editandoId', null)" class="btn btn-ghost">✕
                                                Cancelar</button>
                                        @else
                                            <button wire:click="cambiarTipo({{ $fichaje->id }})"
                                                class="btn btn-warning">⇄ Cambiar tipo</button>
                                            <button wire:click="editarHora({{ $fichaje->id }})"
                                                class="btn btn-blue">✎ Editar hora</button>
                                            <button wire:click="confirmarEliminar({{ $fichaje->id }})"
                                                class="btn btn-danger">✕ Eliminar</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="padding:40px;text-align:center;color:#334155;">
                            <svg width="36" height="36" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24"
                                style="margin:0 auto 10px;display:block;opacity:.4;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <p style="font-size:13px;">No hay fichajes registrados hoy</p>
                        </div>
                    @endif
                @endif

            </div>
        @endif

        {{-- ── EMPLEADOS ── --}}
        @if ($empresaId)
            <div class="fm-card">

                <div class="fm-card-header">
                    <div>
                        <p class="label-xs" style="margin-bottom:2px;">Empleados</p>
                        <p style="font-size:12px;color:#64748b;">Registra entradas y salidas manualmente</p>
                    </div>
                    @php
                        $dentro = collect($empleados)->where('estado', 'dentro')->count();
                        $total = count($empleados);
                    @endphp
                    <div class="header-stats" style="display:flex;gap:12px;align-items:center;">
                        <span style="font-size:12px;color:#34d399;display:flex;align-items:center;gap:5px;">
                            <span class="dot dot-green dot-pulse"></span> {{ $dentro }} dentro
                        </span>
                        <span style="font-size:12px;color:#64748b;display:flex;align-items:center;gap:5px;">
                            <span class="dot dot-gray"></span> {{ $total - $dentro }} fuera
                        </span>
                    </div>
                </div>

                {{-- Buscador --}}
                <div style="padding:14px 20px;border-bottom:1px solid #1a1d27;">
                    <div style="position:relative;">
                        <svg width="14" height="14" fill="none" stroke="#475569" stroke-width="2"
                            viewBox="0 0 24 24"
                            style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="busquedaEmpleado"
                            placeholder="Buscar empleado por nombre..."
                            style="background:#0f1117;border:1px solid #1e2330;color:#f1f5f9;border-radius:10px;padding:9px 12px 9px 34px;font-size:13px;font-family:'Sora',sans-serif;width:100%;outline:none;transition:border-color .2s;"
                            onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#1e2330'">
                        @if ($busquedaEmpleado)
                            <button wire:click="$set('busquedaEmpleado','')"
                                style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#475569;display:flex;align-items:center;padding:2px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                @if (count($empleados) > 0)

                    {{-- TABLA desktop --}}
                    <div class="fm-table-wrap">
                        <table class="fm-table">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Empleado</th>
                                    <th style="text-align:center;">Estado</th>
                                    <th style="text-align:right;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empleados as $empleado)
                                    <tr>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:10px;">
                                                <div
                                                    class="avatar {{ $empleado->estado === 'dentro' ? 'avatar-green' : 'avatar-gray' }}">
                                                    {{ strtoupper(substr($empleado->nombre, 0, 1)) }}
                                                </div>
                                                <span
                                                    style="font-size:14px;font-weight:500;color:#e2e8f0;">{{ $empleado->nombre }}</span>
                                            </div>
                                        </td>
                                        <td style="text-align:center;">
                                            @if ($empleado->estado === 'dentro')
                                                <span class="badge badge-dentro"><span class="dot dot-green"
                                                        style="width:6px;height:6px;"></span> Dentro</span>
                                            @else
                                                <span class="badge badge-fuera"><span class="dot dot-gray"
                                                        style="width:6px;height:6px;"></span> Fuera</span>
                                            @endif
                                        </td>
                                        <td style="text-align:right;">
                                            @if ($empleado->estado === 'dentro')
                                                <button wire:click="ficharSalida({{ $empleado->id }})"
                                                    class="btn btn-danger">↓ Registrar salida</button>
                                            @else
                                                <button wire:click="ficharEntrada({{ $empleado->id }})"
                                                    class="btn btn-success">↑ Registrar entrada</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- TARJETAS móvil --}}
                    <div class="mobile-cards">
                        @foreach ($empleados as $empleado)
                            <div class="m-card">
                                <div class="m-card-row">
                                    <div style="display:flex;align-items:center;gap:10px;min-width:0;flex:1;">
                                        <div class="avatar {{ $empleado->estado === 'dentro' ? 'avatar-green' : 'avatar-gray' }}"
                                            style="flex-shrink:0;">
                                            {{ strtoupper(substr($empleado->nombre, 0, 1)) }}
                                        </div>
                                        <div style="min-width:0;">
                                            <div
                                                style="font-size:14px;font-weight:600;color:#e2e8f0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                {{ $empleado->nombre }}
                                            </div>
                                            <div style="margin-top:4px;">
                                                @if ($empleado->estado === 'dentro')
                                                    <span class="badge badge-dentro"><span class="dot dot-green"
                                                            style="width:5px;height:5px;"></span> Dentro</span>
                                                @else
                                                    <span class="badge badge-fuera"><span class="dot dot-gray"
                                                            style="width:5px;height:5px;"></span> Fuera</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div style="flex-shrink:0;">
                                        @if ($empleado->estado === 'dentro')
                                            <button wire:click="ficharSalida({{ $empleado->id }})"
                                                class="btn btn-danger">↓ Salida</button>
                                        @else
                                            <button wire:click="ficharEntrada({{ $empleado->id }})"
                                                class="btn btn-success">↑ Entrada</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding:40px;text-align:center;color:#334155;">
                        <p style="font-size:13px;">No hay empleados disponibles</p>
                    </div>
                @endif

            </div>
        @endif

    </div>{{-- /fm-wrap --}}

    {{-- ── MODAL ELIMINAR ── --}}
    @if ($mostrarModalEliminar)
        <div class="modal-overlay">
            <div class="modal-card">
                <div class="modal-body">
                    <div
                        style="width:46px;height:46px;border-radius:12px;background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.22);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                        <svg width="20" height="20" fill="none" stroke="#f87171" stroke-width="2"
                            viewBox="0 0 24 24">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                            <path d="M10 11v6M14 11v6" />
                            <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                        </svg>
                    </div>
                    <h3 style="font-size:17px;font-weight:700;color:#f1f5f9;margin-bottom:8px;">Eliminar fichaje</h3>
                    <p style="font-size:13px;color:#64748b;line-height:1.6;">
                        ¿Seguro que deseas eliminar este fichaje? Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('mostrarModalEliminar', false)" class="btn btn-ghost">Cancelar</button>
                    <button wire:click="eliminarFichajeConfirmado" class="btn btn-danger"
                        style="padding:9px 18px;">Sí, eliminar</button>
                </div>
            </div>
        </div>
    @endif

    <script>
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
        });
    </script>

</div>
