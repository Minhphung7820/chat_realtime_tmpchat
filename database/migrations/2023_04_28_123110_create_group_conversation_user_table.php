<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupConversationUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_conversation_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('groupConversation_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('groupConversation_id')->references('id')->on('group_conversations');
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_conversation_user');
    }
}
