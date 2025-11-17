<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Top stat cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Active assets --}}
            <div class="fi-card">
                <p class="fi-card-label">{{ __('stpronk-filament-assets::dashboard.headers.active_assets')  }}</p>
                <p class="fi-card-value">{{ $activeCount }}</p>
            </div>

            {{-- Total invested --}}
            <div class="fi-card">
                <p class="fi-card-label">{{ __('stpronk-filament-assets::dashboard.headers.total_invested') }}</p>
                <p class="fi-card-value">€ {{ number_format(($activeInvestedCents ?? 0) / 100, 2, ',', '.') }}</p>
            </div>

            {{-- Net realized P/L --}}
            @php($net = ($netCents ?? 0) / 100)
            <div class="fi-card">
                <p class="fi-card-label">{{ __('stpronk-filament-assets::dashboard.headers.net_realized') }}</p>
                <p class="fi-card-value {{ $net >= 0 ? 'fi-text-success' : 'fi-text-danger' }}">
                    € {{ number_format($net, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Tables --}}
        <div class="grid grid-cols-1 gap-6">

            {{-- Realized Results --}}
            <div class="fi-panel">
                <h3 class="fi-panel-heading">{{ __('stpronk-filament-assets::dashboard.tables.realized_results.label') }}</h3>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="fi-stat-label">{{ __('stpronk-filament-assets::dashboard.tables.realized_results.columns.earned') }}</p>
                        <p class="fi-stat-value fi-text-success">
                            € {{ number_format(($earnedCents ?? 0) / 100, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="fi-stat-label">{{ __('stpronk-filament-assets::dashboard.tables.realized_results.columns.lost') }}</p>
                        <p class="fi-stat-value fi-text-danger">
                            € {{ number_format(abs($lostCents ?? 0) / 100, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="fi-stat-label">{{ __('stpronk-filament-assets::dashboard.tables.realized_results.columns.net') }}</p>
                        <p class="fi-stat-value {{ ($netCents ?? 0) >= 0 ? 'fi-text-success' : 'fi-text-danger' }}">
                            € {{ number_format(($netCents ?? 0) / 100, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Active Assets --}}
            <div class="fi-panel">
                <h3 class="fi-panel-heading">{{ __('stpronk-filament-assets::dashboard.tables.active_assets.label') }}</h3>
                <div class="overflow-x-auto">
                    <table class="fi-table">
                        <thead>
                        <tr>
                            <th>{{ __('stpronk-filament-assets::dashboard.tables.active_assets.columns.name') }}</th>
                            <th>{{ __('stpronk-filament-assets::dashboard.tables.active_assets.columns.category') }}</th>
                            <th class="fi-text-right">{{ __('stpronk-filament-assets::dashboard.tables.active_assets.columns.price') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($activeAssets as $asset)
                            <tr>
                                <td>{{ $asset->name }}</td>
                                <td>{{ optional($asset->category)->name ?: '—' }}</td>
                                <td class="fi-text-right">€ {{ number_format(($asset->price_cents ?? 0) / 100, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="fi-text-center">{{ __('stpronk-filament-assets::dashboard.tables.active_assets.empty') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Realized Results --}}
            <div class="fi-panel">
                <h3 class="fi-panel-heading">{{ __('stpronk-filament-assets::dashboard.tables.realized_assets.label') }}</h3>

                <div class="overflow-x-auto">
                    <table class="fi-table">
                        <thead>
                        <tr>
                            <th>{{ __('stpronk-filament-assets::dashboard.tables.realized_assets.columns.name') }}</th>
                            <th>{{ __('stpronk-filament-assets::dashboard.tables.realized_assets.columns.category') }}</th>
                            <th>{{ __('stpronk-filament-assets::dashboard.tables.realized_assets.columns.bought') }}</th>
                            <th>{{ __('stpronk-filament-assets::dashboard.tables.realized_assets.columns.sold') }}</th>
                            <th>{{ __('stpronk-filament-assets::dashboard.tables.realized_assets.columns.p_l') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($realized as $row)
                            @php($profit = $row['profit_cents'] ?? 0)
                            <tr>
                                <td>{{ $row['asset']->name }}</td>
                                <td>{{ optional($row['asset']->category)->name ?: '—' }}</td>
                                <td>€ {{ number_format(($row['asset']->price_cents ?? 0) / 100, 2, ',', '.') }}</td>
                                <td>€ {{ number_format(($row['asset']->take_profit_cents ?? 0) / 100, 2, ',', '.') }}</td>
                                <td class="{{ $profit >= 0 ? 'fi-text-success' : 'fi-text-danger' }}">
                                    € {{ number_format($profit / 100, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="fi-text-center">{{ __('stpronk-filament-assets::dashboard.tables.realized_assets.empty') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</x-filament-panels::page>
