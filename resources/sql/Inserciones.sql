USE powerletters;

INSERT INTO tb_generos (nombre)
VALUES ('Ficción'), 
('Ciencia Ficción'),
('Fantasía'), 
('Misterio'), 
('Terror'), 
('Biografía'), 
('Historia'), 
('Novela'), 
('Poesía'), 
('Infantil'), 
('Juvenil'), 
('Ciencia'), 
('Tecnología'), 
('Economía'), 
('Negocios'), 
('Salud'), 
('Cocina'), 
('Arte'), 
('Deportes'), 
('Musica'), 
('Cine'), 
('Teatro'), 
('Música'), 
('Tecnología'), 
('Videojuegos'), 
('Juegos de mesa'), 
('Colección');

SELECT*FROM tb_usuarios;

INSERT INTO tb_clasificaciones (nombre, descripcion)
VALUES ('Best Seller', 'Libros más vendidos y populares del momento'),
('Clásico', 'Obras literarias reconocidas por su relevancia histórica y cultural'),
('Romántico', 'Historias de amor y relaciones sentimentales'),
('Suspenso', 'Narrativas que mantienen al lector en tensión y expectativa'),
('Aventura', 'Relatos emocionantes llenos de acción y riesgo'),
('Histórico', 'Novelas basadas en eventos históricos o ambientadas en épocas pasadas'),
('Fantástico', 'Mundos imaginarios, seres mágicos y elementos sobrenaturales'),
('Autoayuda', 'Libros que buscan el desarrollo personal y el bienestar emocional'),
('Ciencia Ficción', 'Exploración de futuros posibles, tecnología avanzada y realidades alternativas'),
('Policiaco', 'Tramas de crimen, detectives y misterios por resolver'),
('Humor', 'Obras que buscan entretener y provocar risas'),
('Drama', 'Narrativas intensas y emotivas que exploran conflictos humanos'),
('Fantasía Épica', 'Mundos épicos, batallas y magia en historias de gran escala'),
('Thriller Psicológico', 'Suspense centrado en la mente y emociones de los personajes'),
('Novela Negra', 'Historias de crimen, intriga y corrupción'),
('Ciencia', 'Divulgación científica y exploración de conceptos científicos'),
('Terror', 'Narrativas que provocan miedo y tensión en el lector'),
('Romance', 'Relatos de amor y relaciones sentimentales'),
('Biografía', 'Historias reales de la vida de personas destacadas'),
('Viajes', 'Relatos de aventuras y descubrimientos en diferentes lugares del mundo'),
('Ciencia y Tecnología', 'Libros que abordan temas científicos y avances tecnológicos'),
('Novela Gráfica', 'Narrativas ilustradas que combinan texto e imágenes de forma creativa'),
('Autores Clásicos', 'Obras de escritores reconocidos por su influencia en la literatura'),
('Crecimiento Personal', 'Libros enfocados en el desarrollo personal y profesional'),
('Arte y Diseño', 'Exploración de expresiones artísticas y conceptos de diseño'),
('Cocina y Gastronomía', 'Recetas, técnicas culinarias y exploración de la gastronomía'),
('Historia Mundial', 'Relatos históricos que abarcan diferentes épocas y culturas'),
('Religión y Espiritualidad', 'Textos sobre creencias religiosas y prácticas espirituales'),
('Ciencias Sociales', 'Estudios sobre la sociedad, la política y las relaciones humanas'),
('Literatura Infantil', 'Cuentos y libros para niños que fomentan la imaginación y el aprendizaje');

SELECT*FROM tb_clasificaciones;

