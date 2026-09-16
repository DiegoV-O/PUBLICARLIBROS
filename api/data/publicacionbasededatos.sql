USE defaultdb;

-- 1. Tabla de usuarios (Eje central de autenticación)
CREATE TABLE IF NOT EXISTS usuarios(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, 
    rol ENUM('Administrador', 'Personal', 'Lector') NOT NULL DEFAULT 'Lector',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabla autor
CREATE TABLE IF NOT EXISTS autor(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    biografia TEXT
) ENGINE=InnoDB;

-- 3. Tabla libro / publicacion (Se conecta con usuarios para auditoría)
CREATE TABLE IF NOT EXISTS libro(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    isbn VARCHAR(20) UNIQUE,
    titulo VARCHAR(150) NOT NULL,
    sinopsis TEXT,
    estado_publicacion ENUM('En Redacción', 'En Revisión', 'Publicado', 'Archivado') NOT NULL DEFAULT 'En Redacción',
    fecha_publicacion DATE,
    cod_usuario_editor INT NULL, -- ¡NUEVA CONEXIÓN! Quién gestiona la publicación
    
    FOREIGN KEY(cod_usuario_editor) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Tabla lector (Conectada correctamente 1 a 1 opcional)
CREATE TABLE IF NOT EXISTS lector(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CI VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    cod_usuario INT NULL UNIQUE, -- Se añade UNIQUE para evitar que un usuario tenga múltiples lectores
    
    FOREIGN KEY(cod_usuario) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Tabla relacional autor_libro
CREATE TABLE IF NOT EXISTS autor_libro(
    cod_libro INT NOT NULL,
    cod_autor INT NOT NULL,
    tipo_participacion VARCHAR(50) DEFAULT 'Autor Principal',

    PRIMARY KEY(cod_libro, cod_autor),
    FOREIGN KEY(cod_libro) REFERENCES libro(id) ON DELETE CASCADE,
    FOREIGN KEY(cod_autor) REFERENCES autor(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Tabla lectura_libro (Mapea el consumo de los lectores)
CREATE TABLE IF NOT EXISTS lectura_libro(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cod_lector INT NOT NULL,
    cod_libro INT NOT NULL,
    fecha_inicio DATE NOT NULL DEFAULT (CURRENT_DATE),

    FOREIGN KEY(cod_lector) REFERENCES lector(id) ON DELETE CASCADE,
    FOREIGN KEY(cod_libro) REFERENCES libro(id) ON DELETE CASCADE
) ENGINE=InnoDB;
