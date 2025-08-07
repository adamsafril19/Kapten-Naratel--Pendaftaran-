<?php require_once 'require_auth.php'; ?>
<?php
ob_start(); // mulai tangkap output
?>

<div class="d-flex justify-content-between align-items-center px-3 pt-4 pb-2 border-bottom">
  <div>
    <h4 class="fw-bold mb-0">
      <i class="bx bx-comment-dots text-primary me-1"></i> Alasan Pendaftaran
    </h4>
    <small class="text-muted">Kelola daftar alasan yang dipilih pendaftar ketika mendaftar layanan.</small>
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
          <h5 class="modal-title" id="createModalLabel">Tambah Alasan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="createForm">
            <div class="mb-3">
              <label for="namaAlasan" class="col-form-label">Nama Alasan:</label>
              <input type="text" class="form-control" id="namaAlasan" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="createAlasan()">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Update Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Edit Alasan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateForm">
            <input type="hidden" id="updateId">
            <div class="mb-3">
              <label for="updateNamaAlasan" class="col-form-label">Nama Alasan:</label>
              <input type="text" class="form-control" id="updateNamaAlasan" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="updateAlasan()">Simpan</button>
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
          <p>Apakah Anda yakin ingin menghapus data alasan ini?</p>
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
        <table id="alasanTable" class="table table-striped table-hover table-bordered nowrap" style="width:100%">
          <thead class="table-light">
            <tr>
              <th style="width: 5%">No</th>
              <th>Nama Alasan</th>
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
  import { getAlasan, getAlasanById, createAlasan, updateAlasan, deleteAlasan } from '/api.js';

  const tableBody = document.getElementById('table-body');
  tableBody.innerHTML = ''; // kosongkan sebelum render

  getAlasan()
    .then(raw => {
      const data = Array.isArray(raw) ? raw : raw.data;
      if (!Array.isArray(data)) throw new Error('Data bukan array');

      if (!data.length) {
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Tidak ada data</td></tr>';
        return;
      }

      data.forEach((item, idx) => {
        const d = new Date(item.created_at);
        const date = d.toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit',year:'numeric'});
        tableBody.insertAdjacentHTML('beforeend', `
          <tr>
            <td class="text-center">${idx+1}</td>
            <td>${item.nama}</td>
            <td>${date}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-primary me-1" onclick="editAlasan(${item.id})">
                <i class="bx bx-edit"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="hapusAlasan(${item.id})">
                <i class="bx bx-trash"></i>
              </button>
            </td>
          </tr>
        `);
      });

      $('#alasanTable').DataTable({
  
      });
    })
  
    .catch(err => {
      console.error('Gagal memuat data alasan:', err);
      tableBody.innerHTML = '<tr><td colspan="4">Gagal memuat data</td></tr>';
    });

  // Create new alasan
  window.createAlasan = async () => {
    const nama = document.getElementById('namaAlasan').value;
    
    if (!nama) {
      alert('Nama alasan harus diisi');
      return;
    }
    
    try {
      const data = { nama };
      await createAlasan(data);
      location.reload();
    } catch (error) {
      console.error('Error creating alasan:', error);
      alert('Gagal menambahkan alasan');
    }
  };
  
  // Populate update modal with data
  window.editAlasan = (id) => {
    getAlasanById(id)
      .then(response => {
        const item = response.data;      // ambil properti `data`
        document.getElementById('updateId').value = item.id;
        document.getElementById('updateNamaAlasan').value = item.nama;
        new bootstrap.Modal(document.getElementById('updateModal')).show();
      })
      .catch(error => {
        console.error('Error fetching alasan data:', error);
        alert('Gagal memuat data alasan');
      });
  };
  
  // Update existing alasan
  window.updateAlasan = async () => {
    const id = document.getElementById('updateId').value;
    const nama = document.getElementById('updateNamaAlasan').value;
    
    if (!nama) {
      alert('Nama alasan harus diisi');
      return;
    }
    
    try {
      const data = { nama };
      await updateAlasan(id, data);
      location.reload();
    } catch (error) {
      console.error('Error updating alasan:', error);
      alert('Gagal memperbarui alasan');
    }
  };
  
  // Show delete confirmation modal
  window.hapusAlasan = (id) => {
    document.getElementById('deleteId').value = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
  };
  
  // Confirm delete action
  window.confirmDelete = async () => {
    const id = document.getElementById('deleteId').value;
    
    try {
      await deleteAlasan(id);
      location.reload();
    } catch (error) {
      console.error('Error deleting alasan:', error);
      alert('Gagal menghapus alasan');
    }
  };
</script>

<?php
$content = ob_get_clean();
$title   = "Alasan";
include __DIR__ . '/layouts/template.php';
?>
