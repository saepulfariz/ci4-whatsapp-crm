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


function send_whatsapp_message($to, $text)
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
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_USERAGENT => 'Mozilla/5.0',
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


function send_whatsapp_image($phone, $caption = '', $imagePath = null, $imageUrl = null, $options = [])
{
    $url = getenv('GOWA_URL') . '/send/image';
    $username = getenv('GOWA_USERNAME');
    $password = getenv('GOWA_PASSWORD');
    $device = getenv('GOWA_DEVICE');

    $postFields = [
        'phone' => $phone,
        'caption' => $caption,
        // 'view_once' => $options['view_once'] ?? 'false',
        // 'compress' => $options['compress'] ?? 'false',
        // 'duration' => $options['duration'] ?? 3600,
        // 'is_forwarded' => $options['is_forwarded'] ?? 'false'
    ];

    if (!empty($imagePath) && file_exists($imagePath)) {

        $mime = mime_content_type($imagePath);
        $filename = basename($imagePath);

        $postFields['image'] = new CURLFile($imagePath, $mime, $filename);
        // fix mime result curl empty
    }

    // jika pakai image url
    if (!empty($imageUrl)) {
        $postFields['image_url'] = $imageUrl;
    }

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "X-Device-Id: " . $device
        ],
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_USERPWD        => "$username:$password",
    ]);

    if (ENVIRONMENT === 'development') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }


    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return [
            'success' => false,
            'error' => $error
        ];
    }

    curl_close($ch);

    return json_decode($response, true);
}
