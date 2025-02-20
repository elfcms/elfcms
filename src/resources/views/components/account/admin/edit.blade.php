@if ($errors->any())
<div class="alert alert-danger">
    <ul class="errors-list">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if (Session::has('useredited'))
    <div class="alert alert-success">{{ Session::get('useredited') }}</div>
@endif

@if (Session::has('passwordsaved'))
    <div class="alert alert-success">{{ Session::get('passwordsaved') }}</div>
@endif

@if (Session::has('passchangeerror'))
    <div class="alert alert-danger">{{ Session::get('passchangeerror') }}</div>
@endif

@if (Session::has('incorrectpassword'))
    <div class="alert alert-danger">{{ Session::get('incorrectpassword') }}</div>
@endif
<h2>{{ __('elfcms::default.user_data') }}</h2>
<form action="{{ route('account.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="rows-box">
        <div class="input-box">
            <label for="email">{{ __('elfcms::default.email') }}</label>
            <div class="input-wrapper">
                <input type="email" name="email" id="email" autocomplete="off" value="{{ $user->email }}" disabled>
            </div>
        </div>
        <div class="input-box">
            <label for="first_name">{{ __('elfcms::default.first_name') }}</label>
            <div class="input-wrapper">
                <input type="text" name="data[first_name]" id="first_name" value="{{ $user->data['first_name'] ?? '' }}">
            </div>
        </div>
        <div class="input-box">
            <label for="last_name">{{ __('elfcms::default.last_name') }}</label>
            <div class="input-wrapper">
                <input type="text" name="data[last_name]" id="last_name" value="{{ $user->data['last_name'] ?? '' }}">
            </div>
        </div>
        <div class="input-box">
            <label for="photo">{{ __('elfcms::default.photo') }}</label>
            <div class="input-wrapper">
                <input type="hidden" name="data[photo_path]" id="photo_path" value="@if(!empty($user->data['photo'])) {{ asset(file_path($user->data['photo'])) }} @else '' @endif">
                <div class="image-button">
                    <div class="image-button-img">
                    @if (!empty($user->data['photo']))
                        <img src="{{ asset(file_path($user->data['photo'])) }}" alt="User avatar">
                    @else
                        <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                    @endif
                    </div>
                    {{-- <div class="image-button-text">
                    @if (!empty($user->data['photo']))
                        {{ __('elfcms::default.change_file') }}
                    @else
                        {{ __('elfcms::default.choose_file') }}
                    @endif
                    </div> --}}
                    <input type="file" name="data[photo]" id="photo">
                </div>
            </div>
        </div>
    </div>

    <div>
        <button type="submit" class="button submit-button">{{ __('elfcms::default.save') }}</button>
    </div>
</form>
<h2>{{ __('elfcms::default.change_password') }}</h2>
<form action="{{ route('account.update') }}" method="post" class="">
    @csrf
    @method('POST')
    <div class="rows-box">
        <div class="input-box">
            <label for="current_password">{{ __('elfcms::default.current_password') }}</label>
            <div class="input-wrapper">
                <input type="password" name="current_password" id="current_password" autocomplete="off" value="" required>
            </div>
        </div>
        <div class="input-box">
            <label for="password">{{ __('elfcms::default.new_password') }}</label>
            <div class="input-wrapper">
                <input type="password" name="password" id="password" autocomplete="off" value="" required>
            </div>
        </div>
        <div class="input-box">
            <label for="password_confirmation">{{ __('elfcms::default.confirm_new_password') }}</label>
            <div class="input-wrapper">
                <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="off" value="" required>
            </div>
        </div>
    </div>
    <div>
        <button type="submit" class="button submit-button">{{ __('elfcms::default.change_password') }}</button>
    </div>
</form>
<script>
const photoInput = document.querySelector('#photo')
if (photoInput) {
    inputFileImg(photoInput)
}

function inputFileImg (input) {
    if (!input) {
        console.log('err')
        return false
    }
    const wrapper = input.closest('.image-button')
    if (wrapper) {
        const img = wrapper.querySelector('.image-button-img img')
        //const text = wrapper.querySelector('.image-button-text')

        function deleteImage (wrap) {
            const del = wrap.querySelector('.delete-image')
            const hid = wrap.closest('.input-wrapper').querySelector('input[type="hidden"]')
            if (del) {
                del.addEventListener('click',function(){
                    if (img) {
                        img.src = '/images/icons/upload.png'
                    }
                    if (hid) {
                        hid.value = null
                    }
                    input.value = null
                    this.classList.add('hidden')
                    //text.innerHTML = 'Choose file';
                })
            }
        }

        deleteImage(wrapper)

        input.addEventListener('change',function(e){
            const files = e.target.files
            if (files) {
                /* if (text) {
                    text.innerHTML = files[0].name
                } */
                if (FileReader && files && files.length) {
                    var fReader = new FileReader();
                    fReader.onload = function () {
                        if (img) {
                            img.src = fReader.result;
                        }
                        const del = wrapper.querySelector('.delete-image')
                        if (del) {
                            del.classList.remove('hidden')
                        }
                    }
                    fReader.readAsDataURL(files[0]);
                }
            }
            //deleteImage(wrapper)
        })
    }
}
</script>
