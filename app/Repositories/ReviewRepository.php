<?php

namespace App\Repositories;
use App\Models\Review;

class ReviewRepository {
    public function getPublished() { return Review::where('is_published', true)->with('user')->get(); }
    public function getAll() { return Review::with('user')->latest()->get(); }
    public function create(array $data) { return Review::create($data); }
}