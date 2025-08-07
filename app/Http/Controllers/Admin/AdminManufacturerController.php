<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminManufacturerRequest;
use App\Repositories\Contracts\ManufacturerRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AdminManufacturerController
{
    public function __construct(
        private ManufacturerRepositoryInterface $manufacturerRepository
    ) {}

    public function create(): View
    {
        return view('manufacturer.admin.create');
    }

    public function store(AdminManufacturerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->manufacturerRepository->create([
            'name' => $validated['name'],
        ]);

        return redirect('/admin/products')->with('success', 'Manufacturer created successfully!');
    }
}
