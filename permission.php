<?php



// $permissions = 'menus.access|users.access';
$permissions = 'menus.access';
$permissions = explode('|', $permissions);
$can_access = false;
if (count($permissions) > 1) {
    foreach ($permissions as $permission) {
        var_dump($permission);
    }
} else {
    var_dump($permissions);
}
