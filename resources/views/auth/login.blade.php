<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Halaman Login Plugin Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="flex justify-center mb-6">
            <img src="https://storage.googleapis.com/a1aa/image/57bc503c-48e4-43f5-3c93-b7e4983e7f8a.jpg" alt="Logo aplikasi pengelolaan plugin berwarna biru dan putih" class="h-16 w-16 rounded" width="64" height="64" />
        </div>
        <h2 class="text-2xl font-semibold text-center text-gray-900 mb-8">
            Masuk ke Plugin Manager
        </h2>
        <form class="space-y-6" action="{{ route('plugin.login') }}" method="POST" novalidate>
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <div class="relative">
                    <input type="email" id="email" name="email" required placeholder="email@domain.com" class="block w-full rounded-md border border-gray-300 px-4 py-2 pr-10 text-gray-900 placeholder-gray-400 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 @error('email') border-red-500 @enderror" value="{{ old('email') }}" />
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                </div>
                @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Kata Sandi
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi" class="block w-full rounded-md border border-gray-300 px-4 py-2 pr-10 text-gray-900 placeholder-gray-400 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 @error('password') border-red-500 @enderror" />
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 cursor-pointer" id="togglePassword" aria-label="Tampilkan atau sembunyikan kata sandi">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="border border-gray-300 rounded-lg p-4 max-w-md">
              <h1 class="text-special select-none font-semibold text-3xl" id="captchaCity">Padang Sidempuan</h1>

                <div class="flex items-center justify-end space-x-1 text-blue-700 font-semibold text-sm mt-1 cursor-pointer">
                    <i class="fas fa-sync-alt" onclick="getCaptcha()"></i>
                    <span>Perbarui</span>
                </div>
                <input type="text" placeholder="Masukkan nama kota" id="captchaInput" name="captcha" class="mt-3 w-full border border-gray-300 rounded-md px-3 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            @error('captcha')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-1">
                Masuk
            </button>
        </form>

    </div>
    <script>
        // Fungsi untuk memuat nama kota captcha dari server
        function getCaptcha() {
            fetch('/captcha')
                .then(response => response.json())
                .then(data => {
                    const captchaCity = document.getElementById('captchaCity');
                    captchaCity.textContent = data.city; // Tampilkan kota yang acak di sini
                })
                .catch(error => console.error('Error fetching captcha:', error));
        }

        // Memanggil fungsi getCaptcha() untuk pertama kali ketika halaman dimuat
        getCaptcha();

    </script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });

    </script>
</body>
</html>
