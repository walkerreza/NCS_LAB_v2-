<?php
/**
 * Penelitian (Research) Page - Lab Publications with SINTA
 * Display research publications from each laboratory
 */

// Get all lab profiles with publications
$labProfiles = db()->fetchAll("SELECT * FROM lab_sinta_profiles WHERE is_active = TRUE ORDER BY order_index");

// Get total stats
$totalPubs = db()->fetch("SELECT SUM(total_publications) as total FROM lab_sinta_profiles");
$totalLabs = count($labProfiles);
?>

<!-- Page Header -->
<section class="py-20 bg-gradient-to-b from-[#171c28] to-[#1e2433] relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute w-96 h-96 -top-48 -right-48 bg-[#88c9c9] rounded-full blur-3xl"></div>
        <div class="absolute w-96 h-96 -bottom-48 -left-48 bg-[#c3b1e1] rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-8" data-aos="fade-down">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="<?= baseUrl() ?>" class="text-gray-400 hover:text-[#88c9c9] transition-colors">Beranda</a></li>
                <li><span class="text-gray-600">/</span></li>
                <li><span class="text-[#88c9c9]">Penelitian</span></li>
            </ol>
        </nav>
        
        <!-- Page Title -->
        <div class="max-w-3xl">
            <span class="inline-block px-4 py-2 bg-[#88c9c9]/10 border border-[#88c9c9]/30 rounded-full text-[#88c9c9] text-sm font-semibold mb-4" data-aos="fade-up">
                <i class="fas fa-flask mr-2"></i>Penelitian Laboratorium
            </span>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-white mb-4" data-aos="fade-up" data-aos-delay="100">
                Publikasi Penelitian
            </h1>
            <p class="text-gray-400 text-lg" data-aos="fade-up" data-aos-delay="200">
                Silahkan lihat publikasi lebih lengkapnya di SINTA
            </p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-8" data-aos="fade-up" data-aos-delay="300">
            <div class="bg-[#2a3142] rounded-xl p-4 border border-[#3a4255]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#88c9c9]/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-flask text-[#88c9c9]"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white"><?= $totalLabs ?></p>
                        <p class="text-gray-500 text-xs">Laboratorium</p>
                    </div>
                </div>
            </div>
            <div class="bg-[#2a3142] rounded-xl p-4 border border-[#3a4255]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#a8e6cf]/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-[#a8e6cf]"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white"><?= number_format($totalPubs['total'] ?? 0) ?>+</p>
                        <p class="text-gray-500 text-xs">Publikasi</p>
                    </div>
                </div>
            </div>
            <div class="bg-[#2a3142] rounded-xl p-4 border border-[#3a4255] hidden md:block">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#c3b1e1]/10 rounded-lg flex items-center justify-center">
                        <i class="fas fa-external-link-alt text-[#c3b1e1]"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">SINTA</p>
                        <p class="text-gray-500 text-xs">Terindeks</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lab Publications Section -->
