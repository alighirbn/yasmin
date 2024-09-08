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
    'user_create' => 'تم الانشاء من قبل',
    'user_update' => 'تم التحديث من قبل',
    //buttons
    'action' => 'الوظائف',
    'view' => 'عرض',
    'add' => 'أضافة',
    'edit' => 'تعديل',
    'delete' => 'حذف',
    'update' => 'تعديل',
    'save' => 'حفظ',
    'back' => 'رجوع',
    'Log Out' => 'تسجيل خروج',
    'print' => 'طباعة',

    // general values
    'year' => 'سنة',
    'month' => 'شهر',
    'day' => 'يوم',

    //main navigation
    'dashboard' => 'الرئيسية',
    'Map' => 'الخارطة',
    'contract' => 'العقود',
    'Building' => 'العقارات',
    'payment' => 'الدفعات',
    'service' => 'الخدمات',
    'Customer' => 'الزبائن',
    'users' => 'المستخدمين',
    'roles' => 'الصلاحيات',

    // dashboard
    'dashboard_msg' => 'مرحباً بك في الموقع الالكتروني للمجمع السكني واحة الياسمين في محافظة النجف الأشرف',
    'dashboard_title' => 'مرحباً',

    // basic info
    'basic_msg' => 'الصفحة الخاصة بالمعلومات الاساسية',

    //************************************* map        *******************************************
    'map_contracts' => 'خارطة العقود',
    'map_due_installments_0' => 'مستحق الان ',
    'map_due_installments_30' => 'مستحق بعد شهر',
    'map_due_installments_60' => 'مستحق بعد شهرين',
    'map_buildings' => 'خارطة العقارات',
    'map_empty_buildings' => 'خارطة العقارات المتوفرة',


    //************************************* contract        *******************************************
    'id' => 'عدد العقد',
    'contract_id' =>  'عدد العقد',
    'contract_date' => 'تأريخ العقد',
    'contract_amount' => 'المبلغ',
    'contract_note' => 'الملاحظات',

    'contract_customer_id' => 'الاسم',
    'contract_building_id' => 'رقم العقار',
    'contract_payment_method_id' => 'نوع التسديد',
    'method_name' => 'نوع التسديد',

    'installment_number' => 'التسلسل',
    'installment_name' => 'الدفعة',
    'installment_percent' => 'النسبة',
    'installment_amount' => 'مبلغ الدفعة',
    'installment_date' => 'تأريخ استحقاق الدفعة',
    'installment_payment' => 'حالة التسديد',


    //nav
    'contract_add' => 'اضافة عقد',
    'contract_search' => 'بحث عن عقد',
    'contract_view' => 'عرض العقد',
    'contract_edit' => 'تعديل العقد',
    'contract_print' => 'طباعة العقد',
    'contract_transfer' => 'تناقل العقد',
    'contract_due' => 'الدفعات المستحقة',
    'statement' => 'كشف',

    //contract_info
    'contract_info' => 'بيانات العقد',
    'installment_info' => 'بيانات الدفعات',


    //************************************* transfer        *******************************************

    'transfer_id' => 'عدد التناقل',
    'transfer_date' => 'تاريخ التناقل',
    'transfer_amount' => 'اجور التناقل',
    'transfer_note' => 'الملاحظات',

    'oldcustomer' => 'الزبون السابق',
    'newcustomer' => 'الزبون الجديد',
    'new_customer_id' => 'اسم الزبون الجديد',

    //nav
    'transfer_add' => 'اضافة تناقل',
    'transfer_search' => 'بحث عن تناقل',
    'transfer_edit' => 'تعديل التناقل',
    'transfer_print' => 'طباعة التناقل',
    //transfer_info
    'transfer_info' => 'بيانات التناقل',

    'old_customer_picture' => 'صورة الزبون السابق',
    'new_customer_picture' => 'صورة الزبون الجديد',
    'capture' => 'التقاط',


    //************************************* building        *******************************************

    'building_number' => 'رقم العقار',
    'block_number' => 'البلوك',
    'house_number' => 'الدار',
    'building_area' => 'المساحة',
    'building_map_x' => 'احداثيات x',
    'building_map_y' => 'احداثيات y',

    'building_category_id' => 'الفئة',
    'building_type_id' => 'النوع',

    //nav
    'building_add' => 'اضافة عقار',
    'building_search' => 'بحث عن عقار',

    //building_info
    'building_info' => 'بيانات العقار',


    //************************************* payment        *******************************************

    'payment_id' => 'عدد الدفعة',
    'payment_date' => 'تاريخ الدفعة',
    'payment_amount' => 'المبلغ المستلم',
    'payment_note' => 'الملاحظات',
    'add_payment' => 'تسديد الدفعة',

    //nav
    'payment_add' => 'اضافة دفعة',
    'payment_search' => 'بحث عن دفعة',
    'payment_approve' => 'قبول الدفعة',

    //payment_info
    'payment_info' => 'بيانات الدفعة',
    'last_payment' => 'اخر دفعة',
    'payment_pending' => 'الدفعات غير الموافق عليها',



    //************************************* service        *******************************************

    'service_id' => 'عدد الخدمة',
    'service_date' => 'تاريخ الخدمة',
    'service_amount' => 'المبلغ ',
    'service_note' => 'الملاحظات',
    'service_type_id' => 'نوع الخدمة',

    //nav
    'service_add' => 'اضافة خدمة',
    'service_search' => 'بحث عن خدمة',

    //service_info
    'service_info' => 'بيانات الخدمة',


    //************************************* customer        *******************************************

    'customer_full_name' => 'الاسم الرباعي واللقب',
    'customer_phone' => 'رقم الموبايل',
    'customer_email' => 'عنوان البريد الالكتروني',
    'customer_card_number' => 'رقم البطاقة الوطنية',
    'customer_card_issud_auth' => 'جهة الاصدار',
    'customer_card_issud_date' => 'تاريخ الاصدار',


    //nav
    'customer_add' => 'اضافة زبون',
    'customer_search' => 'بحث عن زبون',

    //customer_info
    'customer_info' => 'بيانات الزبون',
    'customer_card' => 'بيانات البطاقة الوطنية',

    //************************************* users        *******************************************
    'user_name' => 'اسم المستخدم',
    'password' => 'كلمة السر',
    'confirm_password' => 'تأكيد كلمة السر',
    'email' => 'عنوان البريد الألكتروني',
    'user_status' => 'الحالة',
    'user_role' => 'الدور',
    'department_id' => 'المؤسسة',

    //nav
    'user_add' => 'اضافة مستخدم',
    'user_search' => 'عرض المستخدمين',

    //user_info
    'user_info' => 'معلومات المستخدم',

    //************************************* role        *******************************************
    //fields
    'role_name' => 'اسم الدور',
    'guard' => 'الحماية',
    'permission' => 'الصلاحيات',

    //nav
    'role_add' => 'اضافة دور',
    'role_search' => 'عرض الأدوار',

    //user_info
    'role_info' => 'معلومات الدور',

    //************************************* notification *******************************************
    'notifications' => 'الإشعارات',
    'show_all' => 'عرض الكل',
    'markallasread' => 'تمييز الكل كمقروء',
    'unreadnotification' => 'الاشعارات غير المقروءة',
    'nonotification' => 'لم يتم اضافة اشعارات',
    'readnotification' => 'الاشعارات المقروءة',



];
