<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
        });

        DB::table('tags')->insert(array('name'=>'free'));
        DB::table('tags')->insert(array('name'=>'food'));
        DB::table('tags')->insert(array('name'=>'outdoors'));
        DB::table('tags')->insert(array('name'=>'exercise'));
        DB::table('tags')->insert(array('name'=>'indoors'));
        DB::table('tags')->insert(array('name'=>'creative'));
        DB::table('tags')->insert(array('name'=>'party'));
        DB::table('tags')->insert(array('name'=>'educational'));
        DB::table('tags')->insert(array('name'=>'music'));
        DB::table('tags')->insert(array('name'=>'lecture'));
        DB::table('tags')->insert(array('name'=>'seminar'));
        DB::table('tags')->insert(array('name'=>'art'));
        DB::table('tags')->insert(array('name'=>'drinking'));
        DB::table('tags')->insert(array('name'=>'social'));
        DB::table('tags')->insert(array('name'=>'sports'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            Schema::dropIfExists('tags');
        });
    }
}
