<?php
    class db{
        private $link;

        public function __construct(){
            $this->connect();
        }

        private function connect(){
            $driver = 'mysql';
            $host = 'localhost';
            $db_name = 'diplom';
            $db_user = 'root';
            $db_password = 'root';
            $charset = 'utf8';

            try{
                $this->link = new PDO("$driver:host=$host;dbname=$db_name;charset=$charset", $db_user, $db_password);
            }catch(PDOException $e){
                die('Не могу подключиться к БД!!!');
            }
        }

        //метод для запросов поиска
        public function query($sql, $params = []){
            $sth = $this->link->prepare($sql); //Подготавливает запрос к выполнению и возвращает связанный с этим запросом объект
            $sth->execute($params); //Запускает подготовленный запрос на выполнение
            $result = $sth->fetchAll(PDO::FETCH_ASSOC); //Возвращает массив, содержащий все строки результирующего набора

            if (!$result){return [];}
            return $result;
        }

        //метод для добавления, удаления, изменения данных, если успешно то возвратит 1
        public function make($sql, $params = []){
            $sth = $this->link->prepare($sql); //Подготавливает запрос к выполнению и возвращает связанный с этим запросом объект
            return $sth->execute($params); //Запускает подготовленный запрос на выполнение
        }

        //для Ajax ответа
        public function response($data){
            header('Content-Type: application/json');
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
$db = new db();
//    $res = $db->make("INSERT INTO `tag` (`name_tag`) VALUES (?);", ['test2']);
//    $db->response($res);

//sleep(1); //задержка выполениня запроса к БД

//if (stristr($_SERVER['REQUEST_URI'], '.php')) {header("Location: /");exit();}

//$test = new db();
//print_r($test->query("SELECT * FROM `user` WHERE id = ?", ['1']));
//print_r($test->make('INSERT INTO `test` (`id`, `text`) VALUES (NULL, ?)', ['asdasasasads']));
//print_r($test->make("UPDATE `test` SET `text` = ? WHERE `test`.`id` = 1", ['ban']));
//print_r($test->make("DELETE FROM `test` WHERE `test`.`id` = ?", ['6']));