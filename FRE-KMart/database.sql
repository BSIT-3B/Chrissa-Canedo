-- SongSong Mart Database Schema - Complete Version
-- This creates a fresh database with all necessary columns for the admin dashboard

CREATE DATABASE IF NOT EXISTS songsong_mart;

USE songsong_mart;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS products;

DROP TABLE IF EXISTS categories;

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table with complete structure for admin dashboard
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    old_price DECIMAL(10, 2) NULL,
    category_id INT,
    image_url VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    in_stock BOOLEAN DEFAULT TRUE,
    stock INT DEFAULT 0 COMMENT 'Current stock quantity',
    min_stock INT DEFAULT 10 COMMENT 'Minimum stock alert threshold',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Record update timestamp',
    FOREIGN KEY (category_id) REFERENCES categories (id)
);

-- Add indexes for better performance
CREATE INDEX idx_products_stock ON products (stock);

CREATE INDEX idx_products_category ON products (category_id);

CREATE INDEX idx_products_created_at ON products (created_at);

CREATE INDEX idx_products_name ON products (name);

-- Insert Categories (Only categories with products)
INSERT INTO
    categories (name, description, image_url)
VALUES (
        'Ramen & Instant Noodles',
        'Korean instant noodles and ramyun varieties',
        'placeholder-ramen.jpg'
    ),
    (
        'Self Cooking Ramyun',
        'DIY cooking ramyun experience',
        'placeholder-diy.jpg'
    ),
    (
        'Toppings',
        'Add-ons for your ramyun',
        'placeholder-toppings.jpg'
    ),
    (
        'Meat & Seafood',
        'Fresh and processed meat and seafood',
        'placeholder-meat.jpg'
    ),
    (
        'Ice Cream',
        'Korean ice cream and frozen desserts',
        'placeholder-icecream.jpg'
    ),
    (
        'Snacks',
        'Korean snacks and treats',
        'placeholder-snacks.jpg'
    );

-- Insert Products with stock information
-- Category 1: Ramen & Instant Noodles (12 products)
INSERT INTO
    products (
        name,
        description,
        price,
        category_id,
        image_url,
        stock,
        min_stock
    )
VALUES (
        'Samyang Ramen (Spicy)',
        'Authentic Korean spicy instant ramen',
        35.00,
        1,
        'placeholder-samyang.jpg',
        150,
        20
    ),
    (
        'Shin Ramen',
        'Premium Korean instant noodles',
        190.00,
        1,
        'placeholder-shin.jpg',
        80,
        15
    ),
    (
        'Jin Ramen',
        'Mild Korean instant ramen',
        170.00,
        1,
        'placeholder-jin.jpg',
        95,
        15
    ),
    (
        'Paldo Bibimyum',
        'Korean mixed noodles',
        40.00,
        1,
        'placeholder-bibim.jpg',
        120,
        20
    ),
    (
        'Ottogi Jin Bibimmyim',
        'Spicy mixed noodles',
        40.00,
        1,
        'placeholder-ottogi.jpg',
        75,
        15
    ),
    (
        'Paldo Kho',
        'Korean curry noodles',
        40.00,
        1,
        'placeholder-kho.jpg',
        90,
        15
    ),
    (
        'Paldo Jjajang',
        'Black bean sauce noodles',
        40.00,
        1,
        'placeholder-jjajang.jpg',
        110,
        20
    ),
    (
        'Yeol Cup',
        'Cup ramyun',
        50.00,
        1,
        'placeholder-yeol.jpg',
        200,
        30
    ),
    (
        'Gosomi',
        'Premium instant noodles',
        50.00,
        1,
        'placeholder-gosomi.jpg',
        60,
        15
    ),
    (
        'Chapagetti',
        'Jjajangmyeon instant noodles',
        45.00,
        1,
        'placeholder-chapa.jpg',
        85,
        20
    ),
    (
        'Neoguri',
        'Seafood udon ramyun',
        45.00,
        1,
        'placeholder-neoguri.jpg',
        70,
        15
    ),
    (
        'Buldak Ramen',
        'Super spicy chicken ramen',
        55.00,
        1,
        'placeholder-buldak.jpg',
        125,
        25
    );

-- Category 2: Self Cooking Ramyun (8 products)
INSERT INTO
    products (
        name,
        description,
        price,
        category_id,
        image_url,
        stock,
        min_stock
    )
