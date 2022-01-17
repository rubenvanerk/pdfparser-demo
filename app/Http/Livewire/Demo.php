<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class Demo extends Component
{
    use WithFileUploads;

    public $pdf;
    public $text;
    public int $pageCount;
    public int $page = 1;
    public ?int $fontSpaceLimit = null;
    public ?string $horizontalOffset = null;

    protected $queryString = [
        'fontSpaceLimit',
        'horizontalOffset',
    ];

    public function mount()
    {
        $config = new \Smalot\PdfParser\Config();
        $this->fontSpaceLimit = $config->getFontSpaceLimit();
        $this->horizontalOffset = $config->getHorizontalOffset();
    }

    public function render()
    {
        return view('livewire.demo');
    }

    public function updated()
    {
        $this->validate([
            'pdf' => ['mimetypes:application/pdf', 'max:1024', 'required'],
            'fontSpaceLimit' => ['int', 'required'],
            'horizontalOffset' => ['nullable'],
        ]);

        $this->parsePdf();
    }

    private function parsePdf()
    {
        $config = new \Smalot\PdfParser\Config();
        $config->setFontSpaceLimit($this->fontSpaceLimit);
        $config->setHorizontalOffset(stripcslashes($this->horizontalOffset));

        $parser = new \Smalot\PdfParser\Parser([], $config);
        $pdf = $parser->parseFile($this->pdf->getRealPath());

        $this->text = $pdf->getPages()[$this->page]?->getText();
        $this->pageCount = count($pdf->getPages());
    }
}
