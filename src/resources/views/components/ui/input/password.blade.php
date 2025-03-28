<div class="input-password-wrapper" @if (!empty($color)) style="--input-color: {{ $color }}" @endif>
    <input type="password" name="{{ $name ?? 'password' }}" id="{{ $id ?? ($name ?? 'password') }}"
        value="{{ $value ?? '' }}" @disabled($disabled ?? false) @readonly($readonly ?? false)
        @if (!empty($autocomplete)) autocomplete="{{ $autocomplete }}" @endif
        @if (!empty($placeholder)) placeholder="{{ $placeholder }}" @endif>
    <div class="password-visiblity off"></div>
</div>
@once
    @push('footerscript')
        <script>
            const passwordVisiblities = document.querySelectorAll('.password-visiblity');
            if (passwordVisiblities) {
                passwordVisiblities.forEach((passwordVisiblity) => {
                    passwordVisiblity.addEventListener('click', (e) => {
                        e.preventDefault();
                        const input = e.target.previousElementSibling;
                        if (input) {
                            if (input.type === 'password') {
                                input.type = 'text';
                                passwordVisiblity.classList.remove('off');
                            } else {
                                input.type = 'password';
                                passwordVisiblity.classList.add('off');
                            }
                        }
                    });
                });
            }
        </script>
    @endpush
@endonce
