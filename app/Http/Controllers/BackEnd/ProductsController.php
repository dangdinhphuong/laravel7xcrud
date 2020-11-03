<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Model\Backend\ProductsModel;
use Illuminate\Http\Request;
//dung để nạp bảng DB
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
//them anh


//Để thực hiện phân trang bạn cần phải dùng 1 class DB trong laravel
class ProductsController extends Controller
{
    public function index(Request $request)
    {

//$products= ProductsModel::all();//sẽ giúp chúng ta lấy tất cả các bản ghi trong bảng products
//echo "<pre>";
//print_r($products);
//echo "</pre>";
        $sort = $request->query('product_sort', "");
//        $products = DB::table('products')->paginate(10);//lay 10 ban ghi
        $searchKeyword = $request->query('product_name', "");

        $productStatus = (int)$request->query('product_status', "");
        $category_id = (int) $request->query('category_id', 0);
        $allProductStatus = [1, 2];

        //$products = DB::table('products')->paginate(10);
        $categories = DB::table('category')->get();
        $queryORM = ProductsModel::where('product_name', "LIKE", "%" . $searchKeyword . "%");//mệnh đề where có 3 đối số bạn cần truyền vào
        if (in_array($productStatus, $allProductStatus)) {
            $queryORM = $queryORM->where('product_status', $productStatus);
            //nó chỉ có 2 đối số truyền vào là vìnếu đối số là = trong sql thì bạn không cần truyền vì mặc định nó tự hiểu là = rồi
//chỉ khi nào operator toán tử khác không phải là = thì bạn hãy truyền vào cho đủ 3 đối số
        }

        $queryORM = $queryORM->where('category_id', $category_id);
        
        if ($sort == "price_asc") {
            $queryORM->orderBy('product_price', 'asc');
        }
        if ($sort == "price_desc") {
            $queryORM->orderBy('product_price', 'desc');
        }

        if ($sort == "quantity_asc") {
            $queryORM->orderBy('product_quantity', 'asc');
        }

        if ($sort == "quantity_desc") {
            $queryORM->orderBy('product_quantity', 'desc');
        }
        $products = $queryORM->paginate(10);

        // truyền dữ liệu xuống view

        $data = [];

        $data["products"] = $products;
        $data["sort"] = $sort;

        // truyền keyword search xuống view

        $data["searchKeyword"] = $searchKeyword;
        $data["category_id"] = $category_id;
        $data["productStatus"] = $productStatus;
        $data["categories"] = $categories;
        return view("backend.products.index", $data);

    }

    public function create()
    {

        $data = [];



        $categories = DB::table('category')->get();

        $data["categories"] = $categories;



        echo "<pre>";

        print_r($categories);

        echo "</pre>";
        return view("backend.products.create",$data);

    }

    public function edit($id)
    {

        $product = ProductsModel::findOrFail($id);//để lấy ra 1 sản phẩm từ ProductsModel dựa theo id chúng ta lấy được từ router như sau :
//        echo "<pre>";
//
//        print_r($product);
//
//        echo "</pre>";
        $categories = DB::table('category')->get();


        // truyền dữ liệu xuống view

        $data = [];
        $data["product"] = $product;
        $data["categories"] = $categories;
        return view("backend.products.edit", $data);

    }

// phương thức sẽ nhập data post đi và cập

// nhật vào trong CSDL

    // phương thức sẽ nhập data post đi và cập

// nhật vào trong CSDL

