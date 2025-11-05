<div>
    <form wire:submit.prevent="submit" class="contact-form space-y-6">
        <x-honeypot livewire-model="extraFields" />

        <div>
            <label for="name" class="block mb-2 font-medium">Name</label>
            <input id="name" type="text" wire:model.defer="name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary focus:outline-none bg-white dark:bg-gray-800" required />
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="email" class="block mb-2 font-medium">E-mail</label>
            <input id="email" type="email" wire:model.defer="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary focus:outline-none bg-white dark:bg-gray-800" required />
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="message" class="block mb-2 font-medium">Message</label>
            <textarea id="message" rows="5" wire:model.defer="message" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary focus:outline-none bg-white dark:bg-gray-800" required></textarea>
            @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Owner contact details (non-input) --}}
        @php
            $ownerEmail = config('mail.from.address') ?? env('COMPANY_EMAIL');
            $kvk = env('COMPANY_KVK');
        @endphp
        <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1 flex flex-row justify-between items-center">
            @if(!empty($ownerEmail))
                <p>E-mail: <span class="font-medium">{{ $ownerEmail }}</span></p>
            @endif
            @if(!empty($kvk))
                <p>KVK: <span class="font-medium">{{ $kvk }}</span></p>
            @endif
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-md hover:bg-emerald-600 transition cursor-pointer" wire:loading.attr="disabled">Submit</button>
            <button type="button" wire:click="clear" class="px-6 py-3 border border-primary text-primary dark:text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition cursor-pointer">Clear</button>
        </div>

        @if($sent)
            <div class="my-2 px-4 py-2 rounded-lg shadow bg-primary text-white">Thank you for your message, here will be an copy send to your inbox as well!</div>
        @endif
    </form>
</div>
