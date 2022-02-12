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
    public $metaData;
    public int $pageCount;
    public int $page = 1;
    public ?int $fontSpaceLimit = null;
    public ?string $horizontalOffset = null;
    public string $parseMetrics;

    protected $queryString = [
        'fontSpaceLimit' => ['except' => -50],
        'horizontalOffset' => ['except' => ' '],
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

    public function updatingFontSpaceLimit(&$value)
    {
        $value = (int) $value;
    }

    public function updatingPdf()
    {
        $this->page = 1;
    }

    public function updated()
    {
        $this->validate([
            'pdf' => ['mimetypes:application/pdf', 'max:8000', 'required'],
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
        $this->metaData = $pdf->getDetails();
    }
}
