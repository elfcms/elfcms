<form action="{{ route('account.register') }}" method="post" class="">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
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
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('elfcms::default.confirm_password') }}" required>
    </div>
    <div>
        <div>
            <input type="checkbox" name="terms" id="terms" required>
            <label for="terms">
                {!! __('elfcms::default.terms_agreeing_links',['terms'=>'#','privacy'=>'/privacy']) !!}
            </label>
        </div>
    </div>
    <div>
        <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.sign_up') }}</button>
    </div>
</form>
<div class="register-link">
    <a href="{{ route('account.login') }}">{{ __('elfcms::default.login') }}</a>
</div>
