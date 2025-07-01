<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Role;
use App\Models\User;
use App\Models\Domain;
use App\Models\ActivityLog;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index() {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek kredensial
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('monitor.index');
        }

        // Kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login
        return redirect()->route('login.index');
    }

    public function Usermanagement()
    {
        // Mengambil semua data user dengan relasi role dan organization
        $users = User::with(['role'])->get();

        // Mengambil semua role untuk menampilkan dropdown pilihan role
        $roles = Role::all();

        return view('user.user-management', compact('users', 'roles')); // Mengirim data ke view
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Ambil data user berdasarkan id
        $user = User::findOrFail($id);
        $roles = Role::all(); // Ambil semua roles
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required|exists:roles,id',
        ]);

        // Cari user berdasarkan id dan update data
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->role_id = $request->role; // Update role_id
        $user->save();

        return redirect()->route('user.management')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        // Cari user berdasarkan id
        $user = User::findOrFail($id);

        // Hapus user
        $user->delete();

        // Redirect ke halaman user index dengan pesan sukses
        return redirect()->route('user.management')->with('success', 'User deleted successfully');
    }

    public function loglogin()
    {
        // Query untuk log detail
        $logs = DB::table('login_logs')
            ->join('users', 'login_logs.user_id', '=', 'users.id')
            ->select('users.name', 'login_logs.ip_address', 'login_logs.logged_in_at')
            ->orderBy('login_logs.logged_in_at', 'desc')
            ->paginate(5); // Menampilkan 10 log per halaman

        // Query untuk menghitung jumlah login per user
        $loginSummary = DB::table('login_logs')
            ->join('users', 'login_logs.user_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(login_logs.id) as total_logins'))
            ->groupBy('users.name')
            ->orderBy('total_logins', 'desc')
            ->get();

        return view('login_logs.index', compact('logs', 'loginSummary'));
    }

    public function organization(Request $request)
    {
        $organizationId = $request->input('organization_id'); // Dapatkan organisasi aktif
        $domain = Domain::where('organization_id', $organizationId)->get();

        return view('domain.domain', ['domain' => $domain]);
    }


}
