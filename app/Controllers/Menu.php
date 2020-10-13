<?php

namespace App\Controllers;

use \App\Models\JoinModel;
use \App\Models\RegisModel;
use \App\Models\MenuModel;
use \App\Models\SubModel;

class Menu extends BaseController
{
    protected $regisModel;
    protected $joinModel;
    protected $menuModel;
    protected $subModel;
    protected $validation;
    public function __construct()
    {
        $this->validation;
        $this->joinModel = new JoinModel();
        $this->regisModel = new RegisModel();
        $this->menuModel = new MenuModel();
        $this->subModel = new SubModel();
    }

    #menampilkan menu management dapat menghapus atau menambah atau mengedit menu
    public function index()
    {
        $data = [
            'title' => 'Menu Management',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'mg' => $this->menuModel->findAll(),
            'validation' => $this->validation
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/menu/index', $data);
    }

    #form menambah menu dan validasi jika berhasil maka menu ditambah
    public function add()
    {
        if (!$this->validate([
            'menu' => [
                'rules' => 'required',
            ]
        ])) {
            session()->setFlashdata('danger', 'Harus Diisi');
            return redirect()->to('/menu')->withInput();
        } else {
            $this->menuModel->save([
                'menu' => $this->request->getVar('menu'),
            ]);
            session()->setFlashdata('pesan', 'berhasil');
            return redirect()->to('/menu')->withInput();;
            // return redirect()->to('/home/login')->withInput();
        }
    }

    #form sub menu dapat dapat menghapus atau menambah atau mengedit sub menu
    public function submenu()
    {
        $data = [
            'title' => 'Sub Menu Management',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'mg' => $this->joinModel->joinsub(),
            'validation' => $this->validation
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/menu/submenu', $data);
    }

    #form menambah sub menu dan validasi jika berhasil maka sub menu ditambah
    public function subadd()
    {
        if (!$this->validate([
            'title' => [
                'rules' => 'required',
            ],
            'menu_id' => [
                'rules' => 'required',
            ],
            'url' => [
                'rules' => 'required',
            ],
            'icon' => [
                'rules' => 'required',
            ]
        ])) {
            session()->setFlashdata('danger', 'Harus Diisi');
            return redirect()->to('/menu/submenu')->withInput();
        } else {
            $this->subModel->save([
                'menu_id' => $this->request->getVar('menu_id'),
                'title' => $this->request->getVar('title'),
                'url' => $this->request->getVar('url'),
                'icon' => $this->request->getVar('icon'),
                'is_active' => $this->request->getVar('is_active'),
            ]);
            session()->setFlashdata('pesan', 'berhasil');
            return redirect()->to('/menu/submenu')->withInput();;
            // return redirect()->to('/home/login')->withInput();
        }
    }
}
