<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plugin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class pluginController extends Controller
{
    public function index(){
        return view('auth.login');
    }
    public function dashboard(){
        $plugins = Plugin::all(); // Mengambil semua data plugin
        return view('dashboard.index', compact('plugins'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
            // Jika berhasil login, redirect ke /dashboard
        return redirect()->intended('/dashboard');


    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout user

        $request->session()->invalidate(); // Hapus semua session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/')->with('success', 'Anda telah logout dengan sukses.');
    }



     // Method untuk menyimpan plugin
     public function add(Request $request)
     {
         $request->validate([
             'plugin_name' => 'required|string|max:255',
             'plugin_file' => 'required|file|mimes:zip,tar,rar|max:10240',
             'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
             'description' => 'required|string',
         ]);

         // Simpan logo dan plugin file
         $pluginFilePath = $request->file('plugin_file')->store('plugins');
         $logoPath = $request->file('logo')->store('logos');

         // Simpan data plugin ke database
         Plugin::create([
             'plugin_name' => $request->plugin_name,
             'plugin_file' => $pluginFilePath,
             'logo' => $logoPath,
             'description' => $request->description,
         ]);

         return redirect()->route('plugin.dashboard')->with('success', 'Plugin berhasil ditambahkan!');
     }

     public function download($id)
    {$plugin = Plugin::findOrFail($id);

        // Cek apakah path filenya ada
        if (!$plugin->plugin_file) {
            return redirect()->back()->with('error', 'Path file tidak ditemukan.');
        }

        // Cek apakah file ada di storage
        if (Storage::exists($plugin->plugin_file)) {
            // Ambil nama asli plugin dan format file
            $originalName = $plugin->plugin_name;
            $extension = pathinfo($plugin->plugin_file, PATHINFO_EXTENSION);
            $downloadName = $originalName . '.' . $extension;

            // Lakukan download dengan nama sesuai nama plugin
            return Storage::download($plugin->plugin_file, $downloadName);
        } else {
            return redirect()->back()->with('error', 'File tidak ditemukan di storage.');
        }
    }
}
