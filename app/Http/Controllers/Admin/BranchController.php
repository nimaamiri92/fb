<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Branch\BranchRequest;
use App\Models\Branch;

class BranchController extends BaseController
{
    public function create()
    {
        $this->setPageTitle('مدیریت شعب');
        $this->setSideBar('branch');
        return view('admin.branch.create');
    }

    public function store(BranchRequest $request)
    {
        $this->setPageTitle('مدیریت شعب');
        $this->setSideBar('branch');
        $data = $request->validated();
        Branch::query()->create($data);
        return redirect()->route('admin.branch.index')->with('message', 'شعبه با موفقیت اضافه شد');

    }
}
