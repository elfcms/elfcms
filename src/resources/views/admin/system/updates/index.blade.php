@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.system.index') }}" class="button round-button theme-button" style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>
    <h2>{{ __('elfcms::default.update_is_available_for_following_modules') }}</h2>
    @empty($modules->count())
        <p>
            {{ __('elfcms::default.no_updates_available') }}
        </p>
    @else
    <form action="{{ route('admin.system.update-all') }}" method="POST" enctype="multipart/form-data" data-submit="check">
        @csrf
        @method('POST')
        <div class="grid-table-wrapper">
            <table class="grid-table table-cols" style="--first-col:4rem; --minw:50rem; --cols-count:5;">
                <thead>
                    <tr>
                        <th>
                            <div class="small-checkbox-wrapper">
                                <div class="small-checkbox" style="--switch-color:var(--default-color);">
                                    <input type="checkbox" name="all-modules" id="all-modules" value="1"
                                        data-check="all" checked>
                                    <i></i>
                                </div>
                            </div>
                        </th>
                        <th>{{ __('elfcms::default.module') }}</th>
                        <th>{{ __('elfcms::default.current_version') }}</th>
                        <th>{{ __('elfcms::default.latest_version') }}</th>
                        <th>{{ __('elfcms::default.source') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @include('elfcms::admin.system.updates.content.modules')
                </tbody>
            </table>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button">{{ __('elfcms::default.update') }}</button>
            </div>
        </div>
    </form>
    @endempty
    <script>
        const checkAllInput = document.querySelector('#all-modules');
        const checkInputs = document.querySelectorAll('input[name="modules[]"]');
        if (checkAllInput && checkInputs) {
            checkAllInput.addEventListener('change', () => {
                checkInputs.forEach(input => {
                    input.checked = checkAllInput.checked;
                });
                checkAllInput.dataset.check = checkAllInput.checked ? 'all' : 'none';
            });
            checkInputs.forEach(input => {
                input.addEventListener('change', () => {
                    inputs = Array.from(checkInputs);
                    let all = inputs.every(input => input.checked);
                    let noone = inputs.every(input => !input.checked)
                    checkAllInput.checked = all;
                    if (!all && !noone) {
                        checkAllInput.dataset.check = 'part';
                    } else if (all) {
                        checkAllInput.dataset.check = 'all';
                    } else {
                        checkAllInput.dataset.check = 'none';
                    }
                })
            });
        }
        const submitButton = document.querySelector('form button[type="submit"]');
        if (submitButton) {
            submitButton.addEventListener('click', function() {
                submitButton.innerText = "{{ __('elfcms::default.will_be_updated') }}";
                submitButton.classList.add('load-button');
                setTimeout(() => {
                    submitButton.disabled = true;
                }, 200);
            });
        }
    </script>
@endsection
