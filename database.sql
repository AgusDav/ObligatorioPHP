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

INSERT INTO usuarios (username, password, role) VALUES 
('admin', '$2y$10$v1ElTLh/B7Ot7RI8jZ7Nu.JZq6xzZplZJxlDEa8GJW3IAb6Cv8Upa', 'admin'), -- admin123
('cliente', '$2y$10$2a0YwzvAX99UWrqR3xQfZOSgUl/n2e98ZJgN3Z8et0JHd.7lWmPBW', 'cliente'); -- cliente123


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
