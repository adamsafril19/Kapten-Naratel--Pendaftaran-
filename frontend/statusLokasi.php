<?php require_once 'require_auth.php'; ?>
<?php
ob_start(); // mulai tangkap output
?>

<div class="d-flex justify-content-between align-items-center px-3 pt-4 pb-2 border-bottom">
  <div>
    <h4 class="fw-bold mb-0">
      <i class="bx bx-map-pin text-primary me-1"></i> Status Lokasi
    </h4>
    <small class="text-muted">Kelola status lokasi pemasangan atau calon pelanggan.</small>
  </div>
  <button type="button" class="btn btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#createModal">
    <i class="bx bx-plus"></i> Tambah
  </button>
</div>

  <!-- Create Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createModalLabel">Tambah Status Lokasi Baru</h5>
          <button type="button" class="btn-close bg-danger " data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="createForm">
            <div class="mb-3">
              <label for="namaStatus" class="col-form-label">Nama Status:</label>
              <input type="text" class="form-control" id="namaStatus" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="createStatus()">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Update Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Edit Status Lokasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateForm">
            <input type="hidden" id="updateId">
            <div class="mb-3">
              <label for="updateNamaStatus" class="col-form-label">Nama Status:</label>
              <input type="text" class="form-control" id="updateNamaStatus" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="updateStatus()">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus status lokasi ini?</p>
          <input type="hidden" id="deleteId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
        </div>
      </div>
    </div>
  </div>
  <div class="card mt-3 shadow-sm border">
    <div class="card-body">
      <div class="table-responsive">
        <table id="statusLokasiTable" class="table table-striped table-hover table-bordered nowrap" style="width:100%">
          <thead class="table-light">
            <tr>
              <th style="width: 5%">No</th>
              <th>Nama Status</th>
              <th>Dibuat Pada</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="table-body">
            <tr><td colspan="4" class="text-center">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<script type="module">
  import { getStatusLokasi, getStatusLokasiById, createStatusLokasi, updateStatusLokasi, deleteStatusLokasi } from '/api.js';

  const tableBody = document.getElementById('table-body');
  tableBody.innerHTML = ''; // kosongkan "Loading..."

  getStatusLokasi()
    .then(data => {
      if (!Array.isArray(data)) throw new Error('Data bukan array');

      if (data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4">Tidak ada data</td></tr>';
        return;
      }

      data.forEach((item, index) => {
        // format tanggal menjadi dd-mm-yyyy
        const dateObj = new Date(item.created_at);
        const formattedDate = dateObj.toLocaleDateString('id-ID', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric'
        });

        const row = `
          <tr>
            <td>${index + 1}</td>
            <td>${item.nama}</td>
            <td>${formattedDate}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-primary me-1" onclick="editStatus(${item.id})">
                <i class="bx bx-edit"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="hapusStatus(${item.id})">
                <i class="bx bx-trash"></i>
              </button>
            </td>
          </tr>
        `;

        tableBody.insertAdjacentHTML('beforeend', row);
      });

      // setelah semua row dimasukkan:
      $('#statusLokasiTable').DataTable({
      });

    })
    .catch(err => {
      console.error('Gagal memuat data status lokasi:', err);
      tableBody.innerHTML = '<tr><td colspan="4">Gagal memuat data</td></tr>';
    });


  // Create new status
  window.createStatus = async () => {
    const nama = document.getElementById('namaStatus').value;
    
    if (!nama) {
      alert('Nama status harus diisi');
      return;
    }
    
    try {
      const data = { nama };
      await createStatusLokasi(data);
      location.reload();
    } catch (error) {
      console.error('Error creating status:', error);
      alert('Gagal menambahkan status lokasi');
    }
  };
  
  // Populate update modal with data
  window.editStatus = (id) => {
    getStatusLokasiById(id)
      .then(response => {
        const item = response.data;      // ambil properti `data`
        document.getElementById('updateId').value = item.id;
        document.getElementById('updateNamaStatus').value = item.nama;
        new bootstrap.Modal(document.getElementById('updateModal')).show();
      })
      .catch(error => {
        console.error('Error fetching status data:', error);
        alert('Gagal memuat data status lokasi');
      });
  };
  
  // Update existing status
  window.updateStatus = async () => {
    const id = document.getElementById('updateId').value;
    const nama = document.getElementById('updateNamaStatus').value;
    
    if (!nama) {
      alert('Nama status harus diisi');
      return;
    }
    
    try {
      const data = { nama };
      await updateStatusLokasi(id, data);
      location.reload();
    } catch (error) {
      console.error('Error updating status:', error);
      alert('Gagal memperbarui status lokasi');
    }
  };
  
  // Show delete confirmation modal
  window.hapusStatus = (id) => {
    document.getElementById('deleteId').value = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
  };
  
  // Confirm delete action
  window.confirmDelete = async () => {
    const id = document.getElementById('deleteId').value;
    
    try {
      await deleteStatusLokasi(id);
      location.reload();
    } catch (error) {
      console.error('Error deleting status:', error);
      alert('Gagal menghapus status lokasi');
    }
  };
</script>

<?php
// akhir tangkapan output, inject ke template
$content = ob_get_clean();
$title   = "Status Lokasi";
include __DIR__ . '/layouts/template.php';
?>
