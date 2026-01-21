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

        'languages' => [
            'overview' => 'Languages Overview',
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

        'users' => [
            'edit' => 'Edit User',
            'delete' => 'Delete User',
            'overview' => 'Users Overview',
            'unlink' => 'Unlink User Account',
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
            'city' => 'City',
            'coc_number' => 'COC Number',
            'company_id' => 'Company ID',
            'country' => 'Country',
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

        'languages' => [
            'name' => 'Language Name',
        ],

        'permissions' => [
            'name' => 'Permission Name',
        ],

        'roles' => [
            'name' => 'Role Name',
            'permissions' => 'Permissions',
            'no_permissions_assigned' => 'No permissions assigned.',
        ],

        'users' => [
            'no_permissions_assigned' => 'No permissions assigned.',
            'languages' => 'Languages',
            'select_language' => 'Select Language',
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

        'languages' => [
            'create' => 'Create Language',
            'edit' => 'Edit Language',
        ],

        'permissions' => [
            'create' => 'Create Permission',
        ],
        
        'roles' => [
            'create' => 'Create Role',
            'assign_permission' => 'Assign Permission',
        ],

        'users' => [
            'unlink' => 'Unlink',
            'unlink_account' => 'Unlink Account',
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

        'languages' => [
            'delete_confirmation' => 'Are you sure you want to delete the language <b>:name</b>? This action cannot be undone.',
            'languages_no_records' => 'No languages found.',
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

        'users' => [
            'delete_confirmation' => 'Are you sure you want to delete the user <b>:name</b>? This action cannot be undone.',
            'unlink_confirmation' => 'Are you sure you want to unlink this user\'s account? This will reset their onboarding status, onboarding step and remove their account link.',
            'users_no_records' => 'No users found.',
        ],
    ],

    // Toasts
    'toast' => [
        'api' => [
            'unauthorized_error' => 'Unauthorized access to the API. Please check the API key configuration.',
        ],

        'company' => [
            'created' => 'Company created successfully.',
            'deleted' => 'Company deleted successfully.',
            'updated' => 'Company updated successfully.',

            'bank_account_added' => 'Bank account added to company successfully.',
            'bank_account_removed' => 'Bank account removed from company successfully.',
            'employee_assigned' => 'Employee assigned to company successfully.',
            'employee_already_assigned' => 'This employee is already assigned to the company.',
            'employee_removed' => 'Employee removed from company successfully.',
            'plot_removed' => 'Plot removed from company successfully.',

            'update_failed' => 'Failed to update company. Please try again.',
            'bank_account_add_failed' => 'Failed to add bank account to company. Please try again.',
            'employee_assign_failed' => 'Failed to assign employee to company. Please try again.',
            'plot_remove_failed' => 'Failed to remove plot from company. Please try again.',
        ],

        'languages' => [
            'created' => 'Language created successfully.',
            'deleted' => 'Language deleted successfully.',
            'updated' => 'Language updated successfully.',
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
        
        'users' => [
            'account_unlink_api_error' => 'There was an error contacting the account unlink API.',
            'account_unlink_missing_player_error' => 'Missing player UUID.',
            'account_unlinked' => 'User account unlinked successfully.',
            'deleted' => 'User deleted successfully.',
            'updated' => 'User updated successfully.',
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