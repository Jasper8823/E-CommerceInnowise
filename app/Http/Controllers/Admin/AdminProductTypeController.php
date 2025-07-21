<?php

namespace App\Http\Controllers\Admin;
use App\Models\ProductType;
use Illuminate\Http\Request;

class AdminProductTypeController
{
    public function create()
    {
        return view('product_type.admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_types,name',
        ]);

        $product_type = new ProductType([
            'name' => $validated['name']
        ]);

        $product_type->save();

        return redirect('/admin/products')->with('success', 'Product type created successfully!');
    }
}
