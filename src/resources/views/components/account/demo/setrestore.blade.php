<form action="{{ route('account.setnewpassword') }}" method="post" class="">
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
    <input type="hidden" name="token" value="{{ $token }}">
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
        <button type="submit" class="button submit-button">{{ __('elfcms::default.save') }}</button>
    </div>
    @endif
</form>

