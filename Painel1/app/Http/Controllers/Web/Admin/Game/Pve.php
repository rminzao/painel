<?php

namespace App\Http\Controllers\Web\Admin\Game;

use App\Http\Controllers\Web\Controller;
use App\Models\Server;

/**
 * PVE Web Controller
 * 
 * File: App/Http/Controllers/Web/Admin/Game/Pve.php
 */
class Pve extends Controller
{
    public function index()
    {
        // Usa sistema de linguagem existente
        $pveTypes = getLanguage('map.pve.types.');
        arr_sort($pveTypes, "name");

        return $this->view->render('admin.game.pve.index', [
            'servers' => Server::all(),
            'pveTypes' => $pveTypes,
            'difficulties' => $this->getDifficulties(), // Adiciona dificuldades
        ]);
    }

    /**
     * Get difficulty levels with visual metadata
     */
    private function getDifficulties(): array
    {
        return [
            'simple' => [
                'name' => 'Fácil',
                'color' => 'success',
                'icon' => '🟢'
            ],
            'normal' => [
                'name' => 'Normal',
                'color' => 'primary',
                'icon' => '🔵'
            ],
            'hard' => [
                'name' => 'Difícil',
                'color' => 'warning',
                'icon' => '🟠'
            ],
            'terror' => [
                'name' => 'Avançado',
                'color' => 'danger',
                'icon' => '🔴'
            ],
            'nightmare' => [
                'name' => 'Pesadelo',
                'color' => 'dark',
                'icon' => '⚫'
            ],
            'epic' => [
                'name' => 'Épico',
                'color' => 'info',
                'icon' => '💜'
            ]
        ];
    }
}