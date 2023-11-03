<form action="{{ route('account.login') }}" method="post" class="">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (Session::has('toemailconfirm'))
        <div class="alert alert-success">{{ Session::get('toemailconfirm') }}</div>
    @endif
    @csrf
    @method('POST')
    <div>
        <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('elfcms::default.email') }}" required>
    </div>
    <div>
        <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('elfcms::default.password') }}" required>
    </div>
    <div>
        <div>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">
                {{ __('elfcms::default.remember_me') }}
            </label>
        </div>
    </div>
    <div>
        <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.login') }}</button>
    </div>
</form>
<div class="register-link">
    <a href="{{ route('account.getrestore') }}">{{ __('elfcms::default.forgot_your_password') }}</a> | <a href="{{ route('account.register') }}">{{ __('elfcms::default.registration') }}</a>
</div>
