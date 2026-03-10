<div style="min-height:100vh;background:#0a0c10;padding:24px 16px;font-family:'Sora',sans-serif;position:relative;">

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

        .act-bg::before {
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

        .act-bg::after {
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

        .fu4 {
            animation: fadeUp .45s .24s ease both;
        }

        .act-wrap {
            position: relative;
            z-index: 1;
            max-width: 860px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Card base */
        .act-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 20px;
            overflow: hidden;
        }

        .act-card-header {
            padding: 16px 22px;
            border-bottom: 1px solid #1e2330;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
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

        /* Inputs */
        .fm-input {
            background: #0f1117;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            font-family: 'Sora', sans-serif;
            width: 100%;
            outline: none;
            transition: border-color .2s;
            color-scheme: dark;
        }

        .fm-input:focus {
            border-color: #3b82f6;
        }

        .fm-select {
            background: #0f1117;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 10px;
            padding: 10px 36px 10px 14px;
            font-size: 13px;
            font-family: 'Sora', sans-serif;
            width: 100%;
            outline: none;
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2338bdf8' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            transition: border-color .2s;
        }

        .fm-select:focus {
            border-color: #3b82f6;
        }

        .fm-select option {
            background: #111318;
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

        .btn-toggle {
            background: #1a1d27;
            color: #94a3b8;
            border: 1px solid #1e2330;
            border-radius: 9px;
            padding: 8px 14px;
            font-size: 12px;
        }

        .btn-toggle:hover {
            background: #1e2330;
            color: #f1f5f9;
        }

        .btn-toggle-active {
            background: rgba(59, 130, 246, .12);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, .25);
            border-radius: 9px;
            padding: 8px 14px;
            font-size: 12px;
        }

        /* Badge tipo */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
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

        /* Time chip */
        .time-chip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: #1a1d27;
            border: 1px solid #1e2330;
            border-radius: 6px;
            padding: 2px 9px;
            color: #94a3b8;
        }

        /* Tabla desktop */
        .act-table {
            width: 100%;
            border-collapse: collapse;
        }

        .act-table thead tr {
            background: #0a0c10;
        }

        .act-table th {
            padding: 10px 20px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #334155;
            text-align: left;
        }

        .act-table td {
            padding: 13px 20px;
            vertical-align: middle;
        }

        .act-table tbody tr {
            border-top: 1px solid #1a1d27;
            transition: background .14s;
        }

        .act-table tbody tr:hover {
            background: rgba(255, 255, 255, .018);
        }

        /* Mobile cards */
        .mobile-cards {
            display: none;
        }

        .m-card {
            border-bottom: 1px solid #1a1d27;
            padding: 14px 18px;
        }

        .m-card:last-child {
            border-bottom: none;
        }

        /* Resumen lista */
        .resumen-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 22px;
            border-bottom: 1px solid #1a1d27;
            gap: 12px;
            flex-wrap: wrap;
            transition: background .14s;
        }

        .resumen-item:hover {
            background: rgba(255, 255, 255, .018);
        }

        .resumen-item:last-child {
            border-bottom: none;
        }

        /* Total horas card */
        .total-card {
            background: rgba(59, 130, 246, .07);
            border: 1px solid rgba(59, 130, 246, .18);
            border-radius: 16px;
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Filtros grid */
        .filtros-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width:640px) {
            .act-table-wrap {
                display: none !important;
            }

            .mobile-cards {
                display: block !important;
            }

            .filtros-grid {
                grid-template-columns: 1fr 1fr;
            }

            .filtros-grid .filtro-tipo {
                grid-column: 1 / -1;
            }

            .act-card-header {
                padding: 14px 16px;
            }

            .total-card {
                padding: 14px 16px;
            }
        }

        @media (max-width:400px) {
            .filtros-grid {
                grid-template-columns: 1fr;
            }

            .filtros-grid .filtro-tipo {
                grid-column: auto;
            }
        }
    </style>

    <div class="act-bg" style="position:fixed;inset:0;pointer-events:none;"></div>

    <div class="act-wrap">

        {{-- ── HEADER ── --}}
        <div class="fu" style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
            <div
                style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
            </div>
            <div>
                <h1 style="font-size:20px;font-weight:700;color:#f1f5f9;line-height:1.2;">Actividad</h1>
                <p style="font-size:12px;color:#475569;">Historial de fichajes y resumen horario</p>
            </div>
        </div>

        {{-- ── FILTROS ── --}}
        <div class="act-card fu2">
            <div class="act-card-header">
                <div>
                    <p class="label-xs" style="margin-bottom:0;">Filtrar actividad</p>
                </div>
            </div>
            <div style="padding:20px 22px;">
                <div class="filtros-grid">
                    <div>
                        <span class="label-xs">Desde</span>
                        <input type="date" wire:model.defer="desde" class="fm-input">
                    </div>
                    <div>
                        <span class="label-xs">Hasta</span>
                        <input type="date" wire:model.defer="hasta" class="fm-input">
                    </div>
                    <div class="filtro-tipo">
                        <span class="label-xs">Tipo</span>
                        <select wire:model.defer="tipo" class="fm-select">
                            <option value="">Todos</option>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top:16px;display:flex;justify-content:flex-end;">
                    <button wire:click="aplicarFiltros" class="btn btn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                        </svg>
                        Aplicar filtros
                    </button>
                </div>
            </div>
        </div>

        {{-- ── TOTAL HORAS + TOGGLE MODO ── --}}
        <div class="fu3" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">

            <div class="total-card" style="flex:1;min-width:200px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div
                        style="width:36px;height:36px;border-radius:10px;background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.22);display:flex;align-items:center;justify-content:center;">
                        <svg width="16" height="16" fill="none" stroke="#60a5fa" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div>
                        <p
                            style="font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:2px;">
                            Total horas trabajadas</p>
                        <p style="font-size:20px;font-weight:700;color:#f1f5f9;font-family:'JetBrains Mono',monospace;">
                            {{ $totalHoras }}</p>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:6px;">
                <button wire:click="$set('modo','tabla')"
                    class="btn {{ $modo === 'tabla' ? 'btn-toggle-active' : 'btn-toggle' }}">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <line x1="3" y1="9" x2="21" y2="9" />
                        <line x1="3" y1="15" x2="21" y2="15" />
                        <line x1="9" y1="3" x2="9" y2="21" />
                    </svg>
                    Tabla
                </button>
                <button wire:click="$set('modo','resumen')"
                    class="btn {{ $modo === 'resumen' ? 'btn-toggle-active' : 'btn-toggle' }}">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <line x1="8" y1="6" x2="21" y2="6" />
                        <line x1="8" y1="12" x2="21" y2="12" />
                        <line x1="8" y1="18" x2="21" y2="18" />
                        <circle cx="3" cy="6" r="1" />
                        <circle cx="3" cy="12" r="1" />
                        <circle cx="3" cy="18" r="1" />
                    </svg>
                    Resumen diario
                </button>
            </div>

        </div>

        {{-- ── MODO TABLA ── --}}
        @if ($modo === 'tabla')
            <div class="act-card fu4">
                <div class="act-card-header">
                    <div>
                        <p class="label-xs" style="margin-bottom:0;">Fichajes</p>
                    </div>
                    @if (!empty($fichajes) && count($fichajes) > 0)
                        <span
                            style="background:#1a1d27;border:1px solid #1e2330;border-radius:20px;padding:3px 12px;font-size:11px;color:#64748b;">
                            {{ count($fichajes) }} registros
                        </span>
                    @endif
                </div>

                @if (empty($fichajes) || count($fichajes) === 0)
                    <div style="padding:48px;text-align:center;color:#334155;">
                        <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" style="margin:0 auto 10px;display:block;opacity:.4;">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <p style="font-size:13px;">No hay fichajes para mostrar</p>
                    </div>
                @else
                    {{-- TABLA desktop --}}
                    <div class="act-table-wrap">
                        <table class="act-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fichajes as $f)
                                    <tr>
                                        <td style="font-size:13px;color:#94a3b8;">
                                            {{ \Carbon\Carbon::parse($f->fecha_hora)->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <span class="time-chip">
                                                {{ \Carbon\Carbon::parse($f->fecha_hora)->format('H:i') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $f->tipo }}">
                                                {{ $f->tipo === 'entrada' ? '↑ Entrada' : '↓ Salida' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- TARJETAS móvil --}}
                    <div class="mobile-cards">
                        @foreach ($fichajes as $f)
                            <div class="m-card">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <span class="badge badge-{{ $f->tipo }}">
                                            {{ $f->tipo === 'entrada' ? '↑ Entrada' : '↓ Salida' }}
                                        </span>
                                        <span style="font-size:13px;color:#64748b;">
                                            {{ \Carbon\Carbon::parse($f->fecha_hora)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <span class="time-chip">
                                        {{ \Carbon\Carbon::parse($f->fecha_hora)->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @endif
            </div>
        @endif

        {{-- ── MODO RESUMEN ── --}}
        @if ($modo === 'resumen')
            <div class="act-card fu4">
                <div class="act-card-header">
                    <div>
                        <p class="label-xs" style="margin-bottom:2px;">Resumen diario</p>
                        <p style="font-size:13px;font-weight:600;color:#e2e8f0;">{{ $resumen['empleado'] ?? '' }}</p>
                    </div>
                    @if (!empty($resumen['total']))
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span
                                style="font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#475569;">Total</span>
                            <span
                                style="font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:600;color:#60a5fa;">
                                {{ $resumen['total'] }}
                            </span>
                        </div>
                    @endif
                </div>

                @if (!empty($resumen['detalle']))
                    @foreach ($resumen['detalle'] as $dia)
                        <div class="resumen-item">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div
                                    style="width:34px;height:34px;border-radius:9px;background:#1a1d27;border:1px solid #1e2330;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg width="14" height="14" fill="none" stroke="#475569"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                </div>
                                <span style="font-size:13px;color:#94a3b8;">
                                    {{ \Carbon\Carbon::parse($dia['fecha'])->format('d/m/Y') }}
                                </span>
                            </div>
                            <span
                                style="font-family:'JetBrains Mono',monospace;font-size:14px;font-weight:600;color:#f1f5f9;">
                                {{ $dia['horas'] }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <div style="padding:48px;text-align:center;color:#334155;">
                        <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24" style="margin:0 auto 10px;display:block;opacity:.4;">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <p style="font-size:13px;">No hay datos para mostrar en el resumen</p>
                    </div>
                @endif
            </div>
        @endif

    </div>

</div>
