<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'cover',
    ];


    public function getNotices($limit = 5)
    {
        $activities = $this->where('type', 1)->orderBy('id', 'desc')->limit($limit)->get();

        foreach ($activities as $activity) {
            $activity->content = html_entity_decode($activity->content);
        }

        return $activities;
    }

    public function getActivities($limit = 5)
    {
        $activities = $this->where('type', 2)->orderBy('id', 'desc')->limit($limit)->get();

        foreach ($activities as $activity) {
            $activity->content = html_entity_decode($activity->content);
        }

        return $activities;
    }

    public function getAnnouncements($limit = 5)
    {
        $announcements = $this->where('type', 3)->orderBy('id', 'desc')->limit($limit)->get();

        foreach ($announcements as $announcement) {
            $announcement->content = html_entity_decode($announcement->content);
        }

        return $announcements;
    }

    public function getGuides($limit = 24)
    {
        $guides = $this->where('type', 4)->orderBy('id', 'desc')->limit($limit)->get();

        foreach ($guides as $guide) {
            $guide->content = html_entity_decode($guide->content);
        }

        return $guides;
    }

    public function getHot($limit = 5)
    {

        $guides = $this->whereIn('type', [1, 2, 3])->orderBy('views', 'desc')->limit($limit)->get();

        foreach ($guides as $guide) {
            $guide->typeLabel = match ($guide->type) {
                '1' => 'NOTICIAS',
                '2' => 'EVENTOS',
                '3' => 'ANUNCIOS',
                default => 'HOT'
            };
            $guide->content = html_entity_decode($guide->content);
        }

        return $guides;
    }

    public function getSlider()
    {
        return $this->select('id', 'title', 'cover', 'created_at')->where('cover', '!=', null)->orderBy('created_at', 'desc')->limit(5)->get();
    }

    public function cover(): string|null
    {
        if ($this->cover == null) {
            return null;
        }

        return url('storage/article/' . $this->cover);
    }
}
