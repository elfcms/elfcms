<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>{{$subject ?? __('elfcms::default.password_recovery_request')}}</h1>
    <p>{{ $elfUserData['userFormOfAddressLang'] ?? __('elfcms::default.dear_mr_ms') }} {{ $elfUserData['userName'] ?? __('elfcms::default.user_he_she') }},</p>
    <p>
        @if ($content)
        {!! Helpers::templateText(Helpers::templateText($content,$elfSiteSettings),$elfUserData) !!}
        @endif
    </p>
    <p>
        {{__('elfcms::default.to_recover_your_password_follow_the_link')}}
    </p>
    <p>
        <a href="{{ route('admin.setrestore', ['token' => $params['confirm_token']]) }}">{{ route('admin.setrestore', ['token' => $params['confirm_token']]) }}</a>
    </p>
    <p>
        {{ $elfSiteSettings['title'] ?? '' }}
    </p>
</body>
</html>
