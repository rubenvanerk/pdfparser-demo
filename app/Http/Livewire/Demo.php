<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\Stopwatch\Stopwatch;

class Demo extends Component
{
    use WithFileUploads;

    public $pdf;
    public $text;
    public int $pageCount;
    public int $page = 1;
    public $fontSpaceLimit = null;
    public ?string $horizontalOffset = null;
    public string $parseMetrics;

    protected $queryString = [
        'fontSpaceLimit',
        'horizontalOffset',
    ];

    public function mount()
    {
        $config = new \Smalot\PdfParser\Config();
        $this->fontSpaceLimit = $this->fontSpaceLimit ?: $config->getFontSpaceLimit();
        $this->horizontalOffset = $this->horizontalOffset ?: $config->getHorizontalOffset();
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


        $stopwatch = new Stopwatch();
        $stopwatch->start('parseTime');
        $this->parsePdf();
        $event = $stopwatch->stop('parseTime');

        $this->parseMetrics = sprintf('%.2F MiB - %d ms', $event->getMemory() / 1024 / 1024, $event->getDuration());
    }

    private function parsePdf()
    {
        $config = new \Smalot\PdfParser\Config();
        $config->setFontSpaceLimit($this->fontSpaceLimit);
        $config->setHorizontalOffset(stripcslashes($this->horizontalOffset));

        $parser = new \Smalot\PdfParser\Parser([], $config);
        $pdf = $parser->parseFile($this->pdf->getRealPath());

        $this->text = $pdf->getPages()[$this->page - 1]?->getText();
        $this->pageCount = count($pdf->getPages());
    }
}