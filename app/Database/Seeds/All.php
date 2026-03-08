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

        $this->call('SeedCategories');
        $this->call('SeedProducts');
        $this->call('SeedCustomers');
        $this->call('SeedTransactions');

        $this->call('SeedPaymentMethods');

        $this->call('SeedBroadcasts');
        $this->call('SeedShareBroadcasts');

        $this->call('SeedAutoReplies');
        $this->call('SeedChatBots');
    }
}
