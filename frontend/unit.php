<?php
ob_start();
?>

<h4 class="fw-bold py-3 mb-4 d-flex justify-content-between align-items-center">
  Data Unit
  <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-unit" id="btn-tambah">
    + Tambah Unit
  </button>
</h4>

<div class="card">
  <div class="card-datatable table-responsive">
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Kode Unit</th>
          <th>Nama Unit</th>
          <th>Created At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="unit-body">
        <tr><td colspan="5" class="text-center">Memuat data...</td></tr>
      </tbody>
    </table>
    <div class="d-flex flex-column align-items-center p-3">
      <nav>
        <ul class="pagination justify-content-center mt-3" id="pagination"></ul>
        <div class="text-center small text-muted mt-2" id="pagination-info"></div>
      </nav>
    </div>
  </div>
</div>  

<!-- Modal -->
<div class="modal fade" id="modal-unit" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="form-unit">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Tambah Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="unit-id">

        <div class="mb-3">
          <label for="kode-unit" class="form-label">Kode Unit</label>
          <input type="text" class="form-control" id="kode-unit" required style="text-transform: uppercase">
        </div>

        <div class="mb-3">
          <label for="nama-unit" class="form-label">Nama Unit</label>
          <input type="text" class="form-control" id="nama-unit" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>

<script type="module">
  import { getUnits, deleteUnit, createUnit, updateUnit } from './api.js';

  const tableBody = document.getElementById('unit-body');
  const modalElement = document.getElementById('modal-unit');
  const modal = new bootstrap.Modal(modalElement);
  const form = document.getElementById('form-unit');
  const namaUnit = document.getElementById('nama-unit');
  const kodeUnit = document.getElementById('kode-unit');
  const unitId = document.getElementById('unit-id');
  const modalLabel = document.getElementById('modalLabel');
  const paginationElement = document.getElementById('pagination');

  let currentPage = 1;
  const perPage = 10;

  async function renderUnits(page = 1) {
    try {
      const { data, meta } = await getUnits(page, perPage);

      tableBody.innerHTML = '';
      if (!Array.isArray(data) || data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data ditemukan</td></tr>';
        paginationElement.innerHTML = '';
        return;
      }

      data.forEach((unit, index) => {
        const row = `
          <tr>
            <td>${(meta.from - 1) + index + 1}</td>
            <td>${unit.kode_unit || '-'}</td>
            <td>${unit.nama_unit}</td>
            <td>${unit.created_at ? new Date(unit.created_at).toLocaleString('id-ID') : '-'}</td>
            <td>
              <div class="dropdown">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                  Action
                </button>
                <ul class="dropdown-menu">
                  <li>
                    <a href="#" class="dropdown-item text-warning btn-edit" 
                      data-id="${unit.id}" 
                      data-nama="${unit.nama_unit}" 
                      data-kode="${unit.kode_unit || ''}">Edit</a>
                  </li>
                  <li>
                    <a href="#" class="dropdown-item text-danger btn-delete" data-id="${unit.id}">Hapus</a>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
      });

      renderPagination(meta.current_page, meta.last_page);

      document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async (e) => {
          e.preventDefault();
          const id = btn.dataset.id;
          if (confirm('Yakin ingin menghapus unit ini?')) {
            await deleteUnit(id);
            renderUnits(currentPage);
          }
        });
      });

      document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          unitId.value = btn.dataset.id;
          namaUnit.value = btn.dataset.nama;
          kodeUnit.value = btn.dataset.kode;
          modalLabel.textContent = 'Edit Unit';
          modal.show();
          setTimeout(() => kodeUnit.focus(), 200);
        });
      });

    } catch (err) {
      console.error('Gagal memuat data:', err);
      tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>';
    }
  }

  function renderPagination(current, last) {
    const groupSize = 3;
    const currentGroup = Math.floor((current - 1) / groupSize);
    const startPage = currentGroup * groupSize + 1;
    const endPage = Math.min(startPage + groupSize - 1, last);

    let html = '';

    // Tombol « (ke grup sebelumnya)
    if (startPage > 1) {
      html += `
        <li class="page-item">
          <a class="page-link rounded-0 border" href="#" data-page="${startPage - 1}">&laquo;</a>
        </li>
      `;
    }

    // Nomor halaman dalam grup
    for (let i = startPage; i <= endPage; i++) {
      html += `
        <li class="page-item ${i === current ? 'active' : ''}">
          <a class="page-link rounded-0 border" href="#" data-page="${i}">${i}</a>
        </li>
      `;
    }

    // Tombol » (ke grup selanjutnya)
    if (endPage < last) {
      html += `
        <li class="page-item">
          <a class="page-link rounded-0 border" href="#" data-page="${endPage + 1}">&raquo;</a>
        </li>
      `;
    }

    // Render ke HTML
    document.getElementById('pagination').innerHTML = html;
    document.getElementById('pagination-info').textContent = `Page ${current} of ${last}`;

    // Event listener pagination
    document.querySelectorAll('#pagination .page-link').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const page = parseInt(e.target.dataset.page);
        if (page !== currentPage) {
          currentPage = page;
          renderUnits(page); // ganti sesuai fungsi fetch unit
        }
      });
    });
  }

  document.getElementById('btn-tambah').addEventListener('click', () => {
    unitId.value = '';
    namaUnit.value = '';
    kodeUnit.value = '';
    modalLabel.textContent = 'Tambah Unit';
    setTimeout(() => kodeUnit.focus(), 200);
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = unitId.value;
    const data = {
      nama_unit: namaUnit.value.trim(),
      kode_unit: kodeUnit.value.trim().toUpperCase(),
    };

    try {
      if (id) {
        await updateUnit(id, data);
      } else {
        await createUnit(data);
      }
      modal.hide();
      document.getElementById('btn-tambah').focus();
      renderUnits(currentPage);
    } catch (err) {
      alert('Gagal menyimpan data');
      console.error(err);
    }
  });

  renderUnits();
</script>

<?php
$content = ob_get_clean();
$title = "Data Unit";
include 'layouts/template.php';
?>
