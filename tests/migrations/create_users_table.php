<?php

namespace dmitryrogolev\Canis\Tests\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = config('canis.connection');
        $table = app(config('canis.models.user'))->getTable();

        if (! Schema::connection($connection)->hasTable($table)) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                if (config('canis.uses.uuid')) {
                    $table->uuid(config('canis.primary_key'));
                } else {
                    $table->id();
                }

                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();

                if (config('canis.uses.timestamps')) {
                    $table->timestamps();
                }

                if (config('canis.uses.soft_deletes')) {
                    $table->softDeletes();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('canis.connection'))->dropIfExists(app(config('canis.models.user'))->getTable());
    }
};
