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
        // Permissions
        'permission_name' => 'Permission Name',

        // Roles
        'role_name' => 'Role Name',
        'role_permissions' => 'Permissions',
    ],

    // Buttons
    'buttons' => [
        'assign' => 'Assign',

        // Permissions
        'permission_create' => 'Create Permission',

        // Roles
        'role_create' => 'Create Role',
        'assign_permission' => 'Assign Permission',
    ],

    // Messages
    'messages' => [
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

        // API
        'api_unauthorized_error' => 'Unauthorized access to the API. Please check the API key configuration.',
    ],

    // Placeholders
    'placeholders' => [
        // Permissions
        'permission_name' => 'manage_users',

        // Roles
        'role_name' => 'Moderator'
    ]

    // User - Titles
    'users_overview_title' => 'Users Overview',
    'users_modal_edit_title' => 'Edit User',
    'users_modal_delete_title' => 'Delete User',
    'users_modal_unlink_title' => 'Unlink User Account',

    // Users - Messages
    'users_modal_delete_confirmation' => 'Are you sure you want to delete the user <b>:name</b>? This action cannot be undone.',
    'users_modal_unlink_confirmation' => 'Are you sure you want to unlink this user\'s account? This will reset their onboarding status, onboarding step and remove their account link.',
    'users_no_users' => 'No users found.',

    // Users - Labels
    'users_modal_name_label' => 'Name',
    'users_modal_selected_language_label' => 'Selected Language',
    'users_modal_language_select_placeholder' => 'Select Language',

    // Users - Buttons
    'users_unlink_button' => 'Unlink Account',
];