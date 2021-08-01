<?php


function connect(string $host, string $db, string $user, string $password): PDO
{
    try {
        $dsn = "pgsql:host=$host;port=5432;dbname=$db;";

        // make a database connection
        return new PDO(
            $dsn,
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

return connect("ec2-3-237-55-96.compute-1.amazonaws.com", "df7tmdmd3knte0", "nwcfbtkkaamhan", "0b644be7d843fbb19360fcf004c22ca4c4e49b805c623b42c55fabb6a643c691");
