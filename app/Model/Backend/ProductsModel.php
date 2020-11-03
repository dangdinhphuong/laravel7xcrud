<?php

namespace App\Model\Backend;

use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    //
    protected $table = 'products';

    // khai báo khóa chính của bảng

    protected $primaryKey = 'id';
}
