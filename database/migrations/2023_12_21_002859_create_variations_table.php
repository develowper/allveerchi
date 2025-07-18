<?php

use App\Http\Helpers\Variable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('name_en', 200)->nullable();
            $table->string('tags', 200)->nullable();
            $table->enum('status', array_column(Variable::VARIATION_STATUSES, 'name'))->default(array_column(Variable::VARIATION_STATUSES, 'name')[0]);
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action');
            $table->unsignedInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

            $table->enum('grade', \App\Http\Helpers\Variable::GRADES)->nullable();
            $table->unsignedInteger('pack_id')->nullable();
            $table->foreign('pack_id')->references('id')->on('packs')->onDelete('no action');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('no action');
            $table->unsignedBigInteger('repo_id')->nullable();
            $table->foreign('repo_id')->references('id')->on('repositories')->onDelete('no action');
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('no action');
//            $table->unsignedInteger('in_repo')->default(0);
//            $table->unsignedInteger('in_shop')->default(0);
            $table->unsignedDecimal('in_repo', 8, 3)->default(0); //weight|count
            $table->unsignedDecimal('in_shop', 8, 3)->default(0); //weight|count

            $table->unsignedInteger('min_allowed')->default(0);
            $table->unsignedDecimal('weight', 8, 3)->nullable(); //kg
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('auction_price')->default(0);
            $table->boolean('in_auction')->default(true);
//            $table->boolean('is_private')->default(false); //just sell to agencies
            $table->enum('agency_level', array_column(Variable::AGENCY_TYPES, 'level'))->nullable();
            $table->enum('unit', Variable::PRODUCT_UNITS)->default(Variable::PRODUCT_UNITS[0]);
            $table->unsignedBigInteger('sell_count')->default(0);
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->json('categories')->nullable();
            $table->json('prices')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variations');
    }
};
