-- NCS Laboratory Database Schema
-- PostgreSQL Database
-- Updated: December 2025

-- Drop existing tables if exist (for fresh installation)
DROP TABLE IF EXISTS publications CASCADE;
DROP TABLE IF EXISTS lab_sinta_profiles CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS documents CASCADE;
DROP TABLE IF EXISTS gallery CASCADE;
DROP TABLE IF EXISTS agenda CASCADE;
DROP TABLE IF EXISTS services CASCADE;
DROP TABLE IF EXISTS focus_areas CASCADE;
DROP TABLE IF EXISTS roadmap CASCADE;
DROP TABLE IF EXISTS team_members CASCADE;
DROP TABLE IF EXISTS organization_structure CASCADE;
DROP TABLE IF EXISTS settings CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS external_links CASCADE;

-- Create extension for UUID (optional)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- =====================================================
-- CORE TABLES
-- =====================================================

-- Users table for admin authentication
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin' CHECK (role IN ('admin', 'operator')),
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Settings table for dynamic content
CREATE TABLE settings (
    id SERIAL PRIMARY KEY,
    key VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    type VARCHAR(20) DEFAULT 'text',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- ORGANIZATION TABLES
-- =====================================================

-- Organization structure table
CREATE TABLE organization_structure (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(150) NOT NULL,
    photo VARCHAR(255),
    email VARCHAR(100),
    phone VARCHAR(20),
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Team members (development team credits)
CREATE TABLE team_members (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    nim VARCHAR(20),
    role VARCHAR(100),
    photo VARCHAR(255),
    group_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- CONTENT TABLES
-- =====================================================

-- Agenda table for upcoming events
CREATE TABLE agenda (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    event_time TIME,
    location VARCHAR(255),
    image VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gallery table for photos/videos documentation
CREATE TABLE gallery (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_type VARCHAR(20) DEFAULT 'image' CHECK (file_type IN ('image', 'video')),
    category VARCHAR(50),
    event_date DATE,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    view_count INT DEFAULT 0,
    created_by INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Documents table for community service (pengabdian) PDFs
CREATE TABLE documents (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_size INT,
    category VARCHAR(50) NOT NULL CHECK (category IN ('penelitian', 'pengabdian')),
    author VARCHAR(500),
    publication_date DATE,
    keywords TEXT,
    download_count INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- RESEARCH PUBLICATIONS TABLES (SINTA)
-- =====================================================

-- Lab SINTA profiles table
CREATE TABLE lab_sinta_profiles (
    id SERIAL PRIMARY KEY,
    lab_name VARCHAR(255) NOT NULL,
    kepala_lab VARCHAR(255) NOT NULL,
    sinta_url VARCHAR(500) NOT NULL,
    total_publications INT DEFAULT 0,
    icon VARCHAR(50),
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Publications table for lab research publications
CREATE TABLE publications (
    id SERIAL PRIMARY KEY,
    lab_id INT NOT NULL REFERENCES lab_sinta_profiles(id) ON DELETE CASCADE,
    lab_name VARCHAR(255) NOT NULL,
    title TEXT NOT NULL,
    year INT NOT NULL,
    citations INT DEFAULT 0,
    url VARCHAR(500),
    sinta_id VARCHAR(100),
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- SERVICE & FACILITY TABLES
-- =====================================================

-- Services table for facilities and consultation
CREATE TABLE services (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50) NOT NULL CHECK (category IN ('sarana', 'konsultatif')),
    icon VARCHAR(50),
    image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Focus Areas table for lab specialization areas
CREATE TABLE focus_areas (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    icon VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Roadmap table for lab development milestones
CREATE TABLE roadmap (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    year INT NOT NULL,
    quarter VARCHAR(10),
    status VARCHAR(20) DEFAULT 'upcoming' CHECK (status IN ('completed', 'in_progress', 'upcoming')),
    icon VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- External links table
CREATE TABLE external_links (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    url VARCHAR(500) NOT NULL,
    icon VARCHAR(50),
    description VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- COMMUNICATION TABLES
-- =====================================================

-- Comments/Guest messages table
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    is_replied BOOLEAN DEFAULT FALSE,
    reply_message TEXT,
    replied_at TIMESTAMP,
    replied_by INT REFERENCES users(id),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- INDEXES
-- =====================================================

CREATE INDEX idx_documents_category ON documents(category);
CREATE INDEX idx_gallery_category ON gallery(category);
CREATE INDEX idx_agenda_event_date ON agenda(event_date);
CREATE INDEX idx_services_category ON services(category);
CREATE INDEX idx_comments_is_read ON comments(is_read);
CREATE INDEX idx_publications_lab_id ON publications(lab_id);
CREATE INDEX idx_publications_citations ON publications(citations DESC);
CREATE INDEX idx_publications_year ON publications(year DESC);
CREATE INDEX idx_organization_order ON organization_structure(order_index);
CREATE INDEX idx_lab_sinta_order ON lab_sinta_profiles(order_index);

-- =====================================================
-- DEFAULT DATA
-- =====================================================

-- Insert default admin user (password: admin123)
-- Hash generated with: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@ncslab.ac.id', '$2y$10$xLRsKP9NDl7Y1xLHrKd3/.YKqMCG3bwLtPxvGVDvBRaLB8g5.vhWO', 'Administrator', 'admin');

-- Insert default settings
INSERT INTO settings (key, value, type, description) VALUES
('site_name', 'NCS Laboratory', 'text', 'Nama situs web'),
('site_tagline', 'Network & Cyber Security Laboratory', 'text', 'Tagline situs'),
('site_description', 'Laboratorium Network & Cyber Security - Pusat Riset Keamanan Siber', 'textarea', 'Deskripsi situs'),
('site_logo', '', 'image', 'Logo situs'),
('site_favicon', '', 'image', 'Favicon situs'),
('contact_email', 'ncslab@polinema.ac.id', 'text', 'Email kontak'),
('contact_phone', '+62-341-404424', 'text', 'Nomor telepon'),
('contact_address', 'Politeknik Negeri Malang, Jl. Soekarno Hatta No.9, Malang 65141', 'textarea', 'Alamat'),
('pendahuluan', '', 'textarea', 'Pendahuluan/Tentang Lab'),
('visi', '', 'textarea', 'Visi laboratorium'),
('misi', '', 'textarea', 'Misi laboratorium'),
('tujuan', '', 'textarea', 'Tujuan laboratorium'),
('footer_text', '© 2025 NCS Laboratory. All Rights Reserved.', 'text', 'Teks footer'),
('social_instagram', '', 'text', 'Link Instagram'),
('social_youtube', '', 'text', 'Link YouTube'),
('social_github', '', 'text', 'Link GitHub');

-- Insert default external links
INSERT INTO external_links (title, url, icon, description, order_index) VALUES
('Polinema', 'https://www.polinema.ac.id', 'building', 'Website resmi Politeknik Negeri Malang', 1),
('JTI Polinema', 'https://jti.polinema.ac.id', 'university', 'Website resmi Jurusan Teknologi Informasi Polinema', 2),
('SINTA', 'https://sinta.kemdiktisaintek.go.id', 'book', 'Science and Technology Index', 3);
