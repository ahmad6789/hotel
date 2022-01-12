<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Room\Entities\Room;
use Modules\Item\Entities\Item;
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


class ItemController extends Controller
{

    public function __construct() 
    {
        // Page Title
        $this->module_title = 'Items';

        // module name
        $this->module_name = 'item';
        // directory path of the module
        $this->module_path = 'items';  
        // module icon
        $this->module_icon = 'fas fa-university';

        // module model name, path
        $this->module_model = "Modules\Item\Entities\Item";
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
            "item::backend.$module_path.index",
            compact('editNotify','module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
        );

      
        
      
    }
    
  
    public function gettable(Request $request){
      
        if ($request->ajax()) {
			$item = new Item;
          
            $data = $item->get();
     
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
    
        return view("item::backend.$module_path.create");

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Validate the request...
       
        $item = new Item;
        $sameitem=   $item->where('name',$request->name)->get();
        if($sameitem->isEmpty()){
        $item->name = $request->name;
        $item->description = $request->description;

        $save = $item->save();
		
		echo json_encode(array('response'=>$save, 'data'=>$item));
		die();}
        else{
	
            echo json_encode(array('response'=>'similler item exist'));
            die();

        }
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
        $itemEdit = Item::where('id', $id)->first();
        $arr['itemEdit']=$itemEdit;
        return view("item::backend.$module_path.create",$arr);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $item = Item::where('id',$request->id)->first();	
       
        $item->name = $request->name;
        $item->description = $request->description;
        $save = $item->save();
		
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
            $item = Item::find( $id );
            $delete = $item->delete();
           echo json_encode(array('response'=>$delete));
           die();
        }
    }
}
