-- CREATE DATABASE IF NOT EXISTS sufel
--   DEFAULT CHARACTER SET utf8
--   DEFAULT COLLATE utf8_general_ci;
--
-- USE sufel;

CREATE TABLE company
(
  ruc CHAR(11) PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  enable BIT NOT NULL
)ENGINE = INNODB;

CREATE TABLE document
(
  id INTEGER AUTO_INCREMENT NOT NULL,
  emisor CHAR(11) NOT NULL,
  tipo VARCHAR(3) NOT NULL,
  serie VARCHAR(5) NOT NULL,
  correlativo VARCHAR(10) NOT NULL,
  fecha DATE NOT NULL,
  total FLOAT(10, 2) NOT NULL,
  cliente_tipo VARCHAR(2) NOT NULL,
  cliente_doc VARCHAR(15) NOT NULL,
  cliente_nombre VARCHAR(100) NULL,
  last DATETIME NULL,
  filename VARCHAR(40) NOT NULL,
  baja BIT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (emisor) REFERENCES company(ruc)
)ENGINE = INNODB;

SET @@global.sql_mode= '';