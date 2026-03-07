<?php

use App\Entities\Superadmin\Log;
use App\Models\Superadmin\LogModel;

function getProfile()
{
    if (!auth()->id()) {
        return null;
    }

    $cache = \Config\Services::cache();
    $cacheKey = 'auth_user_' . auth()->id();
    if (!$cache->get($cacheKey)) {
        $user =  auth()->getProvider()->select('users.*, auth_identities.name as name, auth_identities.secret as email_user')->join('auth_identities', 'auth_identities.user_id = users.id')->where('users.id', auth()->id())->first();
        $cache->save($cacheKey, $user, CACHE_TTL);
    } else {
        $user = $cache->get($cacheKey);
    }
    return $user;
}

if (!function_exists('log_activity')) {
    function log_activity($action, $tableName, $recordId = null, $changes = null)
    {
        $logModel = new LogModel();

        $userId = auth()->id(); // pastikan session menyimpan user_id

        $data  = [
            'ip_address' => service('request')->getIPAddress(),
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'changes'    => is_array($changes) ? json_encode($changes) : $changes
        ];

        $log = new Log();
        $log->fill($data);


        if (!$logModel->save($log)) {
            log_message('error', 'Exception create logs');
            throw new \Exception('Failed insert logs');
        }

        return true;
    }
}


function isValidToken(string $rawToken): bool
{
    $hashed = hash('sha256', $rawToken);

    $db = \Config\Database::connect();
    $tokenRow =
        $db->table('auth_identities')
        ->where('type', 'access_token')
        ->where('secret', $hashed)
        ->get()->getRowArray();

    if (!$tokenRow) {
        return false; // Token tidak ditemukan
    }

    if (!empty($tokenRow['expires']) && strtotime($tokenRow['expires']) < time()) {
        return false; // Token expired
    }

    return true; // Token valid
}

function checkPermission(string $permission, string $redirect = "")
{
    $user = auth()->user();

    if (! $user || ! $user->can($permission)) {

        $url_redirect = "";
        if ($redirect != "") {
            $url_redirect =  base_url($redirect);
        } else {
            $uri = service('uri');
            $firstSegment = $uri->getSegment(1);

            if ($firstSegment === 'api') {
                $url_redirect =  base_url("api/invalid-access");
            } else {
                $url_redirect = base_url('dashboard');
            }
        }

        return redirect()->to($url_redirect)->with('error', lang('Auth.notEnoughPrivilege'))->send();
    }

    return null;
}
