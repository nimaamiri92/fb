@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <section class="content">
        <div class="card">
        <!-- /.card-header -->
            <div class="card-body" style="overflow-x: scroll">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <label>نام و نام خانوادگی:</label>
                        <span>{{ $data->name }}</span>
                    </div>
                    <div class="col-md-4">
                        <label>ایمیل:</label>
                        <span>{{ $data->email }}</span>
                    </div>
                    <div class="col-md-4">
                        <label>موبایل:</label>
                        <span>{{ $data->phone }}</span>
                    </div>
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-12">
                        <label>پیام:</label>
                        <span>{{ $data->message }}</span>
                    </div>
                </div>
            </div>
        <!-- /.card-body -->
        </div>
    </section>

@endsection()
