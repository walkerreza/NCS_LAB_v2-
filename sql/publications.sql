-- Publications Data for Lab Research
-- Run this after schema.sql to insert publication data
-- Data sourced from SINTA JTI Polinema

-- =====================================================
-- LAB SINTA PROFILES
-- =====================================================

INSERT INTO lab_sinta_profiles (lab_name, kepala_lab, sinta_url, total_publications, icon, order_index) VALUES
('Laboratorium Jaringan dan Keamanan Siber', 'Erfan Rohadi, ST., M.Eng., Ph.D.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/5983891', 184, 'shield-alt', 1),
('Laboratorium Rekayasa Perangkat Lunak', 'Imam Fahrur Rozi, ST., MT.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/6005739', 90, 'code', 2),
('Laboratorium Visi Cerdas dan Sistem Cerdas', 'Dr. Ulla Delfana Rosiani, ST., MT.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/5976576', 79, 'brain', 3),
('Laboratorium Sistem Informasi', 'Dr. Eng. Banni Satria Andoko, S. Kom., M.MSI.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/6090920', 50, 'database', 4),
('Laboratorium Analisa Bisnis', 'Dr. Rakhmat Arianto, S.ST., M.Kom.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/6753831', 70, 'chart-line', 5),
('Laboratorium Teknologi Data', 'Yoppy Yunhasnawa, S.ST., M.Sc.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/6681213', 53, 'server', 6),
('Laboratorium Multimedia dan Perangkat Bergerak', 'Dimas Wahyu Wibowo, ST., MT.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/6162521', 80, 'mobile-alt', 7),
('Laboratorium Informatika Terapan', 'Ir. Yan Watequlis Syaifuddin, ST., M.MT., Ph. D.', 'https://sinta.kemdiktisaintek.go.id/authors/profile/5975696', 155, 'laptop-code', 8);

-- =====================================================
-- PUBLICATIONS (Top Cited from Each Lab)
-- =====================================================

-- Lab 1: Jaringan dan Keamanan Siber
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(1, 'Laboratorium Jaringan dan Keamanan Siber', 'Variations in chlorophyll-a concentration and the impact on Sardinella lemuru catches in Bali Strait, Indonesia', 2010, 149, 'https://sinta.kemdiktisaintek.go.id', 1),
(1, 'Laboratorium Jaringan dan Keamanan Siber', 'Implementation IoT in system monitoring hydroponic plant water circulation and control', 2018, 55, 'https://sinta.kemdiktisaintek.go.id', 2),
(1, 'Laboratorium Jaringan dan Keamanan Siber', 'Sistem Monitoring Budidaya Ikan Lele Berbasis Internet Of Things Menggunakan Raspberry Pi', 2018, 46, 'https://sinta.kemdiktisaintek.go.id', 3),
(1, 'Laboratorium Jaringan dan Keamanan Siber', 'Internet of Things integration in smart grid', 2018, 33, 'https://sinta.kemdiktisaintek.go.id', 4);

-- Lab 2: Rekayasa Perangkat Lunak
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(2, 'Laboratorium Rekayasa Perangkat Lunak', 'Implementasi opinion mining (analisis sentimen) untuk ekstraksi data opini publik pada perguruan tinggi', 2012, 211, 'https://sinta.kemdiktisaintek.go.id', 1),
(2, 'Laboratorium Rekayasa Perangkat Lunak', 'Pengembangan sistem penunjang keputusan penentuan ukt mahasiswa dengan menggunakan metode moora studi kasus politeknik negeri malang', 2017, 72, 'https://sinta.kemdiktisaintek.go.id', 2),
(2, 'Laboratorium Rekayasa Perangkat Lunak', 'Pengembangan Aplikasi Analisis Sentimen Twitter Menggunakan Metode Naïve Bayes Classifier (Studi Kasus SAMSAT Kota Malang)', 2018, 39, 'https://sinta.kemdiktisaintek.go.id', 3),
(2, 'Laboratorium Rekayasa Perangkat Lunak', 'Developing vocabulary card base on Augmented Reality (AR) for learning English', 2021, 34, 'https://sinta.kemdiktisaintek.go.id', 4);

-- Lab 3: Visi Cerdas dan Sistem Cerdas
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(3, 'Laboratorium Visi Cerdas dan Sistem Cerdas', 'Pemanfaatan Wireshark untuk Sniffing Komunikasi Data Berprotokol HTTP pada Jaringan Internet', 2021, 50, 'https://sinta.kemdiktisaintek.go.id', 1),
(3, 'Laboratorium Visi Cerdas dan Sistem Cerdas', 'Segmentasi berbasis k-means pada deteksi citra penyakit daun tanaman jagung', 2020, 31, 'https://sinta.kemdiktisaintek.go.id', 2),
(3, 'Laboratorium Visi Cerdas dan Sistem Cerdas', 'Klasifikasi Jenis Kelamin Pada Citra Wajah Menggunakan Metode Naive Bayes', 2018, 30, 'https://sinta.kemdiktisaintek.go.id', 3),
(3, 'Laboratorium Visi Cerdas dan Sistem Cerdas', 'Sistem Pengambil Keputusan Rekomendasi Lokasi Wisata Malang Raya Dengan Metode MOORA', 2021, 20, 'https://sinta.kemdiktisaintek.go.id', 4);

