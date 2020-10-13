<?php

namespace App\Models;


use CodeIgniter\Model;
use Config\Services;
use CodeIgniter\HTTP\RequestInterface;

class AjaxModel extends Model
{
    protected $table = 'ajax';
    protected $allowedFields = ['img', 'nama', 'job', 'address'];
    protected $column_order = array(null, null, 'nama', 'job', 'address', null);
    protected $column_search = array('nama', 'job', 'address');
    protected $order = array('nama' => 'asc');
    protected $request;
    protected $db;
    protected $dt;

    function __construct(RequestInterface $request)
    {
        parent::__construct();
        $this->db = db_connect();
        $this->request = $request;
        //untuk join bisa diubah dibawah
        $this->dt = $this->db->table($this->table);
    }

    public function get()
    {
        // $builder=$this->table('orang');
        // $builder->like('nama', '$keyword');
        // return $builder;
        return $this->findAll();
    }

    public function eventHandler()
    {
        if ($this->request->getVar('mode') == 'select') {
            $data = [
                'table' => $this->findAll()
            ];
            $table = [
                'data' => view('/layout/table', $data)
            ];
            return $table;
        } else if ($this->request->getPost('mode') == 'tambah') {
            $table = [
                'data' => view('layout/modaltambah')
            ];
            return $table;
        } else if ($this->request->getPost('mode') == 'simpan') {
            $validation = \Config\Services::validation();
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
                $this->save([
                    'img' => $this->request->getVar('gambar'),
                    'nama' => $this->request->getVar('nama'),
                    'job' => $this->request->getVar('job'),
                    'address' => $this->request->getVar('address')
                ]);

                $msg = [
                    'sukses' => 'data berhasil disimpan'
                ];
            }
            return $msg;
        } else if ($this->request->getPost('mode') == 'edit') {

            $id = $this->request->getVar('id');
            $row = $this->find($id);
            $data = [
                'id' => $row['id'],
                'nama' => $row['nama'],
                'gambar' => $row['img'],
                'job' => $row['job'],
                'address' => $row['address']
            ];

            $msg = [
                'sukses' => view('layout/modaledit', $data)
            ];
            return $msg;
        } else if ($this->request->getPost('mode') == 'update') {
            $validation = \Config\Services::validation();
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

                $this->update($id, $data);

                $msg = [
                    'sukses' => 'data berhasil diupdate'
                ];
            }
            return $msg;
        } else if ($this->request->getPost('mode') == 'delete') {
            $id = $this->request->getVar('id');
            $this->delete($id);
            $msg = [
                'sukses' => 'data berhasil dihapus'
            ];
            return $msg;
        } else if ($this->request->getPost('mode') == 'tambahbanyak') {
            $msg = [
                'data' => view('layout/formtambah')
            ];
            return $msg;
        } else if ($this->request->getPost('mode') == 'simpanbanyak') {
            $nama = $this->request->getVar('nama');
            $gambar = $this->request->getVar('gambar');
            $job = $this->request->getVar('job');
            $address = $this->request->getVar('address');

            $jmldata = count($nama);

            for ($i = 0; $i < $jmldata; $i++) {
                $this->insert([
                    'img' => $gambar[$i],
                    'nama' => $nama[$i],
                    'job' => $job[$i],
                    'address' => $address[$i],

                ]);
            }
            $msg = [
                'sukses' => "$jmldata data berhasil disimpan"
            ];
            return $msg;
        } else if ($this->request->getPost('mode') == 'hapusbanyak') {
            $id = $this->request->getVar('id');

            $jmldata = count($id);

            for ($i = 0; $i < $jmldata; $i++) {
                $this->delete($id[$i]);
            }
            $msg = [
                'sukses' => "$jmldata data berhasil dihapus"
            ];
            return $msg;
        } else if ($this->request->getPost('mode') == 'formuplod') {
            $id = $this->request->getVar('id');
            $foto = $this->find($id);
            $data = [
                'id' => $id,
                'foto' => $foto['img']
            ];
            $msg = [
                'sukses' => view('layout/modaluplod', $data)
            ];
            return $msg;
        } else if ($this->request->getPost('mode') == 'ajaxuplod') {
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

                $cek = $this->find($id);
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
                $this->update($id, $updatedata);

                $msg = [
                    'sukses' => 'berhasil diupload'
                ];
            }
            return $msg;
        } else if ($this->request->getPost('mode') == 'elete') {
            // delete
        }
    }
    public function eventDTB()
    {
        $request = Services::request();
        $datamodel = new ServerModel($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $datamodel->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];

                $edit = " <button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"edit('" . $list->id . "')\"><i class=\"fa fa-tags\"></i></button>";
                $hapus = " <button type=\"button\" class=\"btn btn-danger btn-sm\" onclick=\"hapus('" . $list->id . "')\"><i class=\"fa fa-trash\"></i></button>";
                $uplod = " <button type=\"button\" class=\"btn btn-warning btn-sm\" onclick=\"uplod('" . $list->id . "')\"><i class=\"fa fa-image\"></i></button>";

                $row[] = "<input type=\"checkbox\" name=\"id[]\" class=\"form=control centang\" value=\"$list->id\">";
                $row[] = $no;
                $row[] = "<img src=\"/img/$list->img\" width=\"100\">";
                $row[] = $list->nama;
                $row[] = $list->job;
                $row[] = $list->address;
                $row[] = $edit . "" . $hapus . "" . $uplod;
                $data[] = $row;
            }
            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $datamodel->count_all(),
                "recordsFiltered" => $datamodel->count_filtered(),
                "data" => $data
            ];
            return $output;
        }
    }
}
