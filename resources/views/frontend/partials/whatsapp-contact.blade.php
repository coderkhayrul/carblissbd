@php
    $whatsappNumber = preg_replace('/\D/', '', \App\Support\BangladeshPhone::normalize($footerCompany?->hotline ?? ''));
    // Hotline numbers such as 096... also need Bangladesh's country code.
    if (str_starts_with($whatsappNumber, '00')) $whatsappNumber = substr($whatsappNumber, 2);
    if (str_starts_with($whatsappNumber, '0')) $whatsappNumber = '88' . $whatsappNumber;
@endphp
@if (preg_match('/^[1-9][0-9]{6,14}$/', $whatsappNumber))
    <a class="whatsapp-contact" href="https://wa.me/{{ $whatsappNumber }}" target="_blank" rel="noopener noreferrer"
        aria-label="Contact {{ $footerCompany?->name ?: 'us' }} on WhatsApp (opens in a new tab)" title="Chat with us on WhatsApp">
        <svg width="34" height="34" viewBox="0 0 32 32" fill="none" aria-hidden="true" focusable="false">
            <path d="M27 15.4a11 11 0 0 1-16.4 9.6L4 27l1.9-6.5A11 11 0 1 1 27 15.4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
            <path d="m11.1 9.7 2 3.3-1.3 1.5c1.2 2.5 2.7 4 5.2 5.1l1.5-1.4 3.4 1.9c-.5 2-1.8 2.7-3.5 2.2-5.8-1.6-9.1-5-10.1-9.4-.4-1.6.8-3 2.8-3.2Z" fill="currentColor"/>
        </svg>
        <span class="whatsapp-contact-label">Chat on WhatsApp</span>
    </a>
@endif
