-- DROP DATABASE IF EXISTS dbpublicacion;
-- CREATE DATABASE DBpublicacion;
USE defaultdb;

-- 1. Nueva tabla de usuarios (Añadida)
CREATE TABLE usuario(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Almacena contraseñas con hash (ej. bcrypt)
    rol ENUM('Administrador', 'Personal', 'Lector') NOT NULL DEFAULT 'Lector',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabla lector (Modificada con fk_usuario opcional)
CREATE TABLE lector(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    cod_usuario INT NULL, -- Permite que un lector tenga o no una cuenta de usuario
    
    FOREIGN KEY(cod_usuario) REFERENCES usuario(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- tabla autor
CREATE TABLE autor(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    biografia TEXT
) ENGINE=InnoDB;

-- tabla libro / publicacion
CREATE TABLE libro(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    isbn VARCHAR(20) UNIQUE,
    titulo VARCHAR(150) NOT NULL,
    sinopsis TEXT,
    estado_publicacion ENUM('En Redacción', 'En Revisión', 'Publicado', 'Archivado') NOT NULL DEFAULT 'En Redacción',
    fecha_publicacion DATE
) ENGINE=InnoDB;

-- tabla relacional autor_libro
CREATE TABLE autor_libro(
    cod_libro INT NOT NULL,
    cod_autor INT NOT NULL,
    tipo_participacion VARCHAR(50) DEFAULT 'Autor Principal',

    PRIMARY KEY(cod_libro, cod_autor),
    FOREIGN KEY(cod_libro) REFERENCES libro(id) ON DELETE CASCADE,
    FOREIGN KEY(cod_autor) REFERENCES autor(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- tabla lectura_libro
CREATE TABLE lectura_libro(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_lector INT NOT NULL,
    cod_libro INT NOT NULL,
    fecha_inicio DATE NOT NULL DEFAULT (CURRENT_DATE),

    FOREIGN KEY(cod_lector) REFERENCES lector(id) ON DELETE CASCADE,
    FOREIGN KEY(cod_libro) REFERENCES libro(id) ON DELETE CASCADE
) ENGINE=InnoDB;
