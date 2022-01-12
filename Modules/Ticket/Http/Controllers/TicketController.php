<?php

namespace Modules\Ticket\Http\Controllers;
use App\Notifications\AddTicket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Room\Entities\Room;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Entities\TicketActivity;
use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Str;
use Log;
use DataTables;

use App\Authorizable;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Flash;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Tickets';

        // module name
        $this->module_name = 'ticket';
        // directory path of the module
        $this->module_path = 'tickets';
        // module icon
        $this->module_icon = 'fas fa-university';

        // module model name, path
        $this->module_model = "Modules\Ticket\Entities\Ticket";
    }


    public function index(Request $request)
    {

        $editNotify=$request->edit;
        $module_path = $this->module_path;
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = $module_model::paginate();

        Log::info(label_case($module_title.' '.$module_action).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');

        return view(
            "ticket::backend.$module_path.index",
            compact('editNotify','module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
        );




    }


    public function gettable(Request $request){
        if ($request->ajax()) {



             $data = DB::table('tickets')
            ->join('users', 'users.id', '=', 'tickets.assignedto')
            ->join('rooms', 'rooms.id', '=', 'tickets.roomid')
            ->select('tickets.*', 'users.name' ,'rooms.code')->where('tickets.deleted_at',NULL)
            ->get();

            return Datatables::of($data)

                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<a href="#" data-id="'.$row->id.'"
                         class="btn btn-danger btn-sm  delete" data-toggle="modal" data-target="#myModal">
                         <i class="fa fa-trash"></i></a>

                        <a href="#" data-id="'.$row->id.'" class="btn btn-info btn-sm  add" title="عرض التفاصيل"><i class="fas fa-book"></i></a>
                         ';

                         return $btn;
                 })

                    ->rawColumns(['action'])
                    ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id = 0)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

		$rooms = Room::get();
		$users = User::get();
		if(!empty($id)){
			$roomid =  $id;
			return view("ticket::backend.$module_path.create", compact('rooms', 'users', 'roomid'));
		} else {
			return view("ticket::backend.$module_path.create",  compact('rooms', 'users'));
		}


        }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Validate the request...
         $ticket = new Ticket;
        $ticket->roomid = $request->roomid;
        $ticket->status = 1;
        $ticket->assignedto = $request->assignedto;
        $ticket->type = $request->type;
        $ticket->priority = $request->priority;
        $save = $ticket->save();

        $arr['desc']=$request->type;
        $user=User::find($request->assignedto);
        $arr['assignedto']=$user->first_name .''.$user->last_name ;
        $room= Room::where('id',$request->roomid)->first();
        $arr['roomid']=$room->code;

        $arr['usname']=Auth::user()->name;
        $arr['usid']=Auth::user()->id;
        $receptionusers = User::role('reception')->get();
        foreach( $receptionusers as  $receptionuser )
        $receptionuser->notify(new AddTicket($arr));
        $superadminusers = User::role('super admin')->get();
        foreach( $superadminusers as  $superadminuser )
		$superadminuser->notify(new AddTicket($arr));
        $superadminusers = User::role('maintenance')->get();
        foreach( $superadminusers as  $superadminuser )
		$superadminuser->notify(new AddTicket($arr));
        $roomserviceusers = User::role('roomservice')->get();
        foreach( $roomserviceusers as  $roomserviceuser )
		$roomserviceuser->notify(new AddTicket($arr));
		echo json_encode(array('response'=>$save, 'data'=>$ticket));
		die();

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {

        $ticket = Ticket::find( $request->id );
        dd($ticket);

		echo json_encode(array('response'=>$save, 'data'=>$ticket));
		die();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id){
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $ticketEdit = Ticket::where('id', $id)->first();
        $arr['ticketEdit']=$ticketEdit;

         return view("ticket::backend.$module_path.create",$arr);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */

    public function update(Request $request)
    {
        $ticket = Ticket::where('id',$request->id)->first();
        $ticket->status = 1;
        $save = $ticket->save();

        if($request->status==2)
        {
            $TicketActivity = new TicketActivity;
            $TicketActivity->ticketid = $request->id;
            $TicketActivity->descriptions = "".__('Ticket.Done By') . " : ". Auth::user()->name;
            $TicketActivity->save();
        }
		echo json_encode(array('response'=>$save));
		die();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id=null)
    {
        if(empty($id)||!is_numeric($id))
        {
            echo json_encode(array('response'=>'Error'));
            die();

        }
        else
        {
            $ticket = Ticket::find($id);

            $delete = $ticket->delete();
           echo json_encode(array('response'=>$delete));
           die();
        }
    }
}
