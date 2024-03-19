@component('mail::message')

<span>
    Olá <b>{{$user['name']}}</b>, seja bem vindo(a)! <br><br>
    Segue seus dados de login: <br><br>
</span>

<h3>Senha: {{ $user['senha'] }}</h3>

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent