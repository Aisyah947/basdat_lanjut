<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$kategori = $model->getAllKategori();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-group small {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 12px;
    }

    /* Style untuk preview foto */
    .photo-upload-container {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .upload-area {
        flex: 1;
    }

    .preview-area {
        flex: 1;
        text-align: center;
    }

    .preview-box {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #f9f9f9;
        min-height: 250px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .preview-box.has-image {
        border-color: #4169E1;
        background-color: #f0f4ff;
    }

    #imagePreview {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: none;
    }

    #imagePreview.show {
        display: block;
    }

    .preview-placeholder {
        color: #999;
        font-size: 14px;
    }

    .preview-placeholder i {
        font-size: 48px;
        margin-bottom: 10px;
        opacity: 0.3;
    }

    .remove-image-btn {
        margin-top: 10px;
        padding: 8px 16px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        display: none;
    }

    .remove-image-btn.show {
        display: inline-block;
    }

    .remove-image-btn:hover {
        background-color: #c82333;
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-input-wrapper input[type=file] {
        position: absolute;
        left: -9999px;
    }

    .file-input-label {
        display: block;
        padding: 12px 20px;
        background-color: #4169E1;
        color: white;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .file-input-label:hover {
        background-color: #3151b5;
    }

    .file-name {
        margin-top: 10px;
        font-size: 14px;
        color: #666;
        word-break: break-all;
    }

    button[type="submit"] {
        background-color: #28a745;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        margin-right: 10px;
    }

    button[type="submit"]:hover {
        background-color: #218838;
    }

    .back-link {
        display: inline-block;
        padding: 12px 30px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
    }

    .back-link:hover {
        background-color: #5a6268;
    }

    @media (max-width: 768px) {
        .photo-upload-container {
            flex-direction: column;
        }
    }
</style>

<div class="form-container">
    <h2>Tambah Menu Baru</h2>

    <form action="proses_tambah_menu.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Nama Menu *</label>
            <input type="text" name="nama_menu" required>
        </div>

        <div class="form-group">
            <label>Kategori Menu *</label>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga (Rp) *</label>
            <input type="number" name="harga" required>
            <small>Contoh: 25000 (tanpa titik)</small>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi"></textarea>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status_ketersediaan" required>
                <option value="1">Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Menu</label>
            <div class="photo-upload-container">
                <div class="upload-area">
                    <div class="file-input-wrapper">
                        <input type="file" name="foto_menu" id="fotoMenu" accept="image/*">
                        <label for="fotoMenu" class="file-input-label">
                            📷 Pilih Foto Menu
                        </label>
                    </div>
                    <div class="file-name" id="fileName"></div>
                    <small style="margin-top: 10px; display: block;">Format: JPG, PNG, JPEG (Max 5MB)</small>
                </div>

                <div class="preview-area">
                    <div class="preview-box" id="previewBox">
                        <div class="preview-placeholder" id="placeholder">
                            <div style="font-size: 48px; margin-bottom: 10px;">🖼️</div>
                            <div>Preview foto akan muncul di sini</div>
                        </div>
                        <img id="imagePreview" src="" alt="Preview">
                    </div>
                    <button type="button" class="remove-image-btn" id="removeImageBtn">
                        🗑️ Hapus Foto
                    </button>
                </div>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit">💾 Simpan Menu</button>
            <a href="menu.php" class="back-link">↩️ Kembali</a>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fotoMenu');
    const imagePreview = document.getElementById('imagePreview');
    const removeBtn = document.getElementById('removeImageBtn');
    const previewBox = document.getElementById('previewBox');
    const placeholder = document.getElementById('placeholder');
    const fileName = document.getElementById('fileName');

    // Event listener untuk memilih file
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validasi ukuran file (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maximum 5MB');
                fileInput.value = '';
                return;
            }

            // Validasi tipe file
            if (!file.type.match('image.*')) {
                alert('File harus berupa gambar!');
                fileInput.value = '';
                return;
            }

            // Tampilkan nama file
            fileName.textContent = '📎 ' + file.name;

            // Baca dan tampilkan preview
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.add('show');
                placeholder.style.display = 'none';
                removeBtn.classList.add('show');
                previewBox.classList.add('has-image');
            };
            
            reader.readAsDataURL(file);
        }
    });

    // Event listener untuk tombol hapus
    removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        imagePreview.src = '';
        imagePreview.classList.remove('show');
        placeholder.style.display = 'block';
        removeBtn.classList.remove('show');
        previewBox.classList.remove('has-image');
        fileName.textContent = '';
    });

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const namaMenu = document.querySelector('input[name="nama_menu"]').value.trim();
        const kategori = document.querySelector('select[name="id_kategori"]').value;
        const harga = document.querySelector('input[name="harga"]').value;

        if (!namaMenu) {
            alert('Nama menu harus diisi!');
            e.preventDefault();
            return false;
        }

        if (!kategori) {
            alert('Kategori menu harus dipilih!');
            e.preventDefault();
            return false;
        }

        if (!harga || harga <= 0) {
            alert('Harga harus diisi dengan nilai yang valid!');
            e.preventDefault();
            return false;
        }

        return true;
    });
});
</script>

<?php include_once __DIR__ . '/layout/footer.php'; ?>