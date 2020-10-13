<?php

namespace App\Models;

use App\Controllers\Home;
use CodeIgniter\Model;

class TokenModel extends Model
{
    protected $table = 'user_token';
    protected $allowedFields = ['email', 'token', 'date_created'];

    public function token($token)
    {
        return $this->where(['token' => $token])->first();
    }
}
