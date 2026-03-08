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


function send_message($to, $text)
{

    $url = getenv('GOWA_URL') . '/send/message';
    $username = getenv('GOWA_USERNAME');
    $password = getenv('GOWA_PASSWORD');
    $device = getenv('GOWA_DEVICE');

    $data = [
        "phone"   => $to,
        "message" => $text
    ];

    $ch = curl_init($url);

    if (ENVIRONMENT === 'development') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => [
            "Content-Type: application/json",
            "X-Device-Id: " . $device
        ],
        CURLOPT_USERPWD        => "$username:$password",
    ]);

    $response = curl_exec($ch);
    $error    = curl_error($ch);

    curl_close($ch);

    if ($error) {
        return "cURL Error: " . $error;
    } else {
        return json_decode($response, true);
    }
}
