@if ($form->getLivewire()->currentStep === 3)
    <div class="flex justify-between items-center mt-8">
        <button type="button" wire:click="previousStep"
            class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-gray-800 bg-white border-gray-300 hover:bg-gray-50 focus:ring-primary-600 focus:text-primary-600 focus:bg-primary-50 focus:border-primary-600">
            Kembali
        </button>

        <button type="submit"
            class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white bg-primary-600 border-transparent hover:bg-primary-500 focus:ring-primary-600">
            Daftar Sekarang
        </button>
    </div>
@endif
