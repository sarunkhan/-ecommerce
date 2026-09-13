CREATE TABLE IF NOT EXISTS users (
 id SERIAL PRIMARY KEY, name VARCHAR(120) NOT NULL, email VARCHAR(190) UNIQUE NOT NULL,
 password_hash VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS products (
 id SERIAL PRIMARY KEY, name VARCHAR(200) NOT NULL, description TEXT,
 price NUMERIC(10,2) NOT NULL CHECK(price>=0), image TEXT, category VARCHAR(100),
 stock INTEGER DEFAULT 0 CHECK(stock>=0), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS orders (
 id SERIAL PRIMARY KEY, user_id INTEGER REFERENCES users(id),
 total_amount NUMERIC(10,2) NOT NULL, status VARCHAR(50) DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS order_items (
 id SERIAL PRIMARY KEY, order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
 product_id INTEGER REFERENCES products(id), quantity INTEGER NOT NULL CHECK(quantity>0),
 price NUMERIC(10,2) NOT NULL
);
INSERT INTO products(name,description,price,image,category,stock) VALUES
('Samsung Mobile','Demo product',15999,'','Mobile',10),
('HP Laptop','Demo product',45999,'','Laptop',5),
('Wireless Headphone','Demo product',1999,'','Electronics',20)
ON CONFLICT DO NOTHING;