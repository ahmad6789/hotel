<?php

namespace Modules\Reservation\Http\Controllers;
use App\Notifications\RoomPriceEdit;
use App\Notifications\RoomReservation;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use App\Models\User;
use Modules\Room\Entities\Room;
use Modules\Room\Entities\Bed;
use Modules\Reservation\Entities\Customer;
use Modules\Reservation\Entities\Reservation;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentLine;
use Log;
use DataTables;

class ReservationController extends Controller
{

	public function __construct()
    {
        // Page Title
        $this->module_title = 'Reservations';

        // module name
        $this->module_name = 'reservation';
        // directory path of the module
        $this->module_path = 'reservation';
        // module icon
        $this->module_icon = 'fas fa-book';

        // module model name, path
        $this->module_model = "Modules\Reservation\Entities\Reservation";
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
		$module_path = $this->module_path;
		//$now = Carbon\Carbon::now();

        return view("reservation::backend.$module_path.index");
    }

	public function getRoomCountStatus(){
		$available = Room::where('status',0)->count();
		$booked = Room::where('status',1)->count();
		$issues = Room::where('status','!=', 0)->where('status','!=', 1)->count();

		echo json_encode(array('available'=>$available, 'booked'=>$booked, 'issues'=>$issues));
		die();
	}
	public function gettable(Request $request){
		$now = date('Y-m-d', time());
        if ($request->ajax()) {
			$rooms = DB::table('rooms')
            ->leftJoin('reservation', 'rooms.id', '=', 'reservation.roomid')
            ->leftJoin('customer', 'customer.id', '=', 'reservation.customerid')
			->select('rooms.code', 'reservation.*', 'rooms.price', DB::raw('concat(customer.firstname, " ", customer.lastname) as customer'))
            ->where('reservation.bookingend', '>' , $now);

            $data = $rooms->get();

            return Datatables::of($data)
                    ->addIndexColumn()
					->addColumn('action', function($row){
						$btn = '<a href="#" data-id="'.$row->id.'"
						class="btn btn-danger btn-sm  delete" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i></a>
						<a href="'.route('reservation.edit')."/$row->id".'" data-id="'.$row->id.'" class="btn btn-success btn-sm  edit " title="Edit"> <i class="fa fa-pen"></i> </a>
						';
						return $btn;
					})
                    ->make(true);
        }
    }

	public function indexrooms()
    {
		$module_path = $this->module_path;
		//$now = Carbon\Carbon::now();

        return view("reservation::backend.$module_path.showrooms");
    }

	public function showroomsgettable(Request $request){

        if ($request->ajax()) {

			$rooms = DB::table('rooms')
            ->leftJoin('reservation', function($query)
			{
			   $query->on('rooms.id', '=', 'reservation.roomid')
			   ->whereRaw('reservation.id IN (SELECT res.id FROM reservation as res
												RIGHT JOIN rooms as u2 on u2.id = res.roomid
												WHERE res.bookingend >= '.date('Y-m-d', time()).'
												AND res.status = 0
												group by u2.id)');
			})
            ->leftJoin('customer', 'customer.id', '=', 'reservation.customerid')
			->select('rooms.id as roomroomid','rooms.code as code','rooms.status as roomstatus', 'reservation.*', DB::raw('concat(customer.firstname, " ", customer.lastname) as customer'))
			->groupBy('rooms.code');

            $data = $rooms->get();

            return Datatables::of($data)
                    ->addIndexColumn()
					->addColumn('action', function($row){
						$btn = '';

						//book option
						$btn .= '<a title="'.__("reservation.bookroom").'" href="'.route('reservation.create', ['id'=>$row->roomroomid]).'" class="btn btn-success btn-sm  book" ><i class="fa fa-suitcase"></i></a> ';

						// checkout option
						if($row->roomstatus == 1 || ($row->bookingstart <= date('Y-m-d', time()) && $row->bookingend > date('Y-m-d', time())) || $row->roomstatus == 2){
							$btn .= '<a title="'.__("reservation.checkout").'" href="#" data-id="'.$row->id.'" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm  checkout" ><i class="fa fa-plane"></i></a> ';
						}

						//Start a ticket option
						if($row->bookingend > date('Y-m-d', time())){
							$btn .= '<a title="'.__("reservation.addticket").'" href="'.route('ticket.create', ['id'=>$row->roomroomid]).'" data-id="'.$row->id.'" class="btn btn-danger btn-sm  ticket" ><i class="fa fa-life-ring"></i></a> ';
						}

						//edit option
						if($row->bookingend >= date('Y-m-d', time())){
							$btn .= '<a title="'.__("reservation.editbooking").'" href="'.route('reservation.edit', ['id'=>$row->id]).'" data-id="'.$row->id.'" class="btn btn-warning btn-sm  edit" ><i class="fa fa-pen"></i></a> ';
						}
						return $btn;
					})
					->addColumn('statusname', function($row){
						$str = '';

						if($row->roomstatus == 1){
							$str = '<i class="fa fas fa-circle text-danger"></i> ' . __('reservation.occupied');
						}
						if($row->roomstatus == 0){
							$str = '<i class="fa fas fa-circle text-success"></i> ' . __('reservation.empty');
						}
						if($row->roomstatus == 0 && !empty($row->bookingend) && $row->bookingend >= date('Y-m-d', time())){
							$str = '<i class="fa fas fa-circle text-warning"></i> ' . __('reservation.booked');
						}
						if($row->roomstatus == 2 ){
							$str = '<i class="fa fas fa-circle text-danger"></i> ' . __('reservation.waitingcheckout');
						}
						return $str;
					})
					->rawColumns(['statusname', 'action'])
                    ->make(true);
        }
    }

