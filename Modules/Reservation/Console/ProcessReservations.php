<?php

namespace Modules\Reservation\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

use Modules\Room\Entities\Room;
use Modules\Room\Entities\Bed;
use Modules\Reservation\Entities\Customer;
use Modules\Reservation\Entities\Reservation;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentLine;
use Log;

class ProcessReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process reservations and rooms statuses relevant to the current date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		// Set the status of rooms that are booked to 1
        $now = date('Y-m-d', time());

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
			->select(DB::raw('rooms.id as roomid'),'rooms.code as code','rooms.status as roomstatus', 'reservation.*', DB::raw('concat(customer.firstname, " ", customer.lastname) as customer'))
			->where('reservation.bookingend', '>' , $now)
			->where('reservation.bookingstart', '<=' , $now)
			->where('rooms.status', 0)
			->groupBy('rooms.code');
			
		$data = $rooms->get();
		foreach($data as $room){
			Room::where('id', $room->roomid)->update(array('status' => 1));
		}
		
		// Set the status of rooms that are no longer occupied bnut also not checked out to 2
		$now = date('Y-m-d', time());

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
			->select(DB::raw('rooms.id as roomid'),'rooms.code as code','rooms.status as roomstatus', 'reservation.*', DB::raw('concat(customer.firstname, " ", customer.lastname) as customer'))
			->where('reservation.bookingend', '<' , $now)
			->where('rooms.status', 1)
			->groupBy('rooms.code');
			
		$data = $rooms->get();
		
		foreach($data as $room){
			Room::where('id', $room->roomid)->update(array('status' => 2));
		}
		error_log("done processing rooms");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
