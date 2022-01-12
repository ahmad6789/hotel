<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    /**
     * Default Permissions of the Application.
     */
    public static function defaultPermissions()
    {  
        return [
            'view_users',
            'add_users',
            'edit_users',
            'delete_users',
            'restore_users',
            'block_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',
            'restore_roles',

            'view_backups',
            'add_backups',
            'create_backups',
            'download_backups',
            'delete_backups',

            'view_rooms',
            'add_room',
            'add_category',
            'view_categories',
            'view_items',
            'add_item',
            'view_expenses',
            'add_expense',
            'room_reservations',
            'change_room_status',
            'view_reservations',
            'view_tickets',
            'view_ticketlog',
            'add_ticket',
            'edit_assigned_to',
            'view_assigned_to',
			
			'add_reservation',
			'view_reservations',
			'add_payment',
			'view_payments',
			'add_customer',
			'view_customers',
			
			

        ];
    }

    /**
     * Name should be lowercase.
     *
     * @param string $value Name value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
}
