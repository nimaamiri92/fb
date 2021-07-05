@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <section class="content">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body" style="overflow-x: scroll">
                <br>
                <table id="example1" class="table table-hover" style="overflow-x: scroll">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام شعبه</th>
                        <th>آدرس</th>
                        <th>شماره تماس</th>
                        <th>lat</th>
                        <th>long</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $key => $eachData)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $eachData->name }}</td>
                            <td>{{ $eachData->address }}</td>
                            <td>{{ $eachData->phone }}</td>
                            <td>{{ $eachData->lat }}</td>
                            <td>{{ $eachData->long }}</td>
                            <td width="20">
                                    <a class="btn btn-md btn-warning"
                                       href="{{ route('admin.branch.edit',$eachData->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                            </td>
                            <td width="20">
                                <form action="{{ route('admin.branch.delete',$eachData->id) }}" method="post">
                                    @csrf
                                    <input type="hidden" name="_method" value="delete">
                                    <button onclick="return confirm('آیا اطمینان دارید؟')"
                                            class="btn btn-md btn-danger" type="submit"><i class="fa fa-trash"></i>
                                    </button>
                                </form>
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