-- Lab 4: Sistem Informasi
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(4, 'Laboratorium Sistem Informasi', 'Improving English reading for EFL readers with reviewing kit-build concept map', 2020, 64, 'https://sinta.kemdiktisaintek.go.id', 1),
(4, 'Laboratorium Sistem Informasi', 'An analysis of concept mapping style in EFL reading comprehension from the viewpoint of paragraph structure of text', 2019, 11, 'https://sinta.kemdiktisaintek.go.id', 2),
(4, 'Laboratorium Sistem Informasi', 'A Preliminary Study: Toulmin Arguments in English Reading Comprehension for English as Foreign Language Students', 2021, 10, 'https://sinta.kemdiktisaintek.go.id', 3),
(4, 'Laboratorium Sistem Informasi', 'Evaluating the kit-build concept mapping process using sub-map scoring.', 2024, 9, 'https://sinta.kemdiktisaintek.go.id', 4);

-- Lab 5: Analisa Bisnis
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(5, 'Laboratorium Analisa Bisnis', 'Klasifikasi Sentiment Analysis Pada Komentar Peserta Diklat Menggunakan Metode K-Nearest Neighbor', 2019, 41, 'https://sinta.kemdiktisaintek.go.id', 1),
(5, 'Laboratorium Analisa Bisnis', 'Aplikasi Penentuan Dosen Penguji Skripsi Menggunakan Metode TF-IDF dan Vector Space Model', 2017, 40, 'https://sinta.kemdiktisaintek.go.id', 2),
(5, 'Laboratorium Analisa Bisnis', 'Penerapan Metode K-Means dan C4. 5 Untuk prediksi penderita diabetes', 2020, 22, 'https://sinta.kemdiktisaintek.go.id', 3),
(5, 'Laboratorium Analisa Bisnis', 'Detection of immovable objects on visually impaired people walking aids', 2019, 19, 'https://sinta.kemdiktisaintek.go.id', 4);

-- Lab 6: Teknologi Data
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(6, 'Laboratorium Teknologi Data', 'Sistem Prediksi Penjualan Frozen Food dengan Metode Monte Carlo (Studi Kasus: Supermama Frozen Food)', 2022, 29, 'https://sinta.kemdiktisaintek.go.id', 1),
(6, 'Laboratorium Teknologi Data', 'Web application implementation of Android programming learning assistance system and its evaluations', 2021, 25, 'https://sinta.kemdiktisaintek.go.id', 2),
(6, 'Laboratorium Teknologi Data', 'Pengembangan Sistem Pakar Untuk Diagnosa Penyakit Kulit Pada Manusia Menggunakan Metode Naive Bayes', 2019, 19, 'https://sinta.kemdiktisaintek.go.id', 3),
(6, 'Laboratorium Teknologi Data', 'Implementasi metode K-Means, DBSCAN, dan MeanShift untuk analisis jenis ancaman jaringan pada intrusion detection system', 2022, 16, 'https://sinta.kemdiktisaintek.go.id', 4);

-- Lab 7: Multimedia dan Perangkat Bergerak
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(7, 'Laboratorium Multimedia dan Perangkat Bergerak', 'Rancang Bangun Chatbot Untuk Meningkatkan Performa Bisnis', 2019, 79, 'https://sinta.kemdiktisaintek.go.id', 1),
(7, 'Laboratorium Multimedia dan Perangkat Bergerak', 'Analisis Metode Cosine Similarity Pada Aplikasi Ujian Online Esai Otomatis (Studi Kasus JTI Polinema)', 2021, 28, 'https://sinta.kemdiktisaintek.go.id', 2),
(7, 'Laboratorium Multimedia dan Perangkat Bergerak', 'Decision Tree Berbasis SMOTE dalam Analisis Sentimen Penggunaan Artificial Intelligence untuk Skripsi', 2024, 19, 'https://sinta.kemdiktisaintek.go.id', 3),
(7, 'Laboratorium Multimedia dan Perangkat Bergerak', 'Penerapan Metode Promethee Dalam Seleksi Beasiswa Mahasiswa Berprestasi', 2017, 16, 'https://sinta.kemdiktisaintek.go.id', 4);

-- Lab 8: Informatika Terapan
INSERT INTO publications (lab_id, lab_name, title, year, citations, url, order_index) VALUES
(8, 'Laboratorium Informatika Terapan', 'A proposal of Android programming learning assistant system with implementation of basic application learning', 2020, 45, 'https://sinta.kemdiktisaintek.go.id', 1),
(8, 'Laboratorium Informatika Terapan', 'A proposal of grammar-concept understanding problem in Java programming learning assistant system', 2021, 38, 'https://sinta.kemdiktisaintek.go.id', 2),
(8, 'Laboratorium Informatika Terapan', 'Implementasi Analisis Clustering Dan Sentimen Data Twitter Pada Opini Wisata Pantai Menggunakan Metode K-Means', 2018, 37, 'https://sinta.kemdiktisaintek.go.id', 3),
(8, 'Laboratorium Informatika Terapan', 'Twitter data mining for sentiment analysis on peoples feedback against government public policy', 2017, 27, 'https://sinta.kemdiktisaintek.go.id', 4);
