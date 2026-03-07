<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Language extends BaseController
{
    public function switch($locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session()->set('lang', $locale);
        }

        return redirect()->back();
    }
}
