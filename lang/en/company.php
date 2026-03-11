<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company Language Lines - English
    |--------------------------------------------------------------------------
    |
    */

    'titles' => [
        'bank_accounts_overview' => 'Bank Accounts Overview',
        'best_selling_products' => 'Best Selling Products',
        'choose_bank_account' => 'Choose Bank Account',
        'create_sale' => 'Create Sale',
        'delete_sale' => 'Delete Sale',
        'edit_employee' => 'Edit Employee',
        'edit_stock' => 'Edit Stock',
        'employees' => 'Employees',
        'employee_overview' => 'Employee Overview',
        'inventory' => 'Inventory',
        'item_calculator' => 'Item Calculator',
        'notifications' => 'Notifications',
        'order_details' => 'Order Details',
        'order_summary' => 'Order Summary',
        'products' => 'Products',
        'transactions' => 'Transactions',
        'sales' => 'Sales',
        'settings' => 'Company Settings',
        'update_inventory' => 'Update Inventory',
        'wholesale_orders' => 'Wholesale Orders',
    ],
        
    'labels' => [
        'account' => 'Account',
        'amount' => 'Amount',
        'balance' => 'Balance',
        'collected' => 'Collected',
        'company' => 'Company',
        'completed' => 'Completed',
        'completed_by' => 'Completed By',
        'counterparty' => 'Counterparty',
        'critical_threshold' => 'Critical Threshold',
        'customer' => 'Customer',
        'date' => 'Date',
        'default_salary_percentage' => 'Default Salary Percentage',
        'deposit' => 'Deposit',
        'description' => 'Description',
        'direction' => 'Direction',
        'employee' => 'Employee',
        'employees' => 'Employees',
        'inventory_updated' => 'Inventory Updated',
        'item' => 'Item',
        'item_types' => 'Item Types',
        'items' => 'Items',
        'is_main' => 'Main Account',
        'is_paid' => 'Is Paid',
        'name' => 'Name',
        'owner' => 'Owner',
        'order' => 'Order',
        'order_id' => 'Order ID',
        'pending' => 'Pending',
        'player' => 'Player',
        'preferred_stock_level' => 'Preferred Stock Level',
        'price' => 'Price',
        'product' => 'Product',
        'quantity' => 'Quantity',
        'role' => 'Role',
        'salary_percentage' => 'Salary Percentage',
        'salary_scheme' => 'Salary Scheme',
        'sale_at' => 'Sale at',
        'stacks' => 'Stacks',
        'status' => 'Status',
        'stock' => 'Stock',
        'stock_level' => 'Stock Level',
        'units' => 'Units',
        'unknown' => 'Unknown',
        'tip_scheme' => 'Tip Scheme',
        'total' => 'Total',
        'total_amount' => 'Total Amount',
        'transfer' => 'Transfer',
        'type' => 'Type',
        'warning_threshold' => 'Warning Threshold',
        'wholesale_status' => 'Wholesale Status',
        'withdrawal' => 'Withdrawal',
    ],

    'buttons' => [
        'create_sale' => 'Create Sale',
        'update_inventory' => 'Update Inventory',
        'mark_as_completed' => 'Mark as Completed',
        'mark_as_completed_and_update_inventory' => 'Mark as Completed & Update Inventory',
        'mark_as_uncompleted' => 'Mark as Uncompleted',
        'new_sale' => 'New Sale',
    ],

    'messages' => [
        'bank_accounts_no_records' => 'No bank accounts found.',
        'choose_description' => 'Select the company you want to manage or view.',
        'default_salary_percentage' => 'Sync with company\'s default salary percentage (:percentage%)',
        'delete_sale_confirmation' => 'Are you sure you want to delete this sale? This action cannot be undone.',
        'inventory_no_records' => 'No inventory records found.',
        'more_orders' => 'There are more orders that can\'t be displayed here. Please go to the wholesale orders page to view all orders.',
        'no_companies' => 'No companies found.',
        'no_employees' => 'No employees found.',
        'no_orders' => 'No orders found.',
        'no_order_items' => 'No items found for this order.',
        'no_transactions' => 'No transactions found.',
        'no_sales' => 'No sales found.',
        'wholesale_orders_no_records' => 'No wholesale orders found.',
    ],

    'toasts' => [
        'employee_updated' => 'Employee updated successfully.',
        'inventory_updated' => 'Inventory updated successfully.',
        'notification_marked_as_read' => 'Notification marked as read.',
        'order_marked_as_completed' => 'Order marked as completed.',
        'order_marked_as_uncompleted' => 'Order marked as uncompleted.',
        'sale_created' => 'Sale created successfully.',
        'sale_deleted' => 'Sale deleted successfully.',
        'stock_updated_successfully' => 'Stock updated successfully.',
    ],

    'placeholders' => [
        
    ],

    'roles' => [
        'owner' => 'Owner',
        'manager' => 'Manager',
        'employee' => 'Employee',
    ],

    'options' => [
        'salary_scheme' => [
            'own_sales_percentage' => 'Own Sales Percentage',
            'team_sales_percentage' => 'Team Sales Percentage',
        ],
        'tip_scheme' => [
            'per_employee' => 'Per Employee',
            'shared' => 'Shared',
        ],
    ],

    'notifications' => [
        'stock_critical' => 'Stock for <b>:item</b> is at critical level!',
        'stock_warning' => 'Stock for <b>:item</b> is low.',
    ],
];
