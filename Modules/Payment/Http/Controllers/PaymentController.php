<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use  Modules\Reservation\Entities\Customer;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentLine;
use Illuminate\Support\Facades\DB;


use Log;
use DataTables;
use App\Authorizable;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
	public function __construct()
    {
        // Page Title
        $this->module_title = 'Payments';

        // module name
        $this->module_name = 'payment';

		// directory path of the module
        $this->module_path = 'payment';

        // module icon
        $this->module_icon = 'fas fa-money-bill';

        // module model name, path
        $this->module_model = "Modules\Payment\Entities\Payment";
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
		$module_path = $this->module_path;
        return view("$module_path::backend.$module_path.index");
    }

	public function gettable(Request $request){
		$now = '';
        if ($request->ajax()) {

			$payments = DB::table('payment')
            ->join('payment_line', 'payment.id', '=', 'payment_line.paymentid')
            ->join('customer', 'customer.id', '=', 'payment.payeeid')
            ->join('users', 'users.id', '=', 'payment.receivedby')
            ->select('payment.id', 'payment.type', 'payment.context', 'payment.contextid', DB::raw('concat(customer.firstname, " ", customer.lastname) as payeename'), DB::raw('concat(users.first_name, " ", users.last_name) as receivername'), DB::raw('count(payment_line.description) as paymentlinescount'), DB::raw('sum(payment_line.cost * payment_line.quantity) as paymenttotal'), 'payment.created_at')
			->groupBy('payment.id', 'payment.type', 'payment.context', 'payment.contextid', 'payeename', 'receivername', 'payment.created_at')
            ->get();
						// DB::table('website_tags')
			// ->join('assigned_tags', 'website_tags.id', '=', 'assigned_tags.tag_id')
			// ->select('website_tags.id as id', 'website_tags.title as title', DB::raw("count(assigned_tags.tag_id) as count"))
			// ->groupBy('website_tags.id')
			// ->get();
            return Datatables::of($payments)
			->addIndexColumn()
			->addColumn('typename', function($row){
				$str = __('payment.'.$row->type);
				return $str;
			})
			->addColumn('contextname', function($row){
				$str = __('payment.'.$row->context);
				return $str;
			})
			->addColumn('action', function($row){
				$btn = '<a href="'.route('payment.print', ['id'=>$row->id]).'" data-id="'.$row->id.'" class="btn btn-info btn-sm  print" ><i class="fa fa-file-pdf"></i></a>'.
				'  <a href="'.route('payment.show', ['id'=>$row->id]).'" data-id="'.$row->id.'" class="btn btn-warning btn-sm  view "><i class="fa fa-eye"></i> </a>
				';
				return $btn;
			})

			->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('payment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
		$module_path = $this->module_path;

		$payment = Payment::where('id', $id)->first();
		$paymentlines = PaymentLine::where('paymentid', $payment->id)->get();

        return view("$module_path::backend.$module_path.show", compact('payment','paymentlines'));
    }

    public function print($id)
    {
		$module_path = $this->module_path;

		$payment = Payment::where('id', $id)->first();
        $paymentlines = PaymentLine::where('paymentid', $payment->id)->get();
        $customer = Customer::where('id', $payment->payeeid)->first();
        $totalcost=PaymentLine::select(DB::raw("SUM(quantity*cost) as sum"))->where('paymentid', $payment->id)->first();
        return view("$module_path::backend.$module_path.invoice", compact('customer','paymentlines','payment','totalcost'));
    }


	public function linegettable(Request $request, $id){
        if ($request->ajax()) {

			$paymentlines = PaymentLine::where('paymentid',$id)->get();
            return Datatables::of($paymentlines)
			->addIndexColumn()
			->addColumn('total', function($row){
				$val = $row->cost * $row->quantity;
				return $val;
			})
			->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('payment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
