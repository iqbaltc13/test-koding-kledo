<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Approver;
use App\Models\ApprovalStage;
use App\Models\Approval;
use App\Models\Expense;
use App\Models\Status;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use stdClass;

class ApprovalStageController extends Controller
{
    //
    public function __construct()
    {
        
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'approver_id' => 'required | unique:approval_stages,approver_id',
            
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        DB::beginTransaction();
        try {
            $data = ApprovalStage::create(['approver_id' => $request->approver_id]);
            $response = [
                        'response_code'=>200,
                        'message'=>'success',

                        'data'=>$this->removeStringToNull($data,"null"),


                ];
            DB::commit();

        //     // all good
        } catch (QueryException $e) {
             DB::rollback();
            $response = [
                            'response_code'=>401,
                            'message'=>'Error Query, Proses Gagal',

                            'data'=>$data,


                    ];
            return response($response, 401);
        }

        return response($response, 200);
    }

    public function update($id, Request $request){
        $validator = Validator::make($request->all(), [
            'approver_id' => 'required | unique:approval_stages,approver_id,' . $id,
            
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $checkData = ApprovalStage::where('id',$id)->first();
        if(is_null($checkData)){
            $response = [
                            'response_code'=>401,
                            'message'=>'Data Not Exist',

                            'data'=>NULL,


                    ];
            return response($response, 401);
        }
        DB::beginTransaction();
        try {
            $data = ApprovalStage::where('id',$id)->update(['approver_id' => $request->approver_id]);
            $response = [
                        'response_code'=>200,
                        'message'=>'success',

                        'data'=>$this->removeStringToNull($data,"null"),


                ];
            DB::commit();

        //     // all good
        } catch (QueryException $e) {
             DB::rollback();
            $response = [
                            'response_code'=>401,
                            'message'=>'Error Query, Proses Gagal',

                            'data'=>$data,


                    ];
            return response($response, 401);
        }

        return response($response, 200);
    }
}
