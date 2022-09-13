<?php
declare(strict_types=1);

namespace App\Model;

use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use PDO;
use PDOException;

abstract class AbstractModel{
    protected PDO $conn;

    public function __construct(array $config)
    {
        try{
            $this->validateConfig($config);
            $this->createConnection($config);
        } 
        catch(PDOException $e){
            throw new StorageException('Connection error');
        };
       
}
protected function validateConfig(array $config){
    if(
        empty($config['database'])
        || empty($config['host'])
        || empty($config['user'])
        || empty($config['password'])
    ){
        throw new ConfigurationException(('Storage configuration error'));
    }
}
protected function createConnection(array $config):void
{
    $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO(       // szukanie klasy w namespace globalnym -> \ można jeszcze zaimportowac 'use PDO;'
            $dsn,
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
    );       
}
}