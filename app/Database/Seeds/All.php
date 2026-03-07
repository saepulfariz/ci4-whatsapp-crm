<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class All extends Seeder
{
    public function run()
    {
        $this->call('SeedAuthPermissionsGroups');
        $this->call('SeedUsers');
        $this->call('SeedAuthMenus');
    }
}