VALUES (
        'Blackbean Jjajangmyeon (Non-Spicy)',
        'DIY black bean noodles',
        65.00,
        2,
        'placeholder-jjajang-diy.jpg',
        45,
        10
    ),
    (
        'Blackbean Jjajangmyeon (Spicy)',
        'DIY spicy black bean noodles',
        85.00,
        2,
        'placeholder-jjajang-spicy.jpg',
        35,
        10
    ),
    (
        'K-Ramyun Stir Fry (Non-Spicy)',
        'DIY stir-fried ramyun',
        60.00,
        2,
        'placeholder-stirfry.jpg',
        50,
        10
    ),
    (
        'K-Ramyun Stir Fry (Mild Spicy)',
        'DIY mild spicy stir fry',
        60.00,
        2,
        'placeholder-stirfry-mild.jpg',
        40,
        10
    ),
    (
        'K-Ramyun Stir Fry (Spicy)',
        'DIY spicy stir fry',
        85.00,
        2,
        'placeholder-stirfry-spicy.jpg',
        55,
        10
    ),
    (
        'K-Ramyun Soup (Non-Spicy)',
        'DIY ramyun soup',
        40.00,
        2,
        'placeholder-soup.jpg',
        50,
        10
    ),
    (
        'K-Ramyun Soup (Mild Spicy)',
        'DIY mild spicy soup',
        55.00,
        2,
        'placeholder-soup-mild.jpg',
        40,
        10
    ),
    (
        'K-Ramyun Soup (Spicy)',
        'DIY spicy ramyun soup',
        70.00,
        2,
        'placeholder-soup-spicy.jpg',
        55,
        10
    );

-- Category 3: Toppings (17 products)
-- ₱20 Toppings
INSERT INTO
    products (
        name,
        description,
        price,
        category_id,
        image_url,
        stock,
        min_stock
    )
VALUES (
        'Cheese Bun',
        'Cheese-filled bun topping',
        20.00,
        3,
        'placeholder-cheese-bun.jpg',
        80,
        15
    ),
    (
        'Lobster Ball',
        'Premium lobster ball',
        20.00,
        3,
        'placeholder-lobster.jpg',
        80,
        15
    ),
    (
        'Cheese Gindara Tofu (2pcs)',
        'Cheese cod tofu pieces',
        20.00,
        3,
        'placeholder-tofu.jpg',
        80,
        15
    ),
    (
        'Pork Seafood Bun',
        'Mixed pork and seafood bun',
        20.00,
        3,
        'placeholder-pork-bun.jpg',
        80,
        15
    ),
    (
        'Cheesy Fish Curb',
        'Cheese fish cake',
        20.00,
        3,
        'placeholder-fish-cheese.jpg',
        80,
        15
    ),
    (
        'Squid Bun',
        'Squid-filled bun',
        20.00,
        3,
        'placeholder-squid.jpg',
        80,
        15
    ),
    (
        'Siomai',
        'Chinese-style dumpling',
        20.00,
        3,
        'placeholder-siomai.jpg',
        80,
        15
    ),
    (
        'Spam',
        'Korean spam slice',
        20.00,
        3,
        'placeholder-spam.jpg',
        80,
        15
    ),
    (
        'Milk Sea Urchin',
        'Creamy sea urchin',
        20.00,
        3,
        'placeholder-urchin.jpg',
        80,
        15
    ),
    (
        'Kimchi',
        'Fermented cabbage',
        20.00,
        3,
        'placeholder-kimchi-top.jpg',
        80,
        15
    );

-- ₱15 Toppings
INSERT INTO
    products (
        name,
        description,
        price,
        category_id,
        image_url,
        stock,
        min_stock
    )
VALUES (
        'Fishcake (3pcs)',
        'Korean fishcake pieces',
        15.00,
        3,
        'placeholder-fishcake.jpg',
        100,
        20
    ),
    (
        'Sausage (3pcs)',
        'Korean sausage pieces',
        15.00,
        3,
        'placeholder-sausage.jpg',
        100,
        20
    ),
    (
        'Dumpling (1pc)',
        'Korean dumpling',
        15.00,
        3,
        'placeholder-dumpling.jpg',
        100,
        20
    ),
    (
        'Rice Cake (3pcs)',
        'Chewy rice cake pieces',
        15.00,
        3,
        'placeholder-ricecake.jpg',
        100,
        20
    ),
    (
        'Crabstick (3pcs)',
        'Imitation crab sticks',
        15.00,
        3,
        'placeholder-crabstick.jpg',
        100,
        20
    ),
    (
        'Egg',
        'Fresh egg topping',
        15.00,
        3,
        'placeholder-egg.jpg',
        100,
        20
    ),
    (
        'Enoki',
        'Enoki mushrooms',
        15.00,
        3,
        'placeholder-enoki.jpg',
        100,
        20
    );

-- Category 4: Meat & Seafood (6 products)
INSERT INTO
    products (
        name,
        description,
        price,
        category_id,
        image_url,
        stock,
        min_stock
    )
