<?php
ob_start();
?>
<!-- Header dan Subjudul -->
<div class="d-flex justify-content-between align-items-center px-3 pt-4 pb-2 border-bottom">
  <div>
  <h4 class="fw-bold mb-0">
    <i class="bx bx-list-ul text-primary me-1"></i>
      Data Pendaftaran
    </h4>
    <small class="text-muted">
      Tabel di bawah menampilkan semua pendaftar lengkap dengan detail kontak, lokasi, paket, dan alasan.
    </small>
  </div>
  <div>
    <button id="addBtn" class="btn btn-primary me-2">
      <i class="bx bx-plus me-1"></i> Tambah
    </button>
    <button id="refreshBtn" class="btn btn-outline-secondary">
      <i class="bx bx-revision me-1"></i> Refresh
    </button>
  </div>
</div>

<!-- Card DataTable -->
<div class="card mt-3 shadow-sm border">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table
        id="pendaftaranTable"
        class="table table-striped table-hover nowrap mb-0"
        style="min-width:1200px"
      >
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Type</th>
            <th>Unit</th>
            <th>Nama</th>
            <th>No WA</th>
            <th>Alamat</th>
            <th>Status Lokasi</th>
            <th>Paket</th>
            <th>Type Paket</th>
            <th>Alasan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr>
            <td colspan="12" class="text-center py-4">Loading...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="pendaftaranModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Pendaftaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="pendaftaranForm">
          <input type="hidden" id="id" name="id">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nama Lengkap *</label>
              <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">NIK *</label>
              <input type="text" class="form-control" id="nik" name="nik" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">No WhatsApp *</label>
              <input type="text" class="form-control" id="whatsapp" name="whatsapp" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Alamat *</label>
              <input type="text" class="form-control" id="alamat" name="alamat" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">RT *</label>
              <input type="text" class="form-control" id="rt" name="rt" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">RW *</label>
              <input type="text" class="form-control" id="rw" name="rw" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Patokan *</label>
              <input type="text" class="form-control" id="patokan" name="patokan" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="form-label">Provinsi ID *</label>
              <input type="number" class="form-control" id="provinsi_id" name="provinsi_id" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Kabupaten ID *</label>
              <input type="number" class="form-control" id="kabupaten_id" name="kabupaten_id" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Kecamatan ID *</label>
              <input type="number" class="form-control" id="kecamatan_id" name="kecamatan_id" required>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Kelurahan ID *</label>
              <input type="number" class="form-control" id="kelurahan_id" name="kelurahan_id" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Status Lokasi *</label>
              <select class="form-select" id="status_lokasi_id" name="status_lokasi_id" required>
                <option value="">Pilih Status Lokasi</option>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Produk ID *</label>
              <input type="number" class="form-control" id="produk_id" name="produk_id" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Tahu Layanan *</label>
              <select class="form-select" id="tahu_layanan_id" name="tahu_layanan_id" required>
                <option value="">Pilih Tahu Layanan</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Layanan Digunakan *</label>
              <select class="form-select" id="layanan_digunakan_id" name="layanan_digunakan_id" required>
                <option value="">Pilih Layanan</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Alasan *</label>
              <select class="form-select" id="alasan_id" name="alasan_id" required>
                <option value="">Pilih Alasan</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Foto KTP</label>
              <input type="file" class="form-control" id="foto_ktp" name="foto_ktp" accept="image/*">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Foto KK</label>
              <input type="file" class="form-control" id="foto_kk" name="foto_kk" accept="image/*">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Foto Rumah</label>
            <input type="file" class="form-control" id="foto_rmh" name="foto_rmh" accept="image/*">
          </div>

          <div class="mb-3">
            <label class="form-label">Longlat (Pilih Lokasi di Peta)</label>
            <div id="map" style="height: 300px; border-radius:8px; margin-bottom:10px;"></div>
            <input type="text" class="form-control" id="longlat" name="longlat" placeholder="Klik pada peta untuk memilih lokasi" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus data pendaftaran ini?</p>
        <input type="hidden" id="deleteId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>

