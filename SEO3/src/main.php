<?php

    $page = file_get_contents("../resources/index.tpl");

    $page = str_replace('{$styles}', "../resources/styles.css", $page);
    $page = str_replace('{$scripts}', "", $page);

    
    $repl_str = "";
    $city_repl = "";
    if (reset($_POST) !== false){

        require_once "classes.php";

        $city = $_POST['city'];
        $city = trim($city);
        $city_repl = $city;

        //Create processor
        $weather = null;
        $weather = new OpenWeather($weather);
        $weather = new YahooWeather($weather);
        $weather = new ForecaWeather($weather);
        $weather = new AIWeather($weather);
        $weather = new NinjasWeather($weather);

        $temperatures = $weather->getWeather($city);
    
        $countElements = count($temperatures);
        $result = 0;
        $isHaveData = false;
        foreach ($temperatures as &$i)
            if ($i !== null){
             
                $result += $i;
                $isHaveData = true;

            }else{

                $countElements--;

            }

        if (!$isHaveData || $countElements == 0)
            $repl_str = getErrorPage($city);
        else
            $repl_str = getSucessPage($city, round($result / $countElements));

    }

    $page = str_replace('{$weather}', $repl_str, $page);
    $page = str_replace('{$cityValue}', $city_repl, $page);
    echo $page;
