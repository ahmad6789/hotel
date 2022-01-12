<?php

namespace Modules\Reservation\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Modules\Reservation\Entities\Customer;
use Modules\Reservation\Entities\Reservation;
use Illuminate\Support\Facades\Auth;

use Log;
use DataTables;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
	
	public function __construct()
    {
        // Page Title
        $this->module_title = 'Customers';
        // module name
        $this->module_name = 'Customer';
        // directory path of the module
        $this->module_path = 'customer';  
        // module icon
        $this->module_icon = 'fas fa-person';
        // module model name, path
        $this->module_model = "Modules\Reservation\Entities\Customer";
    }
	
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $module_path = $this->module_path;
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        Log::info(label_case($module_title).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return view("reservation::backend.$module_path.index");
    }
	
	public function gettable(Request $request){
        if ($request->ajax()) {
			
			$data = Customer::select('id', 'firstname', 'lastname', 'idnumber', 'phone1', 'email')->get();
			//dd($data);
            return Datatables::of($data)
			->addIndexColumn()
			->addColumn('action', function($row){
				$btn = '';
				return $btn;
			})
			->make(true);
        }
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id = 0)
    { 	
        $module_path = $this->module_path;
		
        return json_encode(array('response'=>true, 'data'=>array('id'=>1, 'name'=>'customer')));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $customer = new Customer;
        if(!empty($request->id) && $request->id > 0){
			$customer->id = $request->id;
		}
		// dd($request);
		$customer->firstname = $request->firstname;
		$customer->lastname = $request->lastname;
		$customer->sex = $request->sex;
		$customer->birthdate = $request->birthdate;
		$customer->idtype = $request->idtype;
		$customer->idnumber = $request->idnumber;
		$customer->phone1 = $request->phone1;
		$customer->phone2 = $request->phone2;
		$customer->email = $request->email;
		$customer->address1 = $request->address1;
		$customer->address2 = $request->address2;
		$customer->city = $request->city;
		$customer->country = $request->country;
		$customer->nationality = $request->nationality;
		
		$save = $customer->save();
		
		$customer->name = $this->customer_name_with_id($customer);
		echo json_encode(array('response'=>$save, 'data'=>$customer));
		die();
    }
	
	public function customer_name_with_id($customer){
		$name = $customer->firstname . ' ' . $customer->lastname . ' (' . $customer->idnumber . ')';
		return $name;
	}
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
		$customer = new Customer;
		$customerrecord = $customer->where('id',$id)->first();
        return view('reservation::show',
            compact('customerrecord'));
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
