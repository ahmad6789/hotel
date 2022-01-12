<?php

namespace Modules\Room\Http\Controllers;
use App\Notifications\AddRooms;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Room\Entities\Room;
use App\Models\User;
use Modules\Room\Entities\Bed;
use Illuminate\Support\Facades\DB;
use Modules\Room\Entities\RoomCategory;
use Illuminate\Support\Str;
use Log;
use DataTables;

use App\Authorizable;

use Auth;
use Carbon\Carbon;
use Flash;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Rooms';

        // module name
        $this->module_name = 'room';
        // directory path of the module
        $this->module_path = 'rooms';
        // module icon
        $this->module_icon = 'fas fa-university';

        // module model name, path
        $this->module_model = "Modules\Room\Entities\Room";
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
            "room::backend.$module_path.index",
            compact('editNotify','module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
        );
    }



    public function gettable(Request $request){
        if ($request->ajax()) {
			$room = new Room;

            $data = $room->get();

            return Datatables::of($data)
                    ->addIndexColumn()
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
                        $btn = '<a href="#" data-id="'.$row->id.'"
                         class="btn btn-danger btn-sm  delete" data-toggle="modal" title="حذف الغرفة"data-target="#myModal"><i class="fa fa-trash"></i></a>
                        <a href="#" data-id="'.$row->id.'" class="btn btn-success btn-sm  edit"title="تعديل الغرفة"> <i class="fa fa-wrench"></i></a>
                        <a href="#" data-id="'.$row->id.'" class="btn btn-info btn-sm  add "title="عرض محتويات الغرفة"> <i class="fa fa-book"></i> </a>
                        ';
                        }
                        else if($perm=="roomservice"){
                            $btn ='<a href="#" data-id="'.$row->id.'" class="btn btn-info btn-sm  add "title="عرض محتويات الغرفة"> <i class="fa fa-book"></i> </a>
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
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $categories=RoomCategory::all();
        $arr['categories']=$categories;
        return view("room::backend.$module_path.create",$arr);

        }
    /**
         * add item To Room
         * @param int $id
         * @return Renderable
         */
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Validate the request...

        $room = new Room;
        $sameroom=   $room->where('code',$request->code)->get();
        if($sameroom->isEmpty()){
        $room->code = $request->code;
        $room->capacity = $request->capacity;
        $room->price = $request->price;
        $room->categoryid = $request->categoryid;
        $room->status = $request->status;

        $save = $room->save();
        $arr['roomid']=$room->code;
        $arr['usname']=Auth::user()->name;
        $arr['usid']=Auth::user()->id;
        $categories=RoomCategory::find($room->categoryid);
        $arr['cat']=$categories->name;
        $receptionusers = User::role('reception')->get();
        foreach( $receptionusers as  $receptionuser )
        $receptionuser->notify(new AddRooms($arr));
        $superadminusers = User::role('super admin')->get();
        foreach( $superadminusers as  $superadminuser )
		$superadminuser->notify(new AddRooms($arr));
		echo json_encode(array('response'=>$save, 'data'=>$room));
		die();}
        else{

            echo json_encode(array('response'=>'similler room exist'));
            die();

        }
    }

    public function getNotify()
    {
        $noti='';
        $notifications = optional(auth()->user())->unreadNotifications;
        $notifications_count = optional($notifications)->count();
        $notifications_latest = optional($notifications)->take(5);

        foreach ($notifications_latest as $notification){

                $notification_text = $notification->data['title'];
                $url = route('backend.notifications.show', $notification);
                $noti=$noti."<a class='dropdown-item' href='$url'> $notification_text </a>".'';
        }


        $data=array('notifications_count'=>$notifications_count,'noti'=>$noti);

        echo json_encode($data);
		die();
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
		$room = Room::where('id', $id)->first();
        return view('room::show',['data'=>$room]);
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
        $roomEdit = Room::where('id', $id)->first();
        $arr['roomEdit']=$roomEdit;
        $categories=RoomCategory::all();
        $arr['categories']=$categories;
        return view("room::backend.$module_path.create",$arr);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $room = Room::where('id',$request->id)->first();
        $room->capacity = $request->capacity;
        $room->price = $request->price;
        $room->categoryid = $request->categoryid;
        $room->status = $request->status;
        $save = $room->save();

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
            $room = Room::find( $id );
            $delete = $room->delete();
           echo json_encode(array('response'=>$delete));
           die();
        }
    }
}
