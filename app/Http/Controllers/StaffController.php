<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Staff::orderby('id', 'ASC')->paginate();
        return view('admin.staff.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|string|min:0|max:255',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email',
            'sex' => 'required',
            'username' => 'required|unique:staff',
            'password' => 'required',
            'position' => 'required',
            'role' => 'required',
            'status' => 'required',
        ];

        $message= [
            'name.required' => 'Họ tên nhân viên không được để trống.',
            'phone.required' => 'Số điện thoại không được để trống',
            'address.required' => 'Vui lòng nhập địa chỉ của nhân viên.',
            'email.required' => 'Trường này bắt buộc nhâp',
            'sex.required' => 'Trường này bắt buộc nhâp',
            'username.required' => 'Tên tài khoản không được để trống.',
            'username.unique' => 'Tài khoản này đã tồn tại.',
            'password.required' => 'Mật khẩu không được để trống.',
            'position.required' => 'Vui lòng chọn chức vụ.',
            'role.required' => 'Trường này bắt buộc nhâp',
            'status.required' => 'Trường này bắt buộc nhâp',
        ];

        $data = $request->validate($rule, $message);
        $staff = new Staff();
        $staff->name = $data['name'];
        $staff->phone = $data['phone'];
        $staff->address = $data['address'];
        $staff->email = $data['email'];
        $staff->sex = $data['sex'];
        $staff->username = $data['username'];
        $staff->password = bcrypt($data['password']);
        $staff->position = $data['position'];
        $staff->role = $data['role'];
        $staff->status = $data['status'];
        $staff->save();
        return redirect()->route('staff.index')->with('success', 'Thêm nhân viên mới thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $rule = [
            'name' => 'required|string|min:0|max:255',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required',
            'sex' => 'required',
            'username' => 'required',
            'password' => 'required',
            'position' => 'required',
            'role' => 'required',
            'status' => 'required',
        ];

        $message= [
            'name.required' => 'Trường này bắt buộc nhâp',
            'phone.required' => 'Trường này bắt buộc nhâp',
            'address.required' => 'Trường này bắt buộc nhâp',
            'email.required' => 'Trường này bắt buộc nhâp',
            'sex.required' => 'Trường này bắt buộc nhâp',
            'username.required' => 'Trường này bắt buộc nhâp',
            'password.required' => 'Trường này bắt buộc nhâp',
            'position.required' => 'Trường này bắt buộc nhâp',
            'role.required' => 'Trường này bắt buộc nhâp',
            'status.required' => 'Trường này bắt buộc nhâp',
        ];

        $data = $request->validate($rule, $message);
        // $staff = new Staff();
        $staff->name = $data['name'];
        $staff->phone = $data['phone'];
        $staff->address = $data['address'];
        $staff->email = $data['email'];
        $staff->sex = $data['sex'];
        $staff->username = $data['username'];
        $staff->password = $data['password'];
        $staff->position = $data['position'];
        $staff->role = $data['role'];
        $staff->status = $data['status'];
        $staff->save();
        return redirect()->route('staff.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        // if ($staff->Inventory->count() == 0) {
            $staff->delete();
            return redirect()->back();
        // }
        // return redirect()->back();
    }

    public function search(Request $request) {
        $keyword = $request->input('query');
        $data = Staff::where('name', 'like', "%$keyword%")
                        ->orWhere('phone', 'like', "%$keyword%")
                        ->paginate();
        return view('admin.staff.index', compact('data', 'keyword'));
    }
}