VALUES (
        'Sausage 1kg',
        'Korean sausage bulk pack',
        530.00,
        4,
        'placeholder-sausage-1kg.jpg',
        35,
        8
    ),
    (
        'Sausage 105g',
        'Korean sausage small pack',
        100.00,
        4,
        'placeholder-sausage-small.jpg',
        45,
        10
    ),
    (
        'Pork Jowl 500g',
        'Premium pork jowl',
        180.00,
        4,
        'placeholder-jowl.jpg',
        35,
        8
    ),
    (
        'Pork Belly 500g',
        'Fresh pork belly',
        250.00,
        4,
        'placeholder-belly.jpg',
        25,
        5
    ),
    (
        'Beef Short Plate 500g',
        'Premium beef short plate',
        320.00,
        4,
        'placeholder-beef.jpg',
        25,
        5
    ),
    (
        'Fish/Hotdog Bar',
        'Fish and hotdog bar',
        65.00,
        4,
        'placeholder-fishbar.jpg',
        30,
        8
    );

-- Category 5: Ice Cream (9 products)
INSERT INTO
    products (
        name,
        description,
        price,
        old_price,
        category_id,
        image_url,
        stock,
        min_stock
    )
VALUES (
        'Melona (All Flavors)',
        'Korean melon ice cream',
        30.00,
        NULL,
        5,
        'placeholder-melona.jpg',
        100,
        20
    ),
    (
        'Papico (All Flavors)',
        'Tube ice cream',
        40.00,
        NULL,
        5,
        'placeholder-papico.jpg',
        85,
        20
    ),
    (
        'Choco Fudge',
        'Chocolate fudge ice cream',
        38.00,
        NULL,
        5,
        'placeholder-choco.jpg',
        70,
        15
    ),
    (
        'Melona Big Bar',
        'Large melon ice cream',
        35.00,
        NULL,
        5,
        'placeholder-melona-big.jpg',
        90,
        20
    ),
    (
        'Power Cup (All Flavors)',
        'Cup ice cream',
        38.00,
        NULL,
        5,
        'placeholder-power.jpg',
        70,
        15
    ),
    (
        'Tetris',
        'Tetris-shaped ice cream',
        35.00,
        NULL,
        5,
        'placeholder-tetris.jpg',
        80,
        15
    ),
    (
        'Red Bean',
        'Red bean ice cream',
        30.00,
        60.00,
        5,
        'placeholder-redbean.jpg',
        55,
        12
    ),
    (
        'GooGoo Pouch',
        'Premium ice cream pouch',
        50.00,
        NULL,
        5,
        'placeholder-googoo.jpg',
        80,
        18
    ),
    (
        'Cledor',
        'Premium ice cream',
        125.00,
        NULL,
        5,
        'placeholder-cledor.jpg',
        60,
        15
    );

-- Category 6: Snacks (7 products)
INSERT INTO
    products (
        name,
        description,
        price,
        category_id,
        image_url,
        stock,
        min_stock
    )
VALUES (
        'Smoky Bacon Chips',
        'Korean bacon flavored chips',
        50.00,
        6,
        'placeholder-bacon.jpg',
        120,
        25
    ),
    (
        'Marshmallow Snack',
        'Sweet marshmallow treat',
        50.00,
        6,
        'placeholder-marshmallow.jpg',
        150,
        30
    ),
    (
        'Oreo',
        'Chocolate sandwich cookies',
        40.00,
        6,
        'placeholder-oreo.jpg',
        100,
        20
    ),
    (
        'Galaxy Chocolate',
        'Premium chocolate bar',
        100.00,
        6,
        'placeholder-galaxy.jpg',
        150,
        30
    ),
    (
        'Pink Chocolate',
        'Strawberry chocolate',
        170.00,
        6,
        'placeholder-pink.jpg',
        100,
        20
    ),
    (
        'Ambasa',
        'Korean soft drink',
        40.00,
        6,
        'placeholder-ambasa.jpg',
        100,
        20
    ),
    (
        'Ottogi Wasabi',
        'Wasabi flavored snack',
        30.00,
        6,
        'placeholder-wasabi.jpg',
        75,
        15
    );

-- Insert some featured products
UPDATE products
SET
    is_featured = TRUE
WHERE
    id IN (1, 2, 3, 4, 13, 14, 25, 30);

-- Insert some products with old prices (on sale)
UPDATE products SET old_price = 200.00 WHERE id = 2;
-- Shin Ramen
UPDATE products SET old_price = 45.00 WHERE id = 4;
-- Paldo Bibimyum
UPDATE products SET old_price = 70.00 WHERE id = 14;
-- Blackbean Jjajangmyeon (Non-Spicy)
UPDATE products SET old_price = 100.00 WHERE id = 23;
-- Chicken Gyoza
UPDATE products SET old_price = 50.00 WHERE id = 33;
-- Melona (Melon)
UPDATE products SET old_price = 90.00 WHERE id = 52;
-- Choco Pie

-- Set some products as out of stock for testing
UPDATE products
SET
    stock = 0,
    in_stock = FALSE
WHERE
    id IN (26, 39, 56);

-- Display final statistics
SELECT 'Database setup completed successfully!' as status;

SELECT
    c.name as category,
    COUNT(p.id) as product_count,
    AVG(p.price) as avg_price,
    SUM(p.stock) as total_stock
FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
GROUP BY
    c.id,
    c.name
ORDER BY c.id;