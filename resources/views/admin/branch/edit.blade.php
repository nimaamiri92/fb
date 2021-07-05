@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Custom Tabs -->
            <div class="card">
                <div class="card-body" style="overflow-x: scroll">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <form action="{{ route('admin.branch.update',$data->id) }}" method="post">
                                @method('patch')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>نام شعبه</label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   name="name"
                                                   value="{{ $data->name }}"
                                                   placeholder="نام شعبه">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label> آدرس</label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                                   name="address"
                                                   value="{{ $data->address }}"
                                                   placeholder="آدرس">
                                            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>شماره تماس</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone"
                                                   value="{{ $data->phone }}"
                                                   placeholder="شماره تماس">
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>lat</label>
                                            <input type="text" class="form-control @error('lat') is-invalid @enderror"
                                                   name="lat"
                                                   value="{{ $data->lat }}"
                                                   placeholder="طول جغرافیایی">
                                            @error('lat')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>long</label>
                                            <input type="text" class="form-control @error('long') is-invalid @enderror"
                                                   name="long"
                                                   value="{{ $data->long }}"
                                                   placeholder="عرض جغرافیایی">
                                            @error('long')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit"
                                                class=" float-left btn  btn-danger">{{ trans('main.save') }}</button>
                                        <a class="float-left btn  btn-info"
                                           href="{{route('admin.branch.index')}}">{{ trans('main.back') }}
                                        </a>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection()



