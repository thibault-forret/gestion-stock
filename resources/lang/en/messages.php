<?php

return [

    'action_success' => 'Your action was successful.',
    'action_failed' => 'Your action failed. Please try again.',
    'action_not_allowed' => 'You are not allowed to perform this action.',
    'action_not_found' => 'The action you are trying to perform does not exist.',
    'langage_not_supported' => 'Language not supported',
    'confirm_order' => 'Your order has been confirmed.',
    'product_added' => 'Product added successfully.',
    'problem_when_adding_product' => 'There was a problem adding the product. Please try again.',
    'stock_not_found' => 'The stock was not found. Please try again.',

    'validate' => [
        'usernmame_required' => 'Please provide your username.',
        'username_string' => 'Please provide a valid username.',
        'email_valid' => 'Please provide a valid email address.',
        'email_required' => 'Please provide your email address.',
        'password_required' => 'Please provide your password.',
        'search_by_name_string' => 'The product name must be a string.',
        'supplier_name_exists' => 'The selected supplier does not exist.',
        'supplier_name_required' => 'The supplier name is required.',
        'category_name_exists' => 'The selected category does not exist.',
        'page_number_integer' => 'The page number must be an integer.',
        'page_number_min' => 'The page number must be greater than or equal to 1.',
        'product_not_found' => 'The product was not found. Please try again.',
        'problem_with_product_data' => 'There was a problem with the product data. Please try again.',
        'problem_when_updating_product' => 'There was a problem updating the product. Please try again.',
        'problem_when_deleting_product' => 'There was a problem deleting the product. Please try again.',
        'quantity_required' => 'Quantity is required.',
        'quantity_integer' => 'Quantity must be an integer.',
        'quantity_min' => 'Quantity must be greater than or equal to 1.',
        'quantity_gte' => 'Quantity must be greater than or equal to the restock threshold.',
        'restock_threshold_required' => 'Restock threshold is required.',
        'restock_threshold_integer' => 'Restock threshold must be an integer.',
        'restock_threshold_min' => 'Restock threshold must be greater than or equal to 0.',
        'alert_threshold_required' => 'Alert threshold is required.',
        'alert_threshold_integer' => 'Alert threshold must be an integer.',
        'alert_threshold_min' => 'Alert threshold must be greater than or equal to 1.',
        'alert_threshold_gte' => 'Alert threshold must be greater than or equal to the restock threshold.',
        'auto_restock_quantity_required' => 'Auto restock quantity is required.',
        'auto_restock_quantity_integer' => 'Auto restock quantity must be an integer.',
        'auto_restock_quantity_min' => 'Auto restock quantity must be greater than or equal to 1.',
        'auto_restock_quantity_gte' => 'Auto restock quantity must be greater than or equal to the restock threshold.',
        'quantity_exceeds_capacity' => 'The quantity of products exceeds the warehouse capacity. Please try again.',
        'thresholds_exceeds_capacity' => 'The thresholds exceed the warehouse capacity. Please try again.',
        'stock_id_required' => 'Stock ID is required.',
        'stock_id_integer' => 'Stock ID must be an integer.',
        'quantity_to_high' => 'The quantity to remove is greater than the available quantity.',
        'products_required' => 'Products are required.',
        'products_array' => 'Products must be an array.',
        'products_each_required' => 'Each product is required.',
        'products_each_integer' => 'Each product must be an integer.',
        'products_each_exists' => 'Each product must exist.',
        'quantities_required' => 'Quantities are required.',
        'quantities_array' => 'Quantities must be an array.',
        'quantities_each_required' => 'Each quantity is required.',
        'quantities_each_integer' => 'Each quantity must be an integer.',
        'quantities_each_min' => 'Each quantity must be greater than or equal to 1.',
    ]
];