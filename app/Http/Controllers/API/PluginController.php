<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Plugin;
use App\Models\Tokens;  // Pastikan model Token sudah di-import
use Illuminate\Http\Request;

class PluginController extends Controller
{
      // Method untuk menampilkan daftar plugin
      public function list(Request $request)
        {
            // Ambil token dari query string
            $token = $request->query('token');
        
            // Validasi apakah token ada dan valid
            $user = $this->getUserFromToken($token);
        
            if (!$user) {
                // Token tidak valid atau sudah kadaluarsa
                return response()->json([
                    'status' =>'Akses ditolak',
                    'message' => 'Unauthorized or token expired'
                ], 401);
            }

            // Ambil data plugin yang terkait dengan user
            // Jika Anda ingin memfilter plugin berdasarkan user, lakukan modifikasi di sini.
            // Contoh: $plugins = Plugin::where('user_id', $user->id)->get();
            $plugins = Plugin::all(); // Atau sesuaikan dengan filter tertentu berdasarkan user
        
            $data = [
                'status' => 'success',
                'message' => 'Data plugins retrieved successfully',
                'data' => $plugins
            ];
            
            return response()->json($data);
        }

    // Fungsi untuk mendapatkan user berdasarkan token dari model Token
    private function getUserFromToken($token)
        {
            // Cari token di database pada model Tokens
            $tokenRecord = Tokens::where('token', $token)->first();

            // Cek apakah token ada dan belum kadaluarsa
            if (!$tokenRecord || ($tokenRecord->expires_at && $tokenRecord->expires_at < now())) {
                // Jika token tidak ditemukan, kadaluarsa, atau expires_at < sekarang
                return null;
            }

            // Token valid, kembalikan user yang terkait dengan token
            return $tokenRecord->user; // Asumsi relasi token dengan user
        }

}
