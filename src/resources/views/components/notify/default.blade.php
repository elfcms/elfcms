@push('footerscript')
    <script>
        try {
            new Notify({
                close: {
                    auto: true,
                    time: 300,
                    delay: 5000
                },
                position: 'center',
            }).new({
                type: '{{ $type }}',
                title: '{{ $title }}',
                text: '{!! $text !!}',
            });
        } catch (e) {
            //
        }
    </script>
@endpush
