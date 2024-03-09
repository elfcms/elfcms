<?php

namespace Elfcms\Elfcms\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;

class AdminImageUpload extends Component
{
    use WithFileUploads;

    public $maxSize = 1024;

    #[Rule('image|max:' . $this->maxSize)] // 1MB Max
    public $image;

    public function save()
    {
        $this->image->store('elfcms/settings/site/image');
    }

    public function render()
    {
        return view('elfcms.livewire.admin-image-upload');
    }
}
