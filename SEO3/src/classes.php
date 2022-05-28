<?php

    //Use pattern Decorator
    //getWeather -- return array with temperatures
    abstract class WeatherDecorator {

        private $weatherDecorator;

        public function __construct($weatherDecorator){    

            if ($weatherDecorator == null || ($weatherDecorator instanceof WeatherDecorator)){
            
                $this->weatherDecorator = $weatherDecorator;

            }else throw new Exception("Object is not decorator");
        
        }

        public function getWeather($city){

            $res = [];
            if (!$this->isNull())
                $res = $this->weatherDecorator->getWeather($city);

            return $res;

        }

        protected function isNull(){

            $res;
            if ($this->weatherDecorator === null){
             
                $res = true;
            
            }else {

                $res = false;

            }

            return $res;

        }

    }

    class OpenWeather extends WeatherDecorator {

        public function __construct($weatherDecorator){

            parent::__construct($weatherDecorator);

        }

        //Override
        public function getWeather($city){

            $res = [];
            if (!$this->isNull())
                $res = parent::getWeather($city);



            $tmpCity = str_replace(" ", "%20", $city);

            $curl = curl_init();
            curl_setopt_array($curl,[
                CURLOPT_URL => "https://community-open-weather-map.p.rapidapi.com/climate/month?q=".$tmpCity,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: community-open-weather-map.p.rapidapi.com",
                    "X-RapidAPI-Key: 965dafe08emsh5284cfab4b93d0cp1522aajsna8b52cbeb901"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err){

                $response = json_decode($response);
                $number = $response->list[0]->temp->average - 273;
                
                array_push($res, $number);

            }

            return $res;
        }

    }

    class YahooWeather extends WeatherDecorator {

        public function __construct($weatherDecorator){

            parent::__construct($weatherDecorator);

        }

        //Override
        public function getWeather($city){

            $res = [];
            if (!$this->isNull())
                $res = parent::getWeather($city);

            $tmpCity = str_replace(" ", "%20", $city);

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://yahoo-weather5.p.rapidapi.com/weather?location=".$tmpCity."&format=json&u=f",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: yahoo-weather5.p.rapidapi.com",
                    "X-RapidAPI-Key: 965dafe08emsh5284cfab4b93d0cp1522aajsna8b52cbeb901"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err){

                $response = json_decode($response);
                $number = ($response->forecasts[0]->high - 32) / 1.8;
                array_push($res, $number);
            }

            return $res;
        }

    }

    class ForecaWeather extends WeatherDecorator {

        public function __construct($weatherDecorator){
            
            parent::__construct($weatherDecorator);

        }

        //Override
        public function getWeather($city){

            $res = [];

            if (!$this->isNull())
                $res = parent::getWeather($city);

            $tmpCity = str_replace(" ", "%20", $city);

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://foreca-weather.p.rapidapi.com/location/search/".$tmpCity."?lang=en",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: foreca-weather.p.rapidapi.com",
                    "X-RapidAPI-Key: 965dafe08emsh5284cfab4b93d0cp1522aajsna8b52cbeb901"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err) {
               
                $response = json_decode($response);
                $tmt = $this->getTemperatureById($response->locations[0]->id);
                if ($tmt !== null)
                    array_push($res, $tmt);

            }
            
            return $res;
        }

        private function getTemperatureById($id){

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://foreca-weather.p.rapidapi.com/forecast/daily/".$id."?alt=0&tempunit=C&windunit=KTS&periods=8&dataset=full",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: foreca-weather.p.rapidapi.com",
                    "X-RapidAPI-Key: 965dafe08emsh5284cfab4b93d0cp1522aajsna8b52cbeb901"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $res;
            if (!$err){
                
                $response = json_decode($response);
                $res = $response->forecast[0]->maxTemp;
                
            }else
                $res = null;

            return $res;

        }

    }
    
    class AIWeather extends WeatherDecorator {

        public function __construct($weatherDecorator){

            parent::__construct($weatherDecorator);

        } 

        //Overload
        public function getWeather($city){

            $res = [];
            if (!$this->isNull())
                $res = parent::getWeather($city);

            $id = $this->getId($city);
            if ($id !== null){
                
                $temp = $this->getTemperature($id);
                if ($temp !== null)
                    array_push($res, $temp);
                
            }

            return $res;
        }

        private function getId($city){
            
            $curl = curl_init();

            $tmpCity = str_replace(" ", "%20", $city);
            
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://ai-weather-by-meteosource.p.rapidapi.com/find_places?text=".$tmpCity."&language=en",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: ai-weather-by-meteosource.p.rapidapi.com",
                    "X-RapidAPI-Key: 965dafe08emsh5284cfab4b93d0cp1522aajsna8b52cbeb901"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $res;

            if (!$err){
               
                $response = json_decode($response);
                if (count($response) > 0){
                    $res = $response[0];
                    $res = $res->place_id;
                    
                }else
                    $res = null;

            }else  
                $res = null;

            return $res;        

        }

        private function getTemperature($id){

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://ai-weather-by-meteosource.p.rapidapi.com/daily?place_id=".$id."&language=en&units=metric",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: ai-weather-by-meteosource.p.rapidapi.com",
                    "X-RapidAPI-Key: 965dafe08emsh5284cfab4b93d0cp1522aajsna8b52cbeb901"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            $res;
            if (!$err){
                
                $response = json_decode($response);
                $res = $response->daily->data[0]->temperature_max;

            }else
                $res = null;

            return $res;

        }

    }

    class NinjasWeather extends WeatherDecorator {

        public function __construct($weatherDecorator){

            parent::__construct($weatherDecorator);

        }

        //Override
        public function getWeather($city){

            $res =[];
            if ($this->isNull())
                $res = parent::getWeather($city);

            $tmpCity = str_replace(" ", "%20", $city);

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://weather-by-api-ninjas.p.rapidapi.com/v1/weather?city=".$tmpCity,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: weather-by-api-ninjas.p.rapidapi.com",
                    "X-RapidAPI-Key: 965dafe08emsh5284cfab4b93d0cp1522aajsna8b52cbeb901"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err){
                
                $response = json_decode($response);
                if (isset($response->max_temp)){
                
                    array_push($res, $response->max_temp);
                
                }

            }

            return $res;
        }

    }


    function getErrorPage($city){

        return "<p>Error! No data about ".$city."</p>";

    }

    function getSucessPage($city, $degrees){

        return "<p>Temperature in ".$city." is ".$degrees."</p>";

    }