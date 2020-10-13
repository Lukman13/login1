<?php

namespace App\Controllers;

use \App\Models\JoinModel;
use \App\Models\RegisModel;
use \App\Models\RoleModel;
use \App\Models\MenuModel;
use \App\Models\FilterModel;

class Admin extends BaseController
{
    protected $regisModel;
    protected $joinModel;
    protected $roleModel;
    protected $menuModel;
    protected $filterModel;
    public function __construct()
    {
        $request = \Config\Services::request();
        $this->joinModel = new JoinModel();
        $this->regisModel = new RegisModel();
        $this->roleModel = new RoleModel();
        $this->menuModel = new MenuModel();
        $this->filterModel = new FilterModel();
    }

    #menu utama admin/menampilkan view dashboard
    public function index()
    {

        $data = [
            'title' => 'Dashboard',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
        ];
        return view('/admin/index', $data);
    }

    #untuk mengambil sub menu dan menampilkannya ke view
    public function sub($id)
    {
        return $this->joinModel->sub($id);
    }

    #untuk mengambil data role dan meengecek role dan menu apa saja yg dapat diakses
    public function rd($role)
    {
        return $this->filterModel->getrole($role);
    }

    #untuk menampilkan form role apa saja yg ada di aplikasi ini
    public function role()
    {
        $data = [
            'title' => 'Role',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'role' => $this->roleModel->findAll()
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/admin/role', $data);
    }

    #untuk menambah role baru
    public function roleadd()
    {
        if (!$this->validate([
            'role' => [
                'rules' => 'required',
            ]
        ])) {
            session()->setFlashdata('pesan', 'Harus Diisi');
            return redirect()->to('/admin/role')->withInput();
        } else {
            $this->menuModel->save([
                'role' => $this->request->getVar('role'),
            ]);
            session()->setFlashdata('pesan', 'berhasil');
            return redirect()->to('/admin/role')->withInput();;
            // return redirect()->to('/home/login')->withInput();
        }
    }

    #request untuk melihat menu yg dapat diakses oleh role tersebut
    public function roleaccess($role_id)
    {
        $data = [
            'title' => 'Role',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'role' => $this->roleModel->find($role_id),
            'mn'   => $this->menuModel->findAll()
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/admin/role-access', $data);
    }
}
