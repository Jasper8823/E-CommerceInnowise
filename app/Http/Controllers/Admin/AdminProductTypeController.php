<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminProductTypeRequest;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AdminProductTypeController
{
    public function create(): View
    {
        return view('product_type.admin.create');
    }

    public function store(AdminProductTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $manufacturer = new ProductType([
            'name' => $validated['name'],
        ]);
        $manufacturer->save();

        return redirect('/admin/products')->with('success', 'Manufacturer created successfully!');
    }
}
