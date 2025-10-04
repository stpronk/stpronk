<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactSubmitted;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';
    public bool $sent = false;

    protected array $rules = [
        'name' => 'nullable|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string|min:5|max:5000',
    ];

    public function submit(): void
    {
        $this->validate();

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
        $this->reset(['name', 'email', 'message', 'sent']);
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
