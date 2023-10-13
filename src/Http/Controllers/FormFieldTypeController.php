<?php

namespace Elfcms\Elfcms\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\FormFieldType;
use Illuminate\Http\Request;

class FormFieldTypeController extends Controller
{
    public function start()
    {
        $fft = new FormFieldType;
        $fft->start();
    }
}
