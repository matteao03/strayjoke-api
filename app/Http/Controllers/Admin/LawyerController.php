<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lawyer;
use Illuminate\Http\Request;
use App\Transformers\Admin\LawyerCheckTransformer;
use App\Transformers\Admin\LawyerTransformer;
use App\Models\LawyerCheck;

class LawyerController extends Controller
{
    public function index(Request $request)
    {
        if ($name = $request->query('name')){
            $lawyers = Lawyer::where('real_name', 'like', '%'.$name.'%' )->paginate($request->query('size'));
        } else {
            $lawyers = Lawyer::paginate($request->query('size'));
        }

        return $this->response->paginator($lawyers, new LawyerTransformer());
    }

    public function check(Request $request)
    {
        //开启事务
        \DB::transaction(function() use ($request) {
            $lawyerId = $request->lawyer_id;
            $status = $request->status;
            $checkBy = auth('admin')->user()->id;

            $lawyer = Lawyer::find($lawyerId);
            $lawyer->update(['status'=>$status]);

            LawyerCheck::create([
                'content'=>$request->content,
                'lawyer_id'=> $lawyerId,
                'status'=>$status,
                'checked_by'=>$checkBy
            ]);
        });

        return $this->response->created();
    }

    public function checkIndex(Request $request)
    {
        $lawyer = Lawyer::find($request->query(lawyerId));

        return $this->response->collection($lawyer->lawyerChecks(), new LawyerCheckTransformer());
    }
}