<section class="py-16 bg-[#1e2433]">
    <div class="container mx-auto px-4">
        <div class="space-y-12">
            <?php foreach ($labProfiles as $index => $lab): ?>
            <?php
            // Get publications for this lab
            $publications = db()->fetchAll(
                "SELECT * FROM publications WHERE lab_id = ? AND is_active = TRUE ORDER BY citations DESC, order_index LIMIT 4",
                [$lab['id']]
            );
            ?>
            
            <!-- Lab Section -->
            <div class="cyber-card rounded-2xl overflow-hidden" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <!-- Lab Header -->
                <div class="bg-gradient-to-r from-[#2a3142] to-[#343d52] p-6 border-b border-[#3a4255]">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#88c9c9] to-[#a7c5eb] rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-<?= $lab['icon'] ?? 'flask' ?> text-gray-800 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="font-display text-xl font-bold text-white"><?= htmlspecialchars($lab['lab_name']) ?></h2>
                                <p class="text-gray-400 text-sm">
                                    <i class="fas fa-user-tie mr-1"></i>Kepala Lab: <?= htmlspecialchars($lab['kepala_lab']) ?>
                                </p>
                            </div>
                        </div>
                        <a href="<?= htmlspecialchars($lab['sinta_url']) ?>" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center px-4 py-2 bg-[#88c9c9]/10 border border-[#88c9c9]/30 text-[#88c9c9] rounded-lg hover:bg-[#88c9c9]/20 transition-colors text-sm">
                            <i class="fas fa-external-link-alt mr-2"></i>Lihat semua di SINTA
                        </a>
                    </div>
                </div>
                
                <!-- Sort Tabs (Visual Only) -->
                <div class="px-6 py-3 bg-[#252b3a] border-b border-[#3a4255] flex items-center justify-between">
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-[#88c9c9] font-medium border-b-2 border-[#88c9c9] pb-1">Most Cited</span>
                        <span class="text-gray-500 hover:text-gray-300 cursor-pointer">Latest</span>
                        <span class="text-gray-500 hover:text-gray-300 cursor-pointer">Oldest</span>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <span>Years</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                
                <!-- Publications List -->
                <div class="divide-y divide-[#3a4255]">
                    <?php if (!empty($publications)): ?>
                        <?php foreach ($publications as $pub): ?>
                        <div class="p-6 hover:bg-[#2a3142]/50 transition-colors">
                            <div class="flex flex-col md:flex-row md:items-start gap-4">
                                <div class="flex-1">
                                    <h3 class="text-white font-medium mb-2 hover:text-[#88c9c9] transition-colors">
                                        <?= htmlspecialchars($pub['title']) ?>
                                    </h3>
                                    <div class="flex flex-wrap items-center gap-4 text-sm">
                                        <span class="text-gray-500">
                                            <i class="fas fa-calendar mr-1"></i><?= $pub['year'] ?>
                                        </span>
                                        <span class="text-[#a8e6cf]">
                                            <i class="fas fa-quote-right mr-1"></i><?= $pub['citations'] ?> citations
                                        </span>
                                    </div>
                                </div>
                                <a href="<?= htmlspecialchars($lab['sinta_url']) ?>" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 bg-[#343d52] text-[#88c9c9] rounded-lg hover:bg-[#3a4255] transition-colors text-sm whitespace-nowrap">
                                    <i class="fas fa-book-open mr-2"></i>Baca
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-6 text-center text-gray-500">
                            <i class="fas fa-file-alt text-2xl mb-2"></i>
                            <p>Publikasi akan segera ditampilkan</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination Footer -->
                <div class="px-6 py-4 bg-[#252b3a] border-t border-[#3a4255] flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <button class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-white hover:bg-[#3a4255] rounded transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span class="text-gray-400 text-sm">1-4 of <?= $lab['total_publications'] ?></span>
                        <button class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-white hover:bg-[#3a4255] rounded transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <a href="<?= htmlspecialchars($lab['sinta_url']) ?>" target="_blank" rel="noopener noreferrer"
                       class="text-[#88c9c9] hover:text-[#a8e6cf] text-sm transition-colors">
                        Silahkan lihat publikasi lebih lengkapnya di SINTA <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- CTA Section -->
        <div class="mt-16 text-center" data-aos="fade-up">
            <div class="cyber-card rounded-2xl p-8 md:p-12 bg-gradient-to-br from-[#2a3142] to-[#343d52] border border-[#3a4255]">
                <div class="w-16 h-16 bg-gradient-to-br from-[#88c9c9] to-[#a7c5eb] rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-[#88c9c9]/20">
                    <i class="fas fa-graduation-cap text-gray-800 text-2xl"></i>
                </div>
                <h3 class="font-display text-2xl font-bold text-white mb-4">Tertarik untuk Berkolaborasi?</h3>
                <p class="text-gray-400 max-w-2xl mx-auto mb-8">
                    Kami membuka kesempatan kolaborasi penelitian dengan institusi akademik, industri, dan pemerintah. 
                    Mari bersama-sama mengembangkan inovasi di bidang Teknologi Informasi.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="https://sinta.kemdiktisaintek.go.id" target="_blank" rel="noopener noreferrer"
                       class="cyber-btn inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#88c9c9] to-[#a7c5eb] text-gray-800 font-semibold rounded-xl hover:opacity-90 transition-all shadow-lg shadow-[#88c9c9]/20">
                        <i class="fas fa-external-link-alt mr-2"></i>Kunjungi SINTA
                    </a>
                    <a href="<?= baseUrl('?page=beranda#contact') ?>"
                       class="inline-flex items-center px-6 py-3 bg-[#2a3142] border border-[#3a4255] text-white font-semibold rounded-xl hover:border-[#88c9c9] transition-colors">
                        <i class="fas fa-envelope mr-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
