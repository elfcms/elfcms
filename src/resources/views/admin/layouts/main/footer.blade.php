<footer id="footer">
    ELF CMS v{{ config('elfcms.elfcms.version') }}
    {{-- @if (config('elfcms.elfcms.is_dev'))
        (dev)
    @elseif (config('elfcms.elfcms.is_alpha'))
        (alpha)
    @elseif (config('elfcms.elfcms.is_beta'))
        (beta)
    @endif --}}
    @if (in_array(config('elfcms.elfcms.release_status'),['dev','alpha','beta']))
        {{ config('elfcms.elfcms.release_status') }}
    @endif
    | &copy; M.Klassen, 2022-{{ date('Y') }}.
</footer>
