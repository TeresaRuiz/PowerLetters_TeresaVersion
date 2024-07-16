USE powerletters;
            
            
            
            SELECT ELT(MONTH(fecha_registro), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre") AS mes, 
       COUNT(*) AS total
FROM tb_usuarios
GROUP BY mes
ORDER BY MIN(fecha_registro) ASC;

INSERT INTO tb_usuarios (nombre_usuario, apellido_usuario, dui_usuario, correo_usuario, telefono_usuario, direccion_usuario, nacimiento_usuario, clave_usuario, imagen, estado_cliente, fecha_registro)
VALUES
('Selena', 'Gómez', '12345678-9', 'selena.gomez@example.com', '12345678', '123 Calle Principal, Los Ángeles', '1992-07-22', 'password123', 'selena.jpg', 1, '2024-07-01'),
('Cristiano', 'Ronaldo', '98765432-1', 'cristiano.ronaldo@example.com', '87654321', '456 Avenida Secundaria, Madrid', '1985-02-05', 'password456', 'cristiano.jpg', 1, '2024-07-02'),
('Kylie', 'Jenner', '54321098-7', 'kylie.jenner@example.com', '45678912', '789 Calle Terciaria, Los Ángeles', '1997-08-10', 'password789', 'kylie.jpg', 1, '2024-07-03'),
('Lionel', 'Messi', '76543210-9', 'lionel.messi@example.com', '23456789', '159 Avenida Principal, Barcelona', '1987-06-24', 'passwordabc', 'messi.jpg', 1, '2024-07-04'),
('Ariana', 'Grande', '09876543-2', 'ariana.grande@example.com', '67890123', '357 Calle Secundaria, Boca Ratón', '1993-06-26', 'passworddef', 'ariana.jpg', 1, '2024-07-05'),
('Kim', 'Kardashian', '13579086-4', 'kim.kardashian@example.com', '24680135', '246 Avenida Principal, Los Ángeles', '1980-10-21', 'passwordghi', 'kim.jpg', 1, '2024-07-06'),
('Beyoncé', 'Knowles', '97531864-2', 'beyonce.knowles@example.com', '86420135', '864 Calle Terciaria, Houston', '1981-09-04', 'passwordjkl', 'beyonce.jpg', 1, '2024-07-07'),
('Justin', 'Bieber', '24680135-7', 'justin.bieber@example.com', '97531864', '864 Avenida Secundaria, Ontario', '1994-03-01', 'passwordmno', 'justin.jpg', 1, '2024-07-08'),
('Kendall', 'Jenner', '86420135-9', 'kendall.jenner@example.com', '24680135', '246 Calle Principal, Los Ángeles', '1995-11-03', 'passwordpqr', 'kendall.jpg', 1, '2024-07-09'),
('Nicki', 'Minaj', '97531864-0', 'nicki.minaj@example.com', '86420135', '864 Avenida Terciaria, Nueva York', '1982-12-08', 'passwordstu', 'nicki.jpg', 1, '2024-07-10'),
('Taylor', 'Swift', '12345678-0', 'taylor.swift@example.com', '12345678', '123 Main Street, Nashville', '1989-12-13', 'passwordvwx', 'taylor.jpg', 1, '2024-07-11'),
('Adele', 'Adkins', '98765432-0', 'adele.adkins@example.com', '87654321', '456 Oak Avenue, London', '1988-05-05', 'passwordyzq', 'adele.jpg', 1, '2024-07-12'),
('Ed', 'Sheeran', '54321098-0', 'ed.sheeran@example.com', '45678912', '789 Elm Street, Suffolk', '1991-02-17', 'passwordabc', 'ed.jpg', 1, '2024-07-13'),
('Bruno', 'Mars', '76543210-0', 'bruno.mars@example.com', '23456789', '159 Pine Road, Honolulu', '1985-10-08', 'passworddef', 'bruno.jpg', 1, '2024-07-14'),
('Rihanna', 'Fenty', '09876543-0', 'rihanna.fenty@example.com', '67890123', '357 Oak Lane, Barbados', '1988-02-20', 'passwordghi', 'rihanna.jpg', 1, '2024-07-15'),
('Selena', 'Gómez', '12345678-9', 'selena.gomez@example.com', '12345678', '123 Calle Principal, Los Ángeles', '1992-07-22', 'password123', 'selena.jpg', 1, '2024-01-01'),
('Cristiano', 'Ronaldo', '98765432-1', 'cristiano.ronaldo@example.com', '87654321', '456 Avenida Secundaria, Madrid', '1985-02-05', 'password456', 'cristiano.jpg', 1, '2024-01-02'),
('Kylie', 'Jenner', '54321098-7', 'kylie.jenner@example.com', '45678912', '789 Calle Terciaria, Los Ángeles', '1997-08-10', 'password789', 'kylie.jpg', 1, '2024-01-03'),
('Lionel', 'Messi', '76543210-9', 'lionel.messi@example.com', '23456789', '159 Avenida Principal, Barcelona', '1987-06-24', 'passwordabc', 'messi.jpg', 1, '2024-01-04'),
('Ariana', 'Grande', '09876543-2', 'ariana.grande@example.com', '67890123', '357 Calle Secundaria, Boca Ratón', '1993-06-26', 'passworddef', 'ariana.jpg', 1, '2024-01-05'),
('Kim', 'Kardashian', '13579086-4', 'kim.kardashian@example.com', '24680135', '246 Avenida Principal, Los Ángeles', '1980-10-21', 'passwordghi', 'kim.jpg', 1, '2024-01-06'),
('Beyoncé', 'Knowles', '97531864-2', 'beyonce.knowles@example.com', '86420135', '864 Calle Terciaria, Houston', '1981-09-04', 'passwordjkl', 'beyonce.jpg', 1, '2024-01-07'),
('Justin', 'Bieber', '24680135-7', 'justin.bieber@example.com', '97531864', '864 Avenida Secundaria, Ontario', '1994-03-01', 'passwordmno', 'justin.jpg', 1, '2024-01-08'),
('Kendall', 'Jenner', '86420135-9', 'kendall.jenner@example.com', '24680135', '246 Calle Principal, Los Ángeles', '1995-11-03', 'passwordpqr', 'kendall.jpg', 1, '2024-01-09'),
('Nicki', 'Minaj', '97531864-0', 'nicki.minaj@example.com', '86420135', '864 Avenida Terciaria, Nueva York', '1982-12-08', 'passwordstu', 'nicki.jpg', 1, '2024-01-10'),
('Taylor', 'Swift', '12345678-0', 'taylor.swift@example.com', '12345678', '123 Main Street, Nashville', '1989-12-13', 'passwordvwx', 'taylor.jpg', 1, '2024-01-11'),
('Adele', 'Adkins', '98765432-0', 'adele.adkins@example.com', '87654321', '456 Oak Avenue, London', '1988-05-05', 'passwordyzq', 'adele.jpg', 1, '2024-01-12'),
('Ed', 'Sheeran', '54321098-0', 'ed.sheeran@example.com', '45678912', '789 Elm Street, Suffolk', '1991-02-17', 'passwordabc', 'ed.jpg', 1, '2024-01-13'),
('Bruno', 'Mars', '76543210-0', 'bruno.mars@example.com', '23456789', '159 Pine Road, Honolulu', '1985-10-08', 'passworddef', 'bruno.jpg', 1, '2024-01-14'),
('Rihanna', 'Fenty', '09876543-0', 'rihanna.fenty@example.com', '67890123', '357 Oak Lane, Barbados', '1988-02-20', 'passwordghi', 'rihanna.jpg', 1, '2024-01-15'),
('Selena', 'Gómez', '12345678-9', 'selena.gomez@example.com', '12345678', '123 Calle Principal, Los Ángeles', '1992-07-22', 'password123', 'selena.jpg', 1, '2024-02-01'),
('Cristiano', 'Ronaldo', '98765432-1', 'cristiano.ronaldo@example.com', '87654321', '456 Avenida Secundaria, Madrid', '1985-02-05', 'password456', 'cristiano.jpg', 1, '2024-02-02'),
('Kylie', 'Jenner', '54321098-7', 'kylie.jenner@example.com', '45678912', '789 Calle Terciaria, Los Ángeles', '1997-08-10', 'password789', 'kylie.jpg', 1, '2024-02-03'),
('Lionel', 'Messi', '76543210-9', 'lionel.messi@example.com', '23456789', '159 Avenida Principal, Barcelona', '1987-06-24', 'passwordabc', 'messi.jpg', 1, '2024-02-04'),
('Ariana', 'Grande', '09876543-2', 'ariana.grande@example.com', '67890123', '357 Calle Secundaria, Boca Ratón', '1993-06-26', 'passworddef', 'ariana.jpg', 1, '2024-02-05'),
('Kim', 'Kardashian', '13579086-4', 'kim.kardashian@example.com', '24680135', '246 Avenida Principal, Los Ángeles', '1980-10-21', 'passwordghi', 'kim.jpg', 1, '2024-02-06'),
('Beyoncé', 'Knowles', '97531864-2', 'beyonce.knowles@example.com', '86420135', '864 Calle Terciaria, Houston', '1981-09-04', 'passwordjkl', 'beyonce.jpg', 1, '2024-02-07'),
('Justin', 'Bieber', '24680135-7', 'justin.bieber@example.com', '97531864', '864 Avenida Secundaria, Ontario', '1994-03-01', 'passwordmno', 'justin.jpg', 1, '2024-02-08'),
('Selena', 'Gómez', '12345678-9', 'selena.gomez@example.com', '12345678', '123 Calle Principal, Los Ángeles', '1992-07-22', 'password123', 'selena.jpg', 1, '2024-03-01'),
('Cristiano', 'Ronaldo', '98765432-1', 'cristiano.ronaldo@example.com', '87654321', '456 Avenida Secundaria, Madrid', '1985-02-05', 'password456', 'cristiano.jpg', 1, '2024-03-02'),
('Kylie', 'Jenner', '54321098-7', 'kylie.jenner@example.com', '45678912', '789 Calle Terciaria, Los Ángeles', '1997-08-10', 'password789', 'kylie.jpg', 1, '2024-03-03');