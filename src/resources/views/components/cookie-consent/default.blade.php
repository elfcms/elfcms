<style>
    .cookie-consent-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.2);
    }
    .cookie-consent-box {
        width: 100%;
        max-width: 540px;
        margin: auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 5px;
        box-sizing: border-box;
    }
    .cookie-consent-buttons {
        display: flex;
        justify-content: flex-end;
    }
    .cookie-consent-accept {
        color: #ffffff;
        background-color: #1e90ff;
        border: 0;
        border-radius: 4px;
        font-size: 16px;
        padding: 6px 18px;
        cursor: pointer;
    }
    .cookie-consent-accept:hover {
        box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.2);
    }
    .cookie-consent-label {
        position: fixed;
        bottom: 70px;
        left: 25px;
        background-color: #1e90ff;
        padding: 7px;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0px 0px 6px 0px rgba(0, 0, 0, 0.2);
    }
    .cookie-consent-label:hover {
        box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.4);
    }
    .cookie-consent-label::after {
        content: '';
        display: block;
        width: 30px;
        height: 30px;
        background-position: center;
        background-size: contain;
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAQAAABIkb+zAAAC83pUWHRSYXcgcHJvZmlsZSB0eXBlIGV4aWYAAHja7ZdRkuMqDEX/WcUswZIQEsvBYKpmB7P8uWDi6aQzH3nva6oMbSAgX4QOkHQ4fv3s4QcSZU8hqnnKKW1IMcfMBQ3fzlRmSVuc5UxpDeHzU3+4BhhdglrOj74G6NFPl8BZFbT0i5DXNbA/D+S49P1FaE0kwyNGoy2hvISEzwFaAqWspWS3r0vYj7Nuj5X4+YRRpE4PsfPll8/REL2mmEeYDyHZUIosGxlPDFJmY5QOQxJHm8RQitDyBAF5F6crZXjUh6vxrdETlatF7/vDK63Iy0Regpyu+m1/IH1PZYb+y8zRV4uf+01OqbC9RH88vTfvc81YRYkJoU5rUY+lzBbsdkwxpvYAvbQZHoWEzZyRHbu6Yiu0rW47cqVMDFydIjUq1OmYdaUKFyMfgQ0N5soyO12MM1cZ/OLI1NkkSwNNljqxR+HLF5rT5q2GOZtj5kYwZYIY4ZWPc/j0hd7HUSDa/IoV/GIewYYbg9woYQYi1FdQdQb4kV/T4CogqCPK44hkBHY/JXalPzeBTNACQ0V9nkGytgQQIkytcIYEBECNRCnRZsxGhEA6ABW4jgPEOwiQKjc4yVEkgQ1OEqbGK0bTlJXRHdCPywwkVBJOmINQAawYFfvHomMPFRWNqprU1DVrSZJi0pSSpXEpFhOLwdSSmbllKy4eXT25uXv2kjkLLk3NKVv2nHMpmLNAueDtAoNSdt5lj7uGPe22+573UrF9aqxaU7XqNdfSuEnD/dFSs+Ytt3LQga10xEOPdNjhRz5Kx1brEnrs2lO37j33clFbWL/lD6jRosaT1DC0ixp6zR4SNK4THcwAjEMkELeBABuaB7PNKUYe5AazLTNOhTKc1MGs0SAGgvEg1k4PdoFPooPc/+IWLD5x4/9KLgx0H5L7zu0dtTa+huokdp7CEdRNcPpgU9jxh++q73X428Cn9S10C91Ct9AtdAvdQv+OkHT8eBj/Bf4GzYSn0GEGrS0AAAEiaUNDUElDQyBwcm9maWxlAAB4nJ2QsUrDUBSGv1TRIjqI4iAOGVwLLmZyqQpBsBBjBaNTmqRYTGJIUopv4Jvow3QQBJ/BWcHZ/0YHB7N4w+H/OJzz//cGOnYaZdXiHmR5Xbp+P7gMruzlNzp0WddnhVFV9D3vlNbz+Ypl9KVnvNrn/jxLcVJF0rkqj4qyButA7MzqwrCKzduhfyR+ENtxlsfiJ/FunMWGza6fpdPox9PcZjXJL85NX7WDywkDPGxGTJmQUtOT5uoc47AvdSkJuacikqYk6s00U3MjquTkcigainSblrztJs9TykgeE3mZhDsyeZo8zP/9Xvs4azatrXkRlmHTWlB1xmN4f4S1ADaeYeW6Jav7+20tM04z8883fgGJAFA1DwI/0AAADz5pVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+Cjx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDQuNC4wLUV4aXYyIj4KIDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+CiAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIKICAgIHhtbG5zOkdJTVA9Imh0dHA6Ly93d3cuZ2ltcC5vcmcveG1wLyIKICAgIHhtbG5zOnRpZmY9Imh0dHA6Ly9ucy5hZG9iZS5jb20vdGlmZi8xLjAvIgogICAgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIgogICB4bXBNTTpEb2N1bWVudElEPSJnaW1wOmRvY2lkOmdpbXA6ZjEzZTNlMzktZWZiMS00NmJjLThiNmYtOGU3YTUyZDY2OGE0IgogICB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOmJlMTJhOTlhLTg4OWMtNDk1NC04M2NkLWYwNGMxYzAwZjZjZSIKICAgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjgxZDMwZDRlLWRhMzItNGI5Zi1hNmYzLWU4ZGY1ZGJmNDUwZiIKICAgZGM6Rm9ybWF0PSJpbWFnZS9wbmciCiAgIEdJTVA6QVBJPSIyLjAiCiAgIEdJTVA6UGxhdGZvcm09IkxpbnV4IgogICBHSU1QOlRpbWVTdGFtcD0iMTcwNzQxMjY1MTgzMDMxOSIKICAgR0lNUDpWZXJzaW9uPSIyLjEwLjMwIgogICB0aWZmOk9yaWVudGF0aW9uPSIxIgogICB4bXA6Q3JlYXRvclRvb2w9IkdJTVAgMi4xMCIKICAgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMzoxMTowOVQxNTo1NjoyNSswMTowMCIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjM6MTE6MDlUMTU6NTY6MjUrMDE6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJzYXZlZCIKICAgICAgc3RFdnQ6Y2hhbmdlZD0iLyIKICAgICAgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo4MDA1ZjE1ZC1iZmVkLTQ2YTQtYmM0Yi1hMzNmZWFlZmY5ZDkiCiAgICAgIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkdpbXAgMi4xMCAoTGludXgpIgogICAgICBzdEV2dDp3aGVuPSIyMDIzLTExLTA5VDE1OjU2OjI3KzAxOjAwIi8+CiAgICAgPHJkZjpsaQogICAgICBzdEV2dDphY3Rpb249InNhdmVkIgogICAgICBzdEV2dDpjaGFuZ2VkPSIvIgogICAgICBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjMwYWJhYmEzLTJmY2ItNGY1ZC05YjNmLTRkMDlkMWViNmRiMyIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iR2ltcCAyLjEwIChMaW51eCkiCiAgICAgIHN0RXZ0OndoZW49IjIwMjQtMDItMDhUMTg6MDU6MjArMDE6MDAiLz4KICAgICA8cmRmOmxpCiAgICAgIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiCiAgICAgIHN0RXZ0OmNoYW5nZWQ9Ii8iCiAgICAgIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MmVhYzNlOGItZjg5NC00YmY1LTgzMDctODlhZDA5MjJhNzAxIgogICAgICBzdEV2dDpzb2Z0d2FyZUFnZW50PSJHaW1wIDIuMTAgKExpbnV4KSIKICAgICAgc3RFdnQ6d2hlbj0iMjAyNC0wMi0wOFQxODoxNzozMSswMTowMCIvPgogICAgPC9yZGY6U2VxPgogICA8L3htcE1NOkhpc3Rvcnk+CiAgPC9yZGY6RGVzY3JpcHRpb24+CiA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgCjw/eHBhY2tldCBlbmQ9InciPz49glsPAAAAAmJLR0QAIebkM2wAAAAJcEhZcwAAIN4AACDeASoa/BYAAAAHdElNRQfoAggRER9Kgq9iAAALgklEQVR42s1ca1BV1xX+7hXQC6gIZFREAR0jan20UFOrsdf3o72tlZiMY9Jo2kBtbGN9xKgzVpvID2OkrUnT1kl8EDsZO8ZJTVJTFbAZzYRqUZH4iAoogiIIBBFB5OsPLoe9z+Oecy/nonv/uefs11pn773W2t9a+zoI25ILcUjCCIxAAgYgBhFwIcxbdhtDcRtBSA4bGIjAcIxGPL6L4XgMEeimWysSDcFgIKSTrZPxI0zDWETD4bNmMe4CjxYDvTAJ8zEVAyzVPg4+Ogw4kISpmIvJcFlucx14VBjog2exFENNlgwAUKgT+mgw0B3TsRwTfZBzD7dwDddRihuoxd/g9L4fBEdwFpF1KeTASGTgZ+il+61r8BXO4RL24SpaFFIr8Zj3VzlGoC4oU0BrOZwZLKZeauR/+TsOpkOn1RdCvclEMLK1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');
    }
    .hidden {
        display: none;
    }