	public function checkout($id){
		$userid = Auth::id();
		$reservation = Reservation::where('id',$id)->first();
		$reservation->status = 1;
		$room = Room::where('id',$reservation->roomid)->first();
		$room->status = 0;
		try{
			DB::beginTransaction();
			$save = $room->save();
			$save = $reservation->save();
			DB::commit();
			echo json_encode(array('response'=>$save));
			die();
		} catch(Exception $e){
			DB::rollBack();
			echo json_encode(array('response'=>false, 'data'=>$e->getMessage()));
			die();
		}

		echo json_encode(array('response'=>$save));
		die();
    }

	public function extend()
    {
		$userid = Auth::id();
		$rooms = Room::where('status',0)->get();
		//dd();
		$customers = Customer::get();
		$module_path = $this->module_path;
        return view("reservation::backend.$module_path.create", compact('userid','rooms', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id = 0)
    {
		$userid = Auth::id();
		$rooms = Room::get();
		$customers = Customer::get();
		$module_path = $this->module_path;
		if($id != 0){
			$roomid =  $id;
			$room = Room::where('id', $id)->first();
			$roomprice =  $room->price;
			return view("reservation::backend.$module_path.create", compact('userid','rooms', 'customers', 'roomid', 'roomprice'));
		} else {
			return view("reservation::backend.$module_path.create", compact('userid','rooms', 'customers'));
		}
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
		$userid = Auth::id();
        $reservation = new Reservation;

        $reservation->roomid = $request->roomid;
        $reservation->customerid = $request->customerid;
		$reservation->employeeid = $request->employeeid;
		$reservation->bedid = $request->bedid;
        $reservation->bookingstart = $request->bookingstart;
        $reservation->bookingend = $request->bookingend;
        $reservation->status = 0;

		$roomserservations = Reservation::where(function ($query) use ($reservation){
			$query->where('bookingstart', '>=', $reservation->bookingstart)
				  ->where('bookingend', '<=', $reservation->bookingstart)
				  ->where('roomid', $reservation->roomid)
				  ->where('status', 0);
		})
		->orWhere(function ($query) use ($reservation){
			$query->where('bookingstart', '>=', $reservation->bookingend)
				  ->where('bookingend', '<=', $reservation->bookingend)
				  ->where('roomid', $reservation->roomid)
				  ->where('status', 0);
		})
		->orWhere(function ($query) use ($reservation){
			$query->where('bookingstart', '<=', $reservation->bookingstart)
				  ->where('bookingend', '>=', $reservation->bookingend)
				  ->where('roomid', $reservation->roomid)
				  ->where('status', 0);
		})
		->orWhere(function ($query) use ($reservation){
			$query->where('bookingstart', '>=', $reservation->bookingstart)
				  ->where('bookingend', '<=', $reservation->bookingend)
				  ->where('roomid', $reservation->roomid)
				  ->where('status', 0);
		})->select('bookingstart','bookingend')->get();
		if(!$roomserservations->isEmpty()){
			echo json_encode(array('response'=>false, 'booked'=>true, 'data'=>$roomserservations));
			die();
		}
		try{
			DB::beginTransaction();
			$save = $reservation->save();

			$payment = new Payment;
			$payment->type = 'received';
			$payment->context = 'reservation';
			$payment->contextid = $reservation->id;
			$payment->payeeid = $reservation->customerid;
			$payment->receivedby = $userid;
			$payment->save();

			$room = Room::where('id',$reservation->roomid)->first();
			$paymentline = new PaymentLine;
			$paymentline->paymentid = $payment->id;
			$paymentline->description = __('reservation.reservedroom') .' '. $room->code . __('reservation.from') . ' ' . $reservation->bookingstart . ' ' . __('reservation.to') . ' '. $reservation->bookingend;
			$arr['oldprice']=$room->price;
			$arr['roomcode']=$room->code;
            $arr['usname']=Auth::user()->name;
            $arr['usid']=Auth::user()->id;
			$arr['newprice']=$request->cost;
			if($room->price!=$request->cost){
            $superadminusers = User::role('super admin')->get();
            foreach( $superadminusers as  $superadminuser )
            $superadminuser->notify(new RoomPriceEdit($arr));
            }
			else{
            $superadminusers = User::role('super admin')->get();
            foreach( $superadminusers as  $superadminuser )
            $superadminuser->notify(new RoomReservation($arr));
            }
			$paymentline->cost = $request->cost;
			$paymentline->quantity = ((strtotime($reservation->bookingend) - strtotime($reservation->bookingstart)) / (60 * 60 * 24)) + 1;
			$paymentline->save();

			// change room status
			if($reservation->bookingstart <= date('Y-m-d', time())){
				$room->status = 1;
				$room->save();
			}

			DB::commit();
			echo json_encode(array('response'=>$save, 'data'=>$reservation->id));
			die();
		} catch(Exception $e){
			DB::rollBack();
			echo json_encode(array('response'=>false, 'data'=>$e->getMessage()));
			die();
		}
    }

	public function getRoomForReservation(Request $request){
		$roomid = $request->roomid;
		$room = Room::where('id',$roomid)->first();

		// $beds = Bed::where('roomid',$roomid)->get();
		// if(!$beds->isEmpty()){
			// echo json_encode(array('room'=>$room, 'beds'=>$beds));
		// } else {
			// echo json_encode(array('room'=>$room));
		// }
		echo json_encode(array('room'=>$room));
		die();
	}

	public function getRoomBedsForReservation(Request $request){
		$roomid = $request->roomid;

		$beds = Bed::where('roomid',$roomid)->get();
		if(!$beds->isEmpty()){
			echo json_encode(array('beds'=>$beds));
		}

	}
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $userid = Auth::id();
		$reservation = Reservation::where('id',$id)->first();
		$room = Room::where('id',$reservation->roomid)->first();
		$rooms = Room::get();
		//dd();
		$customers = Customer::get();
		$module_path = $this->module_path;
        return view("reservation::backend.$module_path.create", compact('reservation', 'userid','rooms', 'customers', 'room'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $userid = Auth::id();
        $reservation = Reservation::where('id',$request->reservationid)->first();

        $reservation->roomid = $request->roomid;
        $reservation->customerid = $request->customerid;
		$reservation->employeeid = $request->employeeid;
		$reservation->bedid = $request->bedid;
        $reservation->bookingstart = $request->bookingstart;
        $reservation->bookingend = $request->bookingend;
        $reservation->status = 1;
		try{
			DB::beginTransaction();
			$save = $reservation->save();

			$payment = Payment::where('context','reservation')->where('contextid',$reservation->id)->first();
			$payment->type = 'received';
			$payment->context = 'reservation';
			$payment->contextid = $reservation->id;
			$payment->payeeid = $reservation->customerid;
			$payment->receivedby = $userid;
			$payment->save();

			$room = Room::where('id',$reservation->roomid)->first();
			$paymentline = PaymentLine::where('paymentid',$payment->id)->first();
			$paymentline->description = 'Reserved room ' . $room->code . ' from ' . $reservation->bookingstart . ' to ' . $reservation->bookingend;

			$paymentline->cost = $room->price;

			$paymentline->quantity = ((strtotime($reservation->bookingend) - strtotime($reservation->bookingstart)) / (60 * 60 * 24)) + 1;
			$paymentline->save();

			// change room status if the booking already ended
			if(strtotime($reservation->bookingend) <= time()){
				$room->status = 0;
				$room->save();
			}

			// TODO: change room status
			DB::commit();
			echo json_encode(array('response'=>$save, 'data'=>$reservation->id));
			die();
		} catch(Exception $e){
			DB::rollBack();
			echo json_encode(array('response'=>false, 'data'=>$e->getMessage()));
			die();
		}
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

		$reservation = Reservation::where('id',$id)->first();
		$room = Room::where('id',$reservation->roomid)->first();
		$payment = Payment::where('context','reservation')->where('contextid',$reservation->id)->first();
		if($payment){
			$paymentline = PaymentLine::where('paymentid',$payment->id)->first();
		}

		try{
			DB::beginTransaction();
			if($payment){
				if($paymentline){
					$paymentline->delete();
				}
				$payment->delete();
			}

			$reservation->delete();
			$room->status = 0;
			$room->save();
			DB::commit();

			echo json_encode(array('response'=>true, 'data'=>$reservation->id));
			die();
		} catch (Exception $e){
			DB::rollBack();
			echo json_encode(array('response'=>false, 'data'=>$e->getMessage()));
			die();
		}
    }
}
