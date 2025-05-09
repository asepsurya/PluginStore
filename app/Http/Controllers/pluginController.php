<?php

namespace App\Http\Controllers;

use session;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Plugin;
use App\Models\Tokens;
use App\Models\Transaction;
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
        $token = Tokens::where('user_id',auth()->user()->id)->first();
        return view('dashboard.index', compact('plugins','token'));
    }

     // Fungsi untuk mendapatkan nama kota acak
     public function getCaptcha()
     {
         $cities = ['Padang Sidempuan', 'Medan', 'Jakarta', 'Bandung', 'Surabaya'];
         $randomCity = $cities[array_rand($cities)];  // Mengambil kota secara acak
         session(['captcha_city' => $randomCity]);
         return response()->json(['city' => $randomCity]);
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
            'password' => 'required',
            'captcha' => 'required|in:' . session('captcha_city'), // Pastikan captcha valid
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
  
         // Simpan logo dan plugin file
         $pluginFilePath = $request->file('plugin_file')->store('plugins');
         $logoPath = $request->file('logo')->store('logos');

         Plugin::create([
            'plugin_name' => $request->plugin_name,
            'plugin_file' => $pluginFilePath,
            'logo' => $logoPath,
            'description' => $request->description,
            'harga' => $request->harga,        // Ubah jika di DB pakai harga
            'versi' => $request->versi,        // Ubah jika di DB pakai versi
            'status' => $request->status,
        ]);
        
         return redirect()->route('plugin.dashboard')->with('success', 'Plugin berhasil ditambahkan!');
     }

     public function download($id)
    {
        $plugin = Plugin::findOrFail($id);

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
    public function update(Request $request)
    {   
        $id = $request->id;
        // Temukan plugin berdasarkan ID
        $plugin = Plugin::findOrFail($id);

        // Validasi input
        $request->validate([
            'plugin_name' => 'required|string|max:255',
            'description' => 'required|string',
            'harga' => 'required|numeric',
            'versi' => 'required|string|max:10',
            'status' => 'required|in:1,2',
            'plugin_file' => 'nullable|file|mimes:zip,rar',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // **1️⃣ Update file plugin jika di-upload baru**
        if ($request->hasFile('plugin_file')) {
            // Hapus file lama jika ada
            if ($plugin->plugin_file && \Storage::exists($plugin->plugin_file)) {
                \Storage::delete($plugin->plugin_file);
            }
            // Simpan file baru
            $pluginFilePath = $request->file('plugin_file')->store('plugins');
            $plugin->plugin_file = $pluginFilePath;
        }

        // **2️⃣ Update logo jika di-upload baru**
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($plugin->logo && \Storage::exists($plugin->logo)) {
                \Storage::delete($plugin->logo);
            }
            // Simpan logo baru
            $logoPath = $request->file('logo')->store('logos');
            $plugin->logo = $logoPath;
        }

        // **3️⃣ Update field lainnya**
        $plugin->plugin_name = $request->plugin_name;
        $plugin->description = $request->description;
        $plugin->harga = $request->harga;
        $plugin->versi = $request->versi;
        $plugin->status = $request->status;

        // **4️⃣ Simpan perubahan ke database**
        $plugin->save();

        return redirect()->route('plugin.dashboard')->with('success', 'Plugin berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        // Temukan plugin berdasarkan ID
        $plugin = Plugin::findOrFail($id);

        // **1️⃣ Hapus file plugin dari storage jika ada**
        if ($plugin->plugin_file && \Storage::exists($plugin->plugin_file)) {
            \Storage::delete($plugin->plugin_file);
        }

        // **2️⃣ Hapus logo dari storage jika ada**
        if ($plugin->logo && \Storage::exists($plugin->logo)) {
            \Storage::delete($plugin->logo);
        }

        // **3️⃣ Hapus data plugin dari database**
        $plugin->delete();

        // **4️⃣ Redirect dengan pesan sukses**
        return redirect()->route('plugin.dashboard')->with('success', 'Plugin berhasil dihapus!');
    }

    public function payment($id){
        return view('payment.index');
    }

    public function paymentprocess(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        
        $orderId = 'ORDER-' . rand();

        // Simpan ke DB
        Transaction::create([
            'order_id' => $orderId,
            'name' => $request->name,
            'plugin' => $request->plugin,
            'price' => $request->price,
            'status' => 'pending',
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->price,
            ],
            'item_details' => [
                [
                    'id' => 'plugin-001',
                    'price' => (int) $request->price,
                    'quantity' => 1,
                    'name' => $request->plugin,
                ],
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => 'dummy@example.com',
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        Transaction::where('order_id', $orderId)->update([
            'snap_token' => $snapToken,
        ]);

        return view('payment.payment_snap', compact('snapToken','orderId'));

    }

}
