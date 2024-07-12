<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\Uuids;

class ApprovalStage extends Model
{
    use HasFactory, SoftDeletes, Uuids;


    protected $casts = [
        'id' => 'string',
    ];
    protected $primaryKey = "id";
    protected $table = "approval_stages";

    protected $guarded = [

    ];


    public function approver()
    {
        return $this->belongsTo('App\Models\Approver', 'approver_id', 'id');
    }

}
