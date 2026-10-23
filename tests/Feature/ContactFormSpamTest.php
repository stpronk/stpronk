<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactSubmitted;
use App\Models\ContactMessage;
use App\Support\ContactSpamDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormSpamTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Honeypot timing check would block instant test submissions.
        config(['honeypot.enabled' => false, 'mail.from.address' => 'owner@example.com']);
    }

    public function test_spam_is_stored_without_sending_mail_but_shows_success(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'TRaPfxzYmyYnqYlajl')
            ->set('email', 'j.le.wi.s4.2@gmail.com')
            ->set('message', 'ePMhllnugtxYbVBCNMabkh')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('sent', true)
            ->assertSee('Thank you for your message');

        Mail::assertNothingSent();
        $this->assertTrue(ContactMessage::sole()->is_spam);
        $this->assertSame('random name, random message, dotted gmail address', ContactMessage::sole()->spam_reason);
    }

    public function test_real_message_is_stored_and_mailed(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'Jan de Vries')
            ->set('email', 'jan.de.vries@gmail.com')
            ->set('message', 'Hi Steve, I would like to discuss a project with you.')
            ->call('submit')
            ->assertSet('sent', true);

        Mail::assertSent(ContactSubmitted::class);
        $this->assertFalse(ContactMessage::sole()->is_spam);
    }

    public function test_detector_does_not_flag_normal_submissions(): void
    {
        $detector = new ContactSpamDetector();

        $this->assertFalse($detector->isSpam('Steve', 'steve@example.com', 'Hello there'));
        $this->assertFalse($detector->isSpam('McDonald', 'a.b.c.d@gmail.com', 'Can we talk about a website?'));
        $this->assertFalse($detector->isSpam('JanVanDerBerg', 'jan@example.nl', 'Interested in working together.'));
        $this->assertFalse($detector->isSpam('Steve', 'steve@sytatsu.nl', 'Graag wil ik vragen naar mogelijkheden voor web development'));
        $this->assertFalse($detector->isSpam('Steve', 'steve@sytatsu.nl', 'Graag wil ik vragen naar mogelijkheden voor het ontwikkelen van een website. Ik zie uit naar onze samenwerking'));
        $this->assertTrue($detector->isSpam('Steve', 'steve@example.com', 'ePMhllnugtxYbVBCNMabkh'));
        $this->assertTrue($detector->isSpam('TRaPfxzYmyYnqYlajl', 'j.le.wi.s4.2@gmail.com', 'Hello there'));
        $this->assertTrue($detector->isSpam('WJGWHLwBcydNRWnGDfW', 'lorraine@leases.net', 'QDBCjyKtrQxffkEBDey'));
    }
}
