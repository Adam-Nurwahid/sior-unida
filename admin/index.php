<?php 
// Menggabungkan Header
include 'header.php'; 

// Menggabungkan Sidebar
include 'sidebar.php'; 

// --- DUMMY DATA (Nanti diganti dengan Query Database MySQL) ---
// Contoh: $jumlah_ukm = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM ukm WHERE status='aktif'"));
$jumlah_ukm_aktif = 24; 
$total_mhs_berprestasi = 15;
?>

<div class="flex-grow-1 p-4 bg-light">
    
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Dashboard Prestasi & UKM UNIDA</span>
            <div class="d-flex align-items-center">
                 <span class="text-muted small me-2"><?php echo date('l, d F Y'); ?></span>
            </div>
        </div>
    </nav>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-2 opacity-75">UKM Aktif</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $jumlah_ukm_aktif; ?></h2>
                        <p class="card-text small mt-2"><i class="fas fa-check-circle"></i> Unit Kegiatan Terdaftar</p>
                    </div>
                    <i class="fas fa-users-cog fa-4x opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-2 opacity-75">Mahasiswa Berprestasi</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $total_mhs_berprestasi; ?></h2>
                        <p class="card-text small mt-2"><i class="fas fa-trophy"></i> Total Tahun Ini</p>
                    </div>
                    <i class="fas fa-medal fa-4x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie me-2"></i>Sebaran Prestasi per Prodi</h6>
                </div>
                <div class="card-body">
                    <canvas id="prodiChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list me-2"></i>Daftar Mahasiswa Berprestasi Terbaru</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Prestasi</th>
                                    <th>Tingkat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Abdullah Fulan</td>
                                    <td>Informatika</td>
                                    <td>Juara 1 Gemastik</td>
                                    <td><span class="badge bg-danger">Nasional</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Siti Aminah</td>
                                    <td>HI</td>
                                    <td>Best Delegate MUN</td>
                                    <td><span class="badge bg-primary">Internasional</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Budi Santoso</td>
                                    <td>Manajemen</td>
                                    <td>Juara 2 Business Plan</td>
                                    <td><span class="badge bg-success">Provinsi</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ahmad Zaki</td>
                                    <td>PAI</td>
                                    <td>Juara 1 MTQ</td>
                                    <td><span class="badge bg-danger">Nasional</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dewi Sartika</td>
                                    <td>Informatika</td>
                                    <td>Finalis Hackathon</td>
                                    <td><span class="badge bg-danger">Nasional</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Konfigurasi Chart Prestasi per Prodi
    const ctx = document.getElementById('prodiChart').getContext('2d');
    const prodiChart = new Chart(ctx, {
        type: 'doughnut', // Bisa diganti 'bar', 'pie', dll
        data: {
            // Label Prodi (Ambil dari database nanti)
            labels: ['Informatika', 'Hubungan Internasional', 'Manajemen', 'PAI', 'Hukum'],
            datasets: [{
                label: 'Jumlah Mahasiswa Berprestasi',
                // Data Jumlah (Ambil dari database nanti)
                data: [5, 3, 4, 2, 1], 
                backgroundColor: [
                    '#4e73df', // Biru
                    '#1cc88a', // Hijau
                    '#36b9cc', // Cyan
                    '#f6c23e', // Kuning
                    '#e74a3b'  // Merah
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>

<?php 
// Menggabungkan Footer
include 'footer.php'; 
?>