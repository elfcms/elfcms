<?php

namespace Elfcms\Elfcms\Aux;

use Elfcms\Elfcms\Models\FileCatalog;

class Files
{

    public static function name(string $file)
    {
        $name = FileCatalog::name($file);

        if (!$name) {
            $name = basename($file);
        }

        return $name;
    }
}
