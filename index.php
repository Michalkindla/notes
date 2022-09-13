<?php

        declare(strict_types=1);

        spl_autoload_register(function(string $classNamespace){
                $path = str_replace(['\\', 'App/'], ['/',''], $classNamespace);
                $path = "src/$path.php";
                require_once($path);
        });
       
        require_once('src/utils/debug.php');
        $configuration = require_once('config/config.php');

        use App\Controller\AbstractController;
        use App\Controller\NoteController;
        use App\Request;
        use App\Exception\AppException;
        use App\Exception\ConfigurationException;



        $request = new Request($_GET, $_POST, $_SERVER);

        
       
        try{
        // Controller::initConfiguration($configuration);
        // (new Controller($request))->run();   //wywolanie metody run z klasy Controller

        AbstractController::initConfiguration($configuration);
        (new NoteController($request))->run();
       }
       catch(ConfigurationException $e){
        dump($e);
        echo '<h1 style="color:red;">wystąpił błąd w aplikacji</h1>';
        echo '<h2 style="color:#eee;">Wystąpił błąd konfiguracji</h2>';
        echo '<h3>Proszę skontaktować się z administratorem</h3>';
       }
       catch(AppException $e){
        echo '<h1 style="color:red;">wystąpił błąd w aplikacji</h1>';
        echo '<h3>' .$e->getMessage(). '</h3>';
       }
       catch(\Throwable $e){
        echo '<h1 style="color:red;">wystąpił błąd w aplikacji</h1>';
        dump($e);
       };

  

       
        
        

        

        

       
        


        







