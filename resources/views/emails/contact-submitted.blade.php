<x-mail::message>
# New Contact Message

- Name: {{ $contact->name ?? 'Anonymous' }}
- Email: {{ $contact->email }}

---

{{ $contact->message }}
</x-mail::message>
