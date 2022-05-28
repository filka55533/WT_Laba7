<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once '../libs/vendor/autoload.php';



    function isCorrectMail(){
        
        if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
            return false;
        
        return true;

    }


    function getPage(){
        
        $page = file_get_contents('../res/page.tpl');
       
        $page = str_replace('{$cssDir}', '../res/style.css', $page);
        $page = str_replace('{$script}', '../res/script.js', $page);    
        $page = str_replace('{$changeTaFunc}', 'processTextArea()', $page);

       
        $page = str_replace('{$mail}', isset($_POST['mail']) ? $_POST['mail'] : '', $page);
        $page = str_replace('{$theme}', isset($_POST['theme']) ? $_POST['theme'] : '', $page);
        $page = str_replace('{$message}', isset($_POST['message']) ? $_POST['message'] : '', $page);
       

        //Checks correct form
        if (reset($_POST) !== false){

            if (!isCorrectMail())
                return str_replace('{$alertScript}', 'alert(\'Error! Incorrect mail\');', $page);

            if (!str_replace("\r\n", '', $_POST['message']))
                return str_replace('{$alertScript}', 'alert(\'Error! Empty message\');', $page);

            $func = sendMail($_POST['mail'], $_POST['theme'], $_POST['message']);
            
            $page = str_replace('{$alertScript}', $func, $page);
        }


        return $page;

    }

    function sendMail($email, $theme, $message){

        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
    
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 0;
    
        $mail->Host = 'ssl://smtp.gmail.com';
        $mail->Port = 465;
        $mail->Username = 'ifilipovich.if@gmail.com';
        $mail->Password = '';
    
        $mail->setFrom($mail->Username, "Some name");
    
        $mail->addAddress($email);
    
        $mail->Subject = $theme;
    
        $mail->Body = $message;
    
        $res;
        if ($mail->send()){
            $res = 'alert(\'Success! Message sent\');';
        } else {
            $res = 'alert(\'Error! Message was not sent\');';
        }
    
        return $res;
    }


    
    echo getPage();