<?php

namespace Stpronk\Assets\Filament\Clusters\Assets\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Stpronk\Assets\Filament\Clusters\Assets\AssetsCluster;
use Stpronk\Assets\Filament\Clusters\Assets\Resources\AssetResource;
use Stpronk\Assets\Filament\Clusters\Assets\Resources\AssetCategoryResource;
use Stpronk\Assets\Models\Asset;

class AssetsDashboard extends Page
{
    use HasPageShield;

    protected static \BackedEnum|null|string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Asset Dashboard';

    protected static ?string $cluster = AssetsCluster::class;

    protected string $view = 'assets::filament.assets.dashboard';

    public function getStyles(): array
    {
        return [
            asset('css/filament.css')
        ];
    }

    public function getViewData(): array
    {
        $userId = auth()->id();

        $activeAssets = Asset::with('category')
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where('status', 'active')
            ->get();
        $activeCount = $activeAssets->count();
        $activeInvestedCents = (int) $activeAssets->sum('price_cents');

        $closedAssets = Asset::with('category')
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->where('status', 'closed')
            ->orderBy('closed_at')
            ->get();
        $closedCount = $closedAssets->count();
        $realized = $closedAssets->map(function (Asset $asset) {
            $profit = (int) ($asset->take_profit_cents ?? 0) - (int) ($asset->price_cents ?? 0);
            return [
                'asset' => $asset,
                'profit_cents' => $profit,
            ];
        });

        $earnedCents = (int) $realized->filter(fn ($r) => $r['profit_cents'] > 0)->sum('profit_cents');
        $lostCents = (int) $realized->filter(fn ($r) => $r['profit_cents'] < 0)->sum('profit_cents');
        $netCents = $earnedCents + $lostCents; // lostCents is negative

        $totalAbs = max(0, (int) abs($earnedCents) + (int) abs($lostCents));
        $percentEarned = $totalAbs > 0 ? round((abs($earnedCents) / $totalAbs) * 100, 1) : 0.0;

        // Quick action URLs
        $assetIndexUrl = AssetResource::getUrl();
        $assetCreateUrl = AssetResource::getUrl('create');
        $categoryIndexUrl = AssetCategoryResource::getUrl();

        return [
            'activeAssets'        => $activeAssets,
            'activeCount'         => $activeCount,
            'activeInvestedCents' => $activeInvestedCents,
            'closedAssets'        => $closedAssets,
            'closedCount'         => $closedCount,
            'realized'            => $realized,
            'earnedCents'         => $earnedCents,
            'lostCents'           => $lostCents,
            'netCents'            => $netCents,
            'percentEarned'       => $percentEarned,
            'assetIndexUrl'       => $assetIndexUrl,
            'assetCreateUrl'      => $assetCreateUrl,
            'categoryIndexUrl'    => $categoryIndexUrl,
        ];
    }
}
