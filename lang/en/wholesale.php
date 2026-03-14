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
        'choose_company' => 'Choose Company',
        'choose_wholesaler' => 'Choose Wholesaler',
        'collect_order' => 'Collect Order',
        'companies' => 'Companies',
        'complete_order' => 'Complete Order',
        'completed_orders' => 'Completed Orders',
        'create_order' => 'Create Wholesale Order',
        'customer' => 'Customer',
        'delete_order' => 'Delete Order',
        'order' => 'Order',
        'order_overview' => 'Order Overview',
        'order_summary' => 'Order Summary',
        'orders_overview' => 'Orders Overview',
        'orders_to_collect' => 'Orders yet to collect',
        'orders_to_complete' => 'Orders yet to complete',
        'select_wholesaler' => 'Select Wholesaler',
        'start' => 'Wholesalers',
        'undo_collect_order' => 'Undo Collect Order',
        'wholesalers' => 'Wholesalers',
    ],

    // Labels
    'labels' => [
        'amount' => 'Amount',
        'amount_of_items' => 'Amount of Items',
        'collected' => 'Collected',
        'collected_by' => 'Collected By',
        'completed_by' => 'Completed By',
        'country' => 'Country',
        'name' => 'Name',
        'total' => 'Total',
    ],

    // Buttons
    'buttons' => [
        'cancel_order' => 'Cancel Order',
        'finish_order' => 'Finish Order',
        'update_order' => 'Update Order',
        'collect_order' => 'Collect Order',
        'complete_order' => 'Complete Order',
        'undo_collect_order' => 'Undo Collect Order',
    ],

    // Messages
    'messages' => [
        'delete_order_confirmation' => 'Are you sure you want to delete this order? This action cannot be undone.',
        'editing_enabled' => 'Editing is enabled',
        'no_collected_orders' => 'There are currently no collected wholesale orders.',
        'no_completed_orders' => 'There are currently no completed wholesale orders.',
        'no_orders' => 'There are currently no wholesale orders.',
        'select_wholesaler_description' => 'Please select the wholesaler you want to manage orders for.',
        'undo_collect_confirmation' => 'Are you sure you want to undo the collection of this order?',
    ],

    // Toasts
    'toasts' => [
        'no_items_company' => 'This company has no items available for wholesale ordering.',
        'no_items_wholesaler' => 'The selected wholesaler has no items available for wholesale ordering.',
        'no_items_in_order' => 'Please add at least one item to the order before finishing.',
        'max_amount_exceeded' => 'The amount for :item exceeds the maximum allowed (:max).',
        'order_created' => 'Wholesale order created successfully.',
        'order_updated' => 'Wholesale order updated successfully.',
        'order_collected' => 'Wholesale order collected successfully.',
        'order_completed' => 'Wholesale order completed successfully.',
        'order_deleted' => 'Wholesale order deleted successfully.',
        'collect_order_undone' => 'Wholesale order collection successfully undone.',
    ],

    'notifications' => [
        'order_collected' => 'Your last order has been collected, and is ready to be picked up.',
        'order_completed' => 'Your last order has been completed, you can now add it to your inventory.'
    ],

    'stepper' => [
        'step1' => 'Choose Company',
        'step2' => 'Choose Wholesaler',
        'step3' => 'Create Order',
    ]
];