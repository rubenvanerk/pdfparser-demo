<?php

namespace App\View\Components;

use Composer\InstalledVersions;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PdfparserVersion extends Component
{
    public function render(): View
    {
        $version = InstalledVersions::getPrettyVersion('smalot/pdfparser');

        return view('components.pdfparser-version', [
            'version' => $version,
        ]);
    }
}
