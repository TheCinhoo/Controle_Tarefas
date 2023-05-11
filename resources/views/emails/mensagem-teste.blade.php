<x-mail::message>

# Introdução

The body of your message.

-   Opção 1
-   Opção 2
-   Opção 3
-   Opção 4

<x-mail::button :url="'www.casacaresc.org.br'">
Botão
</x-mail::button>

Obrigado,<br>
Email enviado por:
{{ config('app.name') }} - CASACARESC
</x-mail::message>
