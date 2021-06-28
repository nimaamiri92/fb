@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Custom Tabs -->
            <div class="card">
                <div class="card-header d-flex p-0">
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link "
                                                href="#tab_2" data-toggle="tab">راهنمای عکس محصول</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body" style="overflow-x: scroll">
                    <div class="tab-content">

                        <div class="tab-pane active "id="tab_2">

                            <div class="form-group">
                                <form class="form-group" method="post"
                                      action="{{ route('admin.products.save_upload_cloth_image_size_show') }}"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <div class="card-footer">
                                        <div class="float-left">
                                            <button type="submit" class="btn btn-primary"> ارسال</button>
                                        </div>
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> فایل ضمیمه
                                            <input type="file" name="image">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if($image)
                                <h4 class="mb-2 mt-5">عکس</h4>
                                <div class="row">
                                        <div class="col-md-3">
                                            <div class="card card-warning">

                                                <!-- /.card-header -->
                                                <div class="card-body" style="overflow-x: scroll">
                                                    <img class="edit-product-image"
                                                         src="{{$image->path}}">
                                                </div>
                                                <!-- /.card-body -->
                                            </div>
                                            <!-- /.card -->
                                        </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()





