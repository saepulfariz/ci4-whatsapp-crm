<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;


class SeedUsers extends Seeder
{
    public function run()
    {
        $users = service('auth')->setAuthenticator(null)->getProvider();

        $user = new User([
            'username' => 'superadmin',
            'email'    => 'super@admin.com',
            'password' => 'password',
        ]);
        $users->save($user);

        $userId = $users->getInsertID();

        $update_indentity = [
            'name' => 'Superadmin',
        ];

        $model_user_identity = new UserIdentityModel();

        $model_user_identity->update($users->getInsertID(), $update_indentity);

        $user = $users->findById($userId);

        // Add to default group
        $users->addToDefaultGroup($user);

        $userModel = model(UserModel::class);

        // ERROR KARENA GAK ADA ENTITI EMAIL di model/table users

        // Ambil user berdasarkan email
        // $user = $userModel->where('email', 'super@admin.com')->first();

        // if ($user) {
        //     $user->addGroup('superadmin', 'beta');
        // }

        // Cari user berdasarkan email (dari auth_identities)
        $user = $userModel
            ->select('users.*') // hanya ambil kolom dari tabel users
            ->join('auth_identities', 'auth_identities.user_id = users.id')
            ->where('auth_identities.secret', 'super@admin.com')
            ->where('auth_identities.type', 'email_password') // pastikan jenis identity-nya email
            ->first();

        if ($user instanceof User) {
            $user->addGroup('admin');
        }

        // Ambil user berdasarkan ID
        $user = $userModel->find($userId); // $userId = id target

        if ($user) {
            $user->addGroup('superadmin', 'beta');
        }


        // ADMIN

        // $users = model(UserModel::class);
        $users = auth()->getProvider();

        // Cek apakah user sudah ada (hindari duplikasi)
        // if ($users->where('email', 'admin@example.com')) {
        //     return;
        // }

        // Buat user baru
        $user = new User([
            'email'    => 'admin@mail.com',
            'username' => 'admin',
            'password' => '123', // Shield akan meng-hash ini secara otomatis
        ]);

        $users->save($user);

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());

        $model_user_identity->update($users->getInsertID(), ['name' => 'Admin']);

        // Add to default group
        $users->addToDefaultGroup($user);
    }
}
