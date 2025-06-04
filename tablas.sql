-- Tabla de usuarios
CREATE TABLE usuario (
    id_u INT(11) AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(65) NOT NULL,
    Edad INT(11),
    Nick VARCHAR(20) NOT NULL UNIQUE,
    Pwd VARCHAR(8) NOT NULL,
    id_p INT(11),
    Borrado CHAR(1) DEFAULT '0',
    FOREIGN KEY (id_p) REFERENCES perfil(id_p) ON DELETE SET NULL
);

-- Tabla de perfiles
CREATE TABLE perfil (
    id_p INT(11) AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(25) NOT NULL,
    Descripcion VARCHAR(70),
    Borrado CHAR(1) DEFAULT '0'
);

-- Tabla de módulos
CREATE TABLE modulo (
    id_mod INT(11) AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(40) NOT NULL,
    URL VARCHAR(70),
    Borrado CHAR(1) DEFAULT '0'
);

-- Tabla de relación entre módulos y perfiles
CREATE TABLE mod_perfil (
    id_mod INT(11),
    id_p INT(11),
    PRIMARY KEY (id_mod, id_p),
    FOREIGN KEY (id_mod) REFERENCES modulo(id_mod) ON DELETE CASCADE,
    FOREIGN KEY (id_p) REFERENCES perfil(id_p) ON DELETE CASCADE
);

-- Tabla de bitácora
CREATE TABLE bitacora (
    id_b INT(11) AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora DATETIME NOT NULL,
    accion VARCHAR(50) NOT NULL,
    id_u INT(11),
    FOREIGN KEY (id_u) REFERENCES usuario(id_u) ON DELETE SET NULL
);