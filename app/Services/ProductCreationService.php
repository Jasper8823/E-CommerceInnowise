<?php

namespace App\Services;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductCreationService
{
    public function validateRequest(Request $request): ?string
    {
        $hasProductTypeId = $request->filled('product_type_id');
        $hasNewProductType = $request->filled('new_product_type');


        if (!$hasProductTypeId && !$hasNewProductType) {
            return 'Either select an existing product type or enter a new one.';
        }

        if ($hasProductTypeId) {
            $validator = Validator::make($request->all(), [
                'product_type_id' => 'exists:product_types,id'
            ]);
            if ($validator->fails()) {
                return 'Selected product type does not exist.';
            }
        }

        if ($hasNewProductType) {
            $validator = Validator::make($request->all(), [
                'new_product_type' => 'string|max:255|min:2'
            ]);
            if ($validator->fails()) {
                return 'New product type is invalid.';
            }
        }

        $hasCompanyId = $request->filled('company_id');
        $hasNewCompany = $request->filled('new_company');

        if (!$hasCompanyId && !$hasNewCompany) {
            return 'Either select an existing company or enter a new one.';
        }

        if ($hasCompanyId) {
            $validator = Validator::make($request->all(), [
                'company_id' => 'exists:companies,id'
            ]);
            if ($validator->fails()) {
                return 'Selected company does not exist.';
            }
        }

        if ($hasNewCompany) {
            $validator = Validator::make($request->all(), [
                'new_company' => 'string|max:255'
            ]);
            if ($validator->fails()) {
                return 'New company is invalid.';
            }
        }

        return null;
    }



    public function createConnectedObjects(Request $request)
    {
        if ($request->filled('new_company')) {
            $company = Company::firstOrCreate(['name' => $request->input('new_company')]);
            $request->merge(['company_id' => $company->id]);
        }else{
            $company = Company::find($request->input('company_id'));
        }

        if ($request->filled('new_product_type')) {
            $productType = ProductType::firstOrCreate(['name' => $request->input('new_product_type')]);
            $request->merge(['product_type_id' => $productType->id]);
        }else{
            $productType = ProductType::find($request->input('product_type_id'));
        }

        return array($company, $productType);
    }

    public function attachServices(Product $product, Request $request)
    {
        if ($request->filled('custom_services')) {
            $customServices = $request->input('custom_services');
            foreach ($customServices as $customService) {
                $service = new Service();
                $service->name = $customService['name'];
                $service->save();

                $product->services()->attach($service->id, [
                    'price' => $customService['price'],
                    'days_needed' => $customService['daysNeeded'],
                ]);
            }
        }
        $services = $request->input('services');
        foreach ($services as $serviceName => $serviceData) {
            if (!isset($serviceData['enabled'])) {
                continue;
            }

            $service = Service::where('name', $serviceName)->first();
            if ($service) {
                $product->services()->attach($service->id, [
                    'price' => $serviceData['price'],
                    'days_needed' => $serviceData['daysNeeded'],
                ]);
            }else{
                $service = new Service();
                $service->name = $serviceName;
                $service->save();

                $product->services()->attach($service->id, [
                    'price' => $serviceData['price'],
                    'days_needed' => $serviceData['daysNeeded'],
                ]);
            }
        }
    }
}
