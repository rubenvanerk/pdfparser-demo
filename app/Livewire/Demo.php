<?php

namespace App\Livewire;

use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Smalot\PdfParser\Config;
use Smalot\PdfParser\Parser;
use Symfony\Component\Stopwatch\Stopwatch;

class Demo extends Component
{
    use WithFileUploads;

    public $pdf;
    public $text;
    public $metaData;
    public ?int $pageCount;
    public int $page = 1;
    #[Url(except: -50)]
    public ?int $fontSpaceLimit = null;
    #[Url(except: ' ')]
    public ?string $horizontalOffset = null;
    public string $parseMetrics;
    public ?string $exceptionMessage = null;

    public function mount(): void
    {
        $config = new Config();
        $this->fontSpaceLimit = $this->fontSpaceLimit ?: $config->getFontSpaceLimit();
        $this->horizontalOffset = $this->horizontalOffset ?: $config->getHorizontalOffset();
    }

    public function updatingFontSpaceLimit(&$value): void
    {
        $value = (int) $value;
    }

    public function updatingPdf(): void
    {
        $this->page = 1;
        $this->exceptionMessage = null;
    }

    public function parse(): void
    {
        $this->validate([
            'pdf' => ['mimetypes:application/pdf', 'max:8000', 'required'],
            'fontSpaceLimit' => ['int'],
            'horizontalOffset' => ['nullable'],
        ]);

        ray()->ban();

        try {
            $stopwatch = new Stopwatch();
            $stopwatch->start('parseTime');
            $this->parsePdf();
            $event = $stopwatch->stop('parseTime');

            $this->parseMetrics = sprintf('%.2F MiB - %d ms', $event->getMemory() / 1024 / 1024, $event->getDuration());
        } catch (Exception $exception) {
            $this->exceptionMessage = $exception->getMessage();

            $this->pageCount = null;
        }

        $this->text = 'hai';
    }

    private function parsePdf(): void
    {
        $config = new Config();
        $config->setFontSpaceLimit($this->fontSpaceLimit);
        $config->setHorizontalOffset(stripcslashes($this->horizontalOffset));

        $parser = new Parser([], $config);
        $pdf = $parser->parseFile($this->pdf->getRealPath());

        $this->text = $pdf->getPages()[$this->page - 1]?->getText();
        $this->pageCount = count($pdf->getPages());
        $this->metaData = $pdf->getDetails();
    }
}
