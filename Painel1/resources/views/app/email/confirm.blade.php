@extends('app.email._layout')
@section('title', 'Confirme e ative sua conta')
<h2>Seja bem-vindo(a) ao {{ $_ENV['APP_NAME'] }} {{ $first_name }}. Vamos confirmar seu cadastro?</h2>
<p>É importante confirmar seu cadastro para ativar as notificações. Assim podemos enviar a você avisos de vencimentos e
    muito mais.</p>
<p><a title='Confirmar Cadastro' href='{{ $confirm_link }}'>CLIQUE AQUI PARA CONFIRMAR</a></p>
