-- Crear la base de datos
CREATE DATABASE programador_bd;

-- Usar la base de datos creada
USE programador_bd;

-- Crear la tabla tipos_instructores
CREATE TABLE tipos_instructores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    horas_maximas INT NOT NULL
);

-- Crear la tabla instructores
CREATE TABLE instructores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    tipo_id INT,
    FOREIGN KEY (tipo_id) REFERENCES tipos_instructores(id)
);

-- Crear la tabla competencias
CREATE TABLE competencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL
);

-- Crear la tabla resultados_aprendizaje
CREATE TABLE resultados_aprendizaje (
    id INT AUTO_INCREMENT PRIMARY KEY,
    competencia_id INT,
    descripcion VARCHAR(255) NOT NULL,
    FOREIGN KEY (competencia_id) REFERENCES competencias(id)
);

-- Crear la tabla programas_formacion
CREATE TABLE programas_formacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nivel_formacion VARCHAR(255) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    ambiente VARCHAR(255) NOT NULL,
    numero_ficha VARCHAR(255) NOT NULL
);

-- Crear la tabla horarios
CREATE TABLE horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT,
    fecha DATE,
    hora_inicio TIME,
    hora_fin TIME,
    competencia_id INT,
    resultado_id INT,
    programa_id INT,
    FOREIGN KEY (instructor_id) REFERENCES instructores(id),
    FOREIGN KEY (competencia_id) REFERENCES competencias(id),
    FOREIGN KEY (resultado_id) REFERENCES resultados_aprendizaje(id),
    FOREIGN KEY (programa_id) REFERENCES programas_formacion(id)
);

-- Crear la tabla horas_acumuladas
CREATE TABLE horas_acumuladas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT,
    mes INT,
    año INT,
    horas_acumuladas INT DEFAULT 0,
    FOREIGN KEY (instructor_id) REFERENCES instructores(id)
);
