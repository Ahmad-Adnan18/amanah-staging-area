<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToPelanggaransTable extends Migration
{
    public function up()
    {
        Schema::table('pelanggarans', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('pelanggarans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
