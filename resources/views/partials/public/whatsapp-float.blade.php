@if (! empty($whatsappChatUrl))
    <a
        href="{{ $whatsappChatUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        class="whatsapp-float"
        aria-label="Chat with us on WhatsApp"
        title="Chat on WhatsApp"
    >
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
        <span class="whatsapp-float-label">WhatsApp</span>
    </a>
@endif
