<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">
    <title>Forecast</title>
    <link href=" {$styles} " rel="stylesheet">
    <script defer src=" {$scripts} "></script>

</head>

<body>

    <div id="container">

        <p class="header">City:</p>

        <form method="post">

            <input type="text" class="city" name="city" value=" {$cityValue} ">
            <input type="submit" class="submit" name="submit" value="Send">
        </form>

    </div>

    {$weather}


</body>



</html>