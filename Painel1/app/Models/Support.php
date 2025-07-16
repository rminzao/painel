<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $table = 'support';

    public function answers()
    {
        return $this->hasMany(SupportAnswer::class, 'support_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function server()
    {
        return $this->hasOne(Server::class, 'id', 'server_id');
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 'open':
                return 'Aberto';
            case 'closed':
                return 'Fechado';
            case 'answered':
                return 'Respondido';
            case 'waiting':
                return 'Aguardando resposta';
            default:
                return 'Desconhecido';
        }
    }

    public function getType()
    {
        switch ($this->type) {
            case 'recharge':
                return 'Problema de recarga';
            case 'email':
                return 'Email ou Senha';
            case 'ban':
                return 'Estorno/Disputa/Banimento';
            case 'event':
                return 'Eventos/Missões/Correio';
            case 'suggestion':
                return 'Sugestões/Melhorias/Denúncias';
            case 'item':
                return 'Problema com jogo';
            case 'partnership':
                return 'Parcerias/YouTubers';
            case 'others':
                return 'Outros Motivos';
            default:
                return 'Desconhecido';
        }
    }
}
