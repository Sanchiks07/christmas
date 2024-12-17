<?php

class Database {
    public $pdo;

    // Konstruktors - izpildās vienu reizi, kad objekts uztaisīts
    public function __construct($config) { // izpildās jau uzreiz, tpc nevajag connect
        // DSN - Data Source Name
        $dsn = "mysql:" . http_build_query($config, "", ";");
        // PDO - PHP Data Object (klase)
        $this->pdo = new PDO($dsn); // objekts = klase
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function query($sql) {
        // 1. Sagatavot vaicājumu (statement)
        $statement = $this->pdo->prepare($sql); // prepare("vaicājums") - metode
        // 2. Izpildīt statement
        $statement->execute(); // execute - metode
        return $statement;
    }
}

?>