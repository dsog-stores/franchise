-- Create database
CREATE DATABASE IF NOT EXISTS dsog_fashion_store;
USE dsog_fashion_store;

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('mens', 'womens', 'kids', 'accessories', 'gifts') NOT NULL,
    images TEXT, -- Comma-separated image URLs
    status ENUM('active', 'inactive', 'sold_out') DEFAULT 'active',
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_status (status)
);

-- Collections table
CREATE TABLE collections (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table (for tracking)
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    product_name VARCHAR(255),
    price DECIMAL(10,2),
    customer_phone VARCHAR(20),
    customer_name VARCHAR(100),
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    franchisee_code VARCHAR(50),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_franchisee (franchisee_code)
);

-- Insert sample collections
INSERT INTO collections (name, slug, description, image_url, display_order) VALUES
('Men\'s Collection', 'mens', 'Premium menswear for every occasion', 'https://images.unsplash.com/photo-1520975916090-3105956dac38?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 1),
('Women\'s Collection', 'womens', 'Elegant womenswear for all styles', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 2),
('Kids Collection', 'kids', 'Comfortable kids wear', 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 3),
('Accessories', 'accessories', 'Complete your fashion look', 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 4),
('Gifts', 'gifts', 'Perfect presents for special occasions', 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 5);