INSERT INTO tb_autores (nombre, biografia)
VALUES ('Gabriel García Márquez', 'Escritor colombiano, premio Nobel de Literatura conocido por "Cien años de soledad"'),
('Jane Austen', 'Escritora británica del siglo XIX, autora de "Orgullo y Prejuicio"'),
('Haruki Murakami', 'Escritor japonés contemporáneo, autor de "Tokio Blues"'),
('Agatha Christie', 'Escritora británica de novelas policíacas, creadora de Hercule Poirot'),
('J.K. Rowling', 'Escritora británica creadora de la saga de Harry Potter'),
('Mario Vargas Llosa', 'Escritor peruano, premio Nobel de Literatura por su obra narrativa'),
('Virginia Woolf', 'Escritora británica modernista, autora de "La señora Dalloway"'),
('Albert Camus', 'Escritor francés y filósofo existencialista, autor de "El extranjero"'),
('Emily Dickinson', 'Poetisa estadounidense del siglo XIX conocida por su estilo único'),
('Gabriela Mistral', 'Poetisa chilena y premio Nobel de Literatura por su obra poética'),
('Julio Cortázar', 'Escritor argentino conocido por su obra "Rayuela" y su estilo innovador'),
('Margaret Atwood', 'Escritora canadiense autora de "El cuento de la criada"'),
('Pablo Neruda', 'Poeta chileno y premio Nobel de Literatura por su poesía lírica'),
('George Orwell', 'Escritor británico conocido por "1984" y "Rebelión en la granja"'),
('Isabel Allende', 'Escritora chilena reconocida por novelas como "La casa de los espíritus"'),
('Fyodor Dostoevsky', 'Escritor ruso del siglo XIX, autor de "Crimen y castigo"'),
('Marguerite Duras', 'Escritora francesa conocida por "El amante" y su estilo intimista'),
('Ernest Hemingway', 'Escritor estadounidense ganador del premio Nobel, autor de "El viejo y el mar"'),
('Toni Morrison', 'Escritora afroamericana ganadora del premio Nobel por sus novelas sobre la experiencia negra en América'),
('Italo Calvino', 'Escritor italiano conocido por obras como "Las ciudades invisibles" y "El barón rampante"'),
('Sylvia Plath', 'Poetisa estadounidense y novelista, autora de "La campana de cristal"'),
('José Saramago', 'Escritor portugués ganador del premio Nobel, autor de "Ensayo sobre la ceguera"'),
('Clarice Lispector', 'Escritora brasileña conocida por su estilo introspectivo y experimental en obras como "La hora de la estrella"'),
('Octavio Paz', 'Poeta mexicano y premio Nobel de Literatura reconocido por su poesía vanguardista y ensayos literarios'),
('Hermann Hesse', 'Escritor alemán autor de obras como "El lobo estepario" y "Siddhartha"');

INSERT INTO tb_editoriales (nombre)
VALUES ('Penguin Random House'),
('HarperCollins'),
('Simon & Schuster'),
('Hachette Livre'),
('Macmillan Publishers'),
('Scholastic Corporation'),
('Pearson Education'),
('Oxford University Press'),
('Cambridge University Press'),
('Bloomsbury Publishing'),
('Penguin Books'),
('Vintage Books'),
('Pantheon Books'),
('Farrar, Straus and Giroux'),
('Knopf Doubleday Publishing Group'),
('Houghton Mifflin Harcourt'),
('Wiley Publishing'),
('Elsevier'),
('Springer Nature'),
('Taylor & Francis Group'),
('John Wiley & Sons'),
('McGraw-Hill Education'),
('Cengage Learning'),
('Pearson Longman'),
('Routledge');

SELECT*FROM administrador;

