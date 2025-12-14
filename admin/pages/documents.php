<?php
/**
 * Admin Documents Management
 * CRUD for community service (pengabdian) documents only
 * Research publications are managed in publications.php
 */

$action = sanitize($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'category' => 'pengabdian', // Always set to pengabdian
            'author' => sanitize($_POST['author'] ?? ''),
            'publication_date' => sanitize($_POST['publication_date'] ?? ''),
            'keywords' => sanitize($_POST['keywords'] ?? ''),
            'is_active' => isset($_POST['is_active']) ? true : false
        ];
        
        // Validate
        if (empty($data['title'])) {
            $error = 'Judul wajib diisi.';
        } else {
            // Handle file upload
            $filePath = null;
            $fileSize = null;
            
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $upload = uploadFile($_FILES['file'], DOCUMENT_PATH, ['pdf'], MAX_FILE_SIZE);
                if ($upload['success']) {
                    $filePath = $upload['filename'];
                    $fileSize = $upload['size'];
                } else {
                    $error = $upload['message'];
                }
            }
            
            if (empty($error)) {
                try {
                    // Convert boolean for PostgreSQL
                    $isActive = $data['is_active'] ? 'true' : 'false';
                    
                    if ($action === 'edit' && $id > 0) {
                        // Update existing document
                        $sql = "UPDATE documents SET title = ?, description = ?, category = ?, author = ?, 
                                publication_date = ?, keywords = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP";
                        $params = [$data['title'], $data['description'], $data['category'], $data['author'],
                                   $data['publication_date'] ?: null, $data['keywords'], $isActive];
                        
                        if ($filePath) {
                            $sql .= ", file_path = ?, file_size = ?";
                            $params[] = $filePath;
                            $params[] = $fileSize;
                            
                            // Delete old file
                            $oldDoc = db()->fetch("SELECT file_path FROM documents WHERE id = ?", [$id]);
                            if ($oldDoc && $oldDoc['file_path']) {
                                deleteFile(DOCUMENT_PATH . '/' . $oldDoc['file_path']);
                            }
                        }
                        
                        $sql .= " WHERE id = ?";
                        $params[] = $id;
                        
                        db()->query($sql, $params);
                        $message = 'Dokumen pengabdian berhasil diperbarui.';
                        
                    } else {
                        // Insert new document
                        if (!$filePath) {
                            $error = 'File PDF wajib diupload.';
                        } else {
                            $userId = $_SESSION['user_id'] ?? null;
                            db()->query(
                                "INSERT INTO documents (title, description, category, author, publication_date, keywords, file_path, file_size, is_active, created_by) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                                [$data['title'], $data['description'], $data['category'], $data['author'],
                                 $data['publication_date'] ?: null, $data['keywords'], $filePath, $fileSize, 
                                 $isActive, $userId]
                            );
                            $message = 'Dokumen pengabdian berhasil ditambahkan.';
                            $action = 'list';
                        }
                    }
                } catch (Exception $e) {
                    $error = 'Terjadi kesalahan: ' . $e->getMessage();
                }
            }
        }
    }
}

// Handle delete
if ($action === 'delete' && $id > 0) {
    try {
        $doc = db()->fetch("SELECT file_path FROM documents WHERE id = ? AND category = 'pengabdian'", [$id]);
        if ($doc) {
            deleteFile(DOCUMENT_PATH . '/' . $doc['file_path']);
            db()->query("DELETE FROM documents WHERE id = ?", [$id]);
            $message = 'Dokumen pengabdian berhasil dihapus.';
        }
    } catch (Exception $e) {
        $error = 'Gagal menghapus dokumen.';
    }
    $action = 'list';
}

// Get document for editing
$document = null;
if ($action === 'edit' && $id > 0) {
    $document = db()->fetch("SELECT * FROM documents WHERE id = ? AND category = 'pengabdian'", [$id]);
    if (!$document) {
        $error = 'Dokumen tidak ditemukan.';
        $action = 'list';
    }
}
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-white">
            <i class="fas fa-hands-helping text-purple-400 mr-2"></i>Dokumen Pengabdian
        </h1>
        <p class="text-gray-400 text-sm mt-1">Kelola dokumen pengabdian masyarakat</p>
    </div>
    <?php if ($action === 'list'): ?>
    <a href="<?= baseUrl('admin/?p=documents&action=add') ?>" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-500 transition-colors text-sm sm:text-base">
        <i class="fas fa-plus mr-2"></i>Tambah Pengabdian
    </a>
    <?php else: ?>
    <a href="<?= baseUrl('admin/?p=documents') ?>" class="inline-flex items-center justify-center px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors text-sm sm:text-base">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <?php endif; ?>
