<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactSubmitted;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;

class ContactForm extends Component
{
    use UsesSpamProtection;

    public string $name = '';
    public string $email = '';
    public string $message = '';
    public bool $sent = false;

    public HoneypotData $extraFields;

    protected array $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string|min:5|max:5000',
    ];

    public function mount(): void
    {
        $this->extraFields = new HoneypotData();
    }

    /**
     * @throws \Exception
     */
    public function submit(): void
    {
        $this->validate();
        try {
            $this->protectAgainstSpam();
        } catch (\Exception $exception) {
            Log::notice('Contact form protected from spam', [
                'name' => $this->name ?: null,
                'email' => $this->email,
                'message' => $this->message,
            ]);
            $this->sent = true;
            $this->clear();
            return;
        }

        $contact = ContactMessage::create([
            'name' => $this->name ?: null,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        $to = env('MAIL_TO_ADDRESS', config('mail.from.address'));
        if (! empty($to)) {
            Mail::to($to)
                ->cc($this->email)
                ->send((new ContactSubmitted($contact))->replyTo($this->email));
        }

        $this->reset(['name', 'email', 'message']);
        $this->sent = true;
        $this->dispatch('contact-form-sent');
    }

    public function clear(): void
    {
        $this->reset(['name', 'email', 'message']);
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
