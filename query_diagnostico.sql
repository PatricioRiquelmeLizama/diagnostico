CREATE DATABASE diagnostico;

CREATE TABLE bodegas (
    ID int NOT NULL AUTO_INCREMENT,
    bodega varchar(255) NOT NULL,
    CONSTRAINT PK_bodegas PRIMARY KEY (ID)
);

CREATE TABLE sucursales (
    ID_sucursal int NOT NULL AUTO_INCREMENT,
    ID int NOT NULL,
    sucursal varchar(255) NOT NULL,
    CONSTRAINT PK_sucursales PRIMARY KEY (ID_sucursal)
);

CREATE TABLE monedas (
    ID_moneda int NOT NULL AUTO_INCREMENT,
    moneda varchar(255) NOT NULL,
    CONSTRAINT PK_monedas PRIMARY KEY (ID_moneda)
);

CREATE TABLE productos (
    ID_producto int NOT NULL AUTO_INCREMENT,
    codigo varchar(255) NOT NULL,
    nombre varchar(255) NOT NULL,
    id_bodega INT NOT NULL,
    id_sucursal INT NOT NULL,
    id_moneda INT NOT NULL,
    precio VARCHAR(20) NOT null,
    tiene_plastico INT,
    tiene_metal INT,
    tiene_madera INT,
    tiene_vidrio INT,
    tiene_textil INT,
    descripcion varchar(255),
    CONSTRAINT PK_monedas PRIMARY KEY (ID_producto,codigo)
);

INSERT INTO bodegas (bodega)
VALUES ('Bodega 1');
INSERT INTO bodegas (bodega)
VALUES ('Bodega 2');
INSERT INTO bodegas (bodega)
VALUES ('Bodega 3');

INSERT INTO sucursales (ID, sucursal)
VALUES (1,'Sucursal 1');

INSERT INTO sucursales (ID, sucursal)
VALUES (2,'Sucursal 1');
INSERT INTO sucursales (ID, sucursal)
VALUES (2,'Sucursal 2');

INSERT INTO sucursales (ID, sucursal)
VALUES (3,'Sucursal 1');
INSERT INTO sucursales (ID, sucursal)
VALUES (3,'Sucursal 2');
INSERT INTO sucursales (ID, sucursal)
VALUES (3,'Sucursal 3');

INSERT INTO monedas (moneda)
VALUES ('DOLAR');
INSERT INTO monedas (moneda)
VALUES ('CLP');