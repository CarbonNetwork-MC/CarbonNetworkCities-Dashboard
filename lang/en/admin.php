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
        'company_edit' => 'Edit Company',
        'company_delete' => 'Delete Company',
        'employees' => 'Employees',
        'bank_accounts' => 'Bank Accounts',
        'plots' => 'Plots',
        'pin_consoles' => 'PIN Consoles',

        'add_employee' => 'Add Employee',

        // Permissions
        'permissions_overview' => 'Permissions Overview',
        'permission_edit' => 'Edit Permission',
        'permission_delete' => 'Delete Permission',

        // Roles
        'roles_overview' => 'Roles Overview',
        'role_edit' => 'Edit Role',
        'role_delete' => 'Delete Role',
        'selected_permissions' => 'Selected Permissions',
    ],

    // Labels
    'labels' => [
        'player_uuid' => 'Player UUID',

        // Companies
        'company_name' => 'Name',
        'company_world_id' => 'World ID',
        'company_coc_number' => 'COC No.',
        'company_owner' => 'Owner',
        'company_bank_accounts' => 'Bank Accounts',
        'company_employees' => 'Employees',
        'company_plots' => 'Plots',
        'company_pin_consoles' => 'PIN Consoles',
        'no_owner_assigned' => 'No owner assigned',

        'employee_name' => 'Employee Name',
        'employee_role' => 'Role',

        'bank_account_number' => 'Account No.',
        'bank_account_balance' => 'Balance',
        'bank_account_is_main' => 'Is Main Account',
        'bank_account_currency' => 'Currency',

        'plot_name' => 'Plot Name',
        'plot_id' => 'Plot ID',
        'plot_location' => 'Location',

        'pin_console_id' => 'PIN Console ID',
        'pin_console_account' => 'Account',
        'pin_console_location' => 'Location',
        'pin_console_is_active' => 'Is Active',

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
        'add_employee' => 'Add Employee',
        'add_bank_account' => 'Add Bank Account',
        'add_plot' => 'Add Plot',
        'add_pin_console' => 'Add PIN Console',
        'delete_company' => 'Delete Company',
        'remove_employee' => 'Remove Employee',

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
        'employees_no_records' => 'No employees found for this company.',
        'bank_accounts_no_records' => 'No bank accounts found for this company.',
        'plots_no_records' => 'No plots found for this company.',
        'pin_consoles_no_records' => 'No PIN consoles found for this company.',
        'company_remove_employee_confirmation' => 'Are you sure you want to remove the employee <b>:name</b> from this company?',

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

        'company_employee_removed' => 'Employee removed from company successfully.',
        'company_bank_account_removed' => 'Bank account removed from company successfully.',

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
        // Companies
        'select_role' => 'Select Role',

        // Permissions
        'permission_name' => 'permission_name',

        // Roles
        'role_name' => 'Moderator'
    ]

];