INSERT INTO tb_libros (titulo, id_autor, precio, descripcion, imagen, id_clasificacion, id_editorial, existencias, id_genero)
VALUES 
  ('Cien años de soledad', 1, 15.99, 'Una saga familiar en Macondo', 'imagen1.jpg', 1, 1, , 1),
  ('Orgullo y Prejuicio', 2, 12.99, 'Amor y prejuicios en la Inglaterra del siglo XIX', 'imagen2.jpg', 2, 2,30, 2),
  ('Tokio Blues', 3, 14.50, 'Juventud y melancolía en Tokio', 'imagen3.jpg', 3, 3, 40, 3),
  ('Asesinato en el Orient Express', 4, 11.75, 'Hercule Poirot resuelve un misterio a bordo del tren', 'imagen4.jpg', 4, 4, 25, 4),
  ('Harry Potter y la piedra filosofal', 5, 18.25, 'El inicio de las aventuras de Harry Potter en Hogwarts', 'imagen5.jpg', 5, 5, 60, 5),
  ('La ciudad y los perros', 6, 13.99, 'Vida y violencia en un colegio militar peruano', 'imagen6.jpg', 6,6 ,35 ,6),
  ('Mrs. Dalloway',7 ,16.50 ,'Un día en la vida de Clarissa Dalloway en Londres','imagen7.jpg' ,7 ,7,45 ,7),
  ('El extranjero' ,8 ,14.75 ,'La indiferencia y absurdo de la existencia','imagen8.jpg' ,8 ,8,20 ,8),
  ('Poemas' ,9 ,9.99 ,'Colección de poemas de Emily Dickinson','imagen9.jpg' ,9 ,9,50 ,9),
  ('Desolación' ,10 ,12.25 ,'Poesía íntima y emotiva de Gabriela Mistral','imagen10.jpg' ,10 ,10,30 ,10),
  ('Rayuela' ,11 ,17.99 ,'Novela experimental y desestructurada','imagen11.jpg' ,11 ,11,40 ,11),
  ('El cuento de la criada' ,12 ,14.50 ,'Distopía feminista en Gilead','imagen12.jpg' ,12 ,12,55 ,12),
  ('Veinte poemas de amor y una canción' ,13 ,11.75 ,'Poesía apasionada de Pablo Neruda','imagen13.jpg' ,13,13 ,25 ,13),
  ('1984' ,14 ,16.99 ,'Distopía totalitaria y vigilancia extrema','imagen14.jpg' ,14 ,14,60 ,14),
  ('La casa de los espíritus' ,15 ,18.25 ,'Saga familiar y realismo mágico en Chile','imagen15.jpg' ,15 ,15,40 ,15),
  ('Crimen y castigo',16,13.99,'Crimen moral y castigo psicológico en San Petersburgo','imagen16.jpg',16,16,30,16),
  ('El amante',17,15.50,'Relato autobiográfico de amor y pasión en Saigón','imagen17.jpg',17,17,35,17),
  ('El viejo y el mar',18,12.75,'Lucha contra la naturaleza y la vejez en el mar Caribe','imagen18.jpg',18,18,20,18),
  ('Beloved',19,14.99,'Exploración del legado del esclavismo en América','imagen19.jpg',19,19,45,19),
  ('Las ciudades invisibles',20,17.25,'Diálogos entre Marco Polo y Kublai Khan sobre ciudades imaginarias','imagen20.jpg',20,20,55,20),
  ('La campana de cristal',21,13.50,'Novela semi-autobiográfica sobre depresión y feminidad','imagen21.jpg',21,21,30,21),
  ('Ensayo sobre la ceguera',22,16.75,'Distopía sobre una epidemia de ceguera repentina','imagen22.jpg',22,22,40,22),
  ('La hora de la estrella',23,11.99,'Historia de Macabéa en Río de Janeiro','imagen23.jpg',23,23,25,23),
  ('Siddhartha',24,15.50,'Búsqueda espiritual y despertar interior en la India','imagen24.jpg',24,24,35,24),
  ('Demian',25,14.75,'Desarrollo personal y dualidad interna del protagonista Emil Sinclair','imagen25.jpg',25,25,30,25);
  
INSERT INTO tb_libros (titulo, id_autor, precio, descripcion, imagen, id_clasificacion, id_editorial, existencias, id_genero)
VALUES 
  ('Moby Dick', 1, 16.00, 'La obsesión de un capitán por una ballena blanca', 'imagen26.jpg', 1, 1, 15, 1),
  ('El gran Gatsby', 2, 14.50, 'La búsqueda del sueño americano en los años 20', 'imagen27.jpg', 2, 2, 20, 1),
  ('Cumbres borrascosas', 3, 12.50, 'Amor y venganza en los páramos ingleses', 'imagen28.jpg', 3, 3, 25, 2),
  ('El diario de una pasión', 4, 13.99, 'Una historia de amor que desafía el tiempo', 'imagen29.jpg', 4, 4, 30, 2),
  ('Dune', 5, 19.99, 'Una épica historia de poder y supervivencia en un planeta desértico', 'imagen30.jpg', 5, 5, 15, 3),
  ('Neuromante', 6, 17.50, 'Un thriller cibernético que define el género', 'imagen31.jpg', 6, 6, 20, 3),
  ('El silencio de los corderos', 7, 16.50, 'Un thriller psicológico que sigue a un asesino en serie', 'imagen32.jpg', 7, 7, 15, 4),
  ('La chica del tren', 8, 14.25, 'Un misterio que se desarrolla desde la perspectiva de varias mujeres', 'imagen33.jpg', 8, 8, 30, 4),
  ('El hobbit', 9, 16.00, 'La aventura de Bilbo Bolsón en la Tierra Media', 'imagen34.jpg', 9, 9, 25, 5),
  ('Juego de tronos', 10, 22.00, 'Intrigas y luchas por el trono en un mundo medieval', 'imagen35.jpg', 10, 10, 30, 5);
  SELECT*FROM tb_libros;
  
