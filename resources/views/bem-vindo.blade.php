Site da Aplicação

{{-- Somente se o usuário ESTIVER AUTENTICADO --}}
@auth
<h1>Usuário Autenticado</h1>
{{ Auth::user()->id }} <br>
@endauth

{{-- Somente se o usuário não estiver autenticado --}}
@guest
Olá Visitante, tudo bem?
@endguest
