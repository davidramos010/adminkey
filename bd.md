-- admin_key.`User` definition

CREATE TABLE `User` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`authKey` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`accessToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `User_UN` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO admin_key.`User`
(id, username, password, authKey, accessToken)
VALUES(1, 'david', '123456', '123456', '1234');


CREATE TABLE admin_key.comunidad (
id INT auto_increment NULL,
nombre varchar(255) NULL,
dirección varchar(255) NULL,
telefono1 varchar(100) NULL,
telefono2 varchar(100) NULL,
contacto varchar(100) NULL,
nomenclatura varchar(6) NULL,
CONSTRAINT comunidad_PK PRIMARY KEY (id),
CONSTRAINT comunidad_UN UNIQUE KEY (nomenclatura)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci;

-- admin_key.llave definition

CREATE TABLE `llave` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_comunidad` int(11) DEFAULT NULL,
`id_tipo` int(11) DEFAULT NULL,
`copia` int(11) DEFAULT NULL,
`codigo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
`descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`observacion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`activa` int(11) DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `llave_UN` (`codigo`),
KEY `llave_FK` (`id_comunidad`),
KEY `llave_FK_1` (`id_tipo`),
CONSTRAINT `llave_FK` FOREIGN KEY (`id_comunidad`) REFERENCES `comunidad` (`id`),
CONSTRAINT `llave_FK_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_llave` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- admin_key.registro definition

CREATE TABLE `registro` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`id_user` int(11) DEFAULT NULL,
`id_llave` int(11) DEFAULT NULL,
`entrada` datetime DEFAULT NULL,
`salida` datetime DEFAULT NULL,
`observacion` varchar(255) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `registro_FK` (`id_user`),
KEY `registro_FK_1` (`id_llave`),
CONSTRAINT `registro_FK` FOREIGN KEY (`id_user`) REFERENCES `User` (`id`),
CONSTRAINT `registro_FK_1` FOREIGN KEY (`id_llave`) REFERENCES `llave` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- admin_key.tipo_llave definition

CREATE TABLE `tipo_llave` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`codigo` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
`descripcion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `tipo_llave_UN` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;