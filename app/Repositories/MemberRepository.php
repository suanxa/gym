<?php

namespace App\Repositories;

use App\Models\Member;

class MemberRepository
{
    public function getAll()
    {
        // Mengambil data member beserta user (relasi) agar nama muncul
        return Member::with('user')->latest()->get();
    }

    public function update($id, array $data)
    {
        $member = Member::findOrFail($id);
        $member->update($data);
        return $member;
    }

    public function delete($id)
    {
        return Member::destroy($id);
    }
}