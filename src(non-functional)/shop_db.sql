Create Database `shop_db`;

-- Table structure for table `cart`

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` float(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `message`

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Table structure for table `orders`

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` float(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `products`

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` float(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `users`

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT;
COMMIT;



---------------------------------------------

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  user_type VARCHAR(20) DEFAULT 'user',
  preferencias_genero VARCHAR(255),
  preferencias_autor VARCHAR(255)
);

CREATE TABLE products (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  stocks INT NOT NULL,
  image VARCHAR(255) NOT NULL,
  genero VARCHAR(100),
  autor VARCHAR(100),
  editora VARCHAR(100),
  tags VARCHAR(255)
);

CREATE TABLE cart (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  product_id INT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  quantity INT NOT NULL
);

CREATE TABLE message (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  number VARCHAR(12) NOT NULL,
  message TEXT NOT NULL
);


CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  number VARCHAR(12) NOT NULL,
  email VARCHAR(100) NOT NULL,
  method VARCHAR(50) NOT NULL,
  address VARCHAR(500) NOT NULL,
  total_products INT NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  placed_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  payment_status VARCHAR(20) DEFAULT 'pending'
);

CREATE TABLE interactions (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  product_id INT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  tipo_interacao VARCHAR(50) CHECK (tipo_interacao IN ('compra', 'visualização', 'avaliação')) NOT NULL,
  data_interacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ratings (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  product_id INT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  classificacao INT CHECK (classificacao >= 1 AND classificacao <= 5) NOT NULL
);

CREATE TABLE recommendations (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  product_id INT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  metodo_recomendacao VARCHAR(50) CHECK (metodo_recomendacao IN ('colaborativa', 'conteúdo', 'híbrida')) NOT NULL,
  data_recomendacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);



      ---------------------------------------------------------------------------

 Users table 
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  name (VARCHAR(100), NOT NULL)
  email (VARCHAR(100), NOT NULL, UNIQUE)
  password (VARCHAR(255), NOT NULL)
  user_type (VARCHAR(20), NOT NULL DEFAULT 'user')
  preferencias_genero (VARCHAR(255))
  preferencias_autor (VARCHAR(255))

 Products Table
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  name (VARCHAR(100), NOT NULL)
  price (DECIMAL(10,2), NOT NULL)
  stocks (INT, NOT NULL)
  image (VARCHAR(255), NOT NULL)
  genero (VARCHAR(100))
  autor (VARCHAR(100))
  editora (VARCHAR(100))
  tags (VARCHAR(255))

 Cart Table
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  user_id (INT, NOT NULL, FOREIGN KEY)
  product_id (INT, NOT NULL, FOREIGN KEY)
  quantity (INT, NOT NULL)

 Orders Table
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  user_id (INT, NOT NULL, FOREIGN KEY)
  number (VARCHAR(12), NOT NULL)
  email (VARCHAR(100), NOT NULL)
  method (VARCHAR(50), NOT NULL)
  address (VARCHAR(500), NOT NULL)
  total_products (INT, NOT NULL)
  total_price (DECIMAL(10,2), NOT NULL)
  placed_on (DATETIME, NOT NULL)
  payment_status (VARCHAR(20), NOT NULL DEFAULT 'pending')

 Message Table
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  user_id (INT, NOT NULL, FOREIGN KEY)
  name (VARCHAR(100), NOT NULL)
  email (VARCHAR(100), NOT NULL)
  number (VARCHAR(12), NOT NULL)
  message (VARCHAR(500), NOT NULL)

 Interactions Table
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  user_id (INT, NOT NULL, FOREIGN KEY)
  product_id (INT, NOT NULL, FOREIGN KEY)
  tipo_interacao (ENUM('compra', 'visualização', 'avaliação'), NOT NULL)
  data_interacao (DATETIME, NOT NULL)

 Ratings Table
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  user_id (INT, NOT NULL, FOREIGN KEY)
  product_id (INT, NOT NULL, FOREIGN KEY)
  classificacao (INT CHECK (classificacao >= 1 AND classificacao <= 5))

 Recommendations Table
  id (INT, AUTO_INCREMENT, PRIMARY KEY)
  user_id (INT, NOT NULL, FOREIGN KEY)
  product_id (INT, NOT NULL, FOREIGN KEY)
  metodo_recomendacao (ENUM('colaborativa', 'conteúdo', 'híbrida'), NOT NULL)
  data_recomendacao (DATETIME, NOT NULL)

   -----------------------------------------------------------------------------------

INSERT INTO users (name, email, password) VALUES ('João Jhon', 'joao.john@gmail.com', 'password1122121idk');
INSERT INTO products (name, price, image) VALUES ('The Great Book', 19.99, 'book.jpg');
INSERT INTO cart (user_id, product_id, quantity) VALUES (1, 1, 2);
