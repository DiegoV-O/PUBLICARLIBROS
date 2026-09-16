-- 1. Insertar Usuarios (Se crean los accesos de administradores, personal y lectores)
INSERT INTO usuarios (username, email, password, rol, activo) VALUES
('admin_carlos', 'carlos@biblioteca.com', '$2y$10$xyz123...', 'Administrador', 1),
('editor_ana', 'ana@biblioteca.com', '$2y$10$abc456...', 'Personal', 1),
('editor_luis', 'luis@biblioteca.com', '$2y$10$def789...', 'Personal', 1),
('lector_sofia', 'sofia.mendoza@email.com', '$2y$10$ghi012...', 'Lector', 1),
('lector_diego', 'diego.gomez@email.com', '$2y$10$jkl345...', 'Lector', 1),
('lector_elena', 'elena.ruiz@email.com', '$2y$10$mno678...', 'Lector', 1);

-- 2. Insertar Autores (Escritores externos que no necesariamente tienen usuario en el sistema)
INSERT INTO autor (CI, nombre, apellidos, biografia) VALUES
('1234567-LP', 'Gabriel', 'García Márquez', 'Escritor colombiano, premio Nobel de Literatura en 1982.'),
('7654321-SC', 'Isabel', 'Allende', 'Reconocida escritora chilena de realismo mágico y ficción histórica.'),
('9876543-CB', 'Jorge', 'Luis Borges', 'Destacado escritor, ensayista y poeta argentino del siglo XX.');

-- 3. Insertar Libros (Asociados al 'Personal' o 'Administrador' que los editó/gestionó)
INSERT INTO libro (isbn, titulo, sinopsis, estado_publicacion, fecha_publicacion, cod_usuario_editor) VALUES
('978-0307474728', 'Cien años de soledad', 'La historia de la familia Buendía en el pueblo ficticio de Macondo.', 'Publicado', '1967-05-30', 2), -- Editado por editor_ana (id: 2)
('978-1501116971', 'La casa de los espíritus', 'Cuatro generaciones de la familia Trueba en un período de cambios sociales.', 'Publicado', '1982-10-01', 2), -- Editado por editor_ana (id: 2)
('978-8420633114', 'El Aleph', 'Colección de cuentos que exploran laberintos, el infinito y la identidad.', 'En Revisión', NULL, 3),        -- Editado por editor_luis (id: 3)
('978-0307389732', 'Crónica de una muerte anunciada', 'Relato que reconstruye el asesinato programado de Santiago Nasar.', 'En Redacción', NULL, 3); -- Editado por editor_luis (id: 3)

-- 4. Insertar Lectores (Mapeados 1 a 1 de forma opcional con los usuarios con rol 'Lector')
INSERT INTO lector (CI, nombre, apellidos, email, cod_usuario) VALUES
('4567890-LP', 'Sofía', 'Mendoza', 'sofia.mendoza@email.com', 4), -- Vinculado a usuario lector_sofia (id: 4)
('5678901-SC', 'Diego', 'Gómez', 'diego.gomez@email.com', 5),     -- Vinculado a usuario lector_diego (id: 5)
('6789012-CB', 'Elena', 'Ruiz', 'elena.ruiz@email.com', 6),       -- Vinculado a usuario lector_elena (id: 6)
('7890123-OR', 'Andrés', 'Castro', 'andres.castro@email.com', NULL); -- Lector registrado físicamente sin usuario web

-- 5. Insertar Relación Autor-Libro (N a N)
INSERT INTO autor_libro (cod_libro, cod_autor, tipo_participacion) VALUES
(1, 1, 'Autor Principal'), -- Cien años de soledad por Gabriel García Márquez
(2, 2, 'Autor Principal'), -- La casa de los espíritus por Isabel Allende
(3, 3, 'Autor Principal'), -- El Aleph por Jorge Luis Borges
(4, 1, 'Autor Principal'); -- Crónica de una muerte anunciada por Gabriel García Márquez

-- 6. Insertar Lecturas de Libros (Historial de consumo de los lectores)
INSERT INTO lectura_libro (cod_lector, cod_libro, fecha_inicio) VALUES
(1, 1, '2026-01-15'), -- Sofía empezó a leer Cien años de soledad
(1, 2, '2026-02-10'), -- Sofía empezó a leer La casa de los espíritus
(2, 1, '2026-02-01'), -- Diego empezó a leer Cien años de soledad
(3, 2, '2026-03-01'); -- Elena empezó a leer La casa de los espíritus
