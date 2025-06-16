create table carrera(
    id_carrera int(1),
    nombre varchar(30),
);

create table productoAca(
    id_Pa int(4),
    titulo varchar(30),
    fecha_inicio Date,
    fecha_termino Date,
    DocumentoProvatorio varchar(1),
    urlConsulta varchar(200),
    borrado int(1),
    id_Tipo int(4),
    id_Estatus int(4)
);

create table estatus(
    id_Estatus int(4),
    nombre varchar(50)
    descripcion varchar(200),
    borrado int(1)
);

create table tipo(
    id_Tipo int(4),
    nombre varchar(50)
    descripcion varchar(200),
    borrado int(1)
);