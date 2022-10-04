<?php

use Erp360\Core\Helpers\DatabaseAdapter;

require_once __DIR__ . '/../vendor/autoload.php';

class Migrate extends DatabaseAdapter{

    private $up = true;
    private $database = null;
    private $migrationFolder = null;

    public function __construct()
    {

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();

        parent::__construct();
        $this->database = $this->conn;

        $this->migrationFolder = __DIR__ . '/Migrations/' ;

        $this->checkOptions();
        $this->prepareMigrations();
    }

    private function checkOptions()
    {
        $mode = getopt('d:');

        if(isset($mode['d'])){
            $this->up = false;
        }

    }

    public function prepareMigrations()
    {
        // create migraiton table if not exist
        $this->createMigrationTable();

        $migrationFiles = $this->getListOfMigrationFiles();
        
        if(empty($migrationFiles)){
            $this->log("Nothing to migrate!");
            exit;
        }

        // create new instance of migration class
        foreach ($migrationFiles as $file) {
            require $file['filepath'];
            $class = new \ReflectionClass($file['filename']);
            $instance = $class->newInstance();

            $executeSql = null;

            if($this->up){
                $executeSql = trim($instance->up());
            }else{
                $executeSql = trim($instance->down());
            }

            if(strlen($executeSql) > 0){
                
                $this->log("starting migration of " . $file['filename']);
                
                $this->database->query('SET foreign_key_checks = 0');
                $statement = $this->database->prepare($executeSql);
                $statement->execute();
                $statement->closeCursor();
                $this->database->query('SET foreign_key_checks = 1');

                if($this->up){
                    $this->insertIntoMigrationHistory($file['filename']);
                }else{
                    $this->dropFromMigrationHistory($file['filename']);
                }

                $this->log("migration ok for " . $file['filename']);

            }

        }


    }

    private function createMigrationTable(): void
    {
        $statement = $this->database->prepare("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(190) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        $statement->execute();
        $statement->closeCursor();
    }

    private function getListOfMigrationFiles(): array
    {
        $files = scandir($this->migrationFolder);

        $realFiles = array_filter($files, function($file) {
            if($file != '.' && $file != '..' && $file != 'migrate.php'){
                return $file;
            }
        });

        $realFiles = array_values($realFiles);

        $migrationToExecute = [];

        foreach ($realFiles as $file) {

            $info = pathinfo($file);
            $statement = $this->database->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
            $statement->execute([$info['filename']]);

            $push = false;

            if($statement->fetchColumn() == 0){

                if($this->up){
                    $push = true;
                }

            }else{

                if($this->up == false){
                    $push = true;
                }

            }

            if($push){
                $migrationToExecute[] = [
                    'filename' => $info['filename'],
                    'filepath' => $this->migrationFolder . $file,
                ];
            }

            $statement->closeCursor();
            
        }

        return $migrationToExecute;

    }

    private function insertIntoMigrationHistory($migration): void
    {
        $stmt = $this->database->prepare("INSERT INTO migrations (migration) VALUES (?)");
        $stmt->execute([$migration]);
        $stmt->closeCursor();
    }

    private function dropFromMigrationHistory($migration): void
    {
        $stmt = $this->database->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$migration]);
        $stmt->closeCursor();
    }

    private function log(string $msg): void
    {
        echo date('Y-m-d H:i:s') . " $msg" . PHP_EOL;
    }


}

$migrate = new Migrate();