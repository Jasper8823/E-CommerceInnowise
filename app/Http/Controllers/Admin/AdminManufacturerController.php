<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminManufacturerRequest;
use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AdminManufacturerController
{
    public function create(): View
    {
        return view('manufacturer.admin.create');
    }

    public function store(AdminManufacturerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $manufacturer = new Manufacturer([
            'name' => $validated['name'],
        ]);

        $manufacturer->save();

        return redirect('/admin/products')->with('success', 'Manufacturer created successfully!');
    }
}
