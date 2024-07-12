<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableApprovalStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table_name = 'approval_stages';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up()
    {
        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('approver_id')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema->dropIfExists($this->table_name);
    }

}