<script type="module">
  import { 
    getPendaftaran, 
    getPendaftaranById,
    createPendaftaran, 
    updatePendaftaran, 
    deletePendaftaran,
    getStatusLokasi,
    getTahuLayanan,
    getLayananDigunakan,
    getAlasan
  } from '/api.js';

  const tableBody = document.getElementById('table-body');
  const pendaftaranModal = new bootstrap.Modal(document.getElementById('pendaftaranModal'));
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
  let dataTable;

  // Load dropdown data
  async function loadDropdowns() {
    try {
      const [statusLokasi, tahuLayanan, layananDigunakan, alasan] = await Promise.all([
        getStatusLokasi(),
        getTahuLayanan(),
        getLayananDigunakan(),
        getAlasan()
      ]);

      // Populate Status Lokasi
      const statusSelect = document.getElementById('status_lokasi_id');
      statusSelect.innerHTML = '<option value="">Pilih Status Lokasi</option>';
      statusLokasi.forEach(item => {
        statusSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
      });

      // Populate Tahu Layanan
      const tahuSelect = document.getElementById('tahu_layanan_id');
      tahuSelect.innerHTML = '<option value="">Pilih Tahu Layanan</option>';
      tahuLayanan.forEach(item => {
        tahuSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
      });

      // Populate Layanan Digunakan
      const layananSelect = document.getElementById('layanan_digunakan_id');
      layananSelect.innerHTML = '<option value="">Pilih Layanan</option>';
      layananDigunakan.forEach(item => {
        layananSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
      });

      // Populate Alasan
      const alasanSelect = document.getElementById('alasan_id');
      alasanSelect.innerHTML = '<option value="">Pilih Alasan</option>';
      alasan.forEach(item => {
        alasanSelect.innerHTML += `<option value="${item.id}">${item.nama}</option>`;
      });
    } catch (error) {
      console.error('Error loading dropdowns:', error);
    }
  }

  // Load table data
  async function loadTableData() {
    try {
      tableBody.textContent = '';
      const data = await getPendaftaran();
      
      if (!Array.isArray(data)) throw new Error('Data bukan array');
      if (data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="12" class="text-center py-4">Tidak ada data</td></tr>';
        return;
      }

      data.forEach((item, i) => {
        const d = new Date(item.tanggal).toLocaleDateString('id-ID', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric'
        });

        tableBody.insertAdjacentHTML('beforeend', `
          <tr>
            <td class="text-center">${i + 1}</td>
            <td>${d}</td>
            <td><span class="badge bg-dark">${item.jenis_daf_id || '-'}</span></td>
            <td><span class="badge bg-info">${item.unit_id || '-'}</span></td>
            <td>${item.nama_lengkap}</td>
            <td>${item.whatsapp?.nomor || '-'}</td>
            <td>${item.alamat}</td>
            <td>${item.status_lokasi?.nama || '-'}</td>
            <td>${item.layanan_digunakan?.nama || '-'}</td>
            <td><span class="badge bg-warning">${item.produk_id || '-'}</span></td>
            <td>${item.alasan?.nama || '-'}</td>
            <td class="text-center">
              <div class="dropdown">
                <button
                  class="btn btn-sm btn-primary dropdown-toggle"
                  type="button"
                  data-bs-toggle="dropdown"
                >
                  ACTION
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/uploads/${item.foto_ktp}" target="_blank">Foto KTP</a></li>
                  <li><a class="dropdown-item" href="/uploads/${item.foto_kk}" target="_blank">Foto KK</a></li>
                  <li><a class="dropdown-item" href="/uploads/${item.foto_rmh}" target="_blank">Foto Rumah</a></li>
                  <li><a class="dropdown-item" href="https://wa.me/${item.whatsapp?.nomor || ''}" target="_blank">Whatsapp</a></li>
                  <li><a class="dropdown-item" href="https://maps.google.com/?q=${item.longlat}" target="_blank">Maps</a></li>
                  <li><a class="dropdown-item text-success" href="#">Verifikasi</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item text-primary edit-btn" href="#" data-id="${item.id}">Edit</a></li>
                  <li><a class="dropdown-item text-danger delete-btn" href="#" data-id="${item.id}">Delete</a></li>
                </ul>
              </div>
            </td>
          </tr>
        `);
      });

      // Initialize DataTable
      if (dataTable) {
        dataTable.destroy();
      }
      dataTable = $('#pendaftaranTable').DataTable({
        scrollX: true,
        autoWidth: false,
        responsive: false,
        fixedHeader: true,
        columnDefs: [
          { targets: 0, orderable: false, width: '4%', className: 'text-center' },
          { targets: 1, width: '8%' },
          { targets: 2, width: '6%' },
          { targets: 3, width: '6%' },
          { targets: 4, width: 'auto' },
          { targets: 5, width: '8%' },
          { targets: 6, width: 'auto' },
          { targets: 7, width: '10%' },
          { targets: 8, width: 'auto' },
          { targets: 9, width: '8%' },
          { targets: 10, width: 'auto' },
          { targets: 11, orderable: false, searchable: false, width: '12%', className: 'text-center' }
        ],
        scrollCollapse: true,
        paging: true,
        searching: true,
        info: true
      });
      
      // Force redraw to ensure proper alignment
      dataTable.columns.adjust().draw();
    } catch (error) {
      console.error('Error loading table:', error);
      tableBody.innerHTML = '<tr><td colspan="12" class="text-center text-danger py-4">Gagal memuat data</td></tr>';
    }
  }

  // Event listeners
  document.getElementById('refreshBtn').addEventListener('click', () => location.reload());
  document.getElementById('addBtn').addEventListener('click', () => openModal());
  document.getElementById('saveBtn').addEventListener('click', saveData);
  document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);

  // Edit and Delete buttons
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('edit-btn')) {
      e.preventDefault();
      const id = e.target.dataset.id;
      openModal(id);
    }
    if (e.target.classList.contains('delete-btn')) {
      e.preventDefault();
      const id = e.target.dataset.id;
      openDeleteModal(id);
    }
  });

  // Modal functions
  let map, marker;
  function openModal(id = null) {
    const form = document.getElementById('pendaftaranForm');
    const modalTitle = document.getElementById('modalTitle');
    form.reset();
    document.getElementById('id').value = '';
    if (id) {
      modalTitle.textContent = 'Edit Pendaftaran';
      loadDataForEdit(id);
    } else {
      modalTitle.textContent = 'Tambah Pendaftaran';
      document.getElementById('longlat').value = '';
      if (marker) { map.removeLayer(marker); }
    }
    setTimeout(() => {
      if (!map) {
        map = L.map('map').setView([-6.200000, 106.816666], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '© OpenStreetMap'
        }).addTo(map);
        map.on('click', function(e) {
          const { lat, lng } = e.latlng;
          document.getElementById('longlat').value = lat.toFixed(6) + ',' + lng.toFixed(6);
          if (marker) { marker.setLatLng(e.latlng); }
          else { marker = L.marker(e.latlng).addTo(map); }
        });
      } else {
        map.invalidateSize();
      }
      // Reset marker for create
      if (!id && marker) { map.removeLayer(marker); marker = null; }
    }, 400);
    pendaftaranModal.show();
  }

  function openDeleteModal(id) {
    document.getElementById('deleteId').value = id;
    deleteModal.show();
  }

  async function loadDataForEdit(id) {
    try {
      const data = await getPendaftaranById(id);
      
      // Populate form fields
      document.getElementById('id').value = data.id || '';
      document.getElementById('nama_lengkap').value = data.nama_lengkap || '';
      document.getElementById('nik').value = data.nik || '';
      document.getElementById('whatsapp').value = data.whatsapp?.nomor || '';
      document.getElementById('alamat').value = data.alamat || '';
      document.getElementById('rt').value = data.rt || '';
      document.getElementById('rw').value = data.rw || '';
      document.getElementById('patokan').value = data.patokan || '';
      document.getElementById('provinsi_id').value = data.provinsi_id || 0;
      document.getElementById('kabupaten_id').value = data.kabupaten_id || 0;
      document.getElementById('kecamatan_id').value = data.kecamatan_id || 0;
      document.getElementById('kelurahan_id').value = data.kelurahan_id || 0;
      document.getElementById('status_lokasi_id').value = data.status_lokasi_id || '';
      document.getElementById('produk_id').value = data.produk_id || 0;
      document.getElementById('tahu_layanan_id').value = data.tahu_layanan_id || '';
      document.getElementById('layanan_digunakan_id').value = data.layanan_digunakan_id || '';
      document.getElementById('alasan_id').value = data.alasan_id || '';
      // File inputs are intentionally not populated for security reasons
      document.getElementById('longlat').value = data.longlat || '';
      // Set marker di peta jika ada longlat
      setTimeout(() => {
        if (map && data.longlat) {
          const [lat, lng] = data.longlat.split(',').map(Number);
          if (!isNaN(lat) && !isNaN(lng)) {
            if (marker) { marker.setLatLng([lat, lng]); }
            else { marker = L.marker([lat, lng]).addTo(map); }
            map.setView([lat, lng], 15);
          }
        }
      }, 400);
    } catch (error) {
      console.error('Error loading data for edit:', error);
      alert('Gagal memuat data untuk edit');
    }
  }

  async function saveData() {
    const form = document.getElementById('pendaftaranForm');
    const formData = new FormData(form);
    const id = document.getElementById('id').value;
    
    // Basic validation
    const requiredFields = ['nama_lengkap', 'nik', 'whatsapp', 'alamat', 'rt', 'rw', 'patokan', 'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'status_lokasi_id', 'produk_id', 'tahu_layanan_id', 'layanan_digunakan_id', 'alasan_id'];
    
    for (const field of requiredFields) {
      if (!document.getElementById(field).value.trim()) {
        alert(`Field ${field.replace('_', ' ')} harus diisi`);
        return;
      }
    }

    try {
      const data = Object.fromEntries(formData.entries());
      
      const nomorWa = data.whatsapp.trim();
      data.whatsapp = { nomor: nomorWa };

      // Convert numeric fields
      data.provinsi_id = parseInt(data.provinsi_id);
      data.kabupaten_id = parseInt(data.kabupaten_id);
      data.kecamatan_id = parseInt(data.kecamatan_id);
      data.kelurahan_id = parseInt(data.kelurahan_id);
      data.status_lokasi_id = parseInt(data.status_lokasi_id);
      data.produk_id = parseInt(data.produk_id);
      data.tahu_layanan_id = parseInt(data.tahu_layanan_id);
      data.layanan_digunakan_id = parseInt(data.layanan_digunakan_id);
      data.alasan_id = parseInt(data.alasan_id);

      if (id) {
        await updatePendaftaran(id, data);
        alert('Data berhasil diperbarui');
      } else {
        await createPendaftaran(data);
        alert('Data berhasil ditambahkan');
      }
      
      pendaftaranModal.hide();
      loadTableData();
    } catch (error) {
      console.error('Error saving data:', error);
      alert('Gagal menyimpan data: ' + error.message);
    }
  }

  async function confirmDelete() {
    const id = document.getElementById('deleteId').value;
    
    try {
      await deletePendaftaran(id);
      alert('Data berhasil dihapus');
      deleteModal.hide();
      loadTableData();
    } catch (error) {
      console.error('Error deleting data:', error);
      alert('Gagal menghapus data: ' + error.message);
    }
  }

  // Initialize
  loadDropdowns();
  loadTableData();
</script>

<style>
  /* Dropdown di dalam scrollable table */
  .dataTables_scrollBody { overflow: visible !important; }
  table.dataTable .dropdown-menu { z-index: 9999 !important; }
</style>
<?php
$content = ob_get_clean();
$title   = "Data Pendaftaran";
include 'layouts/template.php';
?>