.checkbox-switch-wrapper {
    display: flex;
    align-items: center;
    padding: 0px 10px;
    margin-bottom: .5rem;
}

.checkbox-switch {
    position: relative;
    width: 36px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #575757;
}
.checkbox-switch.green {
    color: #09b57c;
}
.checkbox-switch.blue {
    color: #1e90ff;
}
.checkbox-switch.darkblue {
    color: #074ca5;
}
.checkbox-switch.red {
    color: #ff6347;
}
.checkbox-switch > input[type="checkbox"] {
    z-index: 2;
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}
.checkbox-switch > input[type="checkbox"] + i {
    display: block;
    position: relative;
    width: 100%;
    height: 100%;
    background-color: #d3d3d3;
    border: 1px solid #ffffff;
    border-radius: 12px;
    box-shadow: inset 0px 0px 3px 0px rgba(0, 0, 0, 0.3);
    box-sizing: border-box;
}
.checkbox-switch > input[type="checkbox"] + i::after {
    content: "";
    display: block;
    width: 20px;
    height: 20px;
    border-radius: 10px;
    position: absolute;
    top: 1px;
    left: 1px;
    background-color: #ffffff;
    box-shadow: 0px 0px 2px 0px rgba(0, 0, 0, 0.3),
        inset -3px -3px 3px 0px rgba(0, 0, 0, 0.1);
    transition: 0.1s;
}
.checkbox-switch > input[type="checkbox"]:checked + i {
    background-color: currentColor;
}
.checkbox-switch > input[type="checkbox"]:checked + i::after {
    left: calc(100% - 21px);
}
.checkbox-switch-wrapper label {
    margin-left: 1rem
}
.checkbox-switch.disabled {
    opacity: 0.5;
    filter: grayscale(0.5);
}
.checkbox-switch > input[type="checkbox"]:disabled {
    cursor: not-allowed;
}
    </style>
    <div @class(['cookie-consent-wrapper', 'hidden' => $consented])>
        <div class="cookie-consent-box">
            <form action="{{ route('cookie-consent') }}" name="cookie-consent" method="post">
                @method('POST')
                @csrf
                @if (!empty($categories))
                <div class="cookie-consent-title">
                    {{ __('elfcms::default.cookie_settings_title') }}
                </div>
                @endif
                <div class="cookie-consent-content">
                    <p>
                        {!! $text !!}
                    </p>
                @if (!empty($categories))
                <div class="cookie-consent-category">
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.cookie_technically_necessary') }}" :checked="1" :readonly="1" style="blue" />
                </div>
                @endif
                @forelse ($categories as $category)
                    <div class="cookie-consent-category">
                        <x-elfcms-input-checkbox code="categories[{{ $category['name'] }}]" label="{{ $category['name'] }}" :checked="($category['required'] || $category['consented'] || !$consented)" :readonly="$category['required']" style="blue" />
                    </div>
                @empty

                @endforelse
                </div>
                <div class="cookie-consent-buttons">
                    <button type="submit" class="cookie-consent-accept">{{ __('elfcms::default.cookie_accept') }}</button>
                </div>
            </form>
        </div>
    </div>
    <div @class(['cookie-consent-label', 'hidden' => !$consented])></div>
    <script>
    const  cookieConsentBox = document.querySelector('.cookie-consent-wrapper');
    const  cookieConsentButton = document.createElement('button.cookie-consent-accept');
    const  cookieConsentLabel = document.querySelector('.cookie-consent-label');
    const cookieConsentForm = document.querySelector('form[name=cookie-consent]');

    function saveCookie() {
        const formData = new FormData(cookieConsentForm);
        const token = document.querySelector("input[name='_token']").value;
        fetch(cookieConsentForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
            },
            credentials: 'same-origin',
            body: formData
        }).then((result)=>result.json()).
        then((data)=>{
            hideCookieConsent();
        }
        ).catch(error=>{
            hideCookieConsent();
        });
    }

    if (cookieConsentForm) {
        cookieConsentForm.addEventListener('submit',function(e){
            e.preventDefault();
            saveCookie();
        });
    }

    if (cookieConsentLabel) {
        cookieConsentLabel.addEventListener('click',showCookieConsent);
    }
    function showCookieConsent() {
        if (cookieConsentBox) {
            cookieConsentBox.classList.remove('hidden');
        }
        if (cookieConsentLabel) {
            cookieConsentLabel.classList.add('hidden');
        }
    }
    function hideCookieConsent(e) {
        if (cookieConsentBox) {
            cookieConsentBox.classList.add('hidden');
        }
        if (cookieConsentLabel) {
            cookieConsentLabel.classList.remove('hidden');
        }
    }
    </script>
