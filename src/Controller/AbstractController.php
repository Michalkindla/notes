<?php

declare(strict_types=1);



namespace App\Controller;

use App\Request;
use App\View;
use App\model\NoteModel;
use App\Exception\ConfigurationException;
use App\Exception\StorageException;
use App\Exception\NotFoundException;



abstract class AbstractController
{
    protected const DEFAULT_ACTION = 'list';      
        // stała 
    private static array $configuration =[];
    
    protected NoteModel $noteModel;
    protected Request $request;
    protected View $view;

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function __construct(Request $request)
    {
        if(empty(self::$configuration['db'])){
            throw new ConfigurationException('Configuration error');
        }
        $this->noteModel = new NoteModel(self::$configuration['db']);

        $this->request = $request;
        $this->view = new View();
    }

    final public function run(): void
    {   
        try{

             // // ================logika wyswietlania widoku
        $action = $this->action() .'Action';

        if(!method_exists($this, $action))
        {
            $action = self::DEFAULT_ACTION. 'Action';
        }
        $this->$action();
        //string w zmiennej $action wywoluje metode, ktorej nazwa jest w stringu
    } catch(StorageException $e){
        $this->view->render(
            'error',
            ['message' => $e->getMessage()]
        );
    }catch(NotFoundException $e){
        $this->redirect('/', ['error' => 'noteNotFound']);
    }
    }
       
    final protected function redirect(string $to, array $params): void
    {
        $location = $to;

        if(count($params)){
        $queryParams = [];
        foreach($params as $key => $value)
        {
            $queryParams[] = urlencode($key) . '=' .urlencode($value);
        }
        $queryParams = implode('&', $queryParams);
        $location .= '?'. $queryParams;
    }
        
        header("Location: /php2/$location");
            exit;
    }
    private function action(): string
    {   //wydzielenie logiki obslugi zapytan w url
       
        return $this->request->getParam('action', self::DEFAULT_ACTION);  //stala w klasie - self-> nazwa klasy

    }
}