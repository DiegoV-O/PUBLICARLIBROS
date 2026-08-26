USE defaultdb;

-- 1. CLIENTES (Se crea correctamente)
CREATE TABLE CLIENTES(
 id int not null PRIMARY KEY auto_increment,
 ci VARCHAR(20) not null,
 nombre VARCHAR(50) not NULL,
 apellidos varchar(50) not null,
 direccion varchar(250),
 telefono VARCHAR(15)
)ENGINE=InnoDB;

-- 2. EMPLEADOS (Se crea correctamente)
CREATE TABLE EMPLEADOS(
 id int not null PRIMARY key auto_increment,
 ci VARCHAR(20) not null,
 nombre VARCHAR(50) not null,
 apellidos varchar(50) not NULL
)ENGINE=InnoDB;

-- 3. USUARIOS (Corregido: `password_hash` escapado)
CREATE TABLE USUARIOS(
 id int not null PRIMARY KEY auto_increment,
 username varchar(50) not null UNIQUE,
 `password_hash` varchar(255) not null, -- Escapado con comillas invertidas
 estado boolean default true,
 cod_empleado int not null,
 FOREIGN KEY(cod_empleado) REFERENCES EMPLEADOS(id)
)ENGINE=InnoDB;

-- 4. PRODUCTOS (Corregido: NOW() sin paréntesis obligatorios)
CREATE TABLE PRODUCTOS(
 id int not null PRIMARY KEY auto_increment,
 codBarras varchar(100) not null,
 descripcion varchar(100) not NULL,
 stock INT not NULL CHECK(stock>=0),
 precio_unitario DECIMAL(10,2) not null,
 creado_por int, 
 fecha_registro datetime default CURRENT_TIMESTAMP, -- Sintaxis limpia compatible
 FOREIGN KEY(creado_por) REFERENCES USUARIOS(id)
)ENGINE=InnoDB;

-- 5. PEDIDOS 
CREATE TABLE PEDIDOS(
 id int not null PRIMARY key auto_increment,
 cod_cliente int not null,
 fecha_compra datetime not null,
 cantidad int not null,
 cod_empleado int not null,
 creado_por int, 
 FOREIGN KEY(cod_cliente) REFERENCES CLIENTES(id),
 FOREIGN KEY(cod_empleado) REFERENCES EMPLEADOS(id),
 FOREIGN KEY(creado_por) REFERENCES USUARIOS(id)
)ENGINE=InnoDB;

-- 6. PEDIDO_PRODUCTOS (Corregido: DEFAULT limpio)
CREATE TABLE PEDIDO_PRODUCTOS(
 Id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
 cod_producto int not null,
 cod_pedido int not null,
 cantidad int not null,
 precio_unitario DECIMAL(10,2) not null,
 descuento DECIMAL(10,2) DEFAULT 0.0, -- Sin paréntesis redundantes
 FOREIGN KEY(cod_producto) REFERENCES PRODUCTOS(id),
 FOREIGN KEY(cod_pedido) REFERENCES PEDIDOS(id)
)ENGINE=InnoDB;

-- 7. EMPLEADO_PEDIDOS (Corregido: Removido el DEFAULT(NOW()) que rompía MySQL)
CREATE TABLE EMPLEADO_PEDIDOS(
 cod_pedido int not null,
 cod_empleado int not null,
 fecha date not null, -- Se asignará la fecha manualmente en los INSERTS
 PRIMARY KEY(cod_pedido,cod_empleado),
 FOREIGN KEY(cod_pedido) REFERENCES PEDIDOS(id),
 FOREIGN KEY(cod_empleado) REFERENCES EMPLEADOS(id)
)ENGINE=InnoDB;
