# Restaurante PHP App

## Descripción
Aplicación web simple para restaurante. Permite registro/login de usuarios (admin/cliente) y gestión del menú.

## Funcionalidades
- Registro / Login
- ABM de Menú para Admin
- Visualización de menú pública

## Requisitos
- PHP >= 7.4
- MySQL
- Servidor Apache (XAMPP recomendado)

## Instalación
1. Clonar el repositorio
2. Importar `database.sql` en tu MySQL
3. Configurar acceso en `includes/db.php`
4. Ejecutar en Apache (`http://localhost/restaurante_php_app`)

## Demo
Sube un video a YouTube y pon el enlace aquí.

## Usuario de Prueba
- admin / admin123 (rol admin)
- cliente / cliente123 (rol cliente)

# 🍽️ Restaurante PHP App 

## 📋 Descripción
Aplicación web para la gestión de un restaurante desarrollada en PHP con MySQL. Permite el registro de usuarios con diferentes roles (admin/cliente), gestión completa del menú y funcionalidades de favoritos y carrito de compras.

## ✨ Funcionalidades Implementadas

### 📌 Requisitos Obligatorios
✅ Registro de Usuario (admin/cliente)
✅ Inicio de Sesión con validación de roles
✅ Agregar Menú (Solo admin)
✅ Modificar Menú (Solo admin)
✅ Eliminar Menú (Solo admin) - Con eliminación en cascada
✅ Ver lista de menú en la Homepage con ordenamiento

### 🌟 Requisitos Opcionales (Solo clientes)
✅ Agregar/Eliminar Menú de Favoritos
✅ Ordenar Menú por precio y alfabéticamente
✅ Agregar/Eliminar Menú del Carrito de Compra
✅ Ver Carrito de Compras (sin finalización de compra)
⏳ Realizar compra con envío de email (Próximamente)
⏳ Ver historial de compras (Próximamente)

## 🚀 Instalación y Configuración
1. Clonar el repositorio 
2. Configurar la base de datos
    Abrir phpMyAdmin o tu cliente MySQL preferido
    Importar el archivo database.sql
    Verificar que se haya creado la base de datos restaurante
3. Configurar conexión
    \nEditar el archivo config.php si es necesario:
    \nphp$host = 'localhost';
    \n$db   = 'restaurante';
    \n$user = 'root';
    \n$pass = '';
4. Ejecutar la aplicación

Copiar el proyecto a la carpeta htdocs de XAMPP
Iniciar Apache y MySQL en XAMPP
Abrir en el navegador: http://localhost/ObligatorioPHP

## 👤 Usuarios de Prueba
\nAdministrador

Usuario: admin
\nContraseña: admin
\nPermisos: Gestión completa del menú

Cliente

Usuario: cliente
\nContraseña: cliente
\nPermisos: Favoritos, carrito, visualización del menú

## 🗃️ Estructura de la Base de Datos

Tabla usuarios

id (Primary Key)
\nusername (Unique)
\npassword (Hashed)
\nrole (admin/cliente)

Tabla menu

id (Primary Key)
\nnombre
\ndescripcion
\nprecio

Tabla favoritos

user_id (Foreign Key → usuarios.id)
\nmenu_id (Foreign Key → menu.id)

Tabla compras (Para implementación futura)

id (Primary Key)
\nuser_id (Foreign Key → usuarios.id)
\ndetalle
\nfecha

## 🔐 Características de Seguridad

Autenticación con password hashing
\nAutorización basada en roles
\nValidación de datos de entrada
\nRedirección automática según permisos
\nSesiones seguras para manejo de estado

## 🎯 Funcionalidades por Rol

👔 Administrador

✅ Agregar items al menú
\n✅ Editar items existentes
\n✅ Eliminar items (con limpieza automática)
\n✅ Ver lista completa del menú
\n❌ No puede usar favoritos ni carrito

👤 Cliente

✅ Ver menú con ordenamiento
\n✅ Agregar/quitar favoritos
\n✅ Gestionar carrito de compras
\n✅ Ver estadísticas de favoritos
\n❌ No puede modificar el menú

🌐 Usuario no autenticado

✅ Ver menú público
\n✅ Registrarse
\n✅ Iniciar sesión
\n❌ Sin acceso a funcionalidades interactivas

## 🔄 Próximas Funcionalidades

 Sistema de compras con envío de email
 \nHistorial de compras para clientes
 \nDashboard de estadísticas para admins
 \nGestión de categorías de menú
 \nSistema de calificaciones de platos

## 📄 Licencia
Este proyecto fue desarrollado como parte del curso de Desarrollo de Aplicaciones Web con PHP.
