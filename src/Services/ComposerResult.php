<?php

namespace Elfcms\Elfcms\Services;

class ComposerResult
{
    protected bool $success;
    protected ?string $output;
    protected ?string $error;

    public function __construct(bool $success, ?string $output = null, ?string $error = null)
    {
        $this->success = $success;
        $this->output = $output;
        $this->error = $error;
    }

    public function success(): bool
    {
        return $this->success;
    }

    public function failed(): bool
    {
        return !$this->success;
    }

    public function output(): ?string
    {
        return $this->output;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'output' => $this->output,
            'error' => $this->error,
        ];
    }
}