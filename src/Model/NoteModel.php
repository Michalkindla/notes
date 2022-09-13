<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\StorageException;
use PDO;
use Throwable;
use App\Exception\NotFoundException;

class NoteModel extends AbstractModel implements ModelInterface
{
   
    public function searchCount(string $phrase):int
    {
        try{
            $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
            $query = "SELECT count(*) AS cn FROM notes WHERE title LIKE($phrase)" ;
            $result = $this->conn->query($query);
            $result = $result->fetch(PDO::FETCH_ASSOC);
            if($result === false){
                throw new StorageException('Błąd przy próbie pobrania ilości notatek',400);
            }
            return (int)$result['cn'];
        }catch(Throwable $e){
            throw new StorageException('nie udało się pobrać informacji o liczbie notatek',400, $e);
        }   
    }

    public function search(
        string $phrase,
        int $pageNumber, 
        int $pageSize, 
        string $sortBy, 
        string $sortOrder): array
        {
            return $this->findBy($phrase, $pageNumber, $pageSize, $sortBy, $sortOrder);
        }

    public function list(
    int $pageNumber, 
    int $pageSize, 
    string $sortBy, 
    string $sortOrder):array
    {
        return $this->findBy(null, $pageNumber, $pageSize, $sortBy, $sortOrder);
      
    }

    public function count(): int
    {
        try{
            $query = "SELECT count(*) AS cn FROM notes" ;
            $result = $this->conn->query($query);
            $result = $result->fetch(PDO::FETCH_ASSOC);
            if($result === false){
                throw new StorageException('Błąd przy próbie pobrania ilości notatek',400);
            }

            return (int)$result['cn'];
        }catch(Throwable $e){
            throw new StorageException('nie udało się pobrać informacji o liczbie notatek',400, $e);
        }

      
    }
    public function edit(int $id, array $data): void
    {
        try{
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);

            $query = "
            UPDATE notes
            SET title = $title, description = $description
            WHERE id = $id
            ";
            $this->conn->exec($query);     //zaktualizowanie db

        }catch(Throwable $e){ 
            throw new StorageException('Nie udało się zaktualizować notatki', 400);
        }
    }

    public function get(int $id):array
    {
        try{
            
            $query = "SELECT * FROM notes WHERE id = $id";
            $result = $this->conn->query($query);
            $note = $result->fetch(PDO::FETCH_ASSOC);
           
        } catch(Throwable $e)
        {
            throw new StorageException('Nie udało się pobrać notatki', 400, $e);
        }
        if(!$note){
            throw new NotFoundException("Notatka o id: $id nie istnieje");

        }

        return $note;
    }
    public function create(array $data):void
    {   
        try{
           
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $created = $this->conn->quote(date('Y-m-d H:i:s'));
        
            $query = "INSERT INTO notes(title, description, created) VALUES($title,$description, $created)";

            $this->conn->exec($query);    //mtoda wrzucenia notatki do db


        }
        catch(Throwable $e){
            throw new StorageException('nie udalo sie utworzyc nowej notatki',400,previous: $e);


        };
       
    }
    public function delete(int $id):void
    {
        try{
            $query ="DELETE FROM notes WHERE id = $id LIMIT 1
            ";
            $this->conn->exec($query);
        }catch(Throwable $e){
            throw new StorageException('Nie udało się usunąć notatki', 400, $e);
        }
    }

    private function findBy(?string $phrase,
    int $pageNumber, 
    int $pageSize, 
    string $by, 
    string $sortOrder):array
    {
        try{
            $limit = $pageSize;
            $offset = ($pageNumber -1) * $pageSize;
            if(!in_array($by, ['created', 'title'])){
            $sortBy = 'title';
            }
            if(!in_array($sortOrder, ['asc', 'desc'])){
            $sortBy = 'desc';
            }
        
            $wherePart = '';

            if($phrase){
                $phrase = $this->conn->quote('%' . $phrase . '%', PDO::PARAM_STR);
                $wherePart = "WHERE title LIKE ($phrase)";
            }
            $query ="SELECT id, title, created 
            FROM notes 
            $wherePart
            ORDER BY $by $sortOrder 
            LIMIT $offset, $limit";

            $result = $this->conn->query($query);
            return $notes = $result->fetchAll(PDO::FETCH_ASSOC);
        }catch(Throwable $e){
            throw new StorageException('nie udało się pobrać notatek',400, $e);
        }
    }
} 