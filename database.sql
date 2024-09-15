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
