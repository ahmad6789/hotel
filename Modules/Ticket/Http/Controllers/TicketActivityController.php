<?php

namespace Modules\Ticket\Http\Controllers;
use App\Notifications\AddTicketActivits;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
 use Modules\Ticket\Entities\TicketActivity;
use Modules\Ticket\Entities\Ticket;
use Modules\Room\Entities\Room;

use App\Models\User;
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
use Illuminate\Database\Eloquent\SoftDeletes;
class TicketActivityController extends Controller
{

    public function __construct()
    {

        $this->module_path = 'ticket_activity';


     }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $module_path = $this->module_path;
        $activities=TicketActivity::all();
        $ticketid=Ticket::all();
        $arr['activities']=$activities;
        $arr['ticketid']=$ticketid;
         return view("ticket::backend.$module_path.index",$arr);    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('ticket::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        // Validate the request...
         $TicketActivity = new TicketActivity;
        $TicketActivity->ticketid = $request->ticketid;
        $TicketActivity->descriptions = $request->descriptions;


        $save = $TicketActivity->save();

        $ticket=Ticket::where('id', $request->ticketid)->first();
        $room= Room::where('id',$ticket->roomid)->first();
        $arr['roomid']=$room->code;
        $arr['desc']= $request->descriptions;
        $arr['type']= $request->type;
        $arr['usname']=Auth::user()->name;
        $arr['usid']=Auth::user()->id;
        $receptionusers = User::role('reception')->get();
        foreach( $receptionusers as  $receptionuser )
        $receptionuser->notify(new AddTicketActivits($arr));
        $superadminusers = User::role('super admin')->get();
        foreach( $superadminusers as  $superadminuser )
		$superadminuser->notify(new AddTicketActivits($arr));
        $maintenanceusers = User::role('maintenance')->get();
        foreach( $maintenanceusers as  $maintenanceuser )
		$maintenanceuser->notify(new AddTicketActivits($arr));
        $roomserviceusers = User::role('roomservice')->get();
        foreach( $roomserviceusers as  $roomserviceuser )
		$roomserviceuser->notify(new AddTicketActivits($arr));
		echo json_encode(array('response'=>$save, 'data'=>$TicketActivity));
		die();

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function showTicketLog($id)
    {
        $module_path = $this->module_path;
        $activities=TicketActivity::where('ticketid',$id)->get( );
        $ticket=Ticket::find($id);
        $arr['activities']=$activities;
        $arr['type']=$ticket->type;
        $arr['id']=$id;
         return view("ticket::backend.$module_path.show",$arr);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('ticket::edit');
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
    public function destroy( Request $request,$id)
    {


            $ticket = TicketActivity::find( $id );
            $delete = $ticket->delete();
           echo json_encode(array('response'=>$delete));
           die();


    }
}
