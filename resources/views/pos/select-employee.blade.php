<x-app-layout>
    <style>
        .pos-select-wrapper {
            min-height: 100vh;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            padding: 2rem 1rem;
            align-items: center; /* Pusatkan isi secara horizontal */
        }

        /* Header area */
        .pos-header {
            text-align: center;
            margin-bottom: 2rem;
            flex-shrink: 0;
            width: 100%;
            max-width: 1000px;
        }
        .pos-header-icon {
            width: 56px;
            height: 56px;
            background: #e0e7ff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .pos-header h1 {
            font-size: 1.5rem;
            font-weight: 900;
            color: #111827;
            letter-spacing: -0.03em;
            margin: 0 0 0.35rem;
        }
        .pos-header p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        /* Grid capster */
        .capster-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
            flex: 1;
            width: 100%;
            max-width: 1000px; /* Batasi lebar maksimal di layar besar */
            align-content: start; /* Mencegah card tertarik/memanjang secara vertikal */
        }

        /* Card capster */
        .capster-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .capster-card.is-ready {
            border-color: #d1fae5;
        }
        .capster-card.is-ready:hover {
            transform: translateY(-4px) scale(1.02);
            border-color: #10b981;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .capster-card.is-off {
            border-color: #fef3c7;
            opacity: 0.8;
        }
        .capster-card.is-off.admin-override:hover {
            transform: translateY(-4px) scale(1.02);
            border-color: #f59e0b;
            opacity: 1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .capster-card.is-disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        /* Avatar */
        .capster-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 900;
            margin-bottom: 0.625rem;
            position: relative;
            flex-shrink: 0;
        }
        .avatar-ready {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
            box-shadow: 0 4px 10px rgba(99,102,241,0.3);
        }
        .avatar-off {
            background: #f3f4f6;
            color: #9ca3af;
        }
        .status-dot {
            position: absolute;
            bottom: 1px;
            right: 1px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2.5px solid #ffffff;
        }
        .dot-ready { background: #10b981; }
        .dot-off { background: #f59e0b; }

        /* Name & info */
        .capster-name {
            font-size: 0.875rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            margin-bottom: 0.2rem;
            letter-spacing: -0.01em;
        }
        .capster-position {
            font-size: 0.65rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }

        /* Status badge */
        .status-badge {
            padding: 0.2rem 0.625rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .badge-ready {
            background: #d1fae5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .badge-off {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .override-tag {
            position: absolute;
            top: 8px;
            right: 8px;
            padding: 0.15rem 0.4rem;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.55rem;
            font-weight: 900;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border-radius: 6px;
        }

        /* Footer */
        .pos-footer {
            flex-shrink: 0;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .footer-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1.125rem;
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #374151;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            border-radius: 999px;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .footer-link:hover {
            background: #f9fafb;
            color: #111827;
            border-color: #9ca3af;
            transform: translateY(-1px);
        }

        /* Alert */
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            margin-bottom: 0.875rem;
            flex-shrink: 0;
        }
        .alert-error p:first-child {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #991b1b;
            margin: 0 0 0.15rem;
        }
        .alert-error p:last-child {
            font-size: 0.7rem;
            color: #b91c1c;
            margin: 0;
        }

        /* Empty state */
        .empty-state {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
            color: #9ca3af;
        }

        /* Responsive tweaks */
        @media (min-width: 480px) {
            .capster-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }
        }
        @media (min-width: 768px) {
            .pos-select-wrapper {
                padding: 1.75rem 1.5rem;
            }
            .pos-header h1 {
                font-size: 1.5rem;
            }
            .capster-grid {
                grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));
                gap: 1rem;
            }
            .capster-card {
                padding: 1.25rem 1rem;
            }
            .capster-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.375rem;
            }
        }
    </style>

    <div class="pos-select-wrapper">

        {{-- Error Alert --}}
        @if(session('error'))
            <div class="alert-error">
                <div style="flex-shrink:0; width:20px; height:20px; background:#fecaca; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-top:1px;">
                    <svg width="10" height="10" fill="none" stroke="#dc2626" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p>Akses Ditolak</p>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="pos-header">
            <div class="pos-header-icon">
                <svg width="26" height="26" fill="none" stroke="#4f46e5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1>Pilih Capster</h1>
            <p>Siapa yang melayani transaksi ini?</p>
        </div>

        {{-- Grid Capster --}}
        <div class="capster-grid">
            @if ($employees->isEmpty())
                <div class="empty-state">
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom:.75rem;opacity:.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p style="font-size:.875rem; font-weight:600;">Belum ada capster terdaftar.</p>
                </div>
            @else
                @foreach ($employees as $employee)
                    @php
                        $hasCheckedIn = $employee->hasCheckedInToday();
                        $canSelect    = $isAdmin || $hasCheckedIn;

                        if ($hasCheckedIn) {
                            $cardClass = 'is-ready';
                        } elseif ($isAdmin) {
                            $cardClass = 'is-off admin-override';
                        } else {
                            $cardClass = 'is-off is-disabled';
                        }
                    @endphp

                    <a href="{{ $canSelect ? route('pos.transaction', ['store' => $storeId, 'employee' => $employee->id_employee]) : '#' }}"
                       @if(!$canSelect) onclick="event.preventDefault();" @endif
                       class="capster-card {{ $cardClass }}">

                        {{-- Override tag (admin only, off) --}}
                        @if(!$hasCheckedIn && $isAdmin)
                            <span class="override-tag">Override</span>
                        @endif

                        {{-- Avatar --}}
                        <div class="capster-avatar {{ $hasCheckedIn ? 'avatar-ready' : 'avatar-off' }}">
                            {{ strtoupper(substr($employee->employee_name, 0, 1)) }}
                            <span class="status-dot {{ $hasCheckedIn ? 'dot-ready' : 'dot-off' }}"></span>
                        </div>

                        {{-- Info --}}
                        <div class="capster-name">{{ $employee->employee_name }}</div>
                        <div class="capster-position">{{ $employee->position }}</div>

                        {{-- Badge --}}
                        @if($hasCheckedIn)
                            <span class="status-badge badge-ready">Ready</span>
                        @else
                            <span class="status-badge badge-off">Off</span>
                        @endif

                    </a>
                @endforeach
            @endif
        </div>

        {{-- Footer --}}
        <div class="pos-footer">
            @if(Auth::user()->role === 'kasir')
                <a href="{{ route('presence.index') }}" class="footer-link">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Halaman Presensi
                </a>
            @endif
            <a href="{{ route('dashboard') }}" class="footer-link">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
        </div>

    </div>
</x-app-layout>