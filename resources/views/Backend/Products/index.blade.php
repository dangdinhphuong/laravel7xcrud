@extends("Backend.Layouts.Main")
@section("title","san pham")
@section("content")
    <h1>Danh sach san pham</h1>
    <div style="padding: 10px; border: 1px solid #4e73df : margin-bottom: 10px">


        {{--        hàm htmlspecialchars() này là 1 hàm bảo mật nếu chúng ta truyền vào 1 chuỗi có chứa thẻ html thì nó sẽ biến các thẻ html thành các entity--}}
        {{--        ví dụ : chuỗi có thẻ <script>hacker</script>nó sẽ chuyển thành&lt;hacker&gt;--}}
        <form name="search_product" method="get" action="{{ htmlspecialchars($_SERVER["REQUEST_URI"]) }}"
              class="form-inline">
            @csrf
            <input name="product_name" value="{{$searchKeyword}}" class="form-control"
                   style="width: 350px; margin-right: 20px" placeholder="Nhập tên sản phẩm bạn muốn tìm kiếm ..."
                   autocomplete="off">
            <select name="product_status" class="form-control" style="width: 150px; margin-right: 20px">

                <option value="">Lọc theo trạng thái</option>

                <option value="1" {{ $productStatus == 1 ? " selected" : "" }}>Đang mở bán</option>

                <option value="2" {{ $productStatus == 2 ? " selected" : "" }}>Ngừng bán</option>

            </select>

                <select name="category_id"class="form-control"style=" margin-right: 20px" >

                    <option value="">-- Chọn danh mục --</option>

                    @foreach ($categories as $category)

                        <option value="{{ $category->id }}" {{ $category->id == $category_id ? " selected" : "" }}>{{ $category->name }}</option>

                    @endforeach

                </select>

            <select name="product_sort" class="form-control" style="width: 150px; margin-right: 20px" >

                <option value="">Sắp xếp</option>

                <option value="price_asc" id="price_asc" {{ $sort == "price_asc" ? " selected" : "" }}>Giá tăng dần</option>

                <option value="price_desc"{{ $sort == "price_desc" ? " selected" : "" }}>Giá giảm dần</option>

                <option value="quantity_asc"{{ $sort == "quantity_asc" ? " selected" : "" }}>Tồn kho tăng dần</option>

                <option value="quantity_desc"{{ $sort == "quantity_desc" ? " selected" : "" }}>Tồn kho giảm dần</option>

            </select>
            <div style="padding: 10px 0">
                <input type="submit" name="search" class="btn btn-success" value="Lọc kết quả">
            </div>
            <div style="padding: 10px 0">
                <a href="#" id="clear-search" class="btn btn-warning">Clear filter</a>
            </div>
            <input type="hidden" name="page" value="1">
        </form>
    </div>


    {{$products->links()}}   {{-- hiển thị thanh phân trang ở view--}}
    <div style="padding: 20px">
        @if(session('status'))
            <div class="alert alert-danger">
                {{session('status')}}
            </div>
        @endif
        <button style="background: #2c9faf; color: black">
            <a style="color: black" href="{{url("/backend/product/create")}}">Them san pham</a>
        </button>
    </div>
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Avatar</th>
            <th>Name_product</th>
            <th>Price_product</th>
            <th>Count</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>


        @if(isset($products) && !empty($products))

            @foreach ($products as $product)

                <tr>

                    <td>{{ $product->id }}</td>

                    <td>

                        @if ($product->product_image)

                            <?php
                            $product->product_image = str_replace("public/", "", $product->product_image);

                            ?>
                            <div>
                                <img src="{{ asset("storage/$product->product_image") }}"
                                     style="width: 200px; height: auto"/>

                            </div>
                        @endif

                    </td>

                    <td>{{ $product->product_name }}
                        @if($product->product_status == 1)
                            <p><span class="bg-success text-white">Đang mở bán</span></p>
                        @endif
                        @if($product->product_status == 2)
                            <p><span class="bg-danger text-white">Ngừng bán</span></p>
                        @endif
                    </td>

                    <td>{{ $product->product_price }} USD</td>

                    <td>{{ $product->product_quantity }}</td>

                    <td>

                        <a href="{{ url("/backend/product/edit/$product->id") }}" class="btn btn-warning">Sửa sản
                            phẩm</a>

                        <a href="{{ url("/backend/product/delete/$product->id") }}" class="btn btn-danger">Xóa sản
                            phẩm</a>

                    </td>

                </tr>

            @endforeach

        @else

            Chưa có bản ghi nào trong bảng này

        @endif


        </tbody>

    </table>
    {{$products->links()}}   {{-- hiển thị thanh phân trang ở view--}}
@endsection
@section('appendjs')
    {{--Đoạn code phân trang mà vẫn hỗ trợ search và filter sau này như sau :--}}
    <script type="text/javascript">
        $(document).ready(function () {

            $("#clear-search").on("click", function (e) {
                e.preventDefault();
                $("select[name='product_status']").val('');
                $("select[name='product_sort']").val('');
                $("select[name='category_id']").val('');
                $("input[name='product_name']").val('');
                $("form[name='search_product']").trigger("submit");
            });
            $("a.page-link").on("click", function (e) {

                 e.preventDefault(); //Ngăn một liên kết mở URL:
                var rel = $(this).attr("rel");//Trả về giá trị của một thuộc tính: rel->"next or prev"
                if (rel == "next") {

                    var page = $("body").find(".page-item.active > .page-link").eq(0).text();

                    console.log(" : " + page);

                    page = parseInt(page);

                    page += 1;

                } else if (rel == "prev") {

                    var page = $("body").find(".page-item.active > .page-link").eq(0).text();

                    console.log(page);
                    page = parseInt(page);
                    page -= 1;

                } else {

                    var page = $(this).text();

                }
                console.log(page);
                page = parseInt(page);
                $("input[name='page']").val(page);
                $("form[name='search_product']").trigger("submit");

            });

        });

    </script>
@endsection
