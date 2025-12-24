<?php 
// Naik 2 tingkat
include '../../header.php'; 
include '../../sidebar.php'; 
include '../../../config.php';
$id_organisasi = 1;
$query = mysqli_query($conn, "SELECT * FROM profil_organisasi WHERE id = '$id_organisasi'");
$data = mysqli_fetch_array($query);
?>

<div class="flex-grow-1 p-4 bg-light">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav class="navbar navbar-light bg-white shadow-sm rounded flex-grow-1 me-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Profil Organisasi</span>
            </div>
        </nav>
        <a href="edit_profil.php" class="btn btn-warning fw-bold text-uppercase shadow-sm">
            <i class="fas fa-edit"></i> Edit Profil
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            
            <div class="row">
                
                <div class="col-md-6 border-end">
                    
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Nama Organisasi</label>
                        <p class="fs-5">Himpunan Mahasiswa Teknik Informatika</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Singkatan</label>
                        <p class="fw-bold">HMPTI</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Visi Organisasi</label>
                        <p class="bg-light p-2 rounded">Menjadi himpunan yang unggul dalam teknologi dan berakhlak mulia.</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Misi Organisasi</label>
                        <p class="bg-light p-2 rounded">1. Mengembangkan minat bakat.<br>2. Menjalin silaturahmi antar angkatan.</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Alamat Sekretariat</label>
                        <p>Gedung UKM Lt. 2, Universitas Darussalam Gontor.</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Logo Organisasi</label>
                        <div class="d-block mt-2">
                            <img src="https://via.placeholder.com/100" alt="Logo Organisasi" class="img-thumbnail" style="height: 100px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Alamat Surat/Dihubungi</label>
                        <p>Jl. Raya Siman, Ponorogo, Jawa Timur.</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Fasilitas/Properti yang dimiliki</label>
                        <p>Komputer, Printer, Whiteboard, Proyektor.</p>
                    </div>

                     <div class="mb-3">
                        <label class="fw-bold text-muted small">Prestasi yang pernah diraih</label>
                        <p>Juara 1 Web Design Nasional 2024.</p>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="fw-bold text-muted small">Jml Anggota Aktif</label>
                            <p class="fs-5">50</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="fw-bold text-muted small">Jml Anggota Seluruhnya</label>
                            <p class="fs-5">150</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Email Organisasi</label>
                        <p><a href="mailto:hmpti@unida.ac.id">hmpti@unida.ac.id</a></p>
                    </div>

                </div>

                <div class="col-md-6">
                    
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Website</label>
                        <p><a href="#" target="_blank">hmpti.unida.ac.id</a></p>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="fw-bold text-muted small">Facebook</label>
                            <p>-</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="fw-bold text-muted small">Instagram</label>
                            <p>@hmpti_unida</p>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="fw-bold text-muted small">Twitter</label>
                            <p>-</p>
                        </div>
                         <div class="col-6 mb-3">
                            <label class="fw-bold text-muted small">Line</label>
                            <p>-</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Tahun Berdiri</label>
                        <p>2014-09-18</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Tipe Organisasi</label>
                        <span class="badge bg-primary">Himpunan Mahasiswa (HMP)</span>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Telp/Fax</label>
                        <p>0812-3456-7890</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Bentuk/Ukuran Ruang Sekretariat</label>
                        <p>Ruangan Kelas (6x6 meter)</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Jadwal Kegiatan/Latihan</label>
                        <p>Setiap Kamis Sore & Jumat Pagi</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Tempat Kegiatan</label>
                        <p>Lab Komputer Terpadu</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Kegiatan Besar Tahun Sebelumnya</label>
                        <p>IT Festival 2024, Seminar AI Nasional.</p>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Foto Kegiatan Beserta Keterangan</label>
                        <div class="p-2 border rounded bg-light">
                            <i class="fas fa-file-archive text-warning"></i> <a href="#">FotoKegiatan.rar</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold text-muted small">Rencana Kegiatan Tahun Berikutnya</label>
                        <p>Hackathon Internal, Workshop IoT.</p>
                    </div>

                     <div class="mb-3">
                        <label class="fw-bold text-muted small">Kegiatan Rutin</label>
                        <p>Ngoding Bareng, Kajian Teknologi.</p>
                    </div>

                     <div class="mb-3">
                        <label class="fw-bold text-muted small">Kegiatan Non Rutin</label>
                        <p>Rihlah Ilmiah.</p>
                    </div>

                </div>
            </div> </div>
    </div>
</div> 

<?php 
// Naik 2 tingkat
include '../../footer.php'; 
?>