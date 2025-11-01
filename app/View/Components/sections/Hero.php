<?php

namespace App\View\Components\sections;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Hero extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public $subtitle;
    public function __construct()
    {
        $this->title = "UPK MANDE";
        $this->subtitle = "Unit pengelola Kegiatan (UPK) merupakan bagian dari Program Pemberdayaan Kecamatan (PPK). UPK dibentuk untuk kepentingan operasional PPK.";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sections.hero');
    }
}
