@extends('app.email._layout')
@section('title', 'Recuperar conta')
<h2>Olá {{ $first_name }}.</h2>
<p>foi solicitado uma recuperacao de conta no <b>{{ $_ENV['APP_NAME'] }}</b> se não foi você ignore esta mensagem caso contrário clique no link abaixo</p>
<p><a title='Confirmar Cadastro' href='{{ $confirm_link }}'>CLIQUE AQUI PARA TROCAR SUA SENHA</a></p>
