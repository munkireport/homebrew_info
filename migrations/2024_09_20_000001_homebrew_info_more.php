<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class HomebrewInfoMore extends Migration
{
    private $tableName = 'homebrew_info';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->string('core_cask_tap')->nullable();
            $table->string('homebrew_cask_opts')->nullable();
            $table->string('homebrew_make_jobs')->nullable();
            $table->string('rosetta_2')->nullable();
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('core_cask_tap');
            $table->dropColumn('homebrew_cask_opts');
            $table->dropColumn('homebrew_make_jobs');
            $table->dropColumn('rosetta_2');
        });
    }
}
