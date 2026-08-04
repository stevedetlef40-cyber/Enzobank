<?php

namespace App\Routing;

use Illuminate\Routing\UrlGenerator;

class VersionedUrlGenerator extends UrlGenerator
{
    /**
     * Generate the URL to an application asset, appending a cache-busting
     * version query (?v=<file mtime>) so edits reflect immediately even when
     * a CDN (e.g. Cloudflare) caches static files by their default Edge TTL.
     */
    public function asset($path, $secure = null)
    {
        $url = parent::asset($path, $secure);

        $file = public_path($path);
        if (is_file($file)) {
            $url .= (strpos($url, '?') !== false ? '&' : '?').'v='.filemtime($file);
        }

        return $url;
    }
}