</div>

<!-- Messages -->
<?php if ($message): ?>
<div class="alert-dismissible bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6 transition-opacity">
    <i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($message) ?>
</div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert-dismissible bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-lg mb-6 transition-opacity">
    <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<?php if ($action === 'list'): ?>
<!-- Document List -->
<?php
$search = sanitize($_GET['q'] ?? '');
$year = sanitize($_GET['year'] ?? '');
$conditions = "category = 'pengabdian'";
$params = [];

if ($search) {
    $conditions .= " AND (title ILIKE ? OR author ILIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($year) {
    $conditions .= " AND EXTRACT(YEAR FROM publication_date) = ?";
    $params[] = $year;
}

$documents = db()->fetchAll("SELECT * FROM documents WHERE $conditions ORDER BY publication_date DESC, created_at DESC", $params);

// Get available years for filter
$years = db()->fetchAll("SELECT DISTINCT EXTRACT(YEAR FROM publication_date) as year FROM documents WHERE category = 'pengabdian' AND publication_date IS NOT NULL ORDER BY year DESC");
?>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-purple-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-white"><?= count($documents) ?></p>
                <p class="text-gray-500 text-xs">Total Dokumen</p>
            </div>
        </div>
    </div>
    <?php 
    $totalDownloads = db()->fetch("SELECT COALESCE(SUM(download_count), 0) as total FROM documents WHERE category = 'pengabdian'");
    ?>
    <div class="bg-gray-800 rounded-xl p-4 border border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-download text-green-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-white"><?= $totalDownloads['total'] ?? 0 ?></p>
                <p class="text-gray-500 text-xs">Total Download</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-gray-800 rounded-xl p-4 mb-6 border border-gray-700">
    <form method="GET" class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-end">
        <input type="hidden" name="p" value="documents">
        <div class="flex-1">
            <label class="block text-gray-400 text-sm mb-1">Cari</label>
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari judul atau penulis..." class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-purple-500">
        </div>
        <div class="flex gap-4">
            <div class="flex-1 sm:flex-none">
                <label class="block text-gray-400 text-sm mb-1">Tahun</label>
                <select name="year" class="w-full sm:w-auto px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-purple-500">
                    <option value="">Semua Tahun</option>
                    <?php foreach ($years as $y): ?>
                    <option value="<?= $y['year'] ?>" <?= $year == $y['year'] ? 'selected' : '' ?>><?= $y['year'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-500 self-end">
                <i class="fas fa-search mr-1"></i><span class="hidden sm:inline">Filter</span>
            </button>
        </div>
    </form>
</div>

<!-- Mobile Cards View -->
<div class="block sm:hidden space-y-4">
    <?php if (!empty($documents)): ?>
    <?php foreach ($documents as $doc): ?>
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-4">
        <div class="flex items-start space-x-3">
            <div class="w-10 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-file-pdf text-purple-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white font-medium truncate"><?= htmlspecialchars($doc['title']) ?></p>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($doc['author'] ?: '-') ?></p>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <?php if ($doc['publication_date']): ?>
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-700 text-gray-400">
                        <?= date('Y', strtotime($doc['publication_date'])) ?>
                    </span>
                    <?php endif; ?>
                    <span class="px-2 py-1 text-xs rounded-full <?= $doc['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' ?>">
                        <?= $doc['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                    <span class="text-gray-500 text-xs"><i class="fas fa-download mr-1"></i><?= $doc['download_count'] ?></span>
                </div>
            </div>
        </div>
        <div class="flex justify-end space-x-4 mt-3 pt-3 border-t border-gray-700">
            <a href="<?= baseUrl('admin/?p=documents&action=edit&id=' . $doc['id']) ?>" class="text-purple-400 hover:text-purple-300 text-sm">
                <i class="fas fa-edit mr-1"></i>Edit
            </a>
            <a href="<?= baseUrl('admin/?p=documents&action=delete&id=' . $doc['id']) ?>" onclick="return confirmDelete()" class="text-red-400 hover:text-red-300 text-sm">
                <i class="fas fa-trash mr-1"></i>Hapus
            </a>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-8 text-center text-gray-500">Belum ada dokumen pengabdian.</div>
    <?php endif; ?>
</div>

<!-- Desktop Table View -->
<div class="hidden sm:block bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 lg:px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Dokumen</th>
                    <th class="px-4 lg:px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Tahun</th>
                    <th class="px-4 lg:px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Download</th>
                    <th class="px-4 lg:px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Status</th>
                    <th class="px-4 lg:px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php if (!empty($documents)): ?>
                <?php foreach ($documents as $doc): ?>
                <tr class="hover:bg-gray-700/50">
                    <td class="px-4 lg:px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-file-pdf text-purple-400"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-medium truncate max-w-[300px] lg:max-w-none"><?= htmlspecialchars($doc['title']) ?></p>
                                <p class="text-gray-500 text-sm"><?= htmlspecialchars($doc['author'] ?: '-') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 lg:px-6 py-4 text-gray-400">
                        <?= $doc['publication_date'] ? date('Y', strtotime($doc['publication_date'])) : '-' ?>
                    </td>
                    <td class="px-4 lg:px-6 py-4 text-gray-400"><?= $doc['download_count'] ?></td>
                    <td class="px-4 lg:px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?= $doc['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' ?>">
                            <?= $doc['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-4 text-right whitespace-nowrap">
                        <a href="<?= baseUrl('admin/?p=documents&action=edit&id=' . $doc['id']) ?>" class="text-purple-400 hover:text-purple-300 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= baseUrl('admin/?p=documents&action=delete&id=' . $doc['id']) ?>" onclick="return confirmDelete()" class="text-red-400 hover:text-red-300">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada dokumen pengabdian.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<!-- Add/Edit Form -->
<div class="bg-gray-800 rounded-xl border border-gray-700 p-4 sm:p-6">
    <h2 class="text-lg font-semibold text-white mb-6">
        <i class="fas fa-hands-helping text-purple-400 mr-2"></i>
        <?= $action === 'edit' ? 'Edit Dokumen Pengabdian' : 'Tambah Dokumen Pengabdian Baru' ?>
    </h2>
    
    <form method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
        <?= csrf_field() ?>
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Judul Pengabdian *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($document['title'] ?? '') ?>"
                   placeholder="Judul kegiatan pengabdian masyarakat..."
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-purple-500">
        </div>
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Deskripsi</label>
            <textarea name="description" rows="4" 
                      placeholder="Deskripsi kegiatan pengabdian (program studi, skema, dll)..."
                      class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-purple-500"><?= htmlspecialchars($document['description'] ?? '') ?></textarea>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Ketua & Anggota Pengabdian</label>
                <input type="text" name="author" value="<?= htmlspecialchars($document['author'] ?? '') ?>"
                       placeholder="Nama ketua, anggota 1, anggota 2..."
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-purple-500">
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Tanggal Publikasi</label>
                <input type="date" name="publication_date" value="<?= $document['publication_date'] ?? '' ?>"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-purple-500">
            </div>
        </div>
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Keywords (pisahkan dengan koma)</label>
            <input type="text" name="keywords" value="<?= htmlspecialchars($document['keywords'] ?? '') ?>"
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-purple-500"
                   placeholder="pengabdian, pelatihan, UMKM, teknologi...">
        </div>
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">File PDF <?= $action === 'add' ? '*' : '' ?></label>
            <input type="file" name="file" accept=".pdf" <?= $action === 'add' ? 'required' : '' ?>
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white hover:file:bg-purple-500">
            <?php if ($document && $document['file_path']): ?>
            <p class="text-gray-500 text-sm mt-2">File saat ini: <?= htmlspecialchars($document['file_path']) ?> (<?= formatFileSize($document['file_size']) ?>)</p>
            <?php endif; ?>
            <p class="text-gray-500 text-sm mt-1">Maksimal <?= formatFileSize(MAX_FILE_SIZE) ?></p>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" <?= ($document['is_active'] ?? true) ? 'checked' : '' ?>
                   class="w-4 h-4 text-purple-600 bg-gray-700 border-gray-600 rounded focus:ring-purple-500">
            <label for="is_active" class="ml-2 text-gray-300">Aktif (tampilkan di website)</label>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-500 transition-colors">
                <i class="fas fa-save mr-2"></i><?= $action === 'edit' ? 'Simpan Perubahan' : 'Tambah Pengabdian' ?>
            </button>
            <a href="<?= baseUrl('admin/?p=documents') ?>" class="px-6 py-3 bg-gray-700 text-gray-300 font-semibold rounded-lg hover:bg-gray-600 transition-colors text-center">
                Batal
            </a>
        </div>
    </form>
</div>
<?php endif; ?>
