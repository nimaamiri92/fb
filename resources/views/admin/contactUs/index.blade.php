@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')


    <section class="content">
        <div class="card">
        <!-- /.card-header -->
            <div class="card-body">
                <br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام و نام خانوادگی</th>
                        <th>ایمیل</th>
                        <th>شماره تماس</th>
                        <th>نمایش</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $eachData)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $eachData->name }}</td>
                            <td>{{ $eachData->email }}</td>
                            <td>{{ $eachData->phone }}</td>
                            <td width="20">
                                <a class="btn btn-md btn-warning" href="">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        {{ $data->links() }}
        <!-- /.card-body -->
        </div>
    </section>

@endsection()
