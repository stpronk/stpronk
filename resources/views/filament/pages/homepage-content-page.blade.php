<x-filament-panels::page>
    <form wire:submit="save" class="fi-sc-form">
        {{ $this->form }}

        <div class="fi-ac fi-align-start">
            @foreach($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
    <x-filament-actions::modals />
</x-filament-panels::page>
