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

    public function index()
    {
        $this->setPageTitle('مدیریت شعب');
        $this->setSideBar('branch');
        $data = Branch::query()->paginate(15);
        return view('admin.branch.index',compact('data'));
    }

    public function edit(Branch $branch)
    {
        $this->setPageTitle('ویرایش شعب');
        $this->setSideBar('branch');
        $data = $branch;
        return view('admin.branch.edit',compact('data'));
    }

    public function update(Branch $branch,BranchRequest $request)
    {
        $this->setPageTitle('ویرایش شعب');
        $this->setSideBar('branch');
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);
        Branch::query()->where('id',$branch->id)->update($data);
        return redirect()->route('admin.branch.index')
            ->with('message', 'شعبه با موفقیت ویرایش شد');
    }
    public function delete(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('admin.branch.index')->with('message', 'شعبه با موفقیت حذف شد');
    }
}
