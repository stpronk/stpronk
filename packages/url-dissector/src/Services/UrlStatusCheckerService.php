<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Services;

use Illuminate\Support\Facades\Http;
use Stpronk\UrlDissector\Models\Url;
use Stpronk\UrlDissector\Enums\UrlOnlineStatus;

class UrlStatusCheckerService
{
    public function check(Url $url): UrlOnlineStatus
    {
        $reconstructor = app(UrlReconstructorService::class);
        $fullUrl = $reconstructor->rebuild($url);

        try {
            $response = Http::timeout(5)->get($fullUrl);
            $isOnline = $response->successful() ? UrlOnlineStatus::ONLINE : UrlOnlineStatus::OFFLINE;
        } catch (\Exception $e) {
            $isOnline = UrlOnlineStatus::OFFLINE;
        }

        $url->update([
            'is_online' => $isOnline,
            'last_checked_at' => now(),
        ]);

        return $isOnline;
    }

    public function checkAll(): void
    {
        Url::query()->each(function (Url $url) {
            $this->check($url);
        });
    }
}
