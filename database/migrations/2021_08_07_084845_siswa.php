<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Siswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn',20)->unique();
            $table->string('nis',20)->unique();
            $table->string('nama',50);
            $table->string('kode_kelas', 20);
            $table->string('nomor_hp',20);
            $table->text('alamat');
            $table->string('email',50)->unique();
            $table->string('username',50)->unique();
            $table->string('privilege',20);
            $table->string('password',60);
            $table->timestamps();
            $table->foreign('kode_kelas')->references('kode_kelas')->on('kelas')
            ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswa', function($table) {
            $table->dropForeign('siswa_id_kelas_foreign');
        });
        Schema::dropIfExists('siswa');
    }
}
