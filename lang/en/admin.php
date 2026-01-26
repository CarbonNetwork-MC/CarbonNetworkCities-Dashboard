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
            'permissions' => 'User Permissions',
            'roles' => 'User Roles',
            'unlink' => 'Unlink User Account',
        ],

        'itemsmenu' => [
            'edit_category' => 'Edit Item Category',
            'edit_item' => 'Edit Item',
            'create_category' => 'Create Item Category',
            'create_item' => 'Create Item',
            'categories_overview' => 'Item Categories Overview',
            'items_overview' => 'Items Overview',
            'delete_category' => 'Delete Item Category',
            'delete_item' => 'Delete Item',
        ],

        'countries' => [
            'overview' => 'Countries Overview',
            'delete' => 'Delete Country',
            'edit' => 'Edit Country',
        ],

        'city_regions' => [
            'edit' => 'Edit City Region',
            'overview' => 'City Regions Overview',
            'delete' => 'Delete City Region',
        ]
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
            'selected_language' => 'Selected Language',
        ],

        'itemsmenu' => [
            'category_name' => 'Category Name',
            'icon_material' => 'Icon Material',
            'internal_id' => 'Internal ID',
            'item_name' => 'Item Name',
            'category' => 'Category',
            'display_name' => 'Display Name',
            'lore_line' => 'Lore Line :number',
            'shelf_life' => 'Shelf Life (in days)',
            'items_amount' => 'Items Amount',
            'player_username' => 'Player Username',
            'user_name' => 'User Name',
            'change_category' => 'Change category of items to',
            'select_category' => 'Select Category',
            'select_expired_prefix' => 'Select Expired Prefix',
        ],

        'countries' => [
            'name' => 'Country Name',
            'currency' => 'Currency',
            'currency_symbol' => 'Currency Symbol',
            'currency_before_amount' => 'Currency Before Amount',
        ],

        'city_regions' => [
            'internal_name' => 'Internal Name',
            'display_name' => 'Display Name',
            'country' => 'Country',
            'city' => 'City',
            'world_id' => 'World ID',
        ]
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
            'assign_role' => 'Assign Role',
            'assign_permission' => 'Assign Permission',
        ],

        'itemsmenu' => [
            'add_lore_line' => 'Add Lore Line',
            'create_category' => 'Create Category',
            'create_item' => 'Create Item',
        ],

        'countries' => [
            'create' => 'Create Country',
        ],

        'city_regions' => [
            'create' => 'Create City Region',
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

        'permissions' => [
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
            'delete_permission_confirmation' => 'Are you sure you want to remove the permission <b>:permission</b> from this user?',
            'delete_role_confirmation' => 'Are you sure you want to remove the role <b>:role</b> from this user?',
            'no_roles_assigned' => 'No roles assigned to this user.',
            'no_permissions_assigned' => 'No permissions assigned to this user.',
            'unlink_confirmation' => 'Are you sure you want to unlink this user\'s account? This will reset their onboarding status, onboarding step and remove their account link.',
            'users_no_records' => 'No users found.',
        ],

        'itemsmenu' => [
            'categories_no_records' => 'No item categories found.',
            'items_no_records' => 'No items found.',
            'category_delete_confirmation_with_items' => 'Are you sure you want to delete the item category <b>:name</b>? This action cannot be undone. You can choose to move its items to another category below.',
            'category_delete_confirmation_without_items' => 'Are you sure you want to delete the item category <b>:name</b>? This action cannot be undone.',
            'item_delete_confirmation' => 'Are you sure you want to delete the item <b>:name</b>? This action cannot be undone.',
        ],
        
        'countries' => [
            'countries_no_records' => 'No countries found.',
            'delete_confirmation' => 'Are you sure you want to delete the country <b>:name</b>? This action cannot be undone.',
        ],

        'city_regions' => [
            'city_regions_no_records' => 'No city regions found.',
            'delete_confirmation' => 'Are you sure you want to delete the city region <b>:name</b>? This action cannot be undone.',
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
            'reload_api_error' => 'There was an error reloading languages via the API. Changes have been reverted.',
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
            'permission_assigned' => 'Permission assigned to user successfully.',
            'permission_removed' => 'Permission removed from user successfully.',
            'role_assigned' => 'Role assigned to user successfully.',
            'role_removed' => 'Role removed from user successfully.',
            'updated' => 'User updated successfully.',
        ],

        'itemsmenu' => [
            'reload_items_api_error' => 'There was an error reloading items via the API. Changes have been reverted.',
            'category_updated' => 'Item category updated successfully.',
            'item_updated' => 'Item updated successfully.',
            'category_created' => 'Item category created successfully.',
            'item_created' => 'Item created successfully.',
            'category_deleted' => 'Item category deleted successfully.',
            'item_deleted' => 'Item deleted successfully.',
        ],

        'countries' => [
            'reload_countries_api_error' => 'There was an error reloading countries via the API. Changes have been reverted.',
            'updated' => 'Country updated successfully.',
            'deleted' => 'Country deleted successfully.',
            'created' => 'Country created successfully.',
        ],

        'city_regions' => [
            'reload_regions_api_error' => 'There was an error reloading city regions via the API. Changes have been reverted.',
            'updated' => 'City region updated successfully.',
            'created' => 'City region created successfully.',
            'deleted' => 'City region deleted successfully.',
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