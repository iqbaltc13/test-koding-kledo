<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\Uuids;

class Approver extends Model
{
    use HasFactory, SoftDeletes, Uuids;


    protected $casts = [
        'id' => 'string',
    ];
    protected $primaryKey = "id";
    protected $table = "approvers";

    protected $guarded = [

    ];
    public function approval_stages(){
        return $this->hasMany('App\Models\ApprovalStage','approver_id','id');
    }

    public function approvals(){
        return $this->hasMany('App\Models\Approval','approver_id','id');
    }
}
