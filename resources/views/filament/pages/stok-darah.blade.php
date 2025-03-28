<x-filament::page>
    <div class="space-y-8">
        {{-- Tabel Utama --}}
        <x-filament::section heading="Available Blood Stock" compact>
            {{ $this->table }}
        </x-filament::section>

        {{-- Tabel Kedua --}}
        <x-filament::section heading="Expired Blood Stock" compact collapsible>
            {{ $this->expiredTable() }}
        </x-filament::section>
    </div>
</x-filament::page>
