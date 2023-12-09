<!DOCTYPE html>
<html lang="en">
<head>
    <title>Weather service</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<form action="#" method="post">

        <label for="longitude">
            Longitude:<br>
            <input type="text" name="longitude" size="40" pattern="^([-+]?)((([0-9]|[1-9][0-9]|1[0-7][0-9]|180)(\.\d{1,6})?)|180)$">
        </label>
        <br>
        <label for="latitude">
            Latitude:<br>
            <input type="text" name="latitude" size="40" pattern="^([-+]?)([0-9]|[1-8][0-9]|90)(\.\d{1,6})?$">
        </label>
    <input type="submit" value="Get weather data" name ="submit"/>
</form>

<?php if (isset($data)) { ?>
    <div class="">

    </div>
<?php }?>

<div id="footer">
    All rights are reserved.
</div>


</body>
</html>


