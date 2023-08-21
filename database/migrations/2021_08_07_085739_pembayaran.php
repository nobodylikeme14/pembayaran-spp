<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pembayaran',25)->unique();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_petugas');
            $table->date('tanggal_bayar');
            $table->string('bulan_dibayar',10);
            $table->unsignedBigInteger('id_spp');
            $table->double('jumlah_bayar');
            $table->timestamps();
            $table->foreign('id_siswa')->references('id')->on('siswa')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_petugas')->references('id')
            ->on('petugas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_spp')->references('id')->on('spp')
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
        Schema::table('pembayaran', function($table) {
            $table->dropForeign('pembayaran_nisn_foreign');
            $table->dropForeign('pembayaran_id_spp_foreign');
            $table->dropForeign('pembayaran_id_petugas_foreign');
        });
        Schema::dropIfExists('pembayaran');
    }
}
