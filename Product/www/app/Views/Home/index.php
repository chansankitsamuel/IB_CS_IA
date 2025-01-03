<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
    </head>
    <body>
        
        <h1>Welcome</h1>
        <p>Your Google Account does not belong to XXXX school</p>
        <p> <?= auth()->user()->last_name ?> </p>

    </body>
</html>