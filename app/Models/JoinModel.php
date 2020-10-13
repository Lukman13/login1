<?php

namespace App\Models;

use App\Controllers\Home;
use CodeIgniter\Model;

class JoinModel extends Model
{
    public function join($role_id)
    {
        $builder = $this->db->table('user_menu');
        $builder->select('user_menu.id, menu');
        $builder->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id');
        $builder->where('user_access_menu.role_id', $role_id);
        $builder->orderBy('user_access_menu.menu_id', 'ASC');

        return $builder->get()->getResultArray();
        //return $this->db->table('user_menu')->select('user_menu.id, menu')->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id')->where('user_access_menu.role_id', $role_id)->orderBy('user_access_menu.menu_id', 'ASC')->get()->getResultArray();
    }
    public function joinid($role_id)
    {
        $builder = $this->db->table('user_menu');
        $builder->select('user_menu.id');
        $builder->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id');
        $builder->where('user_access_menu.role_id', $role_id);
        $builder->orderBy('user_access_menu.menu_id', 'ASC');

        return $builder->get()->getRowArray();
        //return $this->db->table('user_menu')->select('user_menu.id')->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id')->where('user_access_menu.role_id', $role_id)->orderBy('user_access_menu.menu_id', 'ASC')->get()->getRowArray();
    }
    public function sub($id)
    {
        $builder = $this->db->table('user_sub_menu');
        $builder->select('*');
        $builder->join('user_menu', 'user_sub_menu.menu_id = user_menu.id');
        $builder->where('user_sub_menu.menu_id', $id);
        $builder->where('user_sub_menu.is_active = 1');

        return $builder->get()->getResultArray();
        //return $this->db->table('user_sub_menu')->select('*')->join('user_menu', 'user_sub_menu.menu_id = user_menu.id')->where('user_sub_menu.menu_id', $id)->where('user_sub_menu.is_active = 1')->get()->getResultArray();
    }
    public function joinsub()
    {
        $builder = $this->db->table('user_sub_menu');
        $builder->select('user_sub_menu.*, user_menu.menu');
        $builder->join('user_menu', 'user_sub_menu.menu_id = user_menu.id');

        return $builder->get()->getResultArray();
        //return $this->db->table('user_sub_menu')->select('user_sub_menu.*, user_menu.menu')->join('user_menu', 'user_sub_menu.menu_id = user_menu.id')->get()->getResultArray();
    }
}
