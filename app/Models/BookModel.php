<?php namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model 
{
    protected $table = 'book';
    protected $primaryKey = 'id';

    protected $allowedFields = ['use_date', 'use_type', 'cost','memo'];
}
?>