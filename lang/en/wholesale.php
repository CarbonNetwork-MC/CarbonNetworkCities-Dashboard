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
        'create_order' => 'Create Wholesale Order',
        'order_overview' => 'Order Overview',
        'orders_overview' => 'Orders Overview',
        'order_summary' => 'Order Summary',
        'orders_to_collect' => 'Orders yet to collect',
        'orders_to_complete' => 'Orders yet to complete',
        'completed_orders' => 'Completed Orders',
        'order' => 'Order',
        'customer' => 'Customer',
        'collect_order' => 'Collect Order',
        'complete_order' => 'Complete Order',
        'delete_order' => 'Delete Order',
        'undo_collect_order' => 'Undo Collect Order',
    ],

    // Labels
    'labels' => [
        'amount' => 'Amount',
        'total' => 'Total',
        'amount_of_items' => 'Amount of Items',
        'collected' => 'Collected',
        'collected_by' => 'Collected By',
        'completed_by' => 'Completed By',
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
        'no_orders' => 'There are currently no wholesale orders.',
        'no_collected_orders' => 'There are currently no collected wholesale orders.',
        'no_completed_orders' => 'There are currently no completed wholesale orders.',
        'editing_enabled' => 'Editing is enabled',
        'delete_order_confirmation' => 'Are you sure you want to delete this order? This action cannot be undone.',
        'undo_collect_confirmation' => 'Are you sure you want to undo the collection of this order?',
    ],

    // Toasts
    'toasts' => [
        'no_items' => 'This company has no items available for wholesale ordering.',
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
    ]
];