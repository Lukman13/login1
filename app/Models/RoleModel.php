<?php

namespace App\Models;


use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'user_role';
    protected $allowedFields = ['role'];
    //public function get()
    // {
    //     // $builder=$this->table('orang');
    //     // $builder->like('nama', '$keyword');
    //     // return $builder;
    //     return $this->findAll();
    // }
    // public function getid($role_id)
    // {
    //     return $this->where(['id' => $role_id])->first();
    // }
}
