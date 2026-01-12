<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    */

    // Titles
    'titles' => [
        // Companies
        'companies_overview' => 'Companies Overview',
        'company_delete' => 'Delete Company',

        // Permissions
        'permissions_overview' => 'Permissions Overview',
        'permission_delete' => 'Delete Permission',

        // Roles
        'roles_overview' => 'Roles Overview',
        'role_edit' => 'Edit Role',
        'role_delete' => 'Delete Role',
        'selected_permissions' => 'Selected Permissions',
    ],

    // Labels
    'labels' => [
        // Companies
        'company_name' => 'Name',
        'company_world_id' => 'World ID',
        'company_coc_number' => 'COC No.',
        'company_owner' => 'Owner',
        'company_bank_accounts' => 'Bank Accounts',
        'company_employees' => 'Employees',
        'company_plots' => 'Plots',
        'company_pin_consoles' => 'PIN Consoles',

        // Permissions
        'permission_name' => 'Permission Name',

        // Roles
        'role_name' => 'Role Name',
        'role_permissions' => 'Permissions',
        'no_permissions_assigned' => 'No permissions assigned.',
    ],

    // Buttons
    'buttons' => [
        'assign' => 'Assign',

        // Companies
        'company_create' => 'Create Company',

        // Permissions
        'permission_create' => 'Create Permission',

        // Roles
        'role_create' => 'Create Role',
        'assign_permission' => 'Assign Permission',
    ],

    // Messages
    'messages' => [
        // Companies
        'company_delete_confirmation' => 'Are you sure you want to delete the company <b>:name</b>? This action cannot be undone.',
        'companies_no_records' => 'No companies found.',

        // Permissions
        'permissions_modal_delete_confirmation' => 'Are you sure you want to delete the permission <b>:name</b>? This action cannot be undone.',
        'permissions_no_records' => 'No permissions found.',

        // Roles
        'roles_modal_delete_confirmation' => 'Are you sure you want to delete the role <b>:name</b>? This action cannot be undone.',
        'roles_modal_delete_permission_confirmation' => 'Are you sure you want to remove the permission <b>:permission</b> from the role <b>:role</b>?',
        'roles_no_records' => 'No roles found.',
        'role_no_permissions_assigned' => 'No permissions assigned to this role.',
    ],

    // Toasts
    'toast' => [
        // Companies
        'company_created' => 'Company created successfully.',
        'company_updated' => 'Company updated successfully.',
        'company_deleted' => 'Company deleted successfully.',

        // Permissions
        'permission_created' => 'Permission created successfully.',
        'permission_updated' => 'Permission updated successfully.',
        'permission_deleted' => 'Permission deleted successfully.',

        // Roles
        'role_created' => 'Role created successfully.',
        'role_updated' => 'Role updated successfully.',
        'role_deleted' => 'Role deleted successfully.',
        'role_permission_added' => 'Permission added to role successfully.',
        'role_permission_removed' => 'Permission removed from role successfully.',
    ],

    // Placeholders
    'placeholders' => [
        // Permissions
        'permission_name' => 'permission_name',

        // Roles
        'role_name' => 'Moderator'
    ]

];