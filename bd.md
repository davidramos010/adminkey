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