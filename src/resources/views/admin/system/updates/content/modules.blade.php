@if (!empty($modules->count()))
    @foreach ($modules as $module)
        @include('elfcms::admin.system.updates.content.module')
    @endforeach
@endif
