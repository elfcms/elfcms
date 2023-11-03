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
    <div class="input-box">
        <div class="checkbox-wrapper">
            <div class="checkbox-inner">
                <input type="checkbox" name="terms" id="terms" required>
                <i></i>
                <label for="terms">
                    {!! __('elfcms::default.terms_agreeing_links',['terms'=>'#','privacy'=>'javascript:void()']) !!}
                </label>
            </div>
        </div>
    </div>
    <div class="button-box single-box">
        <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.sign_up') }}</button>
    </div>
</form>
