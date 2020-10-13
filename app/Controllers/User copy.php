<?php

namespace App\Controllers;

use \App\Models\JoinModel;
use \App\Models\RegisModel;

use \App\Models\TimlineModel;
use \App\Models\AjaxModel;
use \App\Models\ServerModel;
use Config\Services;
use phpDocumentor\Reflection\Types\This;

class User extends BaseController
{
    protected $regisModel;
    protected $timlineModel;
    protected $joinModel;
    protected $ajaxModel;
    public function __construct()
    {
        $this->joinModel = new JoinModel();
        $this->timlineModel = new TimlineModel();
        $this->regisModel = new RegisModel();
        //$this->ajaxModel = new AjaxModel();
    }
    public function index()
    {
        $data = [
            'title' => 'My Profile',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/user/index', $data);
    }

    public function edit()
    {
        $data = [
            'title' => 'Edit Profile',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'validation' => \Config\Services::validation()
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/user/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            //'nama' => 'required|is_unique[makanan.nama]'
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} makanan harus diisi.'
                ]
            ],
            'image' => [
                'rules' => 'max_size[image,5024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran terlalu besar',
                    'is_image' => 'Yang anda pilih bukan foto',
                    'mime_in' => 'Yang anda pilih bukan foto'
                ]
            ]
        ])) {
            return redirect()->to('/user/edit/')->withInput();
        }

        $fileFoto = $this->request->getFile('image');

        //cek foto sama atau tidak
        if ($fileFoto->getError() == 4) {
            $namaFoto = $this->request->getVar('imageLama');
        } else {
            //ubah nama file
            $namaFoto = $fileFoto->getRandomName();
            //pindah foto
            $fileFoto->move('img', $namaFoto);
            //hapus file terdahulu
            if ($this->request->getVar('imageLama') != 'default.jpg') {
                unlink('img/' . $this->request->getVar('imageLama'));
            }
        }

        $this->regisModel->save([
            'id' => $id,
            'name' => $this->request->getVar('name'),
            'image' => $namaFoto
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diubah');

        return redirect()->to('/user');
    }

    public function changepassword()
    {
        $data = [
            'title' => 'Change Password',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'validation' => \Config\Services::validation()
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/user/changepassword', $data);
    }

    public function changepw($id)
    {
        if (!$this->validate([
            //'nama' => 'required|is_unique[makanan.nama]'
            'current_password' => [
                'rules' => 'required'
            ],
            'new_password1' => [
                'rules' => 'required|min_length[3]|matches[new_password2]',
                'errors' => [
                    'matches' => 'Password doesnt match',
                    'min_length' => 'password too short'
                ]
            ],
            'new_password2' => [
                'rules' => 'required|matches[new_password1]'
            ]
        ])) {
            return redirect()->to('/user/changepassword')->withInput();;
        } else {
            $pw = $this->request->getVar('current_password');
            $cek = $this->regisModel->getemail(session()->get('email'));
            $pwn = $this->request->getVar('new_password1');
            if (!password_verify($pw, $cek['password'])) {
                session()->setFlashdata('danger', 'Wrong Current Password');
                return redirect()->to('/user/changepassword');
            } else {
                if ($pw == $pwn) {
                    session()->setFlashdata('danger', 'New password cannot be the same as current password');
                    return redirect()->to('/user/changepassword');
                } else {
                    $this->regisModel->save([
                        'id' => $id,
                        'password' => password_hash($this->request->getVar('new_password1'), PASSWORD_DEFAULT),
                    ]);

                    session()->setFlashdata('pesan', 'Password Berhasil Diubah');
                    return redirect()->to('/user');
                }
            }
        }
    }

    public function timline()
    {
        $data = [
            'title' => 'Timeline',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'timline' => $this->timlineModel->get(),
            //'validation' => \Config\Services::validation()
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/user/timline', $data);
    }

    public function event()
    {
        if ($this->request->isAJAX()) {
            $request = Services::request();
            $datamodel = new ajaxModel($request);
            echo json_encode($datamodel->eventDTB());
        } else {
            $data = [
                'title' => 'Event',
                'user' => $this->regisModel->getemail(session()->get('email')),
                'menu' => $this->joinModel->join(session()->get('role_id'))
                //'validation' => \Config\Services::validation()
            ];
            //dd($this->joinModel->join(session()->get('role_id')));
            return view('/user/event', $data);
        }
    }
    public function eventAksi()
    {
        $request = Services::request();
        $datamodel = new ajaxModel($request);
        if ($this->request->isAJAX()) {
            echo json_encode($datamodel->eventHandler());
        } else {
            redirect('user/event');
        }
    }


    public function tambahbnyk()
    {
        if ($this->request->isAJAX()) {
            $msg = [
                'data' => view('layout/formtambah')
            ];
            echo json_encode($msg);
        }
    }

    public function simpanbanyak()
    {
        if ($this->request->isAJAX()) {
            $nama = $this->request->getVar('nama');
            $gambar = $this->request->getVar('gambar');
            $job = $this->request->getVar('job');
            $address = $this->request->getVar('address');

            $jmldata = count($nama);

            for ($i = 0; $i < $jmldata; $i++) {
                $this->ajaxModel->insert([
                    'img' => $gambar[$i],
                    'nama' => $nama[$i],
                    'job' => $job[$i],
                    'address' => $address[$i],

                ]);
            }
            $msg = [
                'sukses' => "$jmldata data berhasil disimpan"
            ];
            echo json_encode($msg);
        }
    }

    public function hapusbanyak()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');

            $jmldata = count($id);

            for ($i = 0; $i < $jmldata; $i++) {
                $this->ajaxModel->delete($id[$i]);
            }
            $msg = [
                'sukses' => "$jmldata data berhasil dihapus"
            ];
            echo json_encode($msg);
        }
    }


    public function formuplod()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $foto = $this->ajaxModel->find($id);
            $data = [
                'id' => $id,
                'foto' => $foto['img']
            ];
            $msg = [
                'sukses' => view('layout/modaluplod', $data)
            ];
            echo json_encode($msg);
        }
    }

    public function ajaxuplod()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $id = $this->request->getVar('id');
            $fileFoto = $this->request->getVar('foto');

            if (!$this->validate([
                'foto' => [
                    'rules' => 'uploaded[foto]|max_size[foto,5024]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'max_size' => 'Ukuran terlalu besar',
                        'is_image' => 'Yang anda pilih bukan foto',
                        'mime_in' => 'Yang anda pilih bukan foto'
                    ]
                ]
            ])) {
                $msg = [
                    'error' => [
                        'foto' => $validation->getError('foto')
                    ]
                ];
            } else {

                $cek = $this->ajaxModel->find($id);
                $fotolama = $cek['img'];
                if ($fotolama != NULL || $fotolama != "") {
                    if ($fotolama != 'default.jpg') {
                        unlink('img/' . $fotolama);
                    }
                }

                $fileFoto = $this->request->getFile('foto');
                $fileFoto->move('img', $id . '.' . $fileFoto->getExtension());

                $updatedata = [
                    'img' => $fileFoto->getName()
                ];
                $this->ajaxModel->update($id, $updatedata);

                $msg = [
                    'sukses' => 'berhasil diupload'
                ];
            }
            echo json_encode($msg);
        }
    }
}
