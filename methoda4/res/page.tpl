<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>Mail sender</title>
    <link href=" {$cssDir} " rel="stylesheet">
    <script defer src=" {$script} "></script>

</head>

<body>

    <form method="post">
        
        <p class="header">Receiver mail:</p>
        <input id="mail" class="text" type="text" name="mail" value="{$mail}">

        <p class="header">Theme:</p>
        <input id="theme" class="text" type="text" name="theme" value="{$theme}">

        <p class="header">Message:</p>
        <textarea id="message" name="message" oninput="{$changeTaFunc}">{$message}</textarea>

        <div>
            <input class="submit" type="submit" value="Send">
        </div>

    </form>

    <script> {$alertScript} </script>

</body>

</html>