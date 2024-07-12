<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\Uuids;

class Approval extends Model
{
    use HasFactory, SoftDeletes, Uuids;


    protected $casts = [
        'id' => 'string',
    ];
    protected $primaryKey = "id";
    protected $table = "approvals";

    protected $guarded = [

    ];

    public function expense()
    {
        return $this->belongsTo('App\Models\Expense', 'expense_id', 'id');
    }
    public function approver()
    {
        return $this->belongsTo('App\Models\Approver', 'approver_id', 'id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id', 'id');
    }
}
