<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $model = Product::class;
    public function up(): void
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('totalIncome');
            $table->timestamps();
        });


        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('uuId')->unique();
            $table->foreignId('product_type_id')->constrained('product_types');
            $table->string('name');
            $table->integer('price');
            $table->timestamp('releaseDate');
            $table->foreignId('company_id')->constrained('companies');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('product_service', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->integer('daysNeeded');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('authorised_users', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('isAdmin')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('product_service');
        Schema::dropIfExists('services');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_types');
    }
};
