<?php

use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CVEController;
use App\Http\Controllers\OTXController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\AnyrunController;
use App\Http\Controllers\BreachController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ShodanController;
use App\Http\Controllers\ThreatController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\RSSFeedController;
use App\Http\Controllers\DeHashedController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TweetFeedController;
use App\Http\Middleware\AutoLogoutMiddleware;
use App\Http\Controllers\OrganizationController;
use App\Http\Middleware\SingleSessionMiddleware;
use App\Http\Controllers\MainDashboardController;
use App\Http\Controllers\VipImpersonateController;
use App\Http\Controllers\BrandMonitoringController;

Route::get('/', [AuthController::class, 'index'])->name('login.index');
Route::post('/', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware([AuthMiddleware::class, AutoLogoutMiddleware::class, SingleSessionMiddleware::class])->group(function () {
    //USER
    Route::get('/user-management', [AuthController::class, 'usermanagement'])->name('user.management');
    Route::get('/login-logs', [AuthController::class, 'loglogin'])->name('login-logs');
    Route::post('/user-management', [AuthController::class, 'store'])->name('user.store');
    Route::get('user/{id}/edit', [AuthController::class, 'edit'])->name('user.edit');
    Route::put('user/{id}', [AuthController::class, 'update'])->name('user.update');
    Route::delete('user/{id}', [AuthController::class, 'destroy'])->name('user.destroy');


    //DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.indexd');
    // Route::delete('/hosts/{id}', [DashboardController::class, 'destroy']);
    // Route::post('/scan', [DashboardController::class, 'scan']);
    // Route::get('/result/{id}', [DashboardController::class, 'showResult'])->name('result');
    // Route::get('/result/{id}/export', [DashboardController::class, 'exportPdf'])->name('dashboard.exportPdf');
    // Route::post('/shodan/new-port', [DashboardController::class, 'handleNewPortData']);

    //NEWS
    Route::get('/news', [NewsController::class, 'index'])->name('news.index'); // Fetch dan simpan ke DB
    Route::get('/news-dashboard', [NewsController::class, 'dashboard'])->name('news.dashboard');
    // Route::get('/news/show', [NewsController::class, 'show'])->name('news.show'); // Tampilkan dari DB

    Route::get('/malware/dashboard', [NewsController::class, 'malwaredashboard'])->name('malware.dashboard');
    Route::get('/malware-trends', [NewsController::class, 'malware'])->name('malware.index');
    Route::get('/malware/{name}', [NewsController::class, 'detail'])->name('malware.detail');

    Route::get('/feeds', [NewsController::class, 'Feeds']);
    Route::get('/hackernews', [NewsController::class, 'hackernews'])->name('hackernews');

    //CVE
    Route::get('/vulnerabilities-dashboard', [CVEController::class, 'index'])->name('vulndashboard.index');
    Route::get('/open-cve', [CVEController::class, 'opencve'])->name('opencve.index');
    Route::get('/cve/{id}', [CVEController::class, 'showCVE'])->name('cve.show');
    Route::get('/cve', [CVEController::class, 'circl'])->name('cve.index');

    //THREAT
    Route::get('/threats', [ThreatController::class, 'index'])->name('threats.index');

    //DASHBOARD
    // Route::get('/shodan/dashboard', [MonitorController::class, 'dashboard']);
    Route::get('/port-monitor', [MonitorController::class, 'index'])->name('monitor.index');
    Route::delete('/hosts/{id}', [MonitorController::class, 'destroy'])->name('hosts.destroy');
    Route::post('/scan', [MonitorController::class, 'scan'])->name('scan');
    Route::get('/result/{id}', [MonitorController::class, 'showResult'])->name('result');
    Route::get('/result/{id}/export', [MonitorController::class, 'exportPdf'])->name('monitor.exportPdf');
    Route::post('/shodan/new-port', [MonitorController::class, 'handleNewPortData']);

    // OSINT
    Route::get('/shodan', [ShodanController::class, 'index'])->name('search.index');
    Route::get('/shodan/search', [ShodanController::class, 'search'])->name('shodan.search');

    //DOMAIN
    Route::get('/domains/organization', [DomainController::class, 'organization'])->name('organization');
    Route::get('/domains', [DomainController::class, 'index'])->name('domains.index');
    Route::post('/domains', [DomainController::class, 'fetchAndStoreDomainData'])->name('domains.store');
    Route::delete('/domains/{id}', [DomainController::class, 'destroy'])->name('domains.destroy');
    // Route::get('/subdomain', [DomainController::class, 'subdomain'])->name('subdomain.index');
    Route::get('/domains/download-pdf', [DomainController::class, 'downloadPdf'])->name('domains.downloadPdf');

    //ANYRUN
    Route::get('/anyrun/threat-intel', [AnyrunController::class, 'getThreatIntel']);

    Route::get('/search', [DeHashedController::class, 'index'])->name('databreach.index');
    Route::post('/search', [DeHashedController::class, 'search'])->name('databreach.search');

    Route::get('/otx', [OTXController::class, 'index'])->name('otx');
    Route::get('/otx/{id}', [OTXController::class, 'show'])->name('otx.show');
    Route::get('/otx/search', [OTXController::class, 'getData'])->name('otx.index');
    Route::get('/{id}/download', [OTXController::class, 'downloadPdf'])->name('otx.download');

});
