<?php

$statement =
    'CREATE TABLE tasks( 
        id SERIAL,
        username  VARCHAR(100) NOT NULL,
        description VARCHAR(400) NULL, 
        email VARCHAR(50) NULL,
        is_complete boolean, 
        created_at timestamp default  now(),
        PRIMARY KEY(id)
    );';

try {
    gettype($pdo->exec("SELECT count(*) FROM tasks")) !== 'integer';
} catch (Exception $e) {
    $pdo->exec($statement);
}

