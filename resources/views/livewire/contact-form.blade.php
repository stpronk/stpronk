<div>
    <form wire:submit.prevent="submit" class="contact-form space-y-6">
        <div>
            <label for="name" class="block mb-2 font-medium">Your Name</label>
            <input id="name" type="text" wire:model.defer="name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary focus:outline-none bg-white dark:bg-gray-800" placeholder="Optional" />
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="email" class="block mb-2 font-medium">Your E-mail</label>
            <input id="email" type="email" wire:model.defer="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary focus:outline-none bg-white dark:bg-gray-800" required />
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="message" class="block mb-2 font-medium">Your Message</label>
            <textarea id="message" rows="5" wire:model.defer="message" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary focus:outline-none bg-white dark:bg-gray-800" required></textarea>
            @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex space-x-4">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-md hover:bg-emerald-600 transition" wire:loading.attr="disabled">Submit</button>
            <button type="button" wire:click="clear" class="px-6 py-3 border border-primary text-primary dark:text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition">Clear</button>
        </div>
        @if($sent)
            <div class="mt-4 text-green-500">Thank you for your message!</div>
        @endif
    </form>
</div>
