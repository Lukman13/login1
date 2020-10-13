<?php

namespace App\Models;


use CodeIgniter\Model;

class TimlineModel extends Model
{
    protected $table = 'timline';
    protected $allowedFields = ['day', 'date', 'title', 'text', 'img'];
}
