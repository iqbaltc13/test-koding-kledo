<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function __construct()
    {
        $this->items = [
            "menunggu persetujuan", "disetujui"
        ];
    }
    public function run()
    {

    	$this->command->info('disabling foreignkeys check');
        Schema::disableForeignKeyConstraints();
        $this->command->info('truncating statuses...');
        DB::table('statuses')->truncate();

        Schema::enableForeignKeyConstraints();

        DB::beginTransaction();
        $create = NULL;
        try {
            foreach ($this->items as $key => $item) {
                $create =  Status::create(['name' => $item ]);
            }
            DB::commit();


        } catch (QueryException $e) {
            DB::rollback();


        }
    }
}
