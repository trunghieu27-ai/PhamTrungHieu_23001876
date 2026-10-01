CREATE DATABASE shopping_cart;
USE shopping_cart;

CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO cart_items (name, price, quantity) VALUES
('Áo phông', 80000.00, 2),
('Quần bò', 250000.00, 6),
('Giày thể thao', 500000.00, 1),
('Mũ lưỡi trai', 45000.00, 10),
('Balo', 150000.00, 4);

SELECT * FROM cart_items;

SELECT * FROM cart_items WHERE price > 100000;

SELECT * FROM cart_items WHERE quantity > 5;

SELECT * FROM cart_items ORDER BY price DESC;

UPDATE cart_items SET price = 90000.00 WHERE id = 1;

UPDATE cart_items SET quantity = 3 WHERE id = 3;

DELETE FROM cart_items WHERE id = 5;

SELECT * FROM cart_items;

SELECT name, price, quantity, (price * quantity) AS thanh_tien FROM cart_items;

SELECT SUM(price * quantity) AS tong_tien_gio_hang FROM cart_items;

CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

INSERT INTO movies (title, price, total_seats, available_seats) VALUES
('Avengers: Endgame', 120000.00, 200, 50),
('Avatar 2', 150000.00, 250, 10),
('Mắt Biếc', 90000.00, 150, 60),
('Bố Già', 110000.00, 200, 0),
('Lật Mặt 6', 95000.00, 180, 100);

select * from movies

SELECT * FROM movies WHERE price > 100000;

SELECT * FROM movies WHERE available_seats > 50;

SELECT * FROM movies ORDER BY price DESC;

UPDATE movies SET available_seats = 45 WHERE id = 1;

DELETE FROM movies WHERE id = 5;

select * from movies

SELECT title, (total_seats - available_seats) AS ve_da_ban 
FROM movies;

SELECT title, ((total_seats - available_seats) * price) AS doanh_thu 
FROM movies;

SELECT SUM((total_seats - available_seats) * price) AS tong_doanh_thu 
FROM movies;

SELECT title, (total_seats - available_seats) AS ve_da_ban 
FROM movies 
ORDER BY (total_seats - available_seats) DESC 
LIMIT 1;















