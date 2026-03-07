<?php

namespace App\Database\Seeds;

use App\Models\PaymentMethodModel;
use CodeIgniter\Database\Seeder;

class SeedPaymentMethods extends Seeder
{
    public function run()
    {
        $payment_methods = [
            [
                'name' => 'Bank Transfer',
            ],
            [
                'name' => 'QRIS',
            ],
            [
                'name' => 'Cash',
            ],
        ];

        $payment_method_model = model(PaymentMethodModel::class);

        foreach ($payment_methods as $payment_method) {
            $payment_method_model->insert($payment_method);
        }
    }
}
