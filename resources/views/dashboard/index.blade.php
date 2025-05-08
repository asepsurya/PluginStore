<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Pengelolaan Plugin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }
        .table-responsive {
            position: relative; /* Menjamin posisi tabel relatif */
        }

        .dropdown-menu {
            position: absolute; /* Menjamin dropdown muncul terpisah dari aliran dokumen */
            z-index: 1050; /* Agar dropdown tampil di atas elemen lainnya */
            left: auto;
            right: 0; /* Posisi dropdown ke kanan */
        }

        @media (max-width: 768px) {
            .dropdown-menu {
                position: static; /* Menjaga dropdown tetap terlihat dengan cara yang baik pada layar kecil */
            }
        }


    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="https://storage.googleapis.com/a1aa/image/57bc503c-48e4-43f5-3c93-b7e4983e7f8a.jpg" alt="Logo aplikasi pengelolaan plugin" width="40" height="40" class="rounded me-2" />
                <span class="fw-semibold text-primary fs-4">Plugin Manager</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto align-items-center mb-2 mb-md-0">

                    <li class="nav-item">
                        <button type="button" class="btn btn-primary d-flex align-items-center me-3" data-bs-toggle="modal" data-bs-target="#addPluginModal">
                            <i class="fas fa-plus me-2"></i> Tambah Plugin
                        </button>
                    </li>

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger d-flex align-items-center">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container flex-grow-1 py-4">
        <h1 class="mb-4 fw-semibold text-dark">Dashboard Pengelolaan Plugin</h1>

        <!-- Summary Cards -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-5">
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-plug fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted small">Total Plugin</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $plugins->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted small">Plugin Aktif</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $plugins->where('status', 1)->count()  }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted small">Plugin Nonaktif</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $plugins->where('status', 2)->count()  }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                       
                        <div class="flex-grow-1">
                            <p class="mb-1 text-muted small">Token API</p>
                            <h3 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <span id="tokenText">{{ $token->token }}</span>
                                <button onclick="copyToClipboard()" class="btn btn-sm btn-outline-primary" title="Salin Token">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">

            <!-- Alert Info -->
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i> <!-- Icon Info dari Font Awesome -->
                <strong>Info Penggunaan API : </strong> /api/plugin/list?token=xxxxxxxxxx.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
  
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari Plugin...">
            </div>
        </div>
        <!-- Plugin Table -->
        <div class="card shadow-sm">

            <div class="">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase text-muted small">
                        <tr>
                            <th scope="col">Nama Plugin</th>
                            <th scope="col">Versi</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Status</th>
                            <th scope="col">Terakhir Update</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plugins as $plugin)
                        <tr>
                            <td class="d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/'.$plugin->logo) }}" alt="{{ $plugin->plugin_name }}" width="40" height="40" class="rounded" />

                                {{ $plugin->plugin_name }}
                            </td>
                            <td>{{ $plugin->versi ?? 'N/A' }}</td>
                            <td>{{ $plugin->harga == 0 ? 'Free' : 'Rp. ' . number_format($plugin->harga, 0, ',', '.') }}</td>

                            <td>
                                <span class="badge bg-success text-white">{{ $plugin->status === '1' ? 'Aktif' : 'Tidak Aktif'}}</span>
                            </td>
                            <td>{{ $plugin->updated_at->locale('id_ID')->isoFormat('D MMMM YYYY') }}</td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <!-- Tombol Download -->
                                        <li>
                                            <a class="dropdown-item text-success" href="{{ route('plugin.download', $plugin->id) }}">
                                                <i class="fas fa-download me-2"></i> Download
                                            </a>
                                        </li>
                            
                                        <!-- Tombol Edit -->
                                        <li>
                                            <button class="dropdown-item text-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editPluginModal-{{ $plugin->id }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </button>
                                        </li>
                            
                                        <!-- Tombol Hapus -->
                                        <li>
                                            <form action="{{ route('plugin.delete') }}" method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus plugin ini?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $plugin->id }}">
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash-alt me-2"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            
                            
                           

                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </main>

    <!-- Modal Tambah Plugin -->
    <div class="modal fade" id="addPluginModal" tabindex="-1" aria-labelledby="addPluginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary fw-semibold" id="addPluginModalLabel">
                        Tambah Plugin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('plugin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="plugin_name" class="form-label">Nama Plugin</label>
                            <input type="text" class="form-control" id="plugin_name" name="plugin_name" placeholder="Masukkan nama plugin" required />
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="plugin_file" class="form-label">Upload File</label>
                                    <input type="file" class="form-control" id="plugin_file" name="plugin_file" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo Aplikasi</label>
                                    <input type="file" class="form-control" id="logo" name="logo" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="versi" class="form-label">Versi Aplikasi</label>
                                    <input type="text" class="form-control" id="versi" name="versi" required placeholder="v.1.0.1" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="2">Disactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="harga" name="harga" required placeholder="Harga jual Plugin" />
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Masukkan deskripsi plugin" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($plugins as $item )
    <div class="modal fade" id="editPluginModal-{{ $item->id }}" tabindex="-1" aria-labelledby="addPluginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary fw-semibold" id="addPluginModalLabel">
                        Tambah Plugin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('plugin.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="plugin_name" class="form-label">Nama Plugin</label>
                            <input type="text" class="form-control" id="plugin_name" name="plugin_name" value="{{ $item->plugin_name }}" placeholder="Masukkan nama plugin" required />
                        </div>
                        <div class="row">
                            <input type="text" value="{{ $item->id }}" name="id" hidden>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="plugin_file" class="form-label">Upload File</label>
                                    <input type="file" class="form-control" id="plugin_file" name="plugin_file" />
                                </div>
                                <div class="card p-2 ">
                                    <center>{{ $item->plugin_name . '.zip' }}</center>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo Aplikasi</label>
                                    <input type="file" class="form-control" id="logo" name="logo" />
                                </div>
                                <div class="card p-2 ">
                                    <center><img src="{{ asset('storage/' . $item->logo) }}" alt="" width="100"></center>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label for="versi" class="form-label">Versi Aplikasi</label>
                                    <input type="text" class="form-control" id="versi" name="versi" required placeholder="v.1.0.1" / value="{{ $item->versi }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{ $item->status == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="2" {{ $item->status == '2' ? 'selected' : '' }}>Disactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="harga" name="harga" required placeholder="Harga jual Plugin" value="{{ $item->harga }}" />
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Masukkan deskripsi plugin" required>{{ $item->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <footer class="bg-white border-top border-secondary py-3 text-center text-muted small mt-auto">
        © 2024 Plugin Manager. Semua hak cipta dilindungi.
    </footer>
    <script>
        // Ambil elemen input dan tabel
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');

        // Event ketika user mengetik
        searchInput.addEventListener('keyup', function() {
            const searchValue = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                // Ambil semua kolom dalam baris tersebut
                const cells = row.querySelectorAll('td');
                let match = false;

                // Looping setiap kolom untuk mengecek kecocokan
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                    }
                });

                // Jika ada kecocokan, tampilkan baris, jika tidak sembunyikan
                row.style.display = match ? '' : 'none';
            });
        });

    </script>
    <script>
        function copyToClipboard() {
            // Ambil teks token
            const token = document.getElementById('tokenText').innerText;

            // Salin ke clipboard
            navigator.clipboard.writeText(token).then(() => {
                alert('Token berhasil disalin ke clipboard!');
            }).catch(err => {
                alert('Gagal menyalin token: ' + err);
            });
        }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
