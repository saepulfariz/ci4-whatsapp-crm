<?php


function asset_url($path = '')
{
    return base_url(PUBLIC_PATH . $path);
}

function copyright($year = null)
{
    $tahun_start = ($year == null) ? date('Y') : $year;
    $tahun_now = date('Y');
    if ($tahun_start == $tahun_now) {
        return $tahun_start;
    } else {
        return $tahun_start . '-' . $tahun_now;
    }
}


function temp_lang(string $line, array $args = [], ?string $locale = null)
{
    $cache = \Config\Services::cache();
    $cacheKey = $line;
    if (!$cache->get($cacheKey)) {
        $data = lang($line, $args, $locale);
        $cache->save('lang_' . $cacheKey . '_' . service('request')->getLocale(), $data, CACHE_TTL); // Cache for 60 minutes
    } else {
        $data = $cache->get($cacheKey);
    }

    return $data;
}
