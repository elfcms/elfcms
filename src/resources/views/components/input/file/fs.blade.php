<div class="inputfile" id="inputfile_{{ $boxId }}">
    <div @class(['inputfile-buttons', 'hidden' => empty($params['value'])])>
        <div class="inputfile-delete"></div>
        @if ($params['download'] && !empty($params['value']))
            <a href="{{ $params['value'] }}" class="inputfile-download" download
                title="{{ __('elfcms::default.download') }}">{!! iconHtmlLocal('/elfcms/admin/images/icons/download.svg', svg: true) !!}</a>
        @endif
    </div>
    <input type="hidden" name="{{ $params['value_name'] }}" value="{{ $params['value'] }}">
    <div class="inputfile-icon default-icon">
        @if (empty($params['value']))
            {!! iconHtmlLocal('/elfcms/admin/images/icons/upload.svg', svg: true) !!}
        @elseif ($params['isImage'])
            <img src="{{ $params['value'] }}" alt="">
        @elseif (!empty($params['icon']))
            {!! iconHtmlLocal($params['icon'], svg: true) !!}
        @else
            {!! iconHtmlLocal('/elfcms/admin/images/icons/filestorage/none.svg', svg: true) !!}
        @endif
    </div>
    <div class="inputfile-title">{{ $params['file_name'] }}</div>
    <input type="file" name="{{ $params['name'] }}" accept="{{ $params['accept'] ?? '*/*' }}">
</div>
@once
    @push('footerscript')
        <script>
            function inputfileInit(inputfileBox) {
                let box = null;
                if (typeof inputfileBox == 'string') {
                    box = document.querySelector(inputfileBox);
                }
                if (!box || !(box instanceof HTMLElement)) {
                    return;
                }
                const inputfile = box.querySelector('input[type="file"]');
                const inputfileDelete = box.querySelector('.inputfile-delete');
                const inputfileIcon = box.querySelector('.inputfile-icon');
                const inputfileTitle = box.querySelector('.inputfile-title');
                const inputfileButtons = box.querySelector('.inputfile-buttons');
                const inputfileHidden = box.querySelector('input[type="hidden"]');

                inputfileDelete.addEventListener('click', () => {
                    inputfileHidden.value = null;
                    inputfileButtons.classList.add('hidden');
                    inputfileIcon.innerHTML = '';
                    inputfileIcon.insertAdjacentHTML('beforeend', `{!! iconHtmlLocal('/elfcms/admin/images/icons/upload.svg', svg: true) !!}`);
                    inputfileTitle.textContent = `{{ __('elfcms::default.choose_file') }}`;
                });

                inputfile.addEventListener('change', function(e) {
                    const files = e.target.files
                    if (files) {
                        if (inputfileTitle) {
                            inputfileTitle.textContent = files[0].name
                        }
                        if (FileReader && files && files.length) {
                            var fReader = new FileReader();
                            fReader.onload = function() {
                                if (inputfileIcon) {
                                    let type = files[0].type.split('/')[1];
                                    if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'svg+xml', 'webp','ico','vnd.microsoft.icon'].includes(
                                            type)) {
                                        const img = document.createElement('img')
                                        img.src = fReader.result;
                                        inputfileIcon.innerHTML = '';
                                        inputfileIcon.appendChild(img);
                                    } else {
                                        fetch(adminPath + '/helper/file-icon-data/' + (type ?? 'any'), {
                                                headers: {
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                }
                                            })
                                            .then((response) => {
                                                return response.text();
                                            })
                                            .then((data) => {
                                                inputfileIcon.innerHTML = '';
                                                inputfileIcon.insertAdjacentHTML('beforeend', data);
                                            });
                                    }
                                    inputfileHidden.value = null;
                                    inputfileButtons.classList.remove('hidden');
                                }
                            };
                            fReader.readAsDataURL(files[0]);
                        }
                    }
                })


            }
        </script>
    @endpush
@endonce
@push('footerscript')
    <script>
        inputfileInit('#inputfile_{{ $boxId }}')
    </script>
@endpush
