@extends("Backend.Layouts.Main")
@section("title","san pham")
@section("content")
    <h1>Danh sach loại san pham</h1>
    <div style="padding: 10px; border: 1px solid #4e73df : margin-bottom: 10px">


{{--                hàm htmlspecialchars() này là 1 hàm bảo mật nếu chúng ta truyền vào 1 chuỗi có chứa thẻ html thì nó sẽ biến các thẻ html thành các entity--}}
{{--                ví dụ : chuỗi có thẻ <script>hacker</script>nó sẽ chuyển thành&lt;hacker&gt;--}}
        <form name="search_cate" method="get" action="{{ htmlspecialchars($_SERVER["REQUEST_URI"]) }}"
              class="form-inline">
            @csrf
            <input name="name" value="{{$searchKeyword}}" class="form-control"
                   style="width: 350px; margin-right: 20px" placeholder="Nhập tên sản phẩm bạn muốn tìm kiếm ..."
                   autocomplete="off">
            <select name="sort" class="form-control" style="width: 150px; margin-right: 20px">

                <option value="">Sắp xếp</option>

                <option value="date_desc" {{ $sort == "date_desc" ? " selected" : "" }}>Mới nhất</option>

                <option value="date_asc"{{ $sort == "date_asc" ? " selected" : "" }}>Cũ nhất</option>

                <option value="updtae_desc" {{ $sort == "updtae_desc" ? " selected" : "" }}> Cập nhật mới nhất</option>
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


    {{$category->links()}} {{--       hiển thị thanh phân trang ở view--}}
    <div style="padding: 20px">
        @if(session('status'))
            <div class="alert alert-danger">
                {{session('status')}}
            </div>
        @endif
        <button style="background: #2c9faf; color: black">
            <a style="color: black" href="{{url("/backend/category/create")}}">Them loại san pham</a>
        </button>
    </div>
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Avatar</th>
            <th>Name_category</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>


        @if(isset($category) && !empty($category))

            @foreach ($category as $cate)
                <tr>

                    <td>{{ $cate->id }}</td>

                    <td>

                        @if (isset($cate->image))

                            <?php
                            $cate->image = str_replace("public/", "", $cate->image);

                            ?>
                            <div>
                                <img src="{{ asset("storage/$cate->image") }}"
                                     style="width: 200px; height: auto"/>
                            </div>
                        @endif
                    </td>
                    <td>{{ $cate->name }}

                    </td>

                    <td>

                        <a href="{{ url("/backend/category/edit/$cate->id") }}" class="btn btn-warning">Sửa sản
                            phẩm</a>

                        <a href="{{ url("/backend/category/delete/$cate->id") }}" class="btn btn-danger">Xóa sản
                            phẩm</a>

                    </td>

                </tr>

            @endforeach

        @else

            Chưa có bản ghi nào trong bảng này

        @endif


        </tbody>

    </table>
    {{$category->links()}}
{{--    hiển thị thanh phân trang ở view--}}
@endsection
@section('appendjs')
    {{--Đoạn code phân trang mà vẫn hỗ trợ search và filter sau này như sau :--}}
    <script type="text/javascript">
        $(document).ready(function () {

            $("#clear-search").on("click", function (e) {
                e.preventDefault();

                $("select[name='sort']").val('');
                $("input[name='name']").val('');
                $("form[name='search']").trigger("submit");

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
