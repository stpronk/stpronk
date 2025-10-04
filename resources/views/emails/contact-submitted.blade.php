<x-mail::message>
# New Contact Message

- Name: {{ $contact->name ?? 'Anonymous' }}
- Email: {{ $contact->email }}

---

{{ $contact->message }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
