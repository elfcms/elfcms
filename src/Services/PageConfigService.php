<?php

namespace Elfcms\Elfcms\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PageConfigService
{
    protected array $data = [];

    public function __construct(Request $request)
    {
        $this->data = [
            'title' => '',
            'description' => '',
            'keywords' => '',
            'currentRoute' => Route::currentRouteName(),
            'url' => $request->fullUrl(),
            'lang' => '',
            'logo' => '',
            'icon' => ''
        ];
    }

    public function set(string $key, mixed $value): void
    {
        logger()->info("PageConfig set $key = $value");
        $this->data[$key] = $value;
    }

    public function merge(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function get(string $key)
    {
        return $this->data[$key];
    }

    public function all(): array
    {
        return $this->data;
    }
}
