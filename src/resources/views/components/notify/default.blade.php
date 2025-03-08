@push('footerscript')
    <script>
        try {
            new Notify({
                close: {
                    auto: {{ $close }},
                    time: {{ $time ?? 300 }},
                    delay: {{ $delay ?? 5000 }}
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
