CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `email` varchar(250) NOT NULL,
    `password` varchar(500) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username_unique` (`username`),
    UNIQUE KEY `email_unique` (`email`)
);

CREATE TABLE IF NOT EXISTS `conversions` (
    `id` int(11) AUTO_INCREMENT,
    `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `username` varchar(50) NOT NULL,
    `comment` varchar(500),
    `original_file_name` varchar(100),
    `result_file_name` varchar(100),
    `conversion_type` varchar(50),
    PRIMARY KEY(`id`)
);