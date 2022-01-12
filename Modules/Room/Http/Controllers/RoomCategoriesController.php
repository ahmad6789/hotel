<?php

namespace Modules\Room\Http\Controllers;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Room\Entities\Room;
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
use App\Notifications\AddRoomToCat;
use Illuminate\Support\Facades\Storage;
class RoomCategoriesController extends Controller
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Rooms';

        // module name
        $this->module_name = 'RoomCategory';
        // directory path of the module
        $this->module_path = 'RoomCategories';
        // module icon
        $this->module_icon = 'fas fa-university';

        // module model name, path
        $this->module_model = "Modules\Room\Entities\RoomCategory";
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

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



            $data = DB::table('room_categories')
            ->join('rooms', 'room_categories.id', '=', 'rooms.categoryid')
            ->select(DB::raw('count(rooms.categoryid) as NOR'), 'room_categories.name as name' ,'room_categories.id as id')
           ->groupBy('rooms.categoryid')->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn = '<a href="#" data-id="'.$row->id.'"
                        class="btn btn-danger btn-sm  delete" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i></a>
                       <a href="#" data-id="'.$row->id.'" class="btn btn-success btn-sm  edit"> <i class="fa fa-wrench"></i></a>
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


    public function create(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        return view("room::backend.$module_path.create");

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {  $RoomCategory = new RoomCategory;
        $samcat= $RoomCategory->where('name',$request->name)->get();
        if($samcat->isEmpty()){

        $RoomCategory->name = $request->name;
         $RoomCategory->status = $request->status;
        $save = $RoomCategory->save();

        $arr['cat']=$request->name;
        $arr['usname']=Auth::user()->name;
        $arr['usid']=Auth::user()->id;
        $receptionusers = User::role('reception')->get();
        foreach( $receptionusers as  $receptionuser )
        $receptionuser->notify(new AddRoomToCat($arr));
        $superadminusers = User::role('super admin')->get();
        foreach( $superadminusers as  $superadminuser )
		$superadminuser->notify(new AddRoomToCat($arr));
 		echo json_encode(array('response'=>$save, 'data'=>$RoomCategory));
		die();}
        else{

            echo json_encode(array('response'=>'similler RoomCategory exist'));
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
		$RoomCategory = RoomCategory::where('id', $id)->first();
        return view('RoomCategory::show');
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
        $RoomCategoryEdit = RoomCategory::where('id', $id)->first();
        $arr['RoomCategoryEdit']=$RoomCategoryEdit;

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
        $RoomCategory = RoomCategory::where('id',$request->id)->first();

        $RoomCategory->name = $request->name;
         $RoomCategory->status = $request->status;
        $save = $RoomCategory->save();

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
            $RoomCategory = RoomCategory::find( $id );
            $delete = $RoomCategory->delete();
           echo json_encode(array('response'=>$delete));
           die();
        }
    }
}
