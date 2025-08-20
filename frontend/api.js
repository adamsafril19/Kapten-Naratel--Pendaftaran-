const API_BASE_URL = "http://localhost:8001"; // sesuaikan jika berbeda

// ==============================
// REGISTRASI
// ==============================

// GET /registrasi
export async function getPendaftaran() {
  const res = await fetch(`${API_BASE_URL}/registrasi`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// GET /registrasi/{id}
export async function getPendaftaranById(id) {
  const res = await fetch(`${API_BASE_URL}/registrasi/${id}`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// POST /registrasi
export async function createPendaftaran(data) {
  const res = await fetch(`${API_BASE_URL}/registrasi`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// PUT /registrasi/{id}
export async function updatePendaftaran(id, data) {
  const res = await fetch(`${API_BASE_URL}/registrasi/update/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// DELETE /registrasi/{id}
export async function deletePendaftaran(id) {
  const res = await fetch(`${API_BASE_URL}/registrasi/${id}`, {
    method: "DELETE",
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// ==============================
// STATUS LOKASI
// ==============================

export async function getStatusLokasi() {
  const res = await fetch(`${API_BASE_URL}/status-lokasi`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function getStatusLokasiById(id) {
  const res = await fetch(`${API_BASE_URL}/status-lokasi/${id}`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function createStatusLokasi(data) {
  const res = await fetch(`${API_BASE_URL}/status-lokasi`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function updateStatusLokasi(id, data) {
  const res = await fetch(`${API_BASE_URL}/status-lokasi/update/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function deleteStatusLokasi(id) {
  const res = await fetch(`${API_BASE_URL}/status-lokasi/${id}`, {
    method: "DELETE",
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// ==============================
// SOBAT
// ==============================

export async function getSobat() {
  const res = await fetch(`${API_BASE_URL}/sobat`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function getSobatById(id) {
  const res = await fetch(`${API_BASE_URL}/sobat/${id}`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function createSobat(data) {
  const res = await fetch(`${API_BASE_URL}/sobat`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// Sobat update via POST /sobat/{id}
export async function updateSobat(id, data) {
  const res = await fetch(`${API_BASE_URL}/sobat/update/${id}`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function deleteSobat(id) {
  const res = await fetch(`${API_BASE_URL}/sobat/${id}`, {
    method: "DELETE",
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// ==============================
// TAHU LAYANAN
// ==============================

export async function getTahuLayanan() {
  const res = await fetch(`${API_BASE_URL}/tahu-layanan`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function getTahuLayananById(id) {
  const res = await fetch(`${API_BASE_URL}/tahu-layanan/${id}`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function createTahuLayanan(data) {
  const res = await fetch(`${API_BASE_URL}/tahu-layanan`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function updateTahuLayanan(id, data) {
  const res = await fetch(`${API_BASE_URL}/tahu-layanan/update/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function deleteTahuLayanan(id) {
  const res = await fetch(`${API_BASE_URL}/tahu-layanan/${id}`, {
    method: "DELETE",
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// ==============================
// LAYANAN DIGUNAKAN
// ==============================

export async function getLayananDigunakan() {
  const res = await fetch(`${API_BASE_URL}/layanan-digunakan`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function getLayananDigunakanById(id) {
  const res = await fetch(`${API_BASE_URL}/layanan-digunakan/${id}`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function createLayananDigunakan(data) {
  const res = await fetch(`${API_BASE_URL}/layanan-digunakan`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function updateLayananDigunakan(id, data) {
  const res = await fetch(`${API_BASE_URL}/layanan-digunakan/update/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function deleteLayananDigunakan(id) {
  const res = await fetch(`${API_BASE_URL}/layanan-digunakan/${id}`, {
    method: "DELETE",
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// ==============================
// ALASAN
// ==============================

export async function getAlasan() {
  const res = await fetch(`${API_BASE_URL}/alasan`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function getAlasanById(id) {
  const res = await fetch(`${API_BASE_URL}/alasan/${id}`);
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function createAlasan(data) {
  const res = await fetch(`${API_BASE_URL}/alasan`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function updateAlasan(id, data) {
  const res = await fetch(`${API_BASE_URL}/alasan/update/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

export async function deleteAlasan(id) {
  const res = await fetch(`${API_BASE_URL}/alasan/${id}`, {
    method: "DELETE",
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}

// ==============================
// PAYMENT ACTIVITY (EXTEND REPORT)
// ==============================

// GET payment activity by month and year via local proxy
export async function getPaymentActivity(bulan, tahun) {
  const res = await fetch(
    `${API_BASE_URL}/payment-activity?bulan=${bulan}&tahun=${tahun}`
  );
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return res.json();
}
