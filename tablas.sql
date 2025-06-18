create table carrera(
    id_carrera int(4),
    nombreCa varchar(30),
);

create table productoAca(
    id_pa int(4),
    Tipo varchar(12),
    Estatus varchar(20),
    titulo varchar(30),
    fecha_inicio Date,
    fecha_termino Date,
    DocumentoProvatorio varchar(50),
    urlConsulta varchar(50),
    borrado int(1),
    PRIMARY KEY (id_pa)
);

create table estatus(
    id_Estatus int(4),
    nombre varchar(50)
    descripcion varchar(200),
    borrado int(1)
);