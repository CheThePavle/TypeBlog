<?php
    class db {
        private $link;

        public function __construct()
        {
            $this->connect();
        }

        public function connect()
        {
            $driver = 'mysql';
            $host = 'localhost';
            $charset = 'utf8';
            $dbName = 'native_php';
            $userName = 'root';
            $userPassword = '';

            try {
                $this->link = new PDO("$driver:host=$host;dbname=$dbName;charset=$charset", $userName, $userPassword);
            } catch (PDOException $e) {
                die("Ошибка подключения к базе данных - " . $e->getMessage());
            }
        }

        public function query($sql, $params = [])
        {
            $sth = $this->link->prepare($sql);
            $sth->execute($params);

            $res = $sth->fetchAll(PDO::FETCH_ASSOC);

            if (!$res) return [];
            return $res;
        }

        public function make($sql, $params = [])
        {
            $sth = $this->link->prepare($sql);
            return $sth->execute($params);
        }
    }

    $db = new db();
