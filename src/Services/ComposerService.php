<?php

namespace Elfcms\Elfcms\Services;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ComposerService
{
    protected string $composerLocalPath;
    protected array $env;

    public function __construct()
    {
        $this->composerLocalPath = base_path('composer.phar');
        $this->env = [
            'COMPOSER_HOME' => base_path('.composer')
        ];
    }

    public function run(array $arguments): ComposerResult
    {
        $command = $this->getComposerCommand();
        $command = array_merge($command, $arguments);

        try {
            $process = new Process($command, base_path(), $this->env);
            $process->setTimeout(300);
            $process->run();

            return new ComposerResult(
                $process->isSuccessful(),
                $process->isSuccessful() ? $process->getOutput() : null,
                $process->isSuccessful() ? null : $process->getErrorOutput()
            );
        } catch (\Throwable $e) {
            return new ComposerResult(false, null, $e->getMessage());
        }
    }

    public function require(string $package): ComposerResult
    {
        return $this->run(['require', $package]);
    }

    public function remove(string $package): ComposerResult
    {
        return $this->run(['remove', $package]);
    }

    public function update(?string $package = null): ComposerResult
    {
        return $this->run($package ? ['update', $package] : ['update']);
    }

    public function dumpAutoload(): ComposerResult
    {
        return $this->run(['dump-autoload']);
    }

    public function info(string $package): ComposerResult
    {
        return $this->run(['show', $package, '-v']);
    }

    protected function getComposerCommand(): array
    {
        if ($this->isGlobalComposerAvailable()) {
            return ['composer'];
        }

        if (!File::exists($this->composerLocalPath)) {
            $this->downloadLocalComposer();
        }

        return ['php', $this->composerLocalPath];
    }

    protected function isGlobalComposerAvailable(): bool
    {
        try {
            $process = new Process(['composer', '--version'], null, $this->env);
            $process->setTimeout(10);
            $process->run();

            return $process->isSuccessful();
        } catch (\Throwable) {
            return false;
        }
    }

    protected function downloadLocalComposer(): void
    {
        $url = 'https://getcomposer.org/composer-stable.phar';
        $contents = file_get_contents($url);

        if (!$contents) {
            throw new \Exception("Failed to download composer.phar");
        }

        file_put_contents($this->composerLocalPath, $contents);
        File::chmod($this->composerLocalPath, 0755);
    }
}
