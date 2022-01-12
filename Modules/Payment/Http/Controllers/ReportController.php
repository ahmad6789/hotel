<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentLine;
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

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view("payment::backend.report.index");
    }
	
	
	public function profitloss(Request $request){

		$from = '';
		$to = '';
		if($request && !empty($request->from) && !empty($request->to)){
			$from = date('Y-m-d H:i:s', strtotime($request->from));
			$to = date('Y-m-d H:i:s', strtotime($request->to));
			$condition = ' AND p.created_at BETWEEN "'.$from . '" AND "'.$to .'" ';
		} else {
			$condition = '';
		}
		
		$income = DB::select('SELECT "reservation" as type, SUM(pl.quantity * pl.cost) as total FROM payment as p
		LEFT JOIN payment_line as pl ON p.id = pl.paymentid
		WHERE p.context = "reservation"' .$condition);
		
		$expenses = DB::select('SELECT e.type, SUM(pl.quantity * pl.cost) as total FROM expenses as e
		LEFT JOIN payment as p ON p.contextid = e.id
		LEFT JOIN payment_line as pl ON p.id = pl.paymentid
		WHERE p.context = "expenses"
		AND e.type != "drawings"'  .$condition .'
		GROUP BY e.type');
		
		$drawings = DB::select('SELECT CONCAT(u.first_name, " ", u.last_name) as type, SUM(pl.quantity * pl.cost) as total FROM expenses as e
		LEFT JOIN payment as p ON p.contextid = e.id
		LEFT JOIN payment_line as pl ON p.id = pl.paymentid
		LEFT JOIN users as u ON u.id = e.contextid
		WHERE e.type = "drawings" ' . $condition . '
		GROUP BY type');
		
		
		if(!empty($from)){
			$from = date('Y-m-d', strtotime($from));
			$to = date('Y-m-d', strtotime($to));
		}
		
		return view("payment::backend.report.profitloss", compact('income','expenses','drawings', 'from', 'to'));
	}
	
	public function expenses(Request $request){
		
		$from = '';
		$to = '';
		$addedby = '';
		$type = '';
		$employeeid = '';
		$conditions = '';
		if(!empty($request->from) && !empty($request->to)){
			$from = date('Y-m-d H:i:s', strtotime($request->from));
			$to = date('Y-m-d H:i:s', strtotime($request->to) + 86399);
			$conditions .= ' AND p.created_at BETWEEN "'.$from . '" AND "'.$to .'" ';
		}
		if(!empty($request->addedby)){
			$conditions .= ' AND e.addedby = ' . $request->addedby;
			$addedby = $request->addedby;
		}
		if(!empty($request->type)){
			$conditions .= ' AND e.type = "' . $request->type . '" ';
			$type = $request->type;
			if(($request->type == 'wages' || $request->type == 'drawings') && !empty($request->employeeid)){
				$conditions .= ' AND e.contextid = ' . $request->employeeid;
				$employeeid = $request->employeeid;
			}
		}
		
		$rows = DB::select('SELECT e.*, p.id, SUM(pl.quantity * pl.cost) as total, CONCAT(u.first_name, " ", u.last_name) as addedbyname FROM expenses as e 
		LEFT JOIN payment as p ON p.contextid = e.id 
		LEFT JOIN payment_line as pl ON pl.paymentid = p.id
		LEFT JOIN users as u ON e.addedby = u.id
		WHERE p.context = "expenses" '.
		$conditions
		.' GROUP BY e.id');
		
		$users = DB::table('users')->select('id', DB::raw('CONCAT(first_name, " ", last_name) as name'))->get();
		
		if(!empty($from)){
			$from = date('Y-m-d', strtotime($from));
			$to = date('Y-m-d', strtotime($to));
		}
		
		return view("payment::backend.report.expenses", compact('rows', 'users', 'from', 'to', 'addedby', 'type', 'employeeid'));
	}
	
	
	public function reservations(){
		$rows = DB::select('SELECT SUM(pl.quantity * pl.cost) as total FROM payment as p
		LEFT JOIN payment_line as pl ON p.id = pl.paymentid
		WHERE p.context = "reservation"
		GROUP BY p.id');
		return view("payment::backend.report.reservations");
	}
	
}
