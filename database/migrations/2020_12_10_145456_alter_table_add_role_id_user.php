<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddRoleIdUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger("role_id")->after('email');
            $table->unsignedBigInteger('access_id')->after('role_id');

            $table->foreign("role_id")->references("id")->on("roles");
            $table->foreign("access_id")->references("id")->on("accesses");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropForeign('users_access_id_foreign');
            $table->dropColumn("role_id");
            $table->dropColumn("access_id");
        });
    }
}
