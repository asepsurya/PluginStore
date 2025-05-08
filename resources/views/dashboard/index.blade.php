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
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="https://storage.googleapis.com/a1aa/image/57bc503c-48e4-43f5-3c93-b7e4983e7f8a.jpg"
                    alt="Logo aplikasi pengelolaan plugin" width="40" height="40" class="rounded me-2" />
                <span class="fw-semibold text-primary fs-4">Plugin Manager</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto align-items-center mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="#">Plugins</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-primary d-flex align-items-center me-3"
                            data-bs-toggle="modal" data-bs-target="#addPluginModal">
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
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px;">
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
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px;">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted small">Plugin Aktif</p>
                            <h3 class="mb-0 fw-bold text-dark">12</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px;">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted small">Plugin Perlu Update</p>
                            <h3 class="mb-0 fw-bold text-dark">3</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px;">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-1 text-muted small">Plugin Nonaktif</p>
                            <h3 class="mb-0 fw-bold text-dark">3</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plugin Table -->
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase text-muted small">
                        <tr>
                            <th scope="col">Nama Plugin</th>
                            <th scope="col">Versi</th>
                            <th scope="col">Status</th>
                            <th scope="col">Terakhir Update</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plugins as $plugin)
                            <tr>
                                <td class="d-flex align-items-center gap-3">
                                    <img src="{{ Storage::url($plugin->logo) }}" alt="{{ $plugin->plugin_name }}" width="40" height="40" class="rounded" />
                                    {{ $plugin->plugin_name }}
                                </td>
                                <td>{{ $plugin->version ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success text-white">{{ $plugin->status ?? 'Aktif' }}</span>
                                </td>
                                <td>{{ $plugin->updated_at->format('Y-m-d') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('plugin.download', $plugin->id) }}" class="btn btn-link text-success p-0 me-2" aria-label="Download {{ $plugin->plugin_name }}">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-link text-primary p-0 me-2" aria-label="Edit {{ $plugin->plugin_name }}"  data-bs-toggle="modal" data-bs-target="#editPluginModal-{{ $plugin->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-link text-danger p-0" aria-label="Hapus {{ $plugin->plugin_name }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
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
                <form action="{{ route('plugin.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="plugin_name" class="form-label">Nama Plugin</label>
                            <input type="text" class="form-control" id="plugin_name" name="plugin_name"
                                placeholder="Masukkan nama plugin" required />
                        </div>
                        <div class="mb-3">
                            <label for="plugin_file" class="form-label">Upload File</label>
                            <input type="file" class="form-control" id="plugin_file" name="plugin_file" required />
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo Aplikasi</label>
                            <input type="file" class="form-control" id="logo" name="logo" required />
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                placeholder="Masukkan deskripsi plugin" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                <form action="{{ route('plugin.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="plugin_name" class="form-label">Nama Plugin</label>
                            <input type="text" class="form-control" id="plugin_name" name="plugin_name" value="{{ $item->plugin_name }}"
                                placeholder="Masukkan nama plugin" required />
                        </div>
                        <div class="mb-3">
                            <label for="plugin_file" class="form-label">Upload File</label>
                            <input type="file" class="form-control" id="plugin_file" name="plugin_file" required />
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo Aplikasi</label>
                            <input type="file" class="form-control" id="logo" name="logo" required />
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                placeholder="Masukkan deskripsi plugin" required>{{ $item->description }}</textarea>
                        </div>Sakti
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <footer class="bg-white border-top border-secondary py-3 text-center text-muted small mt-auto">
        © 2024 Plugin Manager. Semua hak cipta dilindungi.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</h