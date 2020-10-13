<?php

namespace App\Models;

use App\Controllers\Home;
use CodeIgniter\Model;

class RegisModel extends Model
{
    protected $table = 'user';
    protected $allowedFields = ['name', 'email', 'image', 'password', 'role_id', 'is_active'];
    protected $useTimestamps = true;

    public function getemail($email)
    {
        return $this->where(['email' => $email])->first();
    }

    public function get($email)
    {
        $builder = $this->table();
        $builder->where(['email' => $email]);
        $builder->where(['is_active' => 1]);

        return $builder->first();
        //return $this->where(['email' => $email])->where(['is_active' => 1])->first();
    }

    public function changePassword($data)
    {
        $cek = $data['cek'];
        if (!password_verify($data['pw'], $cek['password'])) {
            $msg = [
                'errorlog' => [
                    'wrongpw' => 'Wrong Current Password'
                ]
            ];
        } else {
            if ($data['pw'] == $data['pwn']) {
                $msg = [
                    'errorlog' => [
                        'samepw' => 'New password cannot be the same as current password'
                    ]
                ];
            } else {
                $this->regisModel->save([
                    'id' => $data['id'],
                    'password' => password_hash($data['pwn'], PASSWORD_DEFAULT),
                ]);
                $msg = [
                    'sukses' => [
                        'msg' => 'Password Berhasil Diubah',
                        'link' => '/user'
                    ]
                ];
            }
        }
        return $msg;
    }
}
