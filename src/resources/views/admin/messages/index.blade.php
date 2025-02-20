@extends('elfcms::admin.layouts.message')

@section('message-content')
<div class="table-search-box">
    <a href="{{ route('admin.messages.create') }}" class="button success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_message')}}</a>
</div>
@if (Session::has('success'))
<div class="alert alert-alternate">{{ Session::get('success') }}</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="widetable-wrapper">
    <table class="grid-table table-cols-6" style="--first-col:65px; --last-col:140px; --minw:800px">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('elfcms::default.code') }}</th>
                <th>{{ __('elfcms::default.name') }}</th>
                <th>{{ __('elfcms::default.period') }}</th>
                <th>{{ __('elfcms::default.active') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($messages as $message)
            <tr data-id="{{ $message->id }}" @class(['inactive' => $message->active != 1])>
                <td>{{ $message->id }}</td>
                <td>{{ $message->code }}</td>
                <td>
                    <a href="{{ route('admin.messages.edit',$message) }}">
                        {{ $message->name }}
                    </a>
                </td>
                <td>
                    @if($message->date_from) {{ __('elfcms::default.from_date') . ' ' . $message->date_from }} @endif
                    @if($message->date_to) {{ __('elfcms::default.to_date') . ' ' . $message->date_to }} @endif
                </td>
                <td>{{ $message->active != 1 ? __('elfcms::default.inactive') : '' }}</td>
                <td class="button-column non-text-buttons">
                    <a href="{{ route('admin.messages.edit',$message) }}" class="button edit-button" title="{{ __('elfcms::default.edit_message') }}"></a>
                    <form action="{{ route('admin.messages.update',$message) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="id" value="{{ $message->id }}">
                        <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$message->active }}">
                        <input type="hidden" name="notedit" value="1">
                        <button type="submit" @if ($message->is_confirmed == 1) class="button deactivate-button" title="{{__('elfcms::default.deactivate') }}" @else class="button activate-button" title="{{ __('elfcms::default.activate') }}" @endif>

                        </button>
                    </form>
                    <form action="{{ route('admin.messages.destroy',$message->id) }}" method="POST" data-submit="check">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" value="{{ $message->id }}">
                        <input type="hidden" name="name" value="{{ $message->name }}">
                        <button type="submit" class="button delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    const checkForms = document.querySelectorAll('form[data-submit="check"]')

    if (checkForms) {
        checkForms.forEach(message => {
            message.addEventListener('submit',function(e){
                e.preventDefault();
                let messageId = this.querySelector('[name="id"]').value,
                    messageName = this.querySelector('[name="name"]').value,
                    self = this
                popup({
                    title:'{{ __('elfcms::default.deleting_of_message') }}',
                    content:'<p>{{ __('elfcms::default.message') }} "' + messageName + '" (ID ' + messageId + ')</p>' + '<p>{{ __('elfcms::default.are_you_sure_to_deleting_message') }} </p>',
                    buttons:[
                        {
                            title:'{{ __('elfcms::default.delete') }}',
                            class:'button delete-button',
                            callback: function(){
                                self.submit()
                            }
                        },
                        {
                            title:'{{ __('elfcms::default.cancel') }}',
                            class:'button cancel-button',
                            callback:'close'
                        }
                    ],
                    class:'danger'
                })
            })
        })
    }
</script>
@endsection
