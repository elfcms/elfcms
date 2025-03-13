<form action="{{ route('admin.setnewpassword') }}" method="post" class="">
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
        <div class="message-box">
            <div class="alert alert-success">{{ Session::get('requestissended') }}</div>
            <div class="button-box single-box">
                <a href="{{ route('admin.login') }}" class="forgot-pass-link">{{ __('elfcms::default.login') }}</a>
            </div>
        </div>
    @else
    @csrf
    @method('POST')
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="input-box text-box">
        <div class="input-wrapper">
            <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('elfcms::default.email') }}" required>
            <label for="email">{{ __('elfcms::default.email') }}</label>
        </div>
    </div>
    <div class="input-box text-box">
        <div class="input-wrapper">
            <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('elfcms::default.password') }}" required>
            <label for="password">{{ __('elfcms::default.password') }}</label>
        </div>
    </div>
    <div class="input-box text-box">
        <div class="input-wrapper">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('elfcms::default.confirm_password') }}" required>
            <label for="password_confirmation">{{ __('elfcms::default.confirm_password') }}</label>
        </div>
    </div>
    <div class="button-box single-box">
        <button type="submit" class="button submit-button">{{ __('elfcms::default.save') }}</button>
    </div>
    @endif
</form>

