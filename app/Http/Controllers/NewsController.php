<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\HackerNews;
use Illuminate\Http\Request;
use App\Models\CyberSecurityNews;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\NewsAPIService; // Gunakan NewsAPIService

class NewsController extends Controller
{

    public function index()
    {
        $latestNews = CyberSecurityNews::orderBy('published_at', 'desc')->take(9)->get();
        $otherNews = CyberSecurityNews::orderBy('published_at', 'desc')->skip(9)->take(20)->get();

        return view('news', compact('latestNews', 'otherNews'));
    }

    // public function show()
    // {
    //     $latestNews = CyberSecurityNews::orderBy('published_at', 'desc')->take(9)->get();
    //     $otherNews = CyberSecurityNews::orderBy('published_at', 'desc')->skip(9)->take(20)->get();

    //     return view('news', compact('latestNews', 'otherNews'));
    // }

    public function dashboard()
    {
        // Menghitung berita hari ini dari kedua sumber
        $newsToday = CyberSecurityNews::where('published_at', '>=', now()->startOfDay())->count() +
                    HackerNews::where('published_at', '>=', now()->startOfDay())->count();

        // Menggabungkan kategori dari kedua sumber dan menghitung total per kategori
        $topCategories = DB::table('cyber_security_news')
        ->select('category', DB::raw('COUNT(*) as total'))
        ->groupBy('category')
        ->unionAll(
            DB::table('hacker_news')
                ->select('category', DB::raw('COUNT(*) as total'))
                ->groupBy('category')
        )
        ->orderByDesc('total')
        ->limit(10)
        ->get();


        // Mengambil kategori terbanyak
        $topCategory = optional($topCategories->first())->category ?? 'No Data';


        // Label dan Data untuk Chart
        $topCategoriesLabels = $topCategories->pluck('category');
        $topCategoriesData = $topCategories->pluck('total');

        // Data untuk Word Cloud
        $wordCloudData = $topCategories->map(fn ($item) => [$item->category, $item->total])->toArray();

        // Mengambil sumber berita terbanyak dari kedua sumber
        $topNewsSourceData = DB::table(function ($query) {
            $query->select('url')
                ->selectRaw('count(*) as total')
                ->from('cyber_security_news')
                ->groupBy('url')
                ->unionAll(
                    DB::table('hacker_news')
                        ->select('url')
                        ->selectRaw('count(*) as total')
                        ->groupBy('url')
                );
        }, 'combined_sources')
        ->select('url', DB::raw('SUM(total) as total'))
        ->groupBy('url')
        ->orderByDesc('total')
        ->first();

        $topNewsSource = $topNewsSourceData ? parse_url($topNewsSourceData->url, PHP_URL_HOST) : 'No Data';

        return view('news-dashboard', compact(
            'newsToday',
            'topCategory',
            'topCategoriesLabels',
            'topCategoriesData',
            'topNewsSource',
            'wordCloudData'
        ));
    }

    public function malwaredashboard()
    {
        $json_data = file_get_contents(storage_path('app/malware_trends.json'));
        $malware_data = json_decode($json_data, true) ?? [];

        foreach ($malware_data as &$malware) {
            $malware['previous_rank'] = $malware['previous_rank'] ?? $malware['rank'];
        }

        usort($malware_data, fn($a, $b) => $a['rank'] <=> $b['rank']);

        file_put_contents(storage_path('app/malware_trends.json'), json_encode($malware_data, JSON_PRETTY_PRINT));

        return view('malware.dashboard', compact('malware_data'));
    }

    public function malware()
    {
        $json_data = file_get_contents(storage_path('app/malware_trends.json'));
        $malware_data = json_decode($json_data, true) ?? [];

        foreach ($malware_data as &$malware) {
            $malware['previous_rank'] = $malware['previous_rank'] ?? $malware['rank'];
        }

        usort($malware_data, fn($a, $b) => $a['rank'] <=> $b['rank']);

        return view('malware.list', compact('malware_data'));
    }

    public function detail($name)
    {
        $trends_json = file_get_contents(storage_path('app/malware_trends.json'));
        $description_json = file_get_contents(storage_path('app/malware_description.json'));

        $trends_data = json_decode($trends_json, true);
        $description_data = json_decode($description_json, true);

        $malware_trend = collect($trends_data)->firstWhere('name', $name);
        $malware_description = collect($description_data)->firstWhere('name', $name);

        if (!$malware_trend || !$malware_description) {
            abort(404, 'Malware not found');
        }

        // Format ulang IOCs menjadi kategori
        $formatted_iocs = [
            'IP addresses' => array_filter($malware_description['iocs'], fn($ioc) => filter_var($ioc, FILTER_VALIDATE_IP)),
            'domains' => array_filter($malware_description['domains'], fn($ioc) => preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $ioc)),
            'URLs' => array_filter($malware_description['iocs'], fn($ioc) => filter_var($ioc, FILTER_VALIDATE_URL)),
        ];


        // Ambil video URL (jika ada)
        $video_url = $malware_description['video_url'] ?? null;

        $malware_detail = array_merge($malware_trend, $malware_description, ['iocs' => $formatted_iocs, 'video_url' => $video_url]);

        return view('malware-detail')->with('malware', $malware_detail);
    }


    public function Feeds()
{
    // Cek apakah file ada di path yang diberikan
    $jsonPath = storage_path('app/cve.json');

    // Pastikan file ada
    if (!file_exists($jsonPath)) {
        return abort(404, 'File not found');
    }

    // Ambil data dari file feeds.json
    $feeds = json_decode(file_get_contents($jsonPath), true);

    // Cek apakah data valid
    if (json_last_error() !== JSON_ERROR_NONE) {
        return abort(500, 'Invalid JSON data');
    }

    // Pass data ke view
    return view('feeds.index', ['feeds' => $feeds]);
}

        public function hackernews()
    {
        $articles = HackerNews::orderBy('published_at', 'desc')->get();

        $latestNews = $articles->take(9);
        $otherNews = $articles->slice(9);

        return view('news.index', compact('latestNews', 'otherNews'));
    }

}
