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
    <div class="input-box">
        <div class="checkbox-wrapper">
            <div class="checkbox-inner">
                <input type="checkbox" name="remember" id="remember">
                <i></i>
                <label for="remember">
                    {{ __('elfcms::default.remember_me') }}
                </label>
            </div>
        </div>
    </div>
    <div class="button-box single-box">
        <button type="submit" class="button submit-button">{{ __('elfcms::default.login') }}</button>
    </div>
</form>
