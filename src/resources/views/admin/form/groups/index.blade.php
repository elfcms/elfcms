@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.forms.groups.create', $form) }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">{{ __('elfcms::default.create_form_field_group') }}</span>
        </a>
    </div>
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:65px; --last-col:180px; --minw:800px; --cols-count:8;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.form') }}</th>
                    <th>{{ __('elfcms::default.title') }}</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.code') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th>{{ __('elfcms::default.updated') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr data-id="{{ $group->id }}">
                        <td class="subline-{{ $group->level }}">{{ $group->id }}</td>
                        <td>
                            <a href="{{ route('admin.forms.edit', $group->form) }}">
                                {{ $group->form->title }} [{{ $group->form->id }}]
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.forms.groups.edit', ['form'=>$group->form,'group'=>$group]) }}">
                                {{ $group->title }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.forms.groups.edit', ['form'=>$group->form,'group'=>$group]) }}">
                                {{ $group->name }}
                            </a>
                        </td>
                        <td>{{ $group->code }}</td>
                        <td>{{ $group->created_at }}</td>
                        <td>{{ $group->updated_at }}</td>
                        <td class="table-button-column">
                            <form action="{{ route('admin.forms.fields.create',['form'=>$group->form,'group'=>$group]) }}" method="GET">
                                <input type="hidden" name="group_id" value="{{ $group->id }}">
                                <button type="submit" class="button icon-button"
                                    title="{{ __('elfcms::default.add_field') }}">
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/list_plus.svg', svg: true) !!}
                                </button>
                            </form>
                            <a href="{{ route('admin.forms.groups.edit', ['form'=>$group->form,'group'=>$group]) }}" class="button icon-button"
                                title="{{ __('elfcms::default.edit') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                            </a>
                            <form action="{{ route('admin.forms.groups.destroy', ['form'=>$group->form,'group'=>$group]) }}" method="POST"
                                data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $group->id }}">
                                <input type="hidden" name="name" value="{{ $group->name }}">
                                <button type="submit" class="button icon-button icon-alarm-button"
                                    title="{{ __('elfcms::default.delete') }}">
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const checkGroups = document.querySelectorAll('form[data-submit="check"]')

        if (checkGroups) {
            checkGroups.forEach(group => {
                group.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let groupId = this.querySelector('[name="id"]').value,
                        groupName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_of_element') }}' + groupId,
                        content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_group') }} "' +
                            groupName + '" (ID ' + groupId + ')?</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.delete') }}',
                                class: 'button delete-button',
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
