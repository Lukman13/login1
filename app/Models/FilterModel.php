<?php

namespace App\Models;


use CodeIgniter\Model;

class FilterModel extends Model
{
    public function getmenu($menu)
    {
        $builder = $this->table('user_menu');
        $builder->where('menu', $menu);

        return $builder->get()->getResultArray();
        //return $this->db->table('user_menu')->where('menu', $menu)->get()->getResultArray();
    }
    public function get($role_id, $menu_id)
    {
        $builder = $this->db->table('user_access_menu');
        $builder->where('role_id', $role_id);
        $builder->where('menu_id', $menu_id);

        return $builder->get()->getResultArray();
        //return $this->db->table('user_access_menu')->where('role_id', $role_id)->where('menu_id', $menu_id)->get()->getRowArray();
    }
    public function getrole($role_id)
    {
        $builder = $this->db->table('user_access_menu');
        $builder->where('role_id', $role_id);

        return $builder->get()->getResultArray();
        //return $this->db->table('user_access_menu')->where('role_id', $role_id)->get()->getResultArray();
    }
}
