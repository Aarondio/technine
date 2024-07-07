<?php

use App\Models\Organisation;
use App\Models\User;
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
        Schema::create('organisation_user', function (Blueprint $table) {
            // $table->uuid('user_id');
            // $table->uuid('organisation_id');
            // $table->foreign('user_id');
            // $table->foreign('organisation_id');
            // $table->id();
            $table->foreignUuid('user_id');
            $table->foreignUuid('organisation_id');
            // $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
            // $table->foreign('orgId')->references('orgId')->on('organisations')->onDelete('cascade');
            // $table->foreignUuid('userId')->cascadeOnDelete();
            // $table->foreignUuid('orgId')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisation_user');
    }
};
