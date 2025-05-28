CREATE DATABASE IF NOT EXISTS restaurante;
USE restaurante;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','cliente') NOT NULL
);

CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    descripcion TEXT,
    precio DECIMAL(10,2)
);

CREATE TABLE favoritos (
    user_id INT,
    menu_id INT,
    PRIMARY KEY (user_id, menu_id),
    FOREIGN KEY (user_id) REFERENCES usuarios(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    detalle TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id)
);
