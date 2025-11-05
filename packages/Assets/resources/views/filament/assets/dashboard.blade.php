<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Top stat cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Active assets --}}
            <div class="fi-card">
                <p class="fi-card-label">Active Assets</p>
                <p class="fi-card-value">{{ $activeCount }}</p>
            </div>

            {{-- Total invested --}}
            <div class="fi-card">
                <p class="fi-card-label">Total Invested (Active)</p>
                <p class="fi-card-value">€ {{ number_format(($activeInvestedCents ?? 0) / 100, 2, ',', '.') }}</p>
            </div>

            {{-- Net realized P/L --}}
            @php($net = ($netCents ?? 0) / 100)
            <div class="fi-card">
                <p class="fi-card-label">Net Realized P/L</p>
                <p class="fi-card-value {{ $net >= 0 ? 'fi-text-success' : 'fi-text-danger' }}">
                    € {{ number_format($net, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Tables --}}
        <div class="grid grid-cols-1 gap-6">

            {{-- Realized Results --}}
            <div class="fi-panel">
                <h3 class="fi-panel-heading">Realized Results</h3>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="fi-stat-label">Earned</p>
                        <p class="fi-stat-value fi-text-success">
                            € {{ number_format(($earnedCents ?? 0) / 100, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="fi-stat-label">Lost</p>
                        <p class="fi-stat-value fi-text-danger">
                            € {{ number_format(abs($lostCents ?? 0) / 100, 2, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="fi-stat-label">Net</p>
                        <p class="fi-stat-value {{ ($netCents ?? 0) >= 0 ? 'fi-text-success' : 'fi-text-danger' }}">
                            € {{ number_format(($netCents ?? 0) / 100, 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Active Assets --}}
            <div class="fi-panel">
                <h3 class="fi-panel-heading">Active Assets</h3>
                <div class="overflow-x-auto">
                    <table class="fi-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th class="fi-text-right">Price</th>
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
                                <td colspan="3" class="fi-text-center">No active assets.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Realized Results --}}
            <div class="fi-panel">
                <h3 class="fi-panel-heading">Realized Assets</h3>

                <div class="overflow-x-auto">
                    <table class="fi-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Buy</th>
                            <th>Sold</th>
                            <th>P/L</th>
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
                                <td colspan="5" class="fi-text-center">No realized results yet.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</x-filament-panels::page>
