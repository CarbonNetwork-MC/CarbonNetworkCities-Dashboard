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
        'company' => [
            'add_bank_account' => 'Add Bank Account',
            'add_employee' => 'Add Employee',
            'add_pin_console' => 'Add PIN Console',
            'add_plot' => 'Add Plot',
            'bank_accounts' => 'Bank Accounts',
            'delete' => 'Delete Company',
            'edit' => 'Edit Company',
            'employees' => 'Employees',
            'overview' => 'Company Overview',
            'pin_consoles' => 'PIN Consoles',
            'plots' => 'Plots',
        ],

        'permissions' => [
            'overview' => 'Permisions Overview',
            'edit' => 'Edit Permission',
            'delete' => 'Delete Permission',
        ],

        'roles' => [
            'overview' => 'Roles Overview',
            'edit' => 'Edit Role',
            'delete' => 'Delete Role',
            'selected_permissions' => 'Selected Permissions',
        ],
    ],

    // Labels
    'labels' => [
        'player_uuid' => 'Player UUID',

        // Companies
        'company' => [
            'bank_accounts' => 'Bank Accounts',
            'bank_account_number' => 'Account No.',
            'bank_account_balance' => 'Balance',
            'bank_account_currency' => 'Currency',
            'bank_account_is_main' => 'Is Main Account',
            'coc_number' => 'COC Number',
            'employees' => 'Employees',
            'employee_name' => 'Employee Name',
            'employee_role' => 'Role',
            'name' => 'Name',
            'no_owner_assigned' => 'No owner assigned',
            'owner' => 'Owner',
            'pin_consoles' => 'PIN Consoles',
            'pin_console_account' => 'Account',
            'pin_console_id' => 'PIN Console ID',
            'pin_console_is_active' => 'Is Active',
            'pin_console_location' => 'Location',
            'plots' => 'Plots',
            'plot_id' => 'Plot ID',
            'plot_location' => 'Location',
            'plot_name' => 'Plot Name',
            'world_id' => 'World ID',
        ],

        'permissions' => [
            'name' => 'Permission Name',
        ],

        'roles' => [
            'name' => 'Role Name',
            'permissions' => 'Permissions',
            'no_permissions_assigned' => 'No permissions assigned.',
        ],
    ],

    // Buttons
    'buttons' => [
        'assign' => 'Assign',

        'company' => [
            'add_bank_account' => 'Add Bank Account',
            'add_employee' => 'Add Employee',
            'add_pin_console' => 'Add PIN Console',
            'add_plot' => 'Add Plot',
            'create' => 'Create Company',
            'delete' => 'Delete Company',
            'remove_bank_account' => 'Remove Bank Account',
            'remove_employee' => 'Remove Employee',
            'remove_pin_console' => 'Remove PIN Console',
            'remove_plot' => 'Remove Plot',
        ],

        'permissions' => [
            'create' => 'Create Permission',
        ],
        
        'roles' => [
            'create' => 'Create Role',
            'assign_permission' => 'Assign Permission',
        ],
    ],

    // Messages
    'messages' => [
        'company' => [
            'delete_confirmation' => 'Are you sure you want to delete the company <b>:name</b>? This action cannot be undone.',
            'remove_employee_confirmation' => 'Are you sure you want to remove the employee <b>:name</b> from this company?',
            'remove_bank_account_confirmation' => 'Are you sure you want to remove the bank account <b>:id</b> from this company?',
            'remove_pin_console_confirmation' => 'Are you sure you want to remove the PIN console <b>:id</b> from this company.',
            'remove_plot_confirmation' => 'Are you sure you want to remove the plot <b>:id</b> from this company?',
            
            'companies_no_records' => 'No companies found.',
            'employees_no_records' => 'No employees found for this company.',
            'bank_accounts_no_records' => 'No bank accounts found for this company.',
            'pin_consoles_no_records' => 'No PIN consoles found for this company.',
            'plots_no_records' => 'No plots found for this company.',
        ],

        'permission' => [
            'delete_confirmation' => 'Are you sure you want to delete the permission <b>:name</b>? This action cannot be undone.',
            'permissions_no_records' => 'No permissions found.',
        ],

        'roles' => [
            'delete_confirmation' => 'Are you sure you want to delete the role <b>:name</b>? This action cannot be undone.',
            'delete_permission_confirmation' => 'Are you sure you want to remove the permission <b>:permission</b> from the role <b>:role</b>?',
            'roles_no_records' => 'No roles found.',
            'no_permissions_assigned' => 'No permissions assigned to this role.',
        ],
    ],

    // Toasts
    'toast' => [
        'company' => [
            'created' => 'Company created successfully.',
            'deleted' => 'Company deleted successfully.',
            'updated' => 'Company updated successfully.',

            'bank_account_added' => 'Bank account added to company successfully.',
            'bank_account_removed' => 'Bank account removed from company successfully.',
            'employee_removed' => 'Employee removed from company successfully.',
        ],

        'permissions' => [
            'created' => 'Permission created successfully.',
            'deleted' => 'Permission deleted successfully.',
            'updated' => 'Permission updated successfully.',
        ],

        'roles' => [
            'created' => 'Role created successfully.',
            'deleted' => 'Role deleted successfully.',
            'updated' => 'Role updated successfully.',

            'permission_added' => 'Permission added to role successfully.',
            'permission_removed' => 'Permission removed from role successfully.',
        ],
    ],

    // Placeholders
    'placeholders' => [
        'company' => [
            'select_role' => 'Select Role',
        ],

        'permissions' => [
            'permission_name' => 'permission_name',
        ],

        'roles' => [
            'role_name' => 'Moderator',
        ],
    ]

];