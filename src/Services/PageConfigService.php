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
        return $this->data;
    }
}
