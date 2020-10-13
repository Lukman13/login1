<?php

namespace App\Models;


use CodeIgniter\Model;

class SubModel extends Model
{
    protected $table = 'user_sub_menu';
    protected $allowedFields = ['menu_id', 'title', 'url', 'icon', 'is_active'];
    // public function get()
    // {
    //     // $builder=$this->table('orang');
    //     // $builder->like('nama', '$keyword');
    //     // return $builder;
    //     return $this->findAll();
    // }
}
