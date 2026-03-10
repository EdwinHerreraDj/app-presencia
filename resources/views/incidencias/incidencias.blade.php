@extends('layouts.vertical', ['subtitle' => 'Incidencias'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        body,
        .main-content,
        .content-page {
            background: #0a0c10 !important;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* Fondo grid */
        .inc-bg::before {
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

        .inc-bg::after {
            content: '';
            position: fixed;
            top: -180px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.07) 0%, transparent 70%);
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
            animation: fadeUp .45s .09s ease both;
        }

        .fu3 {
            animation: fadeUp .45s .18s ease both;
        }

        .inc-wrap {
            position: relative;
            z-index: 1;
            max-width: 560px;
            margin: 0 auto;
            padding: 28px 16px;
            font-family: 'Sora', sans-serif;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* Card */
        .inc-card {
            background: #111318;
            border: 1px solid #1e2330;
            border-radius: 22px;
            overflow: hidden;
        }

        .inc-card-accent {
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #3b82f6);
        }

        .inc-card-body {
            padding: 28px 28px 32px;
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

        /* Inputs / Selects */
        .fm-control {
            background: #0f1117;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: 'Sora', sans-serif;
            width: 100%;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .fm-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }

        .fm-control::placeholder {
            color: #2a3040;
        }

        .fm-control[type="date"],
        .fm-control[type="time"] {
            color-scheme: dark;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .fm-select {
            background: #0f1117;
            border: 1px solid #1e2330;
            color: #f1f5f9;
            border-radius: 12px;
            padding: 12px 40px 12px 16px;
            font-size: 14px;
            font-family: 'Sora', sans-serif;
            width: 100%;
            outline: none;
            appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%236366f1' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            transition: border-color .2s, box-shadow .2s;
        }

        .fm-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        }

        .fm-select option {
            background: #111318;
        }

        textarea.fm-control {
            resize: vertical;
            min-height: 100px;
            line-height: 1.6;
        }

        /* Form field */
        .fm-field {
            margin-bottom: 18px;
        }

        .fm-field:last-of-type {
            margin-bottom: 0;
        }

        /* Date/time row */
        .date-time-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        /* Btn submit */
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Sora', sans-serif;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
        }

        .btn-submit:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, .35);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Alert flash */
        .flash-alert {
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeUp .4s ease both;
        }

        .flash-success {
            background: rgba(52, 211, 153, .1);
            border: 1px solid rgba(52, 211, 153, .25);
            color: #34d399;
        }

        .flash-error {
            background: rgba(248, 113, 113, .1);
            border: 1px solid rgba(248, 113, 113, .25);
            color: #f87171;
        }

        /* Divider */
        .fm-divider {
            border: none;
            border-top: 1px solid #1e2330;
            margin: 22px 0;
        }

        @media (max-width: 480px) {
            .date-time-row {
                grid-template-columns: 1fr;
            }

            .inc-card-body {
                padding: 22px 18px 26px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="inc-bg" style="position:fixed;inset:0;pointer-events:none;z-index:0;"></div>

    <div class="inc-wrap">

        {{-- ── CABECERA ── --}}
        <div class="fu" style="text-align:center;padding-top:8px;">
            <div
                style="width:52px;height:52px;border-radius:15px;background:linear-gradient(135deg,#6366f1,#3b82f6);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <svg width="22" height="22" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
            </div>
            <h1 style="font-size:21px;font-weight:700;color:#f1f5f9;margin-bottom:4px;">Registro de Incidencias</h1>
            <p style="font-size:13px;color:#475569;">
                Bienvenido,
                <span style="color:#a5b4fc;font-weight:600;">{{ session('user_name') }}</span>
            </p>
        </div>

        {{-- ── FLASH MESSAGES ── --}}
        @if (session('success'))
            <div class="flash-alert flash-success fu2">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24" style="flex-shrink:0;">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="flash-alert flash-error fu2">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24" style="flex-shrink:0;">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── FORMULARIO ── --}}
        <div class="inc-card fu3">
            <div class="inc-card-accent"></div>
            <div class="inc-card-body">

                <form action="{{ route('incidencias.store') }}" method="POST">
                    @csrf

                    {{-- Empresa --}}
                    <div class="fm-field">
                        <span class="label-xs">Empresa</span>
                        <select class="fm-select" id="empresa_id" name="empresa_id" required>
                            <option value="" disabled selected>Selecciona una empresa...</option>
                            @foreach ($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="fm-divider">

                    {{-- Fecha y hora --}}
                    <div class="date-time-row fm-field">
                        <div>
                            <span class="label-xs">Fecha del olvido</span>
                            <input type="date" name="fecha" class="fm-control" required>
                        </div>
                        <div>
                            <span class="label-xs">Hora estimada</span>
                            <input type="time" name="hora" class="fm-control" required>
                        </div>
                    </div>

                    {{-- Tipo --}}
                    <div class="fm-field">
                        <span class="label-xs">Tipo de fichaje</span>
                        <select name="tipo" class="fm-select" required>
                            <option value="entrada">↑ Entrada</option>
                            <option value="salida">↓ Salida</option>
                        </select>
                    </div>

                    <hr class="fm-divider">

                    {{-- Motivo --}}
                    <div class="fm-field">
                        <span class="label-xs">Motivo</span>
                        <textarea name="motivo" class="fm-control" rows="4" placeholder="Describe el motivo del olvido..." required></textarea>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                        Enviar incidencia
                    </button>

                </form>

            </div>
        </div>

        {{-- Nota informativa --}}
        <p style="text-align:center;font-size:11px;color:#1e2330;padding-bottom:8px;">
            Tu solicitud será revisada por un encargado antes de ser procesada.
        </p>

    </div>
@endsection

@section('scripts')
    @vite(['resources/js/pages/fichaje.js'])
@endsection
