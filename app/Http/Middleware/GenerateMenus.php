<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */

    public function handle($request, Closure $next)
    {

        /* package for laravel menue
            * https://github.com/lavary/laravel-menu#installation
        */

        \Menu::make('admin_sidebar', function ($menu) {
            // Dashboard 
            $menu->add('<i class="cil-speedometer c-sidebar-nav-icon"></i> ' . trans('oa_menues.backend.sidebar.dashboard') , [
                'route' => 'backend.dashboard',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([ 
                'order'         => 1,
                'activematches' => 'admin/dashboard*',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            // Rooms menu
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-room"></i> ' . trans('Room.The Rooms'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 10,
                'activematches' => 'admin/rooms*',
                'permission'    => ['view_rooms'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

            // Submenu: Add
            $accessControl->add('<i class="c-sidebar-nav-icon cil-screen-desktop"></i> ' . trans('Room.View') . ' '.trans('Room.The Rooms'), [
                'route' => 'room.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 11,
                'activematches' => 'admin/rooms*',
                'permission'    => ['view_rooms'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
             // Submenu: View
             $accessControl->add('<i class="c-sidebar-nav-icon cil-medical-cross"></i> ' . trans('Room.Add') . ' '.trans('Room.The Rooms'), [
                'route' => 'room.create',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 12,
                'activematches' => 'admin/rooms*',
                'permission'    => ['add_room'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
               // Submenu: View cat
               $accessControl->add('<i class="c-sidebar-nav-icon cil-spreadsheet"></i> ' . trans('Room.View') . ' '.trans('Room.The Room Categories'), [
                'route' => 'RoomCategory.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 13,
                'activematches' => 'admin/rooms*',
                'permission'    => ['view_categories'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
               // Submenu: add cat
               $accessControl->add('<i class="c-sidebar-nav-icon cil-playlist-add"></i> ' . trans('Room.Add') . ' '.trans('Room.Category'), [
                'route' => 'RoomCategory.create',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 14,
                'activematches' => 'admin/rooms*',
                'permission'    => ['add_category'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Item manu
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-coffee"></i> ' . trans('Items.The Items'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 20,
                'activematches' => 'admin/item*',
                'permission'    => ['view_items'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

          
             // Submenu: Manage Items
             $accessControl->add('<i class="c-sidebar-nav-icon cil-american-football"></i> ' . trans('Room.Manage') . ' '.trans('Items.The Items'), [
                'route' => 'item.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 21,
                'activematches' => 'admin/item*',
                'permission'    => ['view_items'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            
             // Submenu: Add Items
             $accessControl->add('<i class="c-sidebar-nav-icon cil-menu"></i> ' . trans('Items.Add') . ' '.trans('Items.Item'), [
                'route' => 'item.create',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 22,
                'activematches' => 'admin/item*',
                'permission'    => ['add_item'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			////////
			// Reservations menu
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-book"></i> ' . trans('reservation.reservations'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 30,
                'activematches' => 'admin/reservation*',
                'permission'    => ['view_reservations'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

          
             // Submenu: Manage reservations
             $accessControl->add('<i class="c-sidebar-nav-icon cil-book"></i> ' . trans('reservation.reservationmanagement'), [
                'route' => 'reservation.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 31,
                'activematches' => 'admin/reservation*',
                'permission'    => ['view_reservations'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            
             // Submenu: Add reservation
             $accessControl->add('<i class="c-sidebar-nav-icon fa fas fa-plus"></i> ' . trans('reservation.create'), [
                'route' => 'reservation.create',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 32,
                'activematches' => 'admin/reservation*',
                'permission'    => ['add_reservation'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			// Submenu: Room status
             $accessControl->add('<i class="c-sidebar-nav-icon fa fas fa-eye"></i> ' . trans('reservation.roomstatus'), [
                'route' => 'reservation.indexrooms',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 33,
                'activematches' => 'admin/reservation*',
                'permission'    => ['view_rooms'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			// Submenu: customers
             $accessControl->add('<i class="c-sidebar-nav-icon fa fas fa-users"></i> ' . trans('reservation.customers'), [
                'route' => 'customer.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 33,
                'activematches' => 'admin/reservation*',
                'permission'    => ['view_rooms'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			/////////////////////
			// Payments menu
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-money"></i> ' . trans('payment.payments'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 40,
                'activematches' => 'admin/payment*',
                'permission'    => ['view_payments'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

          
             // Submenu: Manage payments
             $accessControl->add('<i class="c-sidebar-nav-icon cil-list"></i> ' . trans('payment.payments'), [
                'route' => 'payment.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 41,
                'activematches' => 'admin/payment*',
                'permission'    => ['view_payments'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            
			
            // Ticket menu
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon fa fa-tasks"></i> ' . trans('Ticket.The Tickets'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 50,
                'activematches' => 'admin/tickets*',
                'permission'    => ['view_tickets'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
        ]);
            // Submenu: view ticket
            $accessControl->add('<i class="c-sidebar-nav-icon cil-menu"></i> ' . trans('Room.View') . ' '.trans('Ticket.The Tickets'), [
                'route' => 'ticket.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 51,
                'activematches' => 'admin/tickets*',
                'permission'    => ['view_tickets'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            
                
             // Submenu: Add ticket
             $accessControl->add('<i class="c-sidebar-nav-icon fa fa-plus-square"></i> ' . trans('Ticket.Add') . ' '.trans('Ticket.Ticket'), [
                'route' => 'ticket.create',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 52,
                'activematches' => 'admin/tickets*',
                'permission'    => ['add_tickets'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
                
             // Submenu: view log
             $accessControl->add('<i class="c-sidebar-nav-icon fa fa-table"></i> ' . trans('Room.View') . ' '.trans('Items.Log'), [
                'route' => 'ticketsActivities.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 53,
                'activematches' => 'admin/ticketsActivities*',
                'permission'    => ['view_ticketlog'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			/////////////////////
			// Expenses menu
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-money"></i> ' . trans('expense.expenses'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 60,
                'activematches' => 'admin/expense*',
                'permission'    => ['view_expenses'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

          
             // Submenu: Manage payments
             $accessControl->add('<i class="c-sidebar-nav-icon cil-list"></i> ' . trans('expense.expenses'), [
                'route' => 'expense.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 41,
                'activematches' => 'admin/expense*',
                'permission'    => ['view_expenses'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			 $accessControl->add('<i class="c-sidebar-nav-icon cil-list"></i> ' . trans('expense.addexpense'), [
                'route' => 'expense.create',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 41,
                'activematches' => 'admin/expense*',
                'permission'    => ['add_expense'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			
			/////////////////////
			// Reports menu
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-money"></i> ' . trans('payment.reports'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 60,
                'activematches' => 'admin/expense*',
                'permission'    => ['view_reports'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

          
             // Submenu: Manage payments
             $accessControl->add('<i class="c-sidebar-nav-icon cil-list"></i> ' . trans('payment.profitloss'), [
                'route' => 'report.profitloss',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 41,
                'activematches' => 'admin/report*',
                'permission'    => ['view_expenses'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			 $accessControl->add('<i class="c-sidebar-nav-icon cil-list"></i> ' . trans('payment.expenses'), [
                'route' => 'report.expenses',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 42,
                'activematches' => 'admin/report*',
                'permission'    => ['add_expense'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
			
			
            // Notifications
            $menu->add('<i class="c-sidebar-nav-icon fas fa-bell"></i>' . trans('oa_menues.backend.sidebar.notifications') , [
                'route' => 'backend.notifications.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order'         => 99,
                'activematches' => 'admin/notifications*',
                'permission'    => [],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Separator: Access Management
            $menu->add(trans('oa_menues.backend.sidebar.management'), [
                'class' => 'c-sidebar-nav-title',
            ])
            ->data([
                'order'         => 110,
                'permission'    => ['edit_settings', 'view_backups', 'view_users', 'view_roles', 'view_logs'],
            ]);
         
                 // 
            // Access Control Dropdown
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-shield-alt"></i> ' . trans('oa_menues.backend.sidebar.access_control'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 113,
                'activematches' => [
                    'admin/users*',
                    'admin/roles*',
                ],
                'permission'    => ['view_users', 'view_roles'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

            // Submenu: Users
            $accessControl->add('<i class="c-sidebar-nav-icon cil-people"></i> ' . trans('oa_menues.backend.sidebar.users'), [
                'route' => 'backend.users.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 114,
                'activematches' => 'admin/users*',
                'permission'    => ['view_users'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Roles
            $accessControl->add('<i class="c-sidebar-nav-icon cil-people"></i> ' . trans('oa_menues.backend.sidebar.roles'), [
                'route' => 'backend.roles.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 115,
                'activematches' => 'admin/roles*',
                'permission'    => ['view_roles'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Log Viewer
            // Log Viewer Dropdown
            $accessControl = $menu->add('<i class="c-sidebar-nav-icon cil-list-rich"></i> ' . trans('oa_menues.backend.sidebar.log_viewer'), [
                'class' => 'c-sidebar-nav-dropdown',
            ])
            ->data([
                'order'         => 116,
                'activematches' => [
                    'log-viewer*',
                ],
                'permission'    => ['view_logs'],
            ]);
            $accessControl->link->attr([
                'class' => 'c-sidebar-nav-dropdown-toggle',
                'href'  => '#',
            ]);

            // Submenu: Log Viewer Dashboard
            $accessControl->add('<i class="c-sidebar-nav-icon cil-list"></i> ' . trans('oa_menues.backend.sidebar.dashboard'), [
                'route' => 'log-viewer::dashboard',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 117,
                'activematches' => 'admin/log-viewer',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            // Submenu: Log Viewer Logs by Days
            $accessControl->add('<i class="c-sidebar-nav-icon cil-list-numbered"></i>' . trans('oa_menues.backend.sidebar.logs_by_days'), [
                'route' => 'log-viewer::logs.list',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 118,
                'activematches' => 'admin/log-viewer/logs*',
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);

            /* Access Permission Check
            * Filtering the Items
            * https://github.com/lavary/laravel-menu#filtering-the-items
            */
            $menu->filter(function ($item) {
                if ($item->data('permission')) {
                    if (auth()->check()) {
                        if (auth()->user()->hasRole('super admin')) {
                            return true;
                        } elseif (auth()->user()->hasAnyPermission($item->data('permission'))) {
                            return true;
                        }
                    }

                    return false;
                } else {
                    return true;
                }
            });

            /* Set Active Menu
            * Filtering the Items
            * https://github.com/lavary/laravel-menu#filtering-the-items
            */
            $menu->filter(function ($item) {
                if ($item->activematches) {
                    $matches = is_array($item->activematches) ? $item->activematches : [$item->activematches];

                    foreach ($matches as $pattern) {
                        if (Str::is($pattern, \Request::path())) {
                            $item->activate();
                            $item->active();
                            if ($item->hasParent()) {
                                $item->parent()->activate();
                                $item->parent()->active();
                            }
                            // dd($pattern);
                        }
                    }
                }

                return true;
            });
        })->sortBy('order');

        return $next($request);
    }
}
