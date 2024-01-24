{{ $formheader ?? '' }}
@if (!empty($form))
@if (Session::has('success'))
    <div class="alert alert-success">{{ Session::get('success') }}</div>
@endif
@if (!empty($errors) && !empty($errors->any()))
<div class="alert alert-danger">
    <ul class="errors-list">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form @if ($form->action) action="{{$form->action}}" @endif @if ($form->method) method="{{$form->method}}" @else method="POST" @endif @if ($form->enctype) method="{{$form->enctype}}" @endif>
    @csrf
    <input type="hidden" name="redirect_to" value="{{$form->redirect_to}}">
    <input type="hidden" name="form_id" value="{{$form->id}}">
    @method('POST')
@if (!empty($form->groups))
@foreach ($form->groups as $group)
    <fieldset name="{{$group->name}}">
    @if ($group->title)
        {{-- <legend>{{$group->title}}</legend> --}}
    @endif
    @if (!empty($group->fields))
    @foreach ($group->fields as $field)
        @if ($field->type->name == 'textarea')
        <div>
            {{-- <label for="{{$field->name}}">{{$field->title}}</label> --}}
            <textarea name="{{$field->name}}" id="{{$field->name}}" cols="30" rows="10" @if ($field->required) required @endif placeholder="{{$field->placeholder}}">{{$field->value}}</textarea>
        @elseif ($field->type->name == 'select')
        <div>
            {{-- <label for="{{$field->name}}">{{$field->title}}</label> --}}
            <select name="{{$field->name}}" id="{{$field->name}}" @if ($field->required) required @endif>
            @foreach ($field->options as $option)
                <option value="{{$option->value}}" @if ($option->selected) selected @endif @if ($option->disabled) disabled @endif>{{$option->text}}</option>
            @endforeach
            </select>
        </div>
        @elseif ($field->type->name == 'radio')
        <div>
            <span>{{$field->title}}</span>
            @foreach ($field->options as $option)
            <br>
            <input type="{{$field->type->name}}" name="{{$field->name}}" id="{{$field->name}}_{{$loop->iteration}}" @if ($option->selected) checked @endif @if ($option->disabled) disabled @endif  value="{{$option->value}}">
            <label for="{{$field->name}}_{{$loop->iteration}}">{{$option->text}}</label>
            @endforeach
        </div>
        <div>
        @elseif ($field->type->name == 'checkbox')
            <input type="{{$field->type->name}}" name="{{$field->name}}" id="{{$field->name}}" value="1" @if ($field->required) required @endif>
            <label for="{{$field->name}}">{{$field->title}}</label>
        </div>
        @else
        <div>
            {{-- <label for="{{$field->name}}">{{$field->title}}</label> --}}
            <input type="{{$field->type->name}}" name="{{$field->name}}" id="{{$field->name}}" value="{{$field->value}}" @if ($field->required) required @endif placeholder="{{$field->placeholder}}">
        </div>
        @endif
    @endforeach
    @endif
    </fieldset>
@endforeach
@endif
<div>
@foreach ($form->fieldsWithoutGroup as $field)
    @if ($field->type->name == 'textarea')
    <div>
        <label for="{{$field->name}}">{{$field->title}}</label>
        <textarea name="{{$field->name}}" id="{{$field->name}}" cols="30" rows="10" @if ($field->required) required @endif placeholder="{{$field->placeholder}}">{{$field->value}}</textarea>
    </div>
    @elseif ($field->type->name == 'select')
    <div>
        <label for="{{$field->name}}">{{$field->title}}</label>
        <select name="{{$field->name}}" id="{{$field->name}}" @if ($field->required) required @endif>
        @foreach ($field->options as $option)
            <option value="{{$option->value}}" @if ($option->selected) selected @endif @if ($option->disabled) disabled @endif>{{$option->text}}</option>
        @endforeach
        </select>
    </div>
    @elseif ($field->type->name == 'radio')
    <div>
        <span>{{$field->title}}</span>
        @foreach ($field->options as $option)
        <br>
        <input type="{{$field->type->name}}" name="{{$field->name}}" id="{{$field->name}}_{{$loop->iteration}}" @if ($option->selected) checked @endif @if ($option->disabled) disabled @endif  value="{{$option->value}}">
        <label for="{{$field->name}}_{{$loop->iteration}}">{{$option->text}}</label>
        @endforeach
    </div>
    @elseif ($field->type->name == 'checkbox')
    <div>
        <input type="{{$field->type->name}}" name="{{$field->name}}" id="{{$field->name}}" value="1" @if ($field->required) required @endif>
        <label for="{{$field->name}}">{!!$field->title!!}</label>
    </div>
    @elseif ($field->type->name != 'submit')
    <div>
        <label for="{{$field->name}}">{{$field->title}}</label>
        <input type="{{$field->type->name}}" name="{{$field->name}}" id="{{$field->name}}" value="{{$field->value}}" @if ($field->required) required @endif>
    </div>
    @else
    <div>
        <label for="{{$field->name}}">{{$field->title}}</label>
        <input type="{{$field->type->name}}" name="{{$field->name}}" id="{{$field->name}}" value="{{$field->value}}" @if ($field->required) required @endif placeholder="{{$field->placeholder}}">
    </div>
    @endif
@endforeach
</div>
<div>
@if ($submit)
    <button type="{{$submit->type->name}}" name="{{$submit->name}}" id="{{$submit->name}}" value="{{$submit->value}}">{{$submit->title}}</button>
@elseif ($form->submit_button || $form->submit_title)
    <button type="submit" name="{{$form->submit_name ?? 'submit'}}" id="{{$form->submit_name ?? 'submit'}}" value="{{$form->submit_value ?? ''}}">{{$form->submit_title ?? $form->submit_button}}</button>
@endif
@if ($reset)
    <button type="{{$reset->type->name}}" name="{{$reset->name}}" id="{{$reset->name}}" value="{{$reset->value}}">{{$reset->title}}</button>
@elseif ($form->reset_button || $form->reset_title)
    <button type="reset" name="{{$form->reset_name ?? 'reset'}}" id="{{$form->reset_name ?? 'reset'}}" value="{{$form->reset_value ?? ''}}">{{$form->reset_title ?? $form->reset_button}}</button>
@endif
</div>
</form>
@endif
{{ $formfooter ?? '' }}
