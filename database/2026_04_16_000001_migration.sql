CREATE TABLE `user` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `movement` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `personal_record` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `movement_id` int NOT NULL,
    `value` FLOAT NOT NULL,
    `date` DATETIME NOT NULL,
    PRIMARY KEY (`id`)
);

ALTER TABLE `personal_record` ADD CONSTRAINT `personal_record_fk0` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `personal_record` ADD CONSTRAINT `personal_record_fk1` FOREIGN KEY (`movement_id`) REFERENCES `movement` (`id`);