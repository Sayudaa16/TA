<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ThreatController extends Controller
{
    public function index()
    {
        // Membaca file JSON dari storage
        $jsonData = file_get_contents(storage_path('app/Threat-Actor.json'));
        // Decode JSON ke array
        $data = json_decode($jsonData, true);

        // Ambil data dari properti `values`
        $threatGroups = collect($data['values']);
        $allThreatGroups = $threatGroups; // Menyimpan seluruh data untuk chart

        // Menentukan jumlah item per halaman
        $perPage = 10;

        // Paginasi manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $threatGroups->slice(($currentPage - 1) * $perPage, $perPage);
        $paginatedThreatGroups = new LengthAwarePaginator($currentItems, $threatGroups->count(), $perPage);

        // Menetapkan URL untuk pagination
        $paginatedThreatGroups->setPath(url()->current());

        // Kirim data ke view
        return view('threats.index', compact('paginatedThreatGroups', 'allThreatGroups'));
    }

}
