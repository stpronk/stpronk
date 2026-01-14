<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Services;

use Stpronk\UrlDissector\Models\Path;
use Stpronk\UrlDissector\Models\PathSegment;
use Illuminate\Support\Facades\DB;

class PathParserService
{
    public function parse(string $path): array
    {
        $path = '/' . ltrim($path, '/');
        $segments = array_filter(explode('/', $path), fn($s) => $s !== '');

        return [
            'full_path' => $path,
            'segments' => array_values($segments),
            'depth' => count($segments),
            'hash' => md5($path),
        ];
    }

    public function findOrCreate(string $path): Path
    {
        $parsed = $this->parse($path);

        return DB::transaction(function () use ($parsed) {
            $pathModel = config('url-dissector.models.path')::firstOrCreate(
                ['hash' => $parsed['hash']],
                [
                    'full_path' => $parsed['full_path'],
                    'depth' => $parsed['depth'],
                ]
            );

            if ($pathModel->wasRecentlyCreated) {
                $parentSegmentId = null;
                foreach ($parsed['segments'] as $index => $segmentName) {
                    $segment = config('url-dissector.models.path_segment')::create([
                        'path_id' => $pathModel->id,
                        'segment' => $segmentName,
                        'depth' => $index,
                        'order' => $index,
                        'parent_segment_id' => $parentSegmentId,
                    ]);
                    $parentSegmentId = $segment->id;
                }
            }

            return $pathModel;
        });
    }
}
