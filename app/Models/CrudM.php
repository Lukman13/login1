<?php

namespace App\Models;

use CodeIgniter\Model;

class CrudM extends Model
{
    protected $returnType     = 'object';

    protected $table = 'ajax';
    protected $allowedFields = ['img', 'nama', 'job', 'address'];
    protected $function = 'datatable';
    protected $params = [];
    protected $showQuery = ENVIRONMENT == 'development';

    /**
     * Set parameter datatable
     * @param array $params [description]
     */
    function setParams($params = [])
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Set Fungsi yang akan digunakan untuk build datatable
     * @param string $function [description]
     */
    function setFunction(string $function)
    {
        $this->function = $function;
        return $this;
    }

    function generate($request)
    {
        // Serverside
        if (isset($request['length'])) {
            $limit = $request['length'];
            $offset = $request['start'];
            $search = $request['search']['value'];
            $order = $request['order'];

            // untk pencarian data
            if ($search != null) {

                foreach ($request['columns'] as $key => $column) {
                    if ($column['searchable'] == 'true') {

                        if (isset($column['name']) && $column['name'] != NULL) {
                            $t = explode("|", $column['name']);

                            foreach ($t as $c) {
                                $like[$c] = ($search == null) ? $column['search']['value'] : $search;
                            }
                        } else {
                            $like[$column['data']] = ($search == null) ? $column['search']['value'] : $search;
                        }
                    }
                }

                $this->groupStart();

                if ($search == null) {
                    $this->like($like);
                } else {
                    $this->orLike($like);
                }

                $this->groupEnd();
            }

            $recordsFiltered = call_user_func_array(array($this, $this->function), $this->params)->countAllResults();
            // $recordsFiltered = count($recordsFiltered);
            // $query = (string)$this->getLastQuery();

            foreach ($order as $o) {
                $this->orderBy($request['columns'][$o['column']]['data'], $o['dir']);
            }

            if ($limit > 0) $this->limit($limit, $offset);

            if ($search != null) {
                $this->groupStart();

                if ($search == null) {
                    $this->like($like);
                } else {
                    $this->orLike($like);
                }

                $this->groupEnd();
            }

            $data = call_user_func_array(array($this, $this->function), $this->params)->find();
            $query = (string)$this->getLastQuery();

            // Total yang terlimit
            $recordTotal = count($data);

            $draw = ($_SERVER['REQUEST_METHOD'] == 'POST') ? $_POST['draw'] : $_GET['draw'];

            return array(
                'draw' => intval($draw),
                'recordsTotal' => $recordTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
                'query' => $query
            );
        }
        // Non Serverside
        else {
            $query = '';
            return array(
                'data' => call_user_func_array(array($this, $this->function), $this->params)->find(),
                'query' => $query
            );
        }
    }

    /**
     * Default Datatable
     * Example: 
     * 
     * $this->limit(100);
     * return $this->table($this->table);
     * 
     * @return [type] [description]
     */
    function datatable()
    {
        return $this->table($this->table);
    }
}
