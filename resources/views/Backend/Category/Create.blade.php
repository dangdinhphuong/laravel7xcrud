@extends("Backend.Layouts.Main")
@section("title","tao moi san pham")
@section("content")
<h1>tao moi loai san pham</h1>
@if ($errors->any())
    <div class="alert-danger alert">
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </div>
@endif

<form name="category" action="{{url("/backend/category/store")}}" method="post" enctype="multipart/form-data">
@csrf
    <div class="form-group">

        <label for="name">Tên loại sản phẩm:</label>

        <input type="text" name="name" class="form-control" id="name" value="{{ old('name', "") }}">

    </div>
    <div class="form-group">

        <label for="slug">Link</label>

        <input type="text" name="slug" class="form-control" id="name" value="{{ old('slug', "") }}">

    </div>
    <div class="form-group">

        <label for="image">Ảnh:</label>


        <input type="file" name="image" class="form-control" id="image">

    </div>

    <div class="form-group">

        <label for="desc">Mô tả sản phẩm:</label>

        <textarea name="desc" class="form-control" rows="10" id="desc" >{{ old('desc', "") }}</textarea>

    </div>

    <div class="form-group">

        <label for="created_at">Thời gian tạo:</label>

        <input type="text" name="created_at" style="width: 250px" class="form-control" id="created_at"value="{{ old('created_at', "") }}">

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
