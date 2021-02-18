<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableChangeAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("api_token");
        });
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name','first_name');
            $table->string('last_name')->after('name')->nullable();
            $table->string('image')->after('password')->nullable();
            $table->longText('api_token')->after('password')->nullable();
            $table->string('email')->after('image')->unique();
            $table->bigInteger('phone')->after('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
