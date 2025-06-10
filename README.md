# 🍽️ Restaurante PHP App 

## 📋 Descripción
Aplicación web para la gestión de un restaurante desarrollada en PHP con MySQL. Permite el registro de usuarios con diferentes roles (admin/cliente), gestión completa del menú y funcionalidades de favoritos y carrito de compras.

## ✨ Funcionalidades Implementadas

### 📌 Requisitos Obligatorios
- ✅ Registro de Usuario (admin/cliente)
- ✅ Inicio de Sesión con validación de roles
- ✅ Agregar Menú (Solo admin)
- ✅ Modificar Menú (Solo admin)
- ✅ Eliminar Menú (Solo admin) - Con eliminación en cascada
- ✅ Ver lista de menú en la Homepage con ordenamiento

### 🌟 Requisitos Opcionales (Solo clientes)
- ✅ Agregar/Eliminar Menú de Favoritos
- ✅ Ordenar Menú por precio y alfabéticamente
- ✅ Agregar/Eliminar Menú del Carrito de Compra
- ✅ Ver Carrito de Compras (sin finalización de compra)
- ⏳ Realizar compra con envío de email (Próximamente)
- ⏳ Ver historial de compras (Próximamente)

## 🚀 Instalación y Configuración
1. Clonar el repositorio 
2. Configurar la base de datos
    - Abrir phpMyAdmin o tu cliente MySQL preferido
    - Importar el archivo database.sql
    - Verificar que se haya creado la base de datos restaurante
3. Configurar conexión
    - Editar el archivo config.php si es necesario:
    - php$host = 'localhost';
    - $db   = 'restaurante';
    - $user = 'root';
    - $pass = '';
4. Ejecutar la aplicación

Copiar el proyecto a la carpeta htdocs de XAMPP
Iniciar Apache y MySQL en XAMPP
Abrir en el navegador: http://localhost/ObligatorioPHP

## 👤 Usuarios de Prueba
Administrador
- Usuario: admin
- Contraseña: admin
- Permisos: Gestión completa del menú

Cliente
- Usuario: cliente
- Contraseña: cliente
- Permisos: Favoritos, carrito, visualización del menú

## 🗃️ Estructura de la Base de Datos

Tabla usuarios
- id (Primary Key)
- username (Unique)
- password (Hashed)
- role (admin/cliente)

Tabla menu

- id (Primary Key)
- nombre
- descripcion
- precio

Tabla favoritos

- user_id (Foreign Key → usuarios.id)
- menu_id (Foreign Key → menu.id)

Tabla compras (Para implementación futura)

- id (Primary Key)
- user_id (Foreign Key → usuarios.id)
- detalle
- fecha

## 🔐 Características de Seguridad

- Autenticación con password hashing
- Autorización basada en roles
- Validación de datos de entrada
- Redirección automática según permisos
- Sesiones seguras para manejo de estado

## 🎯 Funcionalidades por Rol

👔 Administrador

- ✅ Agregar items al menú
- ✅ Editar items existentes
- ✅ Eliminar items (con limpieza automática)
- ✅ Ver lista completa del menú
- ❌ No puede usar favoritos ni carrito

👤 Cliente

- ✅ Ver menú con ordenamiento
- ✅ Agregar/quitar favoritos
- ✅ Gestionar carrito de compras
- ✅ Ver estadísticas de favoritos
- ❌ No puede modificar el menú

🌐 Usuario no autenticado

- ✅ Ver menú público
- ✅ Registrarse
- ✅ Iniciar sesión
- ❌ Sin acceso a funcionalidades interactivas

## 🔄 Próximas Funcionalidades

- Sistema de compras con envío de email
- Historial de compras para clientes
- Dashboard de estadísticas para admins
- Gestión de categorías de menú
- Sistema de calificaciones de platos

## 📄 Licencia
Este proyecto fue desarrollado como parte del curso de Desarrollo de Aplicaciones Web con PHP.
