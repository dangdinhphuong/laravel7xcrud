@extends("Backend.Layouts.Main")
@section("title","sua san pham")
@section("content")
<h1>sua loại sản phẩm </h1><a href="{{url("/backend/category/index")}}">Black</a>
{{--thanh công--}}
@if(session('status'))
    <div class="alert alert-danger">
        {{session('status')}}
    </div>
@endif
{{--báo lỗi--}}
@if ($errors->any())
    <div class="alert-danger alert">
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </div>
@endif
<form name="category" action="{{url("/backend/category/update/$category->id")}}" method="post"
      enctype="multipart/form-data">
    @csrf {{--  chưa thêm token : error 419--}}
    <div class="form-group">

        <label for="name">Tên sản phẩm:</label>

        <input type="text" name="name" class="form-control" id="name"
               value="{{ $category->name }}">

    </div>
    <div class="form-group">

        <label for="slug">Link</label>

        <input type="text" name="slug" class="form-control" id="slug" value="{{ $category->slug }}">

    </div>
    <div class="form-group">

        <label for="image">Ảnh:</label>
        <input type="file" name="image" class="form-control" id="image">
        {{--        kiểm tra ảnh có rỗng không--}}
        @if($category->image )
            {{--        str_replace("chuỗi con cần tìm kiếm", "chuỗi sẽ thay thế", "chuỗi mẹ chứa chuỗi con cần thay thế");--}}

            {{--        để thay thế chuỗi sau đó gán lại chính cho biến $product->product_image--}}
            <?php
            $category->image = str_replace("public/", "", $category->image);
            ?>

            <div>

                <img src="{{ asset("storage/$category->image") }}" style="width: 200px; height: auto"/>

            </div>
        @endif

    </div>

    <div class="form-group">

        <label for="desc">Mô tả sản phẩm:</label>

        <textarea name="desc" class="form-control" rows="10" id="desc" >{{ $category->desc }}</textarea>

    </div>

    <div class="form-group">

        <label for="created_at">Thời gian tạo:</label>

        <input type="text" name="created_at" style="width: 250px" class="form-control" id="created_at"value="{{ $category->created_at }}">
        <label for="created_at">Thời gian tạo:</label>
        <?php
        $a=$category->updated_at;
        if($a==null){
            $a = "";
        };

         ?>
        <input type="text" name="updated_at" style="width: 250px" class="form-control" id="updated_at"value="{{ $a }}">
    </div>
    <button type="submit" class="btn btn-info">Thêm loại sản phẩm</button>

</form>
@endsection
@section('appendjs')

    {{--    time--}}
    <link rel="stylesheet" href="{{ asset("/be-asset/js/bootstrap-datetimepicker.min.css") }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>
    <script src="{{asset("/be-asset/js/bootstrap-datetimepicker.min.js")}}"></script>
    <script type="text/javascript">

        $(function () {

            $('#created_at').datetimepicker({

                icons: {
                    time: 'far fa-clock',

                    date: 'far fa-calendar',

                    up: 'fas fa-arrow-up',

                    down: 'fas fa-arrow-down',

                    previous: 'fas fa-chevron-left',

                    next: 'fas fa-chevron-right',

                    today: 'fas fa-calendar-check',

                    clear: 'far fa-trash-alt',

                    close: 'far fa-times-circle'
                },
                format:"YYYY-MM-DD HH:mm:ss"

            });
            $('#updated_at').datetimepicker({

                icons: {
                    time: 'far fa-clock',

                    date: 'far fa-calendar',

                    up: 'fas fa-arrow-up',

                    down: 'fas fa-arrow-down',

                    previous: 'fas fa-chevron-left',

                    next: 'fas fa-chevron-right',

                    today: 'fas fa-calendar-check',

                    clear: 'far fa-trash-alt',

                    close: 'far fa-times-circle'
                },
                format:"YYYY-MM-DD HH:mm:ss"

            });
        });

    </script>
    {{-- end time--}}


    <script src="{{ asset("/be-asset/js/tinymce/tinymce.min.js") }}"></script>

    <script type="text/javascript">
        tinymce.init({
            selector: '#desc'
        });

    </script>
@endsection
