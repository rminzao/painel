<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function checkSave()
    {
        $checkUri = $this->where([
            ['uri', $this->uri],
            ['id', '<>', $this->id],
        ]);

        if ($checkUri->first()) {
            $this->uri = "{$this->uri}-{$this->lastId()}";
        }

        return parent::save();
    }

    public function lastId(): int
    {
        return $this->max('id') + 1;
    }

    public function recentPosts(?int $limit = 4)
    {
        return $this->where('post_at', '<=', Carbon::now()->format('d/m/Y H:i:s'))
            ->limit($limit)
            ->orderBy('post_at', 'DESC')
            ->get();
    }
}
