<?php

namespace App\Http\Controllers\BackEnd;
use App\Http\Controllers\Controller;
use App\Model\Backend\CategoryModel;
use Illuminate\Http\Request;
//dung để nạp bảng DB
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
//them anh

class CategoryController extends Controller
{
    public function index(Request $request)
    {

//$products= ProductsModel::all();//sẽ giúp chúng ta lấy tất cả các bản ghi trong bảng products
//echo "<pre>";
//print_r($products);
//echo "</pre>";
        $sort = $request->query('sort', "");
//        $products = DB::table('products')->paginate(10);//lay 10 ban ghi
        $searchKeyword = $request->query('name', "");

        //$products = DB::table('products')->paginate(10);

        $queryORM = CategoryModel::where('name', "LIKE", "%" . $searchKeyword . "%");//mệnh đề where có 3 đối số bạn cần truyền vào

        if ($sort == "date_asc") {
            $queryORM->orderBy('created_at', 'asc');
        }
        if ($sort == "date_desc") {
            $queryORM->orderBy('created_at', 'desc');
        }
        if ($sort == "updtae_desc") {
            $queryORM->orderBy('updated_at', 'desc');
        }
        $category = $queryORM->paginate(10);

        // truyền dữ liệu xuống view

        $data = [];

        $data["category"] = $category;
        $data["sort"] = $sort;

        // truyền keyword search xuống view

        $data["searchKeyword"] = $searchKeyword;
        return view("backend.category.index", $data);

    }

    public function create()
    {
        return view("backend.category.create");
    }

    public function edit($id)
    {

        $category = CategoryModel::findOrFail($id);//để lấy ra 1 sản phẩm từ ProductsModel dựa theo id chúng ta lấy được từ router như sau :
        // truyền dữ liệu xuống view

        $data = [];
        $data["category"] = $category;
        return view("backend.category.edit", $data);

    }

// phương thức sẽ nhập data post đi và cập

// nhật vào trong CSDL

    public function update(Request $request, $id)
    {
        // validate dữ liệu

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'desc' => 'required',
            'created_at' => 'required',
            'updated_at' => 'required',
        ]);

        $name = $request->input('name', '');
        $desc = $request->input('desc', '');
        $slug = $request->input('slug', '');
        $created_at = $request->input('created_at', '');
        $updated_at = $request->input('updated_at', '');
        // khi $product_publish không được nhập dữ liệu

        // ta sẽ gán giá trị là thời gian hiện tại theo định dạng Y-m-d H:i:s
        $d1 = strtotime($created_at);
        $d2 = strtotime($updated_at);// chuyển kiểu timelap sang kiểu human vd: 2020-12-20 15:21:03 ==>59748487
        if ($d2<$d1) {
            return redirect("/backend/category/edit/$id")->with('status', 'Thời gian cập nhật lớn hơn thời gian tạo !');
        }
        if (!$created_at) {
            $created_at = date("Y-m-d H:i:s");
        }
        if (!$updated_at) {
            $updated_at = date("Y-m-d H:i:s");
        }
        // lấy đối tượng model dựa trên biến $id

        $category = CategoryModel::findOrFail($id);

        // gán dữ liệu từ request cho các thuộc tính của biến $product

        // $product là đối tượng khởi tạo từ model ProductsModel

        $category->name = $name;
        $category->slug = $slug;
        $category->desc = $desc;
        $category->created_at = $created_at;
        $category->updated_at = $updated_at;
        // gắn tạm product_image là rỗng "" vì ta chưa upload ảnh
//        $category->image = "";

// upload ảnh
        if ($request->hasFile('image')) {

            // nếu có ảnh mới upload lên và

            // trong biến $product->product_image có dữ liệu

            // có nghĩa là trước đó đã có ảnh trong db

            if ($category->image) {

                Storage::delete($category->image); //để xóa file
            }
            $pathcateImage = $request->file('image')->store('public/categoryimages');

            $category->image = $pathcateImage;
        }
        // lưu sản phẩm
        $category->save();
        // chuyển hướng về trang /backend/product/edit/id

        return redirect("/backend/category/edit/$id")->with('status', 'cập nhật sản phẩm thành công !');
    }

    public function delete($id)
    {

        $category = CategoryModel::findOrFail($id);

        // truyền dữ liệu xuống view

        $data = [];

        $data["category"] = $category;
        return view("backend.category.delete", $data);

    }




// xóa danh mục thật sự trong CSDL
    public function destroy($id) {
        $countProducts = DB::table('products')->count();
        if ($countProducts > 0) {
            return redirect("/backend/category/index")->with('status', 'xóa tất cả các sản phẩm thuộc danh mục này trước khi xóa danh mục !');
        }
        // lấy đối tượng model dựa trên biến $id
        $category = CategoryModel::findOrFail($id);
        $category->delete();
        // chuyển hướng về trang /backend/category/index
        return redirect("/backend/category/index")->with('status', 'xóa danh mục thành công !');
    }



    public function store(Request $request)
    {
        // validate dữ liệu

        $validatedData = $request->validate([

            'name' => 'required',

            'slug' => 'required',

            'image' => 'required',

            'desc' => 'required',

            'created_at' => 'required',
        ]);
        $name = $request->input('name', '');

        $image = $request->input('image', '');

        $desc = $request->input('desc', '');

        $slug = $request->input('slug', '');
        $created_at = $request->input('created_at', '');

        $pathcateImage = $request->file('image')->store('public/categoryimages');

        $cate = new CategoryModel();

        // khi $product_publish không được nhập dữ liệu

        // ta sẽ gán giá trị là thời gian hiện tại theo định dạng Y-m-d H:i:s

        if (!$created_at) {

            $created_at = date("Y-m-d H:i:s");

        }

        // gán dữ liệu từ request cho các thuộc tính của biến $product

        // $cate là đối tượng khởi tạo từ model ProductsModel

        $cate->name = $name;

        $cate->slug = $slug;

        $cate->desc = $desc;

        $cate->created_at = $created_at;

        // gắn tạm cate_image là rỗng "" vì ta chưa upload ảnh
        $cate->image = $pathcateImage;

        // lưu sản phẩm
        $cate->save();
        // chuyển hướng về trang /backend/product/index

        return redirect("/backend/category/index")->with('status', 'thêm sản phẩm thành công !');

    }
}
