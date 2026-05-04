<?php

namespace App\Repositories;
use App\Models\Presence;

class PresenceRepository {
    public function getByUserId($userId) { return Presence::where('user_id', $userId)->latest()->get(); }
    public function store(array $data) { return Presence::create($data); }
}