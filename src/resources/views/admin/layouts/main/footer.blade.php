<footer id="footer">
    ELF CMS v{{ config('elfcms.elfcms.version') }}
    @if (config('elfcms.elfcms.is_dev'))
        (dev)
    @elseif (config('elfcms.elfcms.is_alpha'))
        (alpha)
    @elseif (config('elfcms.elfcms.is_beta'))
        (beta)
    @endif
    | &copy; M.Klassen, 2022-{{ date('Y') }}.
</footer>
