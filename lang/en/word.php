<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Word Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during viewing all pages for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
     */
    //auth
    'user_create' => 'Created by',
    'user_update' => 'Updated by',
    //buttons
    'action' => 'Actions',
    'view' => 'View',
    'add' => 'Add',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'update' => 'Update',
    'save' => 'Save',
    'back' => 'Back',
    'Log Out' => 'Log Out',
    'print' => 'Print',

    // general values
    'year' => 'Year',
    'month' => 'Month',
    'day' => 'Day',

    //main navigation
    'dashboard' => 'Dashboard',
    'Map' => 'Map',
    'contract' => 'Contracts',
    'accountant' => 'Accounts',
    'Building' => 'Buildings',
    'payment' => 'Payments',
    'expense' => 'Expense Vouchers',
    'service' => 'Services',
    'Customer' => 'Customers',
    'users' => 'Users',
    'roles' => 'Roles',

    // dashboard
    'dashboard_msg' => 'Welcome to the Yasmin Oasis Residential Complex website in Najaf Province',
    'dashboard_title' => 'Welcome',

    // basic info
    'basic_msg' => 'Page for basic information',

    //************************************* map        *******************************************
    'map_contracts' => 'Contract Map',
    'map_due_installments_0' => 'Due Now',
    'map_due_installments_30' => 'Due in a Month',
    'map_due_installments_60' => 'Due in Two Months',
    'map_buildings' => 'Building Map',
    'map_empty_buildings' => 'Available Building Map',


    //************************************* contract        *******************************************
    'id' => 'Contract Number',
    'contract_id' =>  'Contract Number',
    'contract_date' => 'Contract Date',
    'contract_amount' => 'Amount',
    'contract_note' => 'Notes',

    'contract_customer_id' => 'Customer Name',
    'contract_building_id' => 'Building Number',
    'contract_payment_method_id' => 'Payment Type',
    'method_name' => 'Payment Type',

    'installment_number' => 'Sequence',
    'installment_name' => 'Installment',
    'installment_percent' => 'Percentage',
    'installment_amount' => 'Installment Amount',
    'installment_date' => 'Installment Due Date',
    'installment_payment' => 'Payment Status',


    //nav
    'contract_add' => 'Add Contract',
    'contract_search' => 'Search for Contract',
    'contract_view' => 'View Contract',
    'contract_edit' => 'Edit Contract',
    'contract_print' => 'Print Contract',
    'contract_transfer' => 'Transfer Contract',
    'contract_due' => 'Due Installments',
    'statement' => 'Statement',

    //contract_info
    'contract_info' => 'Contract Information',
    'installment_info' => 'Installment Information',


    //************************************* transfer        *******************************************

    'transfer_id' => 'Transfer Number',
    'transfer_date' => 'Transfer Date',
    'transfer_amount' => 'Transfer Fee',
    'transfer_note' => 'Notes',

    'oldcustomer' => 'Previous Customer',
    'newcustomer' => 'New Customer',
    'new_customer_id' => 'New Customer Name',

    //nav
    'transfer_add' => 'Add Transfer',
    'transfer_search' => 'Search for Transfer',
    'transfer_edit' => 'Edit Transfer',
    'transfer_print' => 'Print Transfer',
    //transfer_info
    'transfer_info' => 'Transfer Information',
    'transfer_contract' => 'View Transfers',
    'transfer_approve' => 'Approved',

    'old_customer_picture' => 'Previous Customer Picture',
    'new_customer_picture' => 'New Customer Picture',
    'capture' => 'Capture',


    //************************************* building        *******************************************

    'building_number' => 'Building Number',
    'block_number' => 'Block Number',
    'house_number' => 'House Number',
    'building_area' => 'Area',
    'building_map_x' => 'Coordinate X',
    'building_map_y' => 'Coordinate Y',

    'building_category_id' => 'Category',
    'building_type_id' => 'Type',

    //nav
    'building_add' => 'Add Building',
    'building_search' => 'Search for Building',

    //building_info
    'building_info' => 'Building Information',


    //************************************* payment        *******************************************

    'payment_id' => 'Payment Number',
    'payment_date' => 'Payment Date',
    'payment_amount' => 'Amount Received',
    'payment_note' => 'Notes',
    'add_payment' => 'Make Payment',

    //nav
    'payment_add' => 'Add Payment',
    'payment_search' => 'Search for Payment',
    'payment_approve' => 'Approve Payment',
    'approved' => 'Approved',
    'pending' => 'Pending',

    //payment_info
    'payment_info' => 'Payment Information',
    'last_payment' => 'Last Payment',
    'payment_pending' => 'Pending Payments',
    'payment_status' => 'Payment Status',
    'approve_status' => 'Approval Status',


    //************************************* expense        *******************************************

    'expense_id' => 'Expense Voucher Number',
    'expense_date' => 'Expense Voucher Date',
    'expense_amount' => 'Amount',
    'expense_note' => 'Notes',
    'add_expense' => 'Add Expense Voucher',
    'expense_type_id' => 'Expense Type',

    //nav
    'expense_add' => 'Add Expense Voucher',
    'expense_search' => 'Search for Expense Voucher',
    'expense_approve' => 'Approve Expense Voucher',

    //expense_info
    'expense_info' => 'Expense Voucher Information',
    'expense_pending' => 'Pending Expense Vouchers',
    'expense_status' => 'Payment Status',


    //************************************* cash_account        *******************************************

    'cash_account_id' => 'Cash Account Number',
    'balance' => 'Balance',
    'account_name' => 'Account Name',


    //nav
    'cash_account_add' => 'Add Cash Account',
    'cash_account_search' => 'Search for Cash Account',

    //cash_account_info
    'cash_account_info' => 'Cash Account Information',

    //************************************* cash_transfer        *******************************************

    'cash_transfer_id' => 'Transfer Number',
    'amount' => 'Amount',

    'transfer_date' => 'Transfer Date',
    'from_account_id' => 'From Account',
    'to_account_id' => 'To Account',
    'from_account' => 'From Account',
    'to_account' => 'To Account',

    'transfer_number' => 'Transfer Number',
    'transfer_note' => 'Notes',

    //nav
    'cash_transfer_add' => 'Add Transfer',
    'cash_transfer_search' => 'Search for Transfer',

    //cash_transfer_info
    'cash_transfer_info' => 'Transfer Information',
    'cash_transfer_pending' => 'Pending Transfers',
    'cash_transfer_approve' => 'Approve Transfer',


    //************************************* service        *******************************************

    'service_id' => 'Service Number',
    'service_date' => 'Service Date',
    'service_amount' => 'Amount',
    'service_note' => 'Notes',
    'service_type_id' => 'Service Type',

    //nav
    'service_add' => 'Add Service',
    'service_search' => 'Search for Service',

    //service_info
    'service_info' => 'Service Information',


    //************************************* customer        *******************************************

    'customer_full_name' => 'Full Name',
    'customer_phone' => 'Phone Number',
    'customer_email' => 'Email Address',
    'customer_card_number' => 'National Card Number',
    'customer_card_issud_auth' => 'Issuing Authority',
    'customer_card_issud_date' => 'Issue Date',


    //nav
    'customer_add' => 'Add Customer',
    'customer_search' => 'Search for Customer',

    //customer_info
    'customer_info' => 'Customer Information',
    'customer_card' => 'National Card Information',

    //************************************* users        *******************************************
    'user_name' => 'Username',
    'password' => 'Password',
    'confirm_password' => 'Confirm Password',
    'email' => 'Email Address',
    'user_status' => 'Status',
    'user_role' => 'Role',
    'department_id' => 'Department',

    //nav
    'user_add' => 'Add User',
    'user_search' => 'View Users',

    //user_info
    'user_info' => 'User Information',

    //************************************* role        *******************************************
    //fields
    'role_name' => 'Role Name',
    'guard' => 'Guard',
    'permission' => 'Permissions',

    //nav
    'role_add' => 'Add Role',
    'role_search' => 'View Roles',

    //user_info
    'role_info' => 'Role Information',

    //************************************* reports        *******************************************

    'total_amount' => 'Total Amount',
    'received_amount' => 'Amount Received',
    'remaining_amount' => 'Remaining Amount',
    'contract_report' => 'Contract Report',
    'installment_report' => 'Installment Report',
    'installment_status' => 'Installment Status',

];
