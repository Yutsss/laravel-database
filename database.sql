create database belajar_laravel_database;

use belajar_laravel_database

create table categories (
    id varchar(100) not null primary key,
    name varchar(100) not null,
    description text,
    created_at timestamp
) engine InnoDB;

create table counters (
    id varchar(100) not null primary key,
    value int not null default 0
) engine InnoDB;

delete from counters;

select * from counters;

CREATE TABLE products (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price INT NOT NULL,
    category_id VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

drop table products;

drop table categories;

drop table counters;