-- Insertar datos en la tabla tb_pedidos
INSERT INTO tb_pedidos (id_usuario, direccion_pedido, estado, fecha_pedido) VALUES
    (1, 'Calle Principal 123', 'FINALIZADO', '2024-07-01'),
    (2, 'Avenida Central 456', 'FINALIZADO', '2024-07-02'),
    (3, 'Plaza Mayor 789', 'FINALIZADO', '2024-07-03'),
    (4, 'Calle Secundaria 456', 'FINALIZADO', '2024-07-04'),
    (5, 'Avenida Principal 789', 'FINALIZADO', '2024-07-05'),
    (1, 'Calle Principal 123', 'FINALIZADO', '2024-07-06'),
    (2, 'Avenida Central 456', 'FINALIZADO', '2024-07-07'),
    (3, 'Plaza Mayor 789', 'FINALIZADO', '2024-07-08'),
    (4, 'Calle Secundaria 456', 'FINALIZADO', '2024-07-09'),
    (5, 'Avenida Principal 789', 'FINALIZADO', '2024-07-10'),
    (1, 'Calle Principal 123', 'FINALIZADO', '2024-07-11'),
    (2, 'Avenida Central 456', 'FINALIZADO', '2024-07-12'),
    (3, 'Plaza Mayor 789', 'FINALIZADO', '2024-07-13'),
    (4, 'Calle Secundaria 456', 'FINALIZADO', '2024-07-14'),
    (5, 'Avenida Principal 789', 'FINALIZADO', '2024-07-15'),
    (1, 'Calle Principal 123', 'FINALIZADO', '2024-07-16'),
    (2, 'Avenida Central 456', 'FINALIZADO', '2024-07-17'),
    (3, 'Plaza Mayor 789', 'FINALIZADO', '2024-07-18'),
    (4, 'Calle Secundaria 456', 'FINALIZADO', '2024-07-19'),
    (5, 'Avenida Principal 789', 'FINALIZADO', '2024-07-20');

-- Insertar datos en la tabla tb_detalle_pedidos
INSERT INTO tb_detalle_pedidos (id_libro, cantidad, id_pedido, precio) VALUES
    (1, 2, 1, 19.99),
    (2, 1, 2, 24.99),
    (3, 1, 3, 14.99),
    (4, 3, 4, 29.99),
    (5, 2, 5, 12.99),
    (1, 1, 6, 19.99),
    (2, 2, 7, 24.99),
    (3, 1, 8, 14.99),
    (4, 1, 9, 29.99),
    (5, 3, 10, 12.99),
    (1, 2, 11, 19.99),
    (2, 1, 12, 24.99),
    (3, 2, 13, 14.99),
    (4, 1, 14, 29.99),
    (5, 1, 15, 12.99),
    (1, 1, 16, 19.99),
    (2, 2, 17, 24.99),
    (3, 1, 18, 14.99),
    (4, 3, 19, 29.99),
    (5, 2, 20, 12.99);



-- Insertar datos en la tabla tb_comentarios
INSERT INTO tb_comentarios (comentario, calificacion, estado_comentario, id_detalle) VALUES 
    ('Me encanto', 4, 'ACTIVO', 1),
    ('Muy bueno', 4, 'ACTIVO', 2),
    ('Un poco alto el precio pero estuvo bien', 3, 'ACTIVO', 3),
    ('Lo adore', 5, 'ACTIVO', 4),
    ('No me gusto', 2, 'ACTIVO', 5);
    
    
SELECT*FROM tb_pedidos;
SELECT*FROM tb_detalle_pedidos;
SELECT*FROM tb_comentarios;
