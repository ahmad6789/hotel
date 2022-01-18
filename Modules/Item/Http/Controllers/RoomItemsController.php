<?php

namespace Modules\Item\Http\Controllers;

use App\Models\User;
use App\Notifications\AddItemsToRooms;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;
use Modules\Item\Entities\Item;
use Modules\Item\Entities\RoomItems;
use Modules\Room\Entities\Room;

class RoomItemsController extends Controller
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Items';

        // module name
        $this->module_name = 'item';
        // directory path of the module
        $this->module_path = 'roomItem';
        // module icon
        $this->module_icon = 'fas fa-university';

        // module model name, path
        $this->module_model = "Modules\Item\Entities\RoomItems";
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id=null)
    {

        $module_path = $this->module_path;
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = $module_model::paginate();

        Log::info(label_case($module_title.' '.$module_action).' | User:'.Auth::user()->name.'(ID:'.Auth::user()->id.')');


        $data = DB::table('items')
        ->join('room_items', 'items.id', '=', 'room_items.itemid')
        ->select('items.name as name', 'room_items.quantity as quantity', 'room_items.roomid as roomid', 'room_items.itemid as itemid')
        ->where('roomid', $id)
        ->get();
        $room=Room::find($id);
        $code=$room->code;
        return view(
            "item::backend.$module_path.index",
            compact('data','id', 'code', 'module_title', 'module_name', "$module_name", 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    public function getAvailableItems(Request $request, $id=null)
    {
        $module_path = $this->module_path;
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $Items= DB::table('room_items')
            ->select('itemid')
            ->where('roomid', $id)
            ->get();
        $roomitems=[];
        foreach ($Items as $item) {
            $roomitems[]=$item->itemid;
        }
        $items=DB::table('items')
            ->select('name', 'id')
            ->whereNotIn('id', $roomitems)
            ->get();
        echo json_encode($items);
        die();
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

        $RoomItems = new RoomItems;
        $sameRoomItems=   $RoomItems->where('name', $request->name)->get();
        if ($sameRoomItems->isEmpty()) {
            $RoomItems->name = $request->name;
            $RoomItems->description = $request->description;

            $save = $RoomItems->save();


            echo json_encode(['response'=>$save, 'data'=>$RoomItems]);
            die();
        } else {
            echo json_encode(['response'=>'similler item exist']);
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
        return view('item::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('item::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $items = $request->only(['nameid', 'quantity','roomid']);


        $nameid=$items['nameid'];
        $quantity=$items['quantity'];
        $roomid=$items['roomid'];
        //  $dbrow->roomid = $items['roomid'];
        // $count = 0;
        // foreach ($items['nameid'] as $value ) {
        //     $dbrow->itemid = $items['nameid'][$count];
        //     $dbrow->quantity = $items['quantity'][$count];
        //     $save = $dbrow->save();
        //     $count++;
        //     var_dump($dbrow);
        //     echo '<br>';
        for ($i=0; $i<sizeof($quantity); $i++) {
            $item = RoomItems::where('roomid', $roomid)->where('itemid', $nameid[$i])->get();
            if ($item->isEmpty()) {
                if ($quantity[$i]<=0) {
                    continue;
                }
                DB::table('room_items')->insert(['quantity'=>  $quantity[$i],'roomid'=>$roomid,'itemid'=>$nameid[$i]]);
                $room=Room::where('id', $roomid[$i])->first();
                $item=Item::where('id', $nameid[$i])->first();
                $arr['itemname']=$item->name;
                $arr['roomid']=$room->code;
                $arr['quantity']=$quantity[$i];

                $arr['usname']=Auth::user()->name;
                $arr['usid']=Auth::user()->id;
                $receptionusers = User::role('reception')->get();
                foreach ($receptionusers as  $receptionuser) {
                    $receptionuser->notify(new AddItemsToRooms($arr));
                }
                $superadminusers = User::role('super admin')->get();
                foreach ($superadminusers as  $superadminuser) {
                    $superadminuser->notify(new AddItemsToRooms($arr));
                }
            } else {
                if ($quantity[$i]<=0) {
                    DB::table('room_items')->where('roomid', $roomid)->where('itemid', $nameid[$i])->delete();
                } else {
                    DB::table('room_items')->where('roomid', $roomid)->where('itemid', $nameid[$i])->update(['quantity'=> $quantity[$i]]);
                }
            }
        }


        // $save =  DB::table('room_items')->whereIn('itemid', $value->itemid)->update($value->quantity);
        // }

        echo json_encode(['response'=>true]);
        die();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = explode("-", $request->itemRoomid);


        $roomid=$id[0];
        $itemid=$id[1];
        $delete =DB::table('room_items')->where('itemid', $itemid)->where('roomid', $roomid)->delete();

        echo json_encode(['response'=>$delete]);
        die();
    }
}
