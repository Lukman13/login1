<?php

namespace App\Models;


use CodeIgniter\Model;
use Config\Services;
use CodeIgniter\HTTP\RequestInterface;

class AjaxModel extends Model
{
    protected $table = 'ajax';
    protected $allowedFields = ['img', 'nama', 'job', 'address'];
    // protected $column_order = array(null, null, 'nama', 'job', 'address', null);
    // protected $column_search = array('nama', 'job', 'address');
    // protected $order = array('nama' => 'asc');
    // protected $request;
    // protected $db;
    // protected $dt;

    // function __construct(RequestInterface $request)
    // {
    //     parent::__construct();
    //     $this->db = db_connect();
    //     $this->request = $request;
    //     //untuk join bisa diubah dibawah
    //     $this->dt = $this->db->table($this->table);
    // }

    public function get()
    {
        // $builder=$this->table('orang');
        // $builder->like('nama', '$keyword');
        // return $builder;
        return $this->findAll();
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
