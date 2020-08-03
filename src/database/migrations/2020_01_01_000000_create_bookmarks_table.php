<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarksTable extends Migration
{
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bookmarker_id')->unsigned();
            $table->bigInteger('bookmarkable_id');
            $table->string('bookmarkable_type');
            $table->timestamps();
        });
    }
}
