<form action="{{ route('admin.getrestore') }}" method="post" class="">
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
    <div class="input-box text-box">
        <div class="input-wrapper">
            <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('elfcms::default.email') }}" required>
            <label for="email">{{ __('elfcms::default.email') }}</label>
        </div>
    </div>
    <div class="button-box single-box">
        <button type="submit" class="button submit-button">{{ __('elfcms::default.restore_password') }}</button>
    </div>
    @endif
</form>
