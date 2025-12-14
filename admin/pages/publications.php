<?php
/**
 * Admin Publications Management
 * CRUD for lab SINTA profiles and publications
 */

$action = sanitize($_GET['action'] ?? 'list');
$id = (int)($_GET['id'] ?? 0);
$type = sanitize($_GET['type'] ?? 'labs'); // 'labs' or 'publications'
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Token tidak valid.';
    } else {
        $formType = sanitize($_POST['form_type'] ?? '');
        
        if ($formType === 'lab') {
            // Lab SINTA Profile form
            $data = [
                'lab_name' => sanitize($_POST['lab_name'] ?? ''),
                'kepala_lab' => sanitize($_POST['kepala_lab'] ?? ''),
                'sinta_url' => sanitize($_POST['sinta_url'] ?? ''),
                'total_publications' => (int)($_POST['total_publications'] ?? 0),
                'icon' => sanitize($_POST['icon'] ?? 'flask'),
                'order_index' => (int)($_POST['order_index'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? true : false
            ];
            
            if (empty($data['lab_name']) || empty($data['kepala_lab']) || empty($data['sinta_url'])) {
                $error = 'Nama lab, kepala lab, dan URL SINTA wajib diisi.';
            } else {
                try {
                    $isActive = $data['is_active'] ? 'true' : 'false';
                    
                    if ($action === 'edit' && $id > 0) {
                        db()->query(
                            "UPDATE lab_sinta_profiles SET lab_name = ?, kepala_lab = ?, sinta_url = ?, 
                             total_publications = ?, icon = ?, order_index = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?",
                            [$data['lab_name'], $data['kepala_lab'], $data['sinta_url'], 
                             $data['total_publications'], $data['icon'], $data['order_index'], $isActive, $id]
                        );
                        $message = 'Profil lab berhasil diperbarui.';
                    } else {
                        db()->query(
                            "INSERT INTO lab_sinta_profiles (lab_name, kepala_lab, sinta_url, total_publications, icon, order_index, is_active) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)",
                            [$data['lab_name'], $data['kepala_lab'], $data['sinta_url'], 
                             $data['total_publications'], $data['icon'], $data['order_index'], $isActive]
                        );
                        $message = 'Profil lab berhasil ditambahkan.';
                        $action = 'list';
                    }
                } catch (Exception $e) {
                    $error = 'Terjadi kesalahan: ' . $e->getMessage();
                }
            }
        } else if ($formType === 'publication') {
            // Publication form
            $data = [
                'lab_id' => (int)($_POST['lab_id'] ?? 0),
                'title' => sanitize($_POST['title'] ?? ''),
                'year' => (int)($_POST['year'] ?? date('Y')),
                'citations' => (int)($_POST['citations'] ?? 0),
                'url' => sanitize($_POST['url'] ?? ''),
                'order_index' => (int)($_POST['order_index'] ?? 0),
                'is_active' => isset($_POST['is_active']) ? true : false
            ];
            
            // Get lab name
            $lab = db()->fetch("SELECT lab_name FROM lab_sinta_profiles WHERE id = ?", [$data['lab_id']]);
            $labName = $lab ? $lab['lab_name'] : '';
            
            if (empty($data['title']) || $data['lab_id'] <= 0) {
                $error = 'Judul dan laboratorium wajib diisi.';
            } else {
                try {
                    $isActive = $data['is_active'] ? 'true' : 'false';
                    
                    if ($action === 'edit' && $id > 0) {
                        db()->query(
                            "UPDATE publications SET lab_id = ?, lab_name = ?, title = ?, year = ?, 
                             citations = ?, url = ?, order_index = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?",
                            [$data['lab_id'], $labName, $data['title'], $data['year'], 
                             $data['citations'], $data['url'], $data['order_index'], $isActive, $id]
                        );
                        $message = 'Publikasi berhasil diperbarui.';
                    } else {
                        db()->query(
                            "INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index, is_active) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                            [$data['lab_id'], $labName, $data['title'], $data['year'], 
                             $data['citations'], $data['url'], $data['order_index'], $isActive]
                        );
                        $message = 'Publikasi berhasil ditambahkan.';
                        $action = 'list';
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
        if ($type === 'labs') {
            db()->query("DELETE FROM lab_sinta_profiles WHERE id = ?", [$id]);
            $message = 'Profil lab berhasil dihapus.';
        } else {
            db()->query("DELETE FROM publications WHERE id = ?", [$id]);
            $message = 'Publikasi berhasil dihapus.';
        }
    } catch (Exception $e) {
        $error = 'Gagal menghapus data.';
    }
    $action = 'list';
}

// Get data for editing
$editData = null;
if ($action === 'edit' && $id > 0) {
    if ($type === 'labs') {
        $editData = db()->fetch("SELECT * FROM lab_sinta_profiles WHERE id = ?", [$id]);
    } else {
        $editData = db()->fetch("SELECT * FROM publications WHERE id = ?", [$id]);
    }
    if (!$editData) {
        $error = 'Data tidak ditemukan.';
        $action = 'list';
    }
}

// Get all labs for dropdown
$allLabs = db()->fetchAll("SELECT id, lab_name FROM lab_sinta_profiles ORDER BY order_index");
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-white">Manajemen Publikasi Penelitian</h1>
        <p class="text-gray-400 text-sm mt-1">Kelola profil laboratorium dan publikasi SINTA</p>
    </div>
    <?php if ($action === 'list'): ?>
    <div class="flex flex-wrap gap-2">
        <a href="<?= baseUrl('admin/?p=publications&action=add&type=labs') ?>" class="inline-flex items-center justify-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-500 transition-colors text-sm">
            <i class="fas fa-plus mr-2"></i>Tambah Lab
        </a>
        <a href="<?= baseUrl('admin/?p=publications&action=add&type=publications') ?>" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-500 transition-colors text-sm">
            <i class="fas fa-plus mr-2"></i>Tambah Publikasi
        </a>
    </div>
    <?php else: ?>
    <a href="<?= baseUrl('admin/?p=publications') ?>" class="inline-flex items-center justify-center px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors text-sm">
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

<!-- Tabs -->
<div class="flex border-b border-gray-700 mb-6">
    <a href="<?= baseUrl('admin/?p=publications&type=labs') ?>" 
       class="px-6 py-3 text-sm font-medium transition-colors <?= $type === 'labs' ? 'text-cyan-400 border-b-2 border-cyan-400' : 'text-gray-400 hover:text-white' ?>">
        <i class="fas fa-flask mr-2"></i>Profil Laboratorium
    </a>
    <a href="<?= baseUrl('admin/?p=publications&type=publications') ?>" 
       class="px-6 py-3 text-sm font-medium transition-colors <?= $type === 'publications' ? 'text-cyan-400 border-b-2 border-cyan-400' : 'text-gray-400 hover:text-white' ?>">
        <i class="fas fa-file-alt mr-2"></i>Publikasi
    </a>
</div>

<?php if ($type === 'labs'): ?>
<!-- Lab Profiles List -->
<?php $labs = db()->fetchAll("SELECT * FROM lab_sinta_profiles ORDER BY order_index"); ?>

<div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase">Laboratorium</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase hidden md:table-cell">Kepala Lab</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase hidden lg:table-cell">Total Publikasi</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase">Status</th>
                    <th class="px-4 py-4 text-right text-xs font-medium text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php if (!empty($labs)): ?>
                <?php foreach ($labs as $lab): ?>
                <tr class="hover:bg-gray-700/50">
                    <td class="px-4 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-<?= htmlspecialchars($lab['icon'] ?: 'flask') ?> text-cyan-400"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-medium truncate"><?= htmlspecialchars($lab['lab_name']) ?></p>
                                <a href="<?= htmlspecialchars($lab['sinta_url']) ?>" target="_blank" class="text-cyan-400 text-xs hover:underline">
                                    <i class="fas fa-external-link-alt mr-1"></i>SINTA
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-gray-400 hidden md:table-cell"><?= htmlspecialchars($lab['kepala_lab']) ?></td>
                    <td class="px-4 py-4 text-gray-400 hidden lg:table-cell"><?= $lab['total_publications'] ?></td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?= $lab['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' ?>">
                            <?= $lab['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-right whitespace-nowrap">
                        <a href="<?= baseUrl('admin/?p=publications&action=edit&type=labs&id=' . $lab['id']) ?>" class="text-cyan-400 hover:text-cyan-300 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= baseUrl('admin/?p=publications&action=delete&type=labs&id=' . $lab['id']) ?>" onclick="return confirmDelete()" class="text-red-400 hover:text-red-300">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada profil laboratorium.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<!-- Publications List -->
<?php 
$labFilter = (int)($_GET['lab'] ?? 0);
$conditions = "1=1";
$params = [];
if ($labFilter > 0) {
    $conditions .= " AND lab_id = ?";
    $params[] = $labFilter;
}
$publications = db()->fetchAll("SELECT * FROM publications WHERE $conditions ORDER BY lab_id, citations DESC", $params);
?>

<!-- Filter -->
<div class="bg-gray-800 rounded-xl p-4 mb-6 border border-gray-700">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <input type="hidden" name="p" value="publications">
        <input type="hidden" name="type" value="publications">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-gray-400 text-sm mb-1">Filter Laboratorium</label>
            <select name="lab" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-cyan-500">
                <option value="">Semua Lab</option>
                <?php foreach ($allLabs as $lab): ?>
                <option value="<?= $lab['id'] ?>" <?= $labFilter == $lab['id'] ? 'selected' : '' ?>><?= htmlspecialchars($lab['lab_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-500">
            <i class="fas fa-filter mr-1"></i>Filter
        </button>
    </form>
</div>

<div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase">Publikasi</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase hidden md:table-cell">Laboratorium</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase">Tahun</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase">Sitasi</th>
                    <th class="px-4 py-4 text-left text-xs font-medium text-gray-400 uppercase hidden lg:table-cell">Status</th>
                    <th class="px-4 py-4 text-right text-xs font-medium text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php if (!empty($publications)): ?>
                <?php foreach ($publications as $pub): ?>
                <tr class="hover:bg-gray-700/50">
                    <td class="px-4 py-4">
                        <p class="text-white font-medium line-clamp-2"><?= htmlspecialchars($pub['title']) ?></p>
                    </td>
                    <td class="px-4 py-4 text-gray-400 hidden md:table-cell">
                        <span class="text-xs"><?= htmlspecialchars($pub['lab_name']) ?></span>
                    </td>
                    <td class="px-4 py-4 text-gray-400"><?= $pub['year'] ?></td>
                    <td class="px-4 py-4">
                        <span class="text-green-400 font-medium"><?= $pub['citations'] ?></span>
                    </td>
                    <td class="px-4 py-4 hidden lg:table-cell">
                        <span class="px-2 py-1 text-xs rounded-full <?= $pub['is_active'] ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' ?>">
                            <?= $pub['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-right whitespace-nowrap">
                        <a href="<?= baseUrl('admin/?p=publications&action=edit&type=publications&id=' . $pub['id']) ?>" class="text-cyan-400 hover:text-cyan-300 mr-3">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= baseUrl('admin/?p=publications&action=delete&type=publications&id=' . $pub['id']) ?>" onclick="return confirmDelete()" class="text-red-400 hover:text-red-300">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada publikasi.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php elseif ($action === 'add' || $action === 'edit'): ?>

<?php if ($type === 'labs'): ?>
<!-- Lab Profile Form -->
<div class="bg-gray-800 rounded-xl border border-gray-700 p-4 sm:p-6">
    <h2 class="text-lg font-semibold text-white mb-6">
        <i class="fas fa-flask mr-2 text-cyan-400"></i>
        <?= $action === 'edit' ? 'Edit Profil Laboratorium' : 'Tambah Profil Laboratorium Baru' ?>
    </h2>
    
    <form method="POST" class="space-y-4 sm:space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="form_type" value="lab">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Nama Laboratorium *</label>
                <input type="text" name="lab_name" required value="<?= htmlspecialchars($editData['lab_name'] ?? '') ?>"
                       placeholder="Laboratorium Jaringan dan Keamanan Siber"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-cyan-500">
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Kepala Lab *</label>
                <input type="text" name="kepala_lab" required value="<?= htmlspecialchars($editData['kepala_lab'] ?? '') ?>"
                       placeholder="Erfan Rohadi, ST., M.Eng., Ph.D."
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-cyan-500">
            </div>
        </div>
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">URL SINTA *</label>
            <input type="url" name="sinta_url" required value="<?= htmlspecialchars($editData['sinta_url'] ?? '') ?>"
                   placeholder="https://sinta.kemdiktisaintek.go.id/authors/profile/..."
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-cyan-500">
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Total Publikasi</label>
                <input type="number" name="total_publications" min="0" value="<?= $editData['total_publications'] ?? 0 ?>"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-cyan-500">
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Icon (FontAwesome)</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($editData['icon'] ?? 'flask') ?>"
                       placeholder="shield-alt, code, brain, etc."
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-cyan-500">
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Urutan</label>
                <input type="number" name="order_index" min="0" value="<?= $editData['order_index'] ?? 0 ?>"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-cyan-500">
            </div>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" <?= ($editData['is_active'] ?? true) ? 'checked' : '' ?>
                   class="w-4 h-4 text-cyan-600 bg-gray-700 border-gray-600 rounded focus:ring-cyan-500">
            <label for="is_active" class="ml-2 text-gray-300">Aktif (tampilkan di website)</label>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="px-6 py-3 bg-cyan-600 text-white font-semibold rounded-lg hover:bg-cyan-500 transition-colors">
                <i class="fas fa-save mr-2"></i><?= $action === 'edit' ? 'Simpan Perubahan' : 'Tambah Lab' ?>
            </button>
            <a href="<?= baseUrl('admin/?p=publications') ?>" class="px-6 py-3 bg-gray-700 text-gray-300 font-semibold rounded-lg hover:bg-gray-600 transition-colors text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<?php else: ?>
<!-- Publication Form -->
<div class="bg-gray-800 rounded-xl border border-gray-700 p-4 sm:p-6">
    <h2 class="text-lg font-semibold text-white mb-6">
        <i class="fas fa-file-alt mr-2 text-purple-400"></i>
        <?= $action === 'edit' ? 'Edit Publikasi' : 'Tambah Publikasi Baru' ?>
    </h2>
    
    <form method="POST" class="space-y-4 sm:space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="form_type" value="publication">
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Laboratorium *</label>
            <select name="lab_id" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-cyan-500">
                <option value="">Pilih Laboratorium</option>
                <?php foreach ($allLabs as $lab): ?>
                <option value="<?= $lab['id'] ?>" <?= ($editData['lab_id'] ?? 0) == $lab['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($lab['lab_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Judul Publikasi *</label>
            <textarea name="title" required rows="3"
                      placeholder="Judul lengkap publikasi..."
                      class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-cyan-500"><?= htmlspecialchars($editData['title'] ?? '') ?></textarea>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Tahun *</label>
                <input type="number" name="year" required min="2000" max="<?= date('Y') + 1 ?>" 
                       value="<?= $editData['year'] ?? date('Y') ?>"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-cyan-500">
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Jumlah Sitasi</label>
                <input type="number" name="citations" min="0" value="<?= $editData['citations'] ?? 0 ?>"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-cyan-500">
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-2">Urutan</label>
                <input type="number" name="order_index" min="0" value="<?= $editData['order_index'] ?? 0 ?>"
                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:border-cyan-500">
            </div>
        </div>
        
        <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">URL Publikasi</label>
            <input type="url" name="url" value="<?= htmlspecialchars($editData['url'] ?? '') ?>"
                   placeholder="https://..."
                   class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:border-cyan-500">
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" <?= ($editData['is_active'] ?? true) ? 'checked' : '' ?>
                   class="w-4 h-4 text-cyan-600 bg-gray-700 border-gray-600 rounded focus:ring-cyan-500">
            <label for="is_active" class="ml-2 text-gray-300">Aktif (tampilkan di website)</label>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <button type="submit" class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-500 transition-colors">
                <i class="fas fa-save mr-2"></i><?= $action === 'edit' ? 'Simpan Perubahan' : 'Tambah Publikasi' ?>
            </button>
            <a href="<?= baseUrl('admin/?p=publications&type=publications') ?>" class="px-6 py-3 bg-gray-700 text-gray-300 font-semibold rounded-lg hover:bg-gray-600 transition-colors text-center">
                Batal
            </a>
        </div>
    </form>
</div>
<?php endif; ?>

<?php endif; ?>



