CREATE DATABASE transport_accounts;

USE transport_accounts;

CREATE TABLE private_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    dob DATE,
    id_number VARCHAR(255),
    country VARCHAR(255),
    location VARCHAR(255),
    mode_of_transport VARCHAR(255),
    phone_number VARCHAR(255),
    car_model VARCHAR(255),
    reg_number VARCHAR(255),
    car_color VARCHAR(255),
    services_provided TEXT,
    cost_charges DECIMAL(10, 2)
);

CREATE TABLE corporate_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    corporate_name VARCHAR(255),
    car_reg_number VARCHAR(255),
    car_model VARCHAR(255),
    car_color VARCHAR(255),
    corporate_phone VARCHAR(255),
    email VARCHAR(255),
    country VARCHAR(255),
    location VARCHAR(255),
    services_provided TEXT,
    cost_charges DECIMAL(10, 2)
);
