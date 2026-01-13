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

        // Languages
        'languages_overview' => 'Languages Overview',
        'language_delete' => 'Delete Language',
    ],

    // Labels
    'labels' => [
        // Permissions
        'permission_name' => 'Permission Name',

        // Roles
        'role_name' => 'Role Name',
        'role_permissions' => 'Permissions',
        'no_permissions_assigned' => 'No permissions assigned.',

        // Languages
        'language_name' => 'Language Name',
        'language_short_code' => 'Short Code',
        'language_code' => 'Language Code',
    ],

    // Buttons
    'buttons' => [
        'assign' => 'Assign',

        // Permissions
        'permission_create' => 'Create Permission',

        // Roles
        'role_create' => 'Create Role',
        'assign_permission' => 'Assign Permission',

        // Languages
        'language_create' => 'Create Language',
        'language_edit' => 'Edit Language',
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

        // Languages
        'languages_no_records' => 'No languages found.',
        'languages_modal_delete_confirmation' => 'Are you sure you want to delete the language <b>:name</b>? This action cannot be undone.',
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

        // Languages
        'language_updated' => 'Language updated successfully.',
        'language_created' => 'Language created successfully.',
        'language_deleted' => 'Language deleted successfully.',
    ],

    // Placeholders
    'placeholders' => [
        // Permissions
        'permission_name' => 'permission_name',

        // Roles
        'role_name' => 'Moderator'
    ]

];