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

        // Users
        'users_overview' => 'Users Overview',
        'users_modal_delete' => 'Delete User',
        'users_modal_unlink' => 'Unlink User Account',
        'user_edit' => 'Edit User',
    ],

    // Labels
    'labels' => [
        // Permissions
        'permission_name' => 'Permission Name',

        // Roles
        'role_name' => 'Role Name',
        'role_permissions' => 'Permissions',

        // Users
        'languages' => 'Languages',
        'selected_language' => 'Selected Language',
        'no_permissions_assigned' => 'No permissions assigned.',
    ],

    // Buttons
    'buttons' => [
        'assign' => 'Assign',

        // Permissions
        'permission_create' => 'Create Permission',

        // Roles
        'role_create' => 'Create Role',
        'assign_permission' => 'Assign Permission',

        // Users
        'unlink' => 'Unlink',
        'unlink_account' => 'Unlink Account',
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

        // Users
        'users_modal_delete_confirmation' => 'Are you sure you want to delete the user <b>:name</b>? This action cannot be undone.',
        'users_modal_unlink_confirmation' => 'Are you sure you want to unlink this user\'s account? This will reset their onboarding status, onboarding step and remove their account link.',
        'users_no_users' => 'No users found.',
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

        // Users
        'account_unlinked' => 'User account unlinked successfully.',
        'user_deleted' => 'User deleted successfully.',
        'user_updated' => 'User updated successfully.',
        'account_unlink_missing_player_error' => 'Missing player UUID.',
        'account_unlink_api_error' => 'There was an error contacting the account unlink API.',

        // API
        'api_unauthorized_error' => 'Unauthorized access to the API. Please check the API key configuration.',
    ],

    // Placeholders
    'placeholders' => [
        // Permissions
        'permission_name' => 'permission_name',

        // Roles
        'role_name' => 'Moderator'
    ],
];