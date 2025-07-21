<?php

namespace App\Http\Controllers\Admin;

use App\Models\Manufacturer;
use Illuminate\Http\Request;

class AdminManufacturerController
{
    public function create()
    {
        return view('manufacturer.admin.create');
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:manufacturers,name',
        ]);

        $manufacturer = new Manufacturer([
            'name' => $validated['name']
        ]);

        $manufacturer->save();

        return redirect('/admin/products')->with('success', 'Manufacturer created successfully!');
    }
}
