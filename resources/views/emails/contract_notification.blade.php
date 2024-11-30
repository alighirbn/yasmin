<!DOCTYPE html>
<html>

    <head>
        <title> اشعار بتغيير العقود</title>
    </head>

    <body>

        <p><strong>نوع الحركة:</strong> {{ $changeType }}</p>

        @if ($contractData)
            <p><strong>Contract Data:</strong></p>
            <pre>{{ print_r($contractData, true) }}</pre>
        @endif

        @if ($oldData && $newData)
            <p><strong>البيانات القديمة:</strong></p>
            <ul>
                @foreach ($oldData as $key => $value)
                    <li><strong>{{ __('word.' . $key) }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>

            <p><strong>البيانات الجديدة:</strong></p>
            <ul>
                @foreach ($newData as $key => $value)
                    <li><strong>{{ __('word.' . $key) }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>
        @endif
    </body>

</html>
