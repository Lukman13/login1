<?php

namespace App\Filters;

use CodeIgniter\Database\Query;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class Auth implements FilterInterface
{
    protected $filterModel;
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('role_id')) {
            return redirect()->to('/home');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
