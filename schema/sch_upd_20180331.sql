CREATE TABLE client
(
  tipo VARCHAR(2) NOT NULL,
  documento VARCHAR(15) NOT NULL,
  password VARCHAR(100) NOT NULL,
  created DATETIME DEFAULT CURRENT_TIMESTAMP,
  last_access DATETIME NOT NULL,
  emisor CHAR(11) NOT NULL,
  PRIMARY KEY (tipo, documento),
  FOREIGN KEY (emisor) REFERENCES company(ruc)
)ENGINE = INNODB;