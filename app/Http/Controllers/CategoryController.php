<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Category::orderBy('id', 'ASC')->paginate(); //SELECT * FROM CATEGORY
        return view('admin.category.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) //[]
    {
        $rule = [
            'name' => 'required|unique',
            'status' => 'required'
        ];
        $message = [
            'name.required' => 'Trường này bắt buộc nhâp',
            'name.unique' => 'Trường này phải là duy nhất'
        ];
        $data = $request->validate($rule, $message);
        $categories = new Category();
        $categories->category_name = $data['name']; //$_POST['']
        $categories->status = $data['status'];
        $categories->save();
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
