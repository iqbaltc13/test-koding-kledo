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
use DateTime;

class ExpenseController extends Controller
{
    //
    public function __construct()
    {
        
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'amount' => 'required | numeric|min:1',
            
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        DB::beginTransaction();
        try {
            $menungguPerseujuan = Status::with([])->where('name', 'LIKE', '%menunggu persetujuan%')->first();
            $data = Expense::create(['amount' => $request->amount, 'status_id'=>$menungguPerseujuan->id]);
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

    public function approve($id, Request $request){
        $dateTime = new DateTime;
        $validator = Validator::make($request->all(), [
            'approver_id' => 'required',
            
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        DB::beginTransaction();
        try {
            $disetujui = Status::with([])->where('name', 'LIKE', '%disetujui%')->first();
            $menungguPerseujuan = Status::with([])->where('name', 'LIKE', '%menunggu persetujuan%')->first();
            $approverStages = ApprovalStage::with([])->orderBy('created_at')->get();
            $data = Expense::with(['approvals'])->where('id',$id)->first();
            
            if(is_null($data) || sizeof($approverStages) == 0){
                $response = [
                            'response_code'=>401,
                            'message'=>'Expense atau approval stages tidak ada',

                            'data'=>NULL,


                    ];
                    return response($response, 401);
            }
            $approvalStage = ApprovalStage::with([])->where('approver_id',$request->approver_id)->first();
            if(is_null($approvalStage)){
                 $response = [
                            'response_code'=>401,
                            'message'=>' approval stage tidak ada',

                            'data'=>NULL,


                    ];
                return response($response, 401);
            }
            
            if(sizeof($data->approvals) == 0 && $request->approver_id != $approverStages[0]->approver_id){
                
                $response = [
                            'response_code'=>401,
                            'message'=>' approval stage tidak sesuai',

                            'data'=>NULL,


                    ];
                    return response($response, 401);
            }
            if($data->approver_id == $approverStages[sizeof($approverStages) - 1]->approver_id){
                $response = [
                            'response_code'=>401,
                            'message'=>'tidak memerlukan approval',

                            'data'=>NULL,


                    ];
                    return response($response, 401);
            }
            foreach ($approverStages as $key => $value) {
                if($value->approver_id == $data->approver_id ){
                   
                    if($approvalStage[$key+1]->approver_id == $request->approver_id){
                        break;
                    }
                    else{
                        $response = [
                            'response_code'=>401,
                            'message'=>' approval stage tidak sesuai',

                            'data'=>NULL,


                        ];
                        return response($response, 401);
                    }
                    
                }
            }
            $createApproval = Approval::updateOrCreate([
                'approver_id' => $request->approver_id,
                'expense_id' => $id
                
            ],['updated_at' =>$dateTime->format('Y-m-d H:i:s')  ]);
            $data = Expense::with(['approvals'])->where('id',$id)->first();
            if(sizeof($data->approvals) == sizeof($approverStages) ){
                $data->status_id = $disetujui->id;
            }
            else{
                $data->status_id = $menungguPerseujuan->id;
            }
            $data->save();
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

                            'data'=>NULL,


                    ];
            return response($response, 401);
        }
        return response($response, 200);
    }
    
    public function detail($id, Request $request){
        $validator = Validator::make($request->all(), [
            
            
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $data = Expense::with(['status','approvals'=>function($query){
            $query->with(['approver','status']);
        }])->where('id',$id)->first();
        if($data){
             $response = [
                        'response_code'=>200,
                        'message'=>'success',

                        'data'=>$this->removeStringToNull($data,"null"),


                ];
        }
        else{
            $response = [
                            'response_code'=>401,
                            'message'=>'Expense tidak ada',

                            'data'=>NULL,


                    ];
            return response($response, 401);
        }
        return response($response, 200);
    }
 
}
