<x-mail::message>

# {{ $tarefa }}

Data limete de conclusão {{ $data_limite_conclusao }}

<x-mail::button :url=$url>
Clique aqui para ver a tarefa
</x-mail::button>

Att,<br>
{{ config('app.name') }}
</x-mail::message>
