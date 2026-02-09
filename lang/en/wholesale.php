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
    ],

    // Messages
    'messages' => [
        'no_orders' => 'There are currently no wholesale orders.',
        'no_collected_orders' => 'There are currently no collected wholesale orders.',
        'no_completed_orders' => 'There are currently no completed wholesale orders.',
    ],

    // Toasts
    'toasts' => [
        'no_items' => 'This company has no items available for wholesale ordering.',
        'no_items_in_order' => 'Please add at least one item to the order before finishing.',
        'max_amount_exceeded' => 'The amount for :item exceeds the maximum allowed (:max).',
        'order_created' => 'Wholesale order created successfully.',
    ],
];