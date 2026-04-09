<?php

namespace App\Helpers;

use Spatie\Permission\Models\Permission;

class SidebarMenuHelper
{
    /**
     * Get admin sidebar menu structure with permissions
     */
    public static function getAdminSidebarMenu()
    {
        return [
            'dashboard' => [
                'title' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'permissions' => [
                    'dashboard.view' => 'Can View Dashboard'
                ]
            ],
            'admin_settings' => [
                'title' => 'Admin Settings',
                'icon' => 'fas fa-user-shield',
                'permissions' => [
                    'role.view' => 'Manage Roles',
                    'role.assign' => 'Assign Permissions',
                    'assign-roles.index' => 'Assign Roles',
                    'activity.logs.view' => 'Activity Logs',
                    'admin.create' => 'Add Sub Admin'
                ]
            ],
            'staff_management' => [
                'title' => 'Staff Management',
                'icon' => 'fas fa-users',
                'permissions' => [
                    'staff.view' => 'Staff List',
                    'staff.create' => 'Add Staff',
                    'staff.permission.view' => 'Staff Permissions'
                ]
            ],
            'shop_management' => [
                'title' => 'Shop Management',
                'icon' => 'fas fa-store',
                'permissions' => [
                    'shop.view' => 'Shop List',
                    'shop.create' => 'Add Shop'
                ]
            ],
            'tasks_jobs' => [
                'title' => 'Tasks & Jobs',
                'icon' => 'fas fa-tasks',
                'permissions' => [
                    'task.create' => 'Create Task',
                    'task.view' => 'Assigned Tasks'
                ]
            ],
            'settings' => [
                'title' => 'Settings',
                'icon' => 'fas fa-cog',
                'permissions' => [
                    'permission.view' => 'Permissions Setup',
                    'setting.view' => 'System Settings'
                ]
            ]
        ];
    }

    /**
     * Get all permissions grouped by menu section
     */
    public static function getPermissionsByMenu()
    {
        $menuStructure = self::getAdminSidebarMenu();
        $permissionsByMenu = [];

        foreach ($menuStructure as $key => $menu) {
            $permissionsByMenu[$menu['title']] = [];
            
            foreach ($menu['permissions'] as $permissionName => $label) {
                // Check if permission exists in database
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $permissionsByMenu[$menu['title']][] = [
                        'name' => $permission->name,
                        'label' => $label,
                        'id' => $permission->id
                    ];
                }
            }
        }

        return $permissionsByMenu;
    }
}
