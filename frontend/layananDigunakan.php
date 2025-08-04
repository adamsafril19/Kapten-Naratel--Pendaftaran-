<?php
ob_start();
?>

<div class="d-flex justify-content-between align-items-center px-3 pt-4 pb-2 border-bottom">
  <div>
    <h4 class="fw-bold mb-0">
      <i class="bx bx-conversation text-primary me-1"></i> Layanan Digunakan
    </h4>
    <small class="text-muted">Kelola layanan yang dipakai oleh pendaftar.</small>
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
          <h5 class="modal-title" id="createModalLabel">Tambah Layanan Digunakan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="createForm">
            <div class="mb-3">
              <label for="namaLayanan" class="col-form-label">Nama Layanan:</label>
              <input type="text" class="form-control" id="namaLayanan" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="createLayanan()">Simpan</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Update Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Edit Layanan Digunakan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="updateForm">
            <input type="hidden" id="updateId">
            <div class="mb-3">
              <label for="updateNamaLayanan" class="col-form-label">Nama Layanan:</label>
              <input type="text" class="form-control" id="updateNamaLayanan" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" onclick="updateLayanan()">Simpan</button>
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
          <p>Apakah Anda yakin ingin menghapus layanan digunakan ini?</p>
          <input type="hidden" id="deleteId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
        </div>
      </div>
    </div>
  </div>

<!-- Card DataTable -->
<div class="card mt-3 shadow-sm border">
  <div class="card-body">
    <div class="table-responsive">
      <table id="layananDigunakanTable" class="table table-striped table-hover nowrap mb-0" style="width: 100%">
        <thead class="table-light">
          <tr>
            <th style="width: 5%; text-align:center">No</th>
            <th>Nama Layanan</th>
            <th>Dibuat Pada</th>
            <th style="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <tr><td colspan="4" class="text-center py-4">Loading...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="module">
  import {
    getLayananDigunakan,
    getLayananDigunakanById,
    createLayananDigunakan,
    updateLayananDigunakan,
    deleteLayananDigunakan
  } from '/api.js';

  const tableBody = document.getElementById('table-body');
  tableBody.innerHTML = '';

  getLayananDigunakan()
    .then(raw => {
      const data = Array.isArray(raw) ? raw : raw.data;
      if (!Array.isArray(data)) throw new Error('Data bukan array');
      if (data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Tidak ada data</td></tr>';
        return;
      }

      data.forEach((item, idx) => {
        const d = new Date(item.created_at);
        const formatted = d.toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit',year:'numeric'});
        tableBody.insertAdjacentHTML('beforeend', `
          <tr>
            <td class="text-center">${idx+1}</td>
            <td>${item.nama}</td>
            <td>${formatted}</td>
            <td>
              <button class="btn btn-sm btn-outline-primary me-1" onclick="editLayanan(${item.id})">
                <i class="bx bx-edit"></i>
              </button>
              <button class="btn btn-sm btn-outline-danger" onclick="hapusLayanan(${item.id})">
                <i class="bx bx-trash"></i>
              </button>
            </td>
          </tr>
        `);
      });

      $('#layananDigunakanTable').DataTable({
      });
    })
    .catch(err => {
      console.error(err);
      tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">Gagal memuat data</td></tr>';
    });

  // Create new layanan
  window.createLayanan = async () => {
    const nama = document.getElementById('namaLayanan').value;
    
    if (!nama) {
      alert('Nama layanan harus diisi');
      return;
    }
    
    try {
      const data = { nama };
      await createLayananDigunakan(data);
      location.reload();
    } catch (error) {
      console.error('Error creating layanan:', error);
      alert('Gagal menambahkan layanan digunakan');
    }
  };
  
  // Populate update modal with data
  window.editLayanan = (id) => {
    getLayananDigunakanById(id)
      .then(response => {
        const item = response.data;      // ambil properti data
        document.getElementById('updateId').value = item.id;
        document.getElementById('updateNamaLayanan').value = item.nama;
        new bootstrap.Modal(document.getElementById('updateModal')).show();
      })
      .catch(error => {
        console.error('Error fetching layanan data:', error);
        alert('Gagal memuat data layanan digunakan');
      });
  };
  
  // Update existing layanan
  window.updateLayanan = async () => {
    const id = document.getElementById('updateId').value;
    const nama = document.getElementById('updateNamaLayanan').value;
    
    if (!nama) {
      alert('Nama layanan harus diisi');
      return;
    }
    
    try {
      const data = { nama };
      await updateLayananDigunakan(id, data);
      location.reload();
    } catch (error) {
      console.error('Error updating layanan:', error);
      alert('Gagal memperbarui layanan digunakan');
    }
  };
  
  // Show delete confirmation modal
  window.hapusLayanan = (id) => {
    document.getElementById('deleteId').value = id;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
  };
  
  // Confirm delete action
  window.confirmDelete = async () => {
    const id = document.getElementById('deleteId').value;
    
    try {
      await deleteLayananDigunakan(id);
      location.reload();
    } catch (error) {
      console.error('Error deleting layanan:', error);
      alert('Gagal menghapus layanan digunakan');
    }
  };
</script>

<style>
  .dataTables_scrollBody { overflow: visible !important; }
  table.dataTable .dropdown-menu { z-index: 9999 !important; }
</style>
<?php
$content = ob_get_clean();
$title   = "Layanan Digunakan";
include __DIR__ . '/layouts/template.php';
?>
