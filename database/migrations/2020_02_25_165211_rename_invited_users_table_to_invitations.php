<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameInvitedUsersTableToInvitations extends Migration
{
    private $oldTableName = 'invited_users';
    private $newTableName = 'invitations';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename($this->oldTableName, $this->newTableName);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename($this->newTableName, $this->oldTableName);
    }
}
