<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseGame extends Model
{
    protected $fillable = [];

    protected $rules = [];

    public $timestamps = false;

    protected $primaryKey = null;

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->table = $database.$this->table;
        }
    }

    public function getDbName(): string
    {
        return explode('.', $this->table)[0] ?? '';
    }

    public function getRules(): ?array
    {
        return $this->rules;
    }
}
