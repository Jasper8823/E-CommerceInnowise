<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminProductTypeRequest;
use App\Repositories\Contracts\ProductTypeRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class AdminProductTypeController
{
    public function __construct(
        private ProductTypeRepositoryInterface $productTypeRepository
    ) {}

    public function create(): View
    {
        return view('product_type.admin.create');
    }

    public function store(AdminProductTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->productTypeRepository->create([
            'name' => $validated['name'],
        ]);

        return redirect('/admin/products')->with('success', 'Product type created successfully!');
    }
}