    public function update(Request $request, $id) {





        echo "<br> id : " . $id;

        echo "<pre>";

        print_r($_POST);

        echo "</pre>";



        // validate dữ liệu

        $validatedData = $request->validate([

            'product_name' => 'required',

            'category_id' => 'required',

            'product_publish' => 'required',

            'product_desc' => 'required',

            'product_quantity' => 'required',

            'product_price' => 'required',

        ]);



        $product_name = $request->input('product_name', '');

        $category_id = $request->input('category_id', 0);

        $product_status = $request->input('product_status', 1);

        $product_desc = $request->input('product_desc', '');

        $product_publish = $request->input('product_publish', '');

        $product_quantity = $request->input('product_quantity', 0);

        $product_price = $request->input('product_price', 0);



        // khi $product_publish không được nhập dữ liệu

        // ta sẽ gán giá trị là thời gian hiện tại theo định dạng Y-m-d H:i:s
        if (!$product_publish) {

            $product_publish = date("Y-m-d H:i:s");

        }



        // lấy đối tượng model dựa trên biến $id

        $product = ProductsModel::findOrFail($id);



        // gán dữ liệu từ request cho các thuộc tính của biến $product

        // $product là đối tượng khởi tạo từ model ProductsModel

        $product->product_name = $product_name;

        $product->category_id = $category_id;

        $product->product_status = $product_status;

        $product->product_desc = $product_desc;

        $product->product_publish = $product_publish;

        $product->product_quantity = $product_quantity;

        $product->product_price = $product_price;

        // upload ảnh

        if ($request->hasFile('product_image')){



            // nếu có ảnh mới upload lên và

            // trong biến $product->product_image có dữ liệu

            // có nghĩa là trước đó đã có ảnh trong db

            if ($product->product_image) {

                Storage::delete($product->product_image);

            }



            $pathProductImage = $request->file('product_image')->store('public/productimages');

            $product->product_image = $pathProductImage;

        }



        // lưu sản phẩm

        $product->save();



        // chuyển hướng về trang /backend/product/edit/id

        return redirect("/backend/product/edit/$id")->with('status', 'cập nhật sản phẩm thành công !');



    }

    public function delete($id)
    {

        $product = ProductsModel::findOrFail($id);

        // truyền dữ liệu xuống view

        $data = [];

        $data["product"] = $product;
        return view("backend.products.delete", $data);

    }

    // xóa sản phẩm thật sự trong CSDL

    public function destroy($id)
    {
        echo "<br> id " . $id;
        // lấy đối tượng model dựa trên biến $id
        $product = ProductsModel::findOrFail($id);
        $product->delete();
// chuyển hướng về trang /backend/product/index
        return redirect("/backend/product/index")->with('status', 'xóa sản phẩm thành công !');
    }
    public function store(Request $request)
    {
        // validate dữ liệu

        $validatedData = $request->validate([

            'product_name' => 'required',

            'product_image' => 'required',

            'product_publish' => 'required',

            'product_desc' => 'required',

            'product_quantity' => 'required',

            'product_price' => 'required',
            'product_status' => 'required',
            'category_id' => 'required',
        ]);

        $product_name = $request->input('product_name', '');

        $category_id = $request->input('category_id', 0);

        $product_status = $request->input('product_status', 1);

        $product_desc = $request->input('product_desc', '');

        $product_publish = $request->input('product_publish', '');

        $product_quantity = $request->input('product_quantity', 0);

        $product_price = $request->input('product_price', 0);

        $pathProductImage = $request->file('product_image')->store('public/productimages');

        $product = new ProductsModel();

        // khi $product_publish không được nhập dữ liệu

        // ta sẽ gán giá trị là thời gian hiện tại theo định dạng Y-m-d H:i:s

        if (!$product_publish) {

            $product_publish = date("Y-m-d H:i:s");

        }

        // gán dữ liệu từ request cho các thuộc tính của biến $product

        // $product là đối tượng khởi tạo từ model ProductsModel

        $product->product_name = $product_name;

        $product->category_id = $category_id;

        $product->product_status = $product_status;

        $product->product_desc = $product_desc;

        $product->product_publish = $product_publish;

        $product->product_quantity = $product_quantity;

        $product->product_price = $product_price;

        // gắn tạm product_image là rỗng "" vì ta chưa upload ảnh
        $product->product_image = $pathProductImage;

        // lưu sản phẩm
        $product->save();
        // chuyển hướng về trang /backend/product/index

        return redirect("/backend/product/index")->with('status', 'thêm sản phẩm thành công !');

    }
}
