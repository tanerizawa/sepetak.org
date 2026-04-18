@php
    /**
     * @var \Illuminate\Support\Collection $items Koleksi Spatie\MediaLibrary\MediaCollections\Models\Media
     * @var string $emptyText
     */
    $emptyText = $emptyText ?? 'Belum ada berkas.';
@endphp

@if ($items->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ $emptyText }}</p>
@else
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
        @foreach ($items as $media)
            @php
                $url = $media->getFullUrl();
                $isImage = str_starts_with((string) $media->mime_type, 'image/');
            @endphp
            <a
                href="{{ $url }}"
                target="_blank"
                rel="noopener"
                class="group relative block overflow-hidden rounded-lg border border-gray-200 bg-gray-50 transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800"
            >
                @if ($isImage)
                    <img
                        src="{{ $url }}"
                        alt="{{ $media->name }}"
                        class="aspect-square w-full object-cover transition group-hover:scale-105"
                        loading="lazy"
                    />
                @else
                    <div class="flex aspect-square w-full items-center justify-center bg-gray-100 dark:bg-gray-700">
                        <div class="flex flex-col items-center gap-1 p-3 text-center">
                            <x-heroicon-o-document class="h-10 w-10 text-gray-400" />
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-200 truncate max-w-[8rem]">
                                {{ strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION) ?: 'FILE') }}
                            </span>
                        </div>
                    </div>
                @endif
                <div class="p-2 text-xs">
                    <p class="truncate font-medium text-gray-900 dark:text-gray-100" title="{{ $media->name }}">
                        {{ $media->name }}
                    </p>
                    <p class="text-gray-500 dark:text-gray-400">
                        {{ number_format($media->size / 1024, 1) }} KB
                        @if ($media->mime_type)
                            · {{ $media->mime_type }}
                        @endif
                    </p>
                </div>
            </a>
        @endforeach
    </div>
@endif
