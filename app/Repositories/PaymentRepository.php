<?php

namespace App\Repositories;
use App\Models\Payment;

class PaymentRepository {
    public function getAll() { return Payment::with('user')->latest()->get(); }
    public function getByUserId($userId) { return Payment::where('user_id', $userId)->latest()->get(); }
    public function create(array $data) { return Payment::create($data); }
    public function updateStatus($id, $status) { return Payment::findOrFail($id)->update(['status' => $status]); }
}