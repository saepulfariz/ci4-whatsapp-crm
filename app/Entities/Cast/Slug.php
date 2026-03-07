<?php

namespace App\Entities\Cast;

use CodeIgniter\Entity\Cast\BaseCast;

class Slug extends BaseCast
{

    public static function get($value, array $params = [])
    {
        helper('slug');
        if ($params) {
            if ($params[0] == 'slugify') {
                return slugify($value);
            } else if ($params[0] == 'url_slug') {
                return url_slug($value);
            } else if ($params[0] == 'slug') {
                return Slug($value);
            } else {
                return Slug($value);
            }
        }

        return Slug($value);
    }
}
