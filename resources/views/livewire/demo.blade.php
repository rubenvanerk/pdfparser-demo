<div>
    <!-- Main 3 column grid -->
    <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
        <!-- Left column -->
        <div class="grid grid-cols-1 gap-4">
            <section aria-labelledby="section-1-title">
                <h2 class="sr-only" id="section-1-title">Section title</h2>
                <div class="rounded-lg bg-white overflow-hidden shadow">
                    <div class="p-6 flex-col space-y-4">
                        <div>
                            <input type="file" wire:model="pdf">
                            <div wire:loading wire:target="file">Uploading...</div>
                            @error('pdf') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        @if ($pageCount)
                            <div>
                                <label for="page" class="block text-sm font-medium text-gray-700">Page</label>
                                <select id="page"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-md"
                                        wire:model="page">
                                    @for($i = 1; $i <= $pageCount; $i++)
                                        <option value="{{ $i }}">Page {{ $i }} / {{ $pageCount }}</option>
                                    @endfor
                                </select>
                                @error('page') <span class="error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="font-space-limit" class="block text-sm font-medium text-gray-700">
                                    Font space limit
                                </label>
                                <div class="mt-1">
                                    <input type="number" id="font-space-limit"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           wire:model="fontSpaceLimit">
                                </div>
                                @error('fontSpaceLimit') <span class="error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="horizontal-offset" class="block text-sm font-medium text-gray-700">
                                    Horizontal offset
                                </label>
                                <div class="mt-1">
                                    <input type="text" id="font-space-limit"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                           wire:model="horizontalOffset">
                                </div>
                                @error('horizontalOffset') <span class="error">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <span class="text-sm text-gray-600" wire:loading.class="animate-pulse">
                                    {{ $parseMetrics }}
                                </span>
                            </div>

                            <div class="text-xs bg-gray-50 overflow-scroll rounded-lg p-2">
                                <code>
                                    $config = new \Smalot\PdfParser\Config();<br>
                                    $config->setFontSpaceLimit({{ $fontSpaceLimit }});<br>
                                    $config->setHorizontalOffset("\t");<br><br>
                                    $parser = new \Smalot\PdfParser\Parser([], $config);<br>
                                    $pdf = $parser->parseFile('path/to/file');<br>
                                    $text = $pdf->getText();<br>
                                </code>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>

        <!-- Right column -->
        <div class="grid grid-cols-1 gap-4 lg:col-span-2">
            <section aria-labelledby="section-2-title">
                <h2 class="sr-only" id="section-2-title">Section title</h2>
                <div class="rounded-lg bg-white overflow-hidden shadow">
                    <div class="p-6">
                        <div class="bg-gray-50 overflow-hidden rounded-lg" wire:loading.class="animate-pulse">
                            @if($pdf)
                                <pre class="overflow-scroll px-4 py-5 sm:p-6 ">
                                {!! $text !!}
                            </pre>
                            @else
                                <div class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Please add a file to view the results
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
