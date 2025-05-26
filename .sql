CREATE TABLE actividades (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    fecha DATETIME YEAR TO SECOND NOT NULL,
    situacion CHAR(1) DEFAULT '1'
);

CREATE TABLE asistencia(
    id SERIAL PRIMARY KEY,
    actividad_id INT NOT NULL,
    fecha DATETIME YEAR TO SECOND NOT NULL,
    situacion CHAR(1) DEFAULT '1',
    FOREIGN KEY (actividad_id) REFERENCES actividades(id)
);