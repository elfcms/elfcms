<?php

namespace Elfcms\Elfcms\Services;

use Elfcms\Elfcms\Models\Page;
use Illuminate\Http\Request;

class PageConfigService
{
    protected array $data = [];
    protected Request $request;
    protected Page $page;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->data = [
            'title' => '',
            'description' => '',
            'keywords' => '',
            'currentRoute' => '',
            'url' => '',
            'lang' => '',
            'logo' => '',
            'icon' => '',
            'header_code' => '',
            'footer_code' => '',
        ];


        $routeName = $this->request->route() ? $this->request->route()->getName() : null;

        if ($routeName) {
            $this->page = Page::where('slug',$routeName)->first();
        }
    }

    public function set(string $key, mixed $value): void
    {
        $segments = explode('.', $key);
        $data = &$this->data;

        foreach ($segments as $segment) {
            if (!isset($data[$segment]) || !is_array($data[$segment])) {
                $data[$segment] = [];
            }
            $data = &$data[$segment];
        }

        $data = $value;
    }

    public function merge(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($key === 'currentRoute') {
            return $this->request->route() ? $this->request->route()->getName() : null;
        }
        if ($key === 'url') {
            return $this->request->fullUrl();
        }

        $segments = explode('.', $key);
        $data = $this->data;

        foreach ($segments as $segment) {
            if (is_array($data) && array_key_exists($segment, $data)) {
                $data = $data[$segment];
            } else {
                return $default;
            }
        }

        return $data;
    }

    public function all(): array
    {
        $routeName = $this->request->route() ? $this->request->route()->getName() : null;
        
        $dynamicData = [
            'title' => $this->page->title ?? '',
            'description' => $this->page->meta_description ?? '',
            'keywords' => $this->page->meta_keywords ?? '',
            'currentRoute' => $routeName,
            'url' => $this->request->fullUrl(),
            'header_code' => $this->page->header_code ?? '',
            'footer_code' => $this->page->footer_code ?? '',
        ];

        return array_merge($this->data, $dynamicData);
    }
}
