<?php

namespace Modules\Expense\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentLine;
use Modules\Expense\Entities\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Log;
use DataTables;
use App\Authorizable;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{

	public function __construct()
    {
        // Page Title
        $this->module_title = 'Expenses';

        // module name
        $this->module_name = 'expense';
        // directory path of the module
        $this->module_path = 'expense';
        // module icon
        $this->module_icon = 'fas fa-money';

        // module model name, path
        $this->module_model = "Modules\Expense\Entities\Expense";
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($type = null)
    {
		$module_action = 'List expenses';

        Log::info(label_case($this->module_title.' '.$module_action).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');
		if($type != null){
			return view("expense::backend.expense.index", compact('type'));
		} else {
			return view("expense::backend.expense.index");
		}

    }


	public function gettable(Request $request, $type = null){
        if ($request->ajax()) {

			if($type != null && $type != 'all'){
				$expense = DB::select('SELECT e.*, SUM(pl.cost * pl.quantity) as total FROM expenses as e
				LEFT JOIN payment as p ON p.contextid = e.id
				LEFT JOIN payment_line as pl ON pl.paymentid = p.id
				WHERE p.type = "spent"
				AND p.context = "expenses"
				AND e.type = "'.$type.'"
				GROUP BY e.id');
			} else {
				$expense = DB::select('SELECT e.*, SUM(pl.cost * pl.quantity) as total FROM expenses as e
				LEFT JOIN payment as p ON p.contextid = e.id
				LEFT JOIN payment_line as pl ON pl.paymentid = p.id
				WHERE p.type = "spent"
				AND p.context = "expenses"
				GROUP BY e.id');
			}

            $data = $expense;
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '';

                         return $btn;
					})
					->addColumn('typename', function($row){

                        $typename = __('expense.'.$row->type);

                         return $typename;
					})
                ->addColumn('action', function($row){
                    $user = Auth::user();
                    $roles = $user->getRoleNames();

                    foreach($roles as $role)
                    {
                        if ($role!="roomservice" || $role="super admin")
                            $perm=$role;

                        else $perm="NOPerm";
                    }
                    if($perm=="super admin"){
                        $btn = '
                        <a href="#" data-id="'.$row->id.'" class="btn btn-success btn-sm  edit"title="تعديل المصروف"> <i class="fa fa-wrench"></i></a>

                        ';
                    }
                    else if($perm=="roomservice"){
                        $btn ='
                            ';
                    }
                    else
                        $btn='';
                    return $btn;
                })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }


	public function getUsersList(Request $request){
		$users = DB::table('users')->select('id', DB::raw('CONCAT(first_name, " ", last_name) as name'))->get();
		echo json_encode(array('response'=>true, 'data'=>$users));
		die();
	}

	public function getRoomsList(Request $request){
		$rooms = DB::table('rooms')->select('id', DB::raw('code as name'))->get();
		echo json_encode(array('response'=>true, 'data'=>$rooms));
		die();
	}


	public function getItemsList(Request $request){
		$items = DB::table('items')->select('id', 'name')->get();
		echo json_encode(array('response'=>true, 'data'=>$items));
		die();
	}
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(){
        $edit=false;
        return view("expense::backend.expense.create",compact('edit'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Validate the request...
       try{
		   DB::beginTransaction();
			$expense = new Expense;

			$expense->type = $request->type;
			$expense->description = $request->description;
			$expense->addedby = Auth::user()->id;

			if($request->type == 'drawings' || $request->type == 'wages'){
				$expense->context = 'users';
			} else if($request->type == 'repairs'){
				$expense->context = 'rooms';
			} else if($request->type == 'items'){
				$expense->context = 'items';
			} else {
				$expense->context = $request->type;
			}


			if(isset($request->contextid)){
				$expense->contextid = $request->contextid;
			} else {
				$expense->contextid = 0;
			}

			$expensesave = $expense->save();

			$payment = new Payment;
			$payment->type = 'spent';

			$payment->context = 'expenses';
			$payment->contextid = $expense->id;


			$payment->receivedby = Auth::user()->id;

			$paymentsave = $payment->save();

			foreach($request->item_description as $index => $description){
				$paymentline = new PaymentLine;
				$paymentline->paymentid = $payment->id;
				$paymentline->description = $description;
				$paymentline->cost = $request->item_cost[$index];
				$paymentline->quantity = $request->item_quantity[$index];

				$paymentline->save();
			}
			DB::commit();
			echo json_encode(array('response'=>true, 'data'=>$expense));
			die();
	   } catch(Exception $e){
		   DB::rollBack();
		   echo json_encode(array('response'=>false, 'data'=>$expense));
		die();
	   }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('expense::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $edit=true;

		$expense = Expense::where('id',$id)->first();

        $payment=Payment::where('context','expenses')->where('contextid',$expense->id)->first();


        $paymentLine=PaymentLine::where('paymentid',$payment->id)->get();

        return view("expense::backend.$module_path.create",compact('expense','edit','payment','paymentLine'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {

        try{
            DB::beginTransaction();

            $expense = Expense::where('id',$request->exID)->first();


            $expense->type = $request->type;
            $expense->description = $request->description;
            $expense->addedby = Auth::user()->id;


            if($request->type == 'drawings' || $request->type == 'wages'){
                $expense->context = 'users';
            } else if($request->type == 'repairs'){
                $expense->context = 'rooms';
            } else if($request->type == 'items'){
                $expense->context = 'items';
            } else {
                $expense->context = $request->type;
            }

            $expensesave = $expense->save();
            $payment=Payment::where([['contextid',$expense->contextid],['context','expenses']])->first();


            foreach($request->item_description as $index => $description){

                $paymentline=PaymentLine::find($request->pl_id[$index]);
                $paymentline->description = $description;
                $paymentline->cost = $request->item_cost[$index];
                $paymentline->quantity = $request->item_quantity[$index];
                $paymentline->save();
            }
            DB::commit();
            echo json_encode(array('response'=>true, 'data'=>$expense));
            die();
        } catch(Exception $e) {
            DB::rollBack();



		echo json_encode(array('response'=>true, 'data'=>$expense));
		die();
    }}

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $expense = Expense::where('id',$id)->first();

		$delete = $expense->delete();

		echo json_encode(array('response'=>$delete, 'data'=>$expense));
		die();
    }
}
