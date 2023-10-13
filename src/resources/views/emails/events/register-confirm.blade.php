<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>{{ __('basic::elf.registration_confirmation') }}</h1>
    <p>
        @if ($content)
        {!! Helpers::templateText(Helpers::templateText($content,$elfSiteSettings),$elfUserData) !!}
        @endif
    </p>
    <p>
        {{ __('basic::elf.to_confirm_your_registration_follow_the_link') }}:
    </p>
    <p>
        <a href="{{ route('account.confirm', ['email' => $params['email'], 'token' => $params['confirm_token']]) }}">{{ route('account.confirm', ['email' => $params['email'], 'token' => $params['confirm_token']]) }}</a>
    </p>
    <p>

    </p>
</body>
</html>
