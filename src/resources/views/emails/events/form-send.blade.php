<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>The form {{$params['form']->title}} has been submitted</h1>
        {!! nl2br($content) ?? ''!!}
    </p>
    <p>
        @foreach ($params['data'] as $data)
            <strong>{{ $data['title'] }}:</strong> <span>{{ $data['value'] }}</span>
            <br>
        @endforeach
    </p>
</body>
</html>
