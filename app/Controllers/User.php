<?php

namespace App\Controllers;

use TCPDF;

use \App\Models\JoinModel;
use \App\Models\RegisModel;
use \App\Models\TimlineModel;
use \App\Models\AjaxModel;
use \App\Models\ServerModel;
use \App\Models\CrudM;
use Config\Services;
use phpDocumentor\Reflection\Types\This;

class User extends BaseController
{
    protected $regisModel;
    protected $timlineModel;
    protected $joinModel;
    protected $ajaxModel;
    protected $validation;
    protected $crudM;
    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->joinModel = new JoinModel();
        $this->timlineModel = new TimlineModel();
        $this->regisModel = new RegisModel();
        $this->ajaxModel = new AjaxModel();
        $this->crudM = new CrudM();
    }

    #menu profil user/ menu utama role user
    public function index()
    {
        $data = [
            'title' => 'My Profile',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
        ];
        return view('/user/index', $data);
    }

    #menu untuk mengedit profile user
    public function edit()
    {
        #form untuk mengupdate data user, nama dan gambar 
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $validation = $this->validation;
            if (!$this->validate([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'makanan harus diisi.'
                    ]
                ],
                'image' => [
                    'rules' => 'max_size[image,5024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'max_size' => 'Ukuran terlalu besar',
                        'is_image' => 'Yang anda pilih bukan image',
                        'mime_in' => 'Yang anda pilih bukan image'
                    ]
                ],
            ])) {
                $msg = [
                    'error' => [
                        'name' => $validation->getError('name'),
                        'image' => $validation->getError('image')
                    ]
                ];
            } else {
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

                $msg = [
                    'sukses' => [
                        'msg' => 'Data berhasil diubah',
                        'link' => '/user'
                    ]
                ];
            }

            echo json_encode($msg);
        } else {
            $data = [
                'title' => 'Edit Profile',
                'user' => $this->regisModel->getemail(session()->get('email')),
                'menu' => $this->joinModel->join(session()->get('role_id')),
                'validation' => $this->validation
            ];
            //dd($this->joinModel->join(session()->get('role_id')));
            return view('/user/edit', $data);
        }
    }


    #menu untuk mengubah password
    public function changepassword()

    {
        #form ubah password dan validation penginputan jika benar maka pasword berhasil dirubah
        if ($this->request->isAJAX()) {
            $validation = $this->validation;
            $id = $this->request->getVar('id');
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
                $msg = [
                    'error' => [
                        'current_password' => $validation->getError('current_password'),
                        'new_password1' => $validation->getError('new_password1')
                    ]
                ];
            } else {
                $data = [

                    'pw ' => $this->request->getVar('current_password'),
                    'cek' => session()->get('email'),
                    'id' => $id,
                    'pwn' => $this->request->getVar('new_password1')
                ];
                $msg = $this->regisModel->changePassword;
            }
            echo json_encode($msg);
        } else {
            $data = [
                'title' => 'Change Password',
                'user' => $this->regisModel->getemail(session()->get('email')),
                'menu' => $this->joinModel->join(session()->get('role_id')),
                'validation' => $this->validation
            ];
            //dd($this->joinModel->join(session()->get('role_id')));
            return view('/user/changepassword', $data);
        }
    }


    #menu timline atau untuk menampilkan data yg ingin ditampilkan 
    public function timline()
    {
        $data = [
            'title' => 'Timeline',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id')),
            'timline' => $this->timlineModel->findAll(),
            //'validation' => \Config\Services::validation()
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/user/timline', $data);
    }

    #menampilkan data table
    public function event()
    {
        $data = [
            'title' => 'Event',
            'user' => $this->regisModel->getemail(session()->get('email')),
            'menu' => $this->joinModel->join(session()->get('role_id'))
            //'validation' => \Config\Services::validation()
        ];
        //dd($this->joinModel->join(session()->get('role_id')));
        return view('/user/event', $data);
    }

    #semua tindakan yg dapat dilakukan user dalam form data yg ditampilkan menggunakan ajax request
    public function eventAksi()
    {
        // $request = Services::request();
        // $datamodel = new ajaxModel($request);

        #akses hanya dapat dilakukan jika menggunakan ajax request
        if ($this->request->isAJAX()) {


            #untuk melakukan select table atau menampilkan table, menggukan ajax(mode select)
            if ($this->request->getVar('mode') == 'select') {
                $data = [
                    'table' => $this->ajaxModel->findAll()
                ];
                $table = [
                    'data' => view('/layout/table', $data)
                ];
                echo json_encode($table);


                #untuk melakukan tambah data table, menggukan ajax(mode tambah)
            } else if ($this->request->getPost('mode') == 'tambah') {
                $table = [
                    'data' => view('modal/modaltambah')
                ];
                echo json_encode($table);

                #untuk menyimpan dan validasi data yg telah ditambah di mode tambah tadi ke data table, menggukan ajax(mode simpan)
            } else if ($this->request->getPost('mode') == 'simpan') {
                $this->validation;

                if (!$this->validate([
                    'nama' => [
                        'rules' => 'required'
                    ],
                    'gambar' => [
                        'rules' => 'required'
                    ],
                    'job' => [
                        'rules' => 'required'
                    ],
                    'address' => [
                        'rules' => 'required'
                    ]
                ])) {

                    $msg = [
                        'error' => [
                            'nama' => $this->validation->getError('nama'),
                            'gambar' => $this->validation->getError('gambar'),
                            'job' => $this->validation->getError('job'),
                            'address' => $this->validation->getError('address'),
                        ]
                    ];
                } else {
                    $this->ajaxModel->save([
                        'img' => $this->request->getVar('gambar'),
                        'nama' => $this->request->getVar('nama'),
                        'job' => $this->request->getVar('job'),
                        'address' => $this->request->getVar('address')
                    ]);

                    $msg = [
                        'sukses' => 'data berhasil disimpan'
                    ];
                }
                echo json_encode($msg);

                #untuk melakukan edit data table, menggukan ajax(mode edit)
            } else if ($this->request->getPost('mode') == 'edit') {

                $id = $this->request->getVar('id');
                $row = $this->ajaxModel->find($id);
                $data = [
                    'id' => $row['id'],
                    'nama' => $row['nama'],
                    'gambar' => $row['img'],
                    'job' => $row['job'],
                    'address' => $row['address']
                ];

                $msg = [
                    'sukses' => view('modal/modaledit', $data)
                ];
                echo json_encode($msg);

                #untuk mengupdate dan validasi data yg telah diedit di mode edit tadi ke data table, menggukan ajax(mode update)
            } else if ($this->request->getPost('mode') == 'update') {
                $validation = $this->validation;
                if (!$this->validate([
                    'nama' => [
                        'rules' => 'required'
                    ],
                    'gambar' => [
                        'rules' => 'required'
                    ],
                    'job' => [
                        'rules' => 'required'
                    ],
                    'address' => [
                        'rules' => 'required'
                    ]
                ])) {

                    $msg = [
                        'error' => [
                            'nama' => $validation->getError('nama'),
                            'gambar' => $validation->getError('gambar'),
                            'job' => $validation->getError('job'),
                            'address' => $validation->getError('address'),
                        ]
                    ];
                } else {
                    $id = $this->request->getVar('id');
                    $data = [
                        'img' => $this->request->getVar('gambar'),
                        'nama' => $this->request->getVar('nama'),
                        'job' => $this->request->getVar('job'),
                        'address' => $this->request->getVar('address')
                    ];

                    $this->ajaxModel->update($id, $data);

                    $msg = [
                        'sukses' => 'data berhasil diupdate'
                    ];
                }
                echo json_encode($msg);

                #untuk menghapus data table, menggukan ajax(mode delete)
            } else if ($this->request->getPost('mode') == 'delete') {
                $id = $this->request->getVar('id');
                $this->ajaxModel->delete($id);
                $msg = [
                    'sukses' => 'data berhasil dihapus'
                ];
                echo json_encode($msg);


                #untuk menampikan form input data secara multi insert(banyak), menggukan ajax(mode tambahbanyak)
            } else if ($this->request->getPost('mode') == 'tambahbanyak') {
                $msg = [
                    'data' => view('layout/formtambah')
                ];
                echo json_encode($msg);

                #untuk menyimpan data banyak tadi yg telah ditambah di mode tambahbanyak ke data table, menggukan ajax(mode simpanbanyak)
            } else if ($this->request->getPost('mode') == 'simpanbanyak') {
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

                #untuk melakukan penghapusan data secara banyak yg telah dipiih
            } else if ($this->request->getPost('mode') == 'hapusbanyak') {
                $id = $this->request->getVar('id');

                $jmldata = count($id);

                for ($i = 0; $i < $jmldata; $i++) {
                    $this->ajaxModel->delete($id[$i]);
                }
                $msg = [
                    'sukses' => "$jmldata data berhasil dihapus"
                ];
                echo json_encode($msg);

                #untuk mengganti foto yg sudah ada atu menambah foto baru
            } else if ($this->request->getPost('mode') == 'formuplod') {
                $id = $this->request->getVar('id');
                $foto = $this->ajaxModel->find($id);
                $data = [
                    'id' => $id,
                    'foto' => $foto['img']
                ];
                $msg = [
                    'sukses' => view('modal/modaluplod', $data)
                ];
                echo json_encode($msg);
            }
        } else {
            redirect('user/event');
        }
    }

    public function ajaxupload()
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



    #config untuk melakukan download pdf dan print dari data yg telah diinput
    public function pdf()
    {

        $data = [
            'title' => 'Data Pegawai',
            'table' => $this->ajaxModel->findAll()
        ];
        $html = view('export/pdf', $data);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Lukmanul Hakim');
        $pdf->SetTitle('Data Pegawai');
        $pdf->SetSubject('Data Pegawai');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $pdf->writeHTML($html, true, false, true, false, '');

        $this->response->setContentType('application/pdf');

        $pdf->Output('DataPegawai.pdf', 'I');
    }


    #menu untuk melakukan pendownloadan excel
    public function excel()
    {
        $data = [
            'title' => 'Data Pegawai',
            'table' => $this->ajaxModel->findAll()
        ];
        echo view('export/excel', $data);
    }

    public function tabled()
    {
        if ($this->request->isAJAX()) {
            $post = $this->request->getPost();
            $data = $this->crudM
                // ->setParams([$post['prodi']])
                ->generate($post);

            echo json_encode($data);
        } else {
        }
    }
}
