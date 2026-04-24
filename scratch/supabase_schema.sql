-- SQL Schema for Stormbreaker Portfolio on Supabase
-- Run this in your Supabase SQL Editor

-- 1. Users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 2. Banners table
CREATE TABLE IF NOT EXISTS banners (
    id SERIAL PRIMARY KEY,
    image_path TEXT NOT NULL,
    title TEXT,
    subtitle TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 3. Design Projects table
CREATE TABLE IF NOT EXISTS designs (
    id SERIAL PRIMARY KEY,
    image_path TEXT NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 4. Website Projects table
CREATE TABLE IF NOT EXISTS websites (
    id SERIAL PRIMARY KEY,
    image_path TEXT NOT NULL,
    title TEXT NOT NULL,
    url TEXT,
    description TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 5. Certificates table
CREATE TABLE IF NOT EXISTS certificates (
    id SERIAL PRIMARY KEY,
    image_path TEXT NOT NULL,
    title TEXT NOT NULL,
    description TEXT,
    platform TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 6. Skills table
CREATE TABLE IF NOT EXISTS skills (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    icon_path TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 7. Chatbot Knowledge table
CREATE TABLE IF NOT EXISTS chatbot_knowledge (
    id SERIAL PRIMARY KEY,
    keyword TEXT NOT NULL,
    response TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 8. Partners table
CREATE TABLE IF NOT EXISTS partners (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    logo_path TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 9. Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    role TEXT NOT NULL,
    text TEXT NOT NULL,
    image_path TEXT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- 10. Settings table
CREATE TABLE IF NOT EXISTS settings (
    key_name TEXT PRIMARY KEY,
    key_value TEXT
);

-- Initial Data
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password (adjust later)
INSERT INTO settings (key_name, key_value) VALUES ('ai_provider', 'nvidia'), ('ai_api_key', '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');
