<?php

namespace App\Models;


use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'user_menu';
    protected $allowedFields = ['menu'];
}
