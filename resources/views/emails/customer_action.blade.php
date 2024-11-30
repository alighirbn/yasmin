<!DOCTYPE html>
<html>

    <head>
        <title>إشعار إجراء على قائمة الزبائن</title>
    </head>

    <body>
        <p>عزيزي المسؤول،</p>

        <p>تم إجراء عملية من قبل المستخدم:</p>

        <ul>
            <li><strong>الإجراء:</strong> {{ $action }}</li>
            <li><strong>اسم الزبون:</strong> {{ $customer->customer_full_name }}</li>
            @if ($note)
                <li><strong>ملاحظة:</strong> {{ $note }}</li>
            @endif
        </ul>

        <p>شكراً!</p>
    </body>

</html>
