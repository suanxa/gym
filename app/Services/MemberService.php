<?php

namespace App\Services;

use App\Repositories\MemberRepository;

class MemberService
{
    protected $memberRepo;

    public function __construct(MemberRepository $repo)
    {
        $this->memberRepo = $repo;
    }

    /**
     * Fungsi untuk mengambil semua data member (Menghilangkan Error)
     */
    public function getAllMembers()
    {
        // Memanggil repository untuk mengambil data
        return $this->memberRepo->getAll();
    }

    /**
     * Tambahkan fungsi lainnya yang dipanggil di controller
     */
    public function updateStatus($id, $status)
    {
        return $this->memberRepo->update($id, ['status' => $status]);
    }

    public function deleteMember($id)
    {
        // Sesuai dengan nama method di controller (destroy)
        // Pastikan di Repository juga ada method delete atau destroy
        return $this->memberRepo->delete($id);
    }
}