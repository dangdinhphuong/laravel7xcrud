@extends("Backend.Layouts.Main")
@section("title","xoa san pham")
@section("content")
<h1>xoa san pham</h1>
<form name="category" action="{{url("/backend/category/destroy/$category->id")}}" method="post">
    @csrf
    <div class="form-group">

        <label for="category">ID sản phẩm:</label>

        <p>{{ $category->id }}</p>

    </div>
    <div class="form-group">

        <label for="product_name">Tên loại sản phẩm:</label>

        <p>{{ $category->name }}</p>

    </div>

    <button type="submit" class="btn btn-danger">Xác nhận xóa  loại sản phẩm</button>

</form>
@endsection
