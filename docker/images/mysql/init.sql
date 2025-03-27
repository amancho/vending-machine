CREATE TABLE IF NOT EXISTS products (
    `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `price` FLOAT(4,2) NOT NULL,
    `quantity` TINYINT(2) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO products (`name`, `price`, `quantity`) VALUES ('Juice', 1.00, 10);
INSERT INTO products (`name`, `price`, `quantity`) VALUES ('Soda', 1.50, 10);
INSERT INTO products (`name`, `price`, `quantity`) VALUES ('Water', 0.65, 10);

CREATE TABLE IF NOT EXISTS coins (
    `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
    `value` FLOAT(4,2) NOT NULL,
    `status` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO coins (`value`, `status`) VALUES (0.05, 'stored');
INSERT INTO coins (`value`, `status`) VALUES (0.10, 'stored');
INSERT INTO coins (`value`, `status`) VALUES (0.25, 'stored');
INSERT INTO coins (`value`, `status`) VALUES (1.00, 'stored');
INSERT INTO coins (`value`, `status`) VALUES (1.00, 'stored');
