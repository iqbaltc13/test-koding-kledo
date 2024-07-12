<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function __construct()
    {
        $this->table_name = 'approvals';
        $this->schema = Schema::connection($this->getConnection());
    }

    public function up()
    {
        $this->schema->create($this->table_name, function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('approver_id')->nullable();
            $table->string('expense_id')->nullable();
            $table->string('status_id_id')->nullable();
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
    public function up()
    {
        Schema::create('table_approvals', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('table_approvals');
    }
}
