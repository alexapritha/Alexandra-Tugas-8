<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Peminjaman Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Form validation styles */
        input:invalid {
            border-color: #ef4444;
        }

        .error-message {
            display: none;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        input:invalid + .error-message {
            display: block;
        }

        .table-row-hover:hover {
            background-color: #f7fafc;
        }

        /* Hide scrollbar but keep functionality */
        .overflow-y-auto {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .overflow-y-auto::-webkit-scrollbar {
            display: none;
        }

        /* Adjust modal position and max-height */
        #editModal > div {
            max-height: 90vh;
            margin-bottom: 2rem;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-[#FFF6ED]">
    <div class="container mx-auto px-6 py-8 max-w-6xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Sistem Peminjaman Buku</h1>
            <img src="/images/flower.svg" alt="Dekorasi Bunga" class="w-60 h-12">
        </div>

        <!-- Form Peminjaman -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-xl font-semibold mb-4">Form Peminjaman Buku</h2>
            <form id="peminjamanForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Peminjam</label>
                        <input type="text" name="nama_peminjam" required
                               class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" required
                               class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                        <input type="text" name="judul_buku" required
                               class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" required
                               class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" required
                               class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" required
                                class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Peminjaman -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Peminjaman</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelPeminjaman" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-lg mx-auto mt-20">
            <h2 class="text-xl font-semibold mb-4">Edit Peminjaman</h2>
            <form id="editForm" class="space-y-4">
                <input type="hidden" id="edit_id_peminjaman" name="id_peminjaman">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Peminjam</label>
                    <input type="text" id="edit_nama_peminjam" name="nama_peminjam" required
                           class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" id="edit_nomor_telepon" name="nomor_telepon" required
                           class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Judul Buku</label>
                    <input type="text" id="edit_judul_buku" name="judul_buku" required
                           class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
                    <input type="date" id="edit_tanggal_pinjam" name="tanggal_pinjam" required
                           class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Kembali</label>
                    <input type="date" id="edit_tanggal_kembali" name="tanggal_kembali" required
                           class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="edit_status" name="status" required
                            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dikembalikan">Dikembalikan</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md">
                        Batal
                    </button>
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Setup CSRF token for all AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        document.addEventListener('DOMContentLoaded', function() {
            const peminjamanForm = document.getElementById('peminjamanForm');
            if (peminjamanForm) {
                loadPeminjamanData();
                
                peminjamanForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    
                    fetch('/peminjaman/store', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadPeminjamanData();
                            peminjamanForm.reset();
                            alert('Data berhasil disimpan!');
                        } else {
                            alert(data.message || 'Gagal menyimpan data!');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan pada sistem');
                    });
                });
            }
        });

        function loadPeminjamanData() {
            fetch('/peminjaman/data')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('tabelPeminjaman');
                    tbody.innerHTML = '';
                    
                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            const tr = document.createElement('tr');
                            tr.className = 'hover:bg-gray-100';
                            tr.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap">${item.id_peminjaman}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.nama_peminjam}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.nomor_telepon}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.judul_buku}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${new Date(item.tanggal_pinjam).toLocaleDateString('id-ID')}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${new Date(item.tanggal_kembali).toLocaleDateString('id-ID')}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${item.status}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button" 
                                            onclick="updatePeminjaman('${item.id_peminjaman}')" 
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md mr-2">
                                        Edit
                                    </button>
                                    <button type="button"
                                            onclick="deletePeminjaman('${item.id_peminjaman}')" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                        Hapus
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    }
                });
        }

        function updatePeminjaman(id) {
            fetch(`/peminjaman/show/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('edit_id_peminjaman').value = data.data.id_peminjaman;
                        document.getElementById('edit_nama_peminjam').value = data.data.nama_peminjam;
                        document.getElementById('edit_nomor_telepon').value = data.data.nomor_telepon;
                        document.getElementById('edit_judul_buku').value = data.data.judul_buku;
                        document.getElementById('edit_tanggal_pinjam').value = data.data.tanggal_pinjam;
                        document.getElementById('edit_tanggal_kembali').value = data.data.tanggal_kembali;
                        document.getElementById('edit_status').value = data.data.status;
                        document.getElementById('editModal').classList.remove('hidden');
                    } else {
                        alert(data.message || 'Gagal mengambil data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada sistem');
                });
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/peminjaman/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadPeminjamanData();
                    document.getElementById('editModal').classList.add('hidden');
                    alert('Data berhasil diperbarui!');
                } else {
                    alert(data.message || 'Gagal memperbarui data!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan pada sistem');
            });
        });

        function deletePeminjaman(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                fetch('/peminjaman/destroy', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadPeminjamanData();
                        alert('Data berhasil dihapus!');
                    } else {
                        alert(data.message || 'Gagal menghapus data!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada sistem');
                });
            }
        }
    </script>
</body>
</html>