-- News CMS Database Schema
-- Simple MVP Version

-- Create Database
CREATE DATABASE IF NOT EXISTS news_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE news_cms;

-- 1. Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Categories Table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. States Table
CREATE TABLE states (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Articles Table
CREATE TABLE articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NOT NULL,
    content TEXT NOT NULL,
    media_type ENUM('image', 'video', 'url') DEFAULT 'image',
    image VARCHAR(255),
    video_url VARCHAR(500),
    external_url VARCHAR(500),
    category_id INT NOT NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    use_custom_date TINYINT(1) DEFAULT 0,
    custom_date DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Article-States Linking Table
CREATE TABLE article_states (
    article_id INT NOT NULL,
    state_id INT NOT NULL,
    PRIMARY KEY (article_id, state_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Settings Table
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    site_name VARCHAR(100) DEFAULT 'News Website',
    site_logo VARCHAR(255),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    live_youtube_url VARCHAR(255),
    live_title VARCHAR(255) DEFAULT 'Watch Live News',
    facebook_url VARCHAR(255),
    instagram_url VARCHAR(255),
    youtube_url VARCHAR(255),
    twitter_url VARCHAR(255),
    snapchat_url VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 7. Newsletter Subscribers Table
CREATE TABLE newsletter (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert Default Admin User (password: admin123)
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com');

-- Insert Default Settings
INSERT INTO settings (site_name, contact_email, contact_phone) VALUES 
('N9 India News', 'n9india1@gmail.com', '+91 98765 43210');

-- Insert Sample Categories
INSERT INTO categories (name, slug, description, order_num) VALUES
('Crime', 'crime', 'Latest crime news from across India', 1),
('Politics', 'politics', 'Political news and updates', 2),
('Sports', 'sports', 'Sports news and scores', 3),
('Entertainment', 'entertainment', 'Entertainment and celebrity news', 4),
('Business', 'business', 'Business and economy news', 5),
('Technology', 'technology', 'Tech news and updates', 6);

-- Insert Sample Indian States
INSERT INTO states (name, slug, order_num) VALUES
('Andhra Pradesh', 'andhra-pradesh', 1),
('Arunachal Pradesh', 'arunachal-pradesh', 2),
('Assam', 'assam', 3),
('Bihar', 'bihar', 4),
('Chhattisgarh', 'chhattisgarh', 5),
('Goa', 'goa', 6),
('Gujarat', 'gujarat', 7),
('Haryana', 'haryana', 8),
('Himachal Pradesh', 'himachal-pradesh', 9),
('Jharkhand', 'jharkhand', 10),
('Karnataka', 'karnataka', 11),
('Kerala', 'kerala', 12),
('Madhya Pradesh', 'madhya-pradesh', 13),
('Maharashtra', 'maharashtra', 14),
('Manipur', 'manipur', 15),
('Meghalaya', 'meghalaya', 16),
('Mizoram', 'mizoram', 17),
('Nagaland', 'nagaland', 18),
('Odisha', 'odisha', 19),
('Punjab', 'punjab', 20),
('Rajasthan', 'rajasthan', 21),
('Sikkim', 'sikkim', 22),
('Tamil Nadu', 'tamil-nadu', 23),
('Telangana', 'telangana', 24),
('Tripura', 'tripura', 25),
('Uttar Pradesh', 'uttar-pradesh', 26),
('Uttarakhand', 'uttarakhand', 27),
('West Bengal', 'west-bengal', 28),
('Delhi', 'delhi', 29);

-- Create indexes for better performance
CREATE INDEX idx_articles_category ON articles(category_id);
CREATE INDEX idx_articles_status ON articles(status);
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_states_slug ON states(slug);
