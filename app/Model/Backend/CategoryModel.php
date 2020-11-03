<?php

namespace App\Model\Backend;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    //
    protected $table = 'category';

    // khai báo khóa chính của bảng

    protected $primaryKey = 'id';
}
