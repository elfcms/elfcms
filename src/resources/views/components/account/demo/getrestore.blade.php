<form action="{{ route('account.getrestore') }}" method="post" class="">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (Session::has('requestissended'))
        <div class="alert alert-success">{{ Session::get('requestissended') }}</div>
    @else
    @csrf
    @method('POST')
    <div>
        <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('elfcms::default.email') }}" required>
    </div>
    <div>
        <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.restore_password') }}</button>
    </div>
    @endif
</form>
<div class="register-link">
    <a href="{{ route('account.register') }}">{{ __('elfcms::default.register') }}</a> | <a href="{{ route('account.login') }}">{{ __('elfcms::default.login') }}</a>
</div>
