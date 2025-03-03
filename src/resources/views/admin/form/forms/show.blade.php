@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="table-search-box">
        <a href="{{ route('admin.forms.groups.create', $form) }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span>{{ __('elfcms::default.create_form_field_group') }}</span>
        </a>
    </div>

    <div class="form-container" @if(!empty($pageConfig['second_color'])) style="--second-color:{{$pageConfig['second_color']}};" @endif>

        @if (!empty($form->groups))
            <div class="form-groups" data-form="{{ $form->id }}">
                @foreach ($form->groups as $group)
                    <div class="form-group-box" data-id="{{ $group->id }}">
                        <div @class(['form-group-position', 'inactive' => $group->active != 1]) draggable="true" data-title="{{ $group->title ?? $group->name }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/drag_indicator_slim.svg', svg: true) !!}
                            <span>{{ $group->position }}</span>
                        </div>
                        <div class="form-group-data">
                            <div class="form-group-title-box">
                                <div class="form-group-title">
                                    @if ($group->active != 1)
                                        <span
                                            class="form-group-inactive-title">[{{ __('elfcms::default.inactive') }}]</span>
                                    @endif
                                    {{ $group->title ?? $group->name }}
                                </div>
                                <div class="form-group-button-box">
                                    <a href="{{ route('admin.forms.groups.edit', [$form, $group]) }}"
                                        class="button icon-button"
                                        title="{{ __('elfcms::default.edit') }}">
                                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                                    </a>
                                    <form action="{{ route('admin.forms.groups.destroy', [$form, $group]) }}"
                                        method="POST" data-submit="check">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $group->id }}">
                                        <input type="hidden" name="name" value="{{ $group->title ?? $group->name }}">
                                        <button type="submit" class="button icon-button icon-alarm-button"
                                            title="{{ __('elfcms::default.delete') }}">
                                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="form-group-fields" data-id="{{ $group->id }}">
                                @if (!empty($group->fields))
                                    @foreach ($group->fields as $field)
                                        <x-elfcms::admin.formfield :field="$field" :form="$form" />
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group-fields-buttons">
                                <a href="{{ route('admin.forms.fields.create', ['form' => $form, 'group' => $group->id]) }}"
                                    class="button round-button theme-button" @if(!empty($pageConfig['second_color'])) style="--default-color:{{$pageConfig['second_color']}};" @endif>
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
                                    <span class="button-collapsed-text">{{ __('elfcms::default.create_field') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-groupless-fields">
                @foreach ($form->fieldsWithoutGroup as $field)
                    <x-elfcms::admin.formfield :field="$field" :form="$form" />
                @endforeach
            </div>
        @endif

    </div>

    <div class="table-search-box" @if(!empty($pageConfig['second_color'])) style="--default-color:{{$pageConfig['second_color']}};" @endif>
        <a href="{{ route('admin.forms.fields.create', $form) }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span>{{ __('elfcms::default.create_form_field_group') }}</span>
        </a>
    </div>

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let formId = this.querySelector('[name="id"]').value,
                        formName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_of_element') }}' + formId,
                        content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_group') }}</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.delete') }}',
                                class: 'button color-button red-button',
                                callback: function() {
                                    self.submit()
                                }
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'button cancel-button',
                                callback: 'close'
                            }
                        ],
                        class: 'danger'
                    })
                })
            })
        }
    </script>
@endsection
{{-- @section('footerscript')
<script src="{{ asset('elfcms/admin/js/popnotifi.js') }}"></script>
<script src="{{ asset('elfcms/admin/js/grouporder.js') }}"></script>
<script src="{{ asset('elfcms/admin/js/fieldorder.js') }}"></script>
@endsection --}}
@once
    @push('footerscript')
        <script src="{{ asset('elfcms/admin/js/popnotifi.js') }}"></script>
        <script src="{{ asset('elfcms/admin/js/grouporder.js') }}"></script>
        <script src="{{ asset('elfcms/admin/js/fieldorder.js') }}"></script>
    @endpush
@endonce
