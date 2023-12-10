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
            <input type="text" name="longitude" size="40" pattern="^([-+]?)((([0-9]|[1-9][0-9]|1[0-7][0-9]|180)(\.\d{1,6})?)|180)$" required>
        </label>
        <br>
        <label for="latitude">
            Latitude:<br>
            <input type="text" name="latitude" size="40" pattern="^([-+]?)([0-9]|[1-8][0-9]|90)(\.\d{1,6})?$" required>
        </label>
    <input type="submit" value="Get weather data" name ="submit"/>
</form>

<?php if (isset($data)) { ?>

    <h2>Weather Data</h2>

    <table>
        <thead>
        <tr>
            <th id="cityName">City Name</th>
            <th id="minTemp">Min Temperature (°C)</th>
            <th id="maxTemp">Max Temperature (°C)</th>
            <th id="distance">Distance to Spot (km)</th>
            <th id="weatherDescription">Weather Description</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $cityData): ?>
            <tr>
                <td><?= $cityData['city_name'] ?></td>
                <td><?= $cityData['temp_min'] ?></td>
                <td><?= $cityData['temp_max'] ?></td>
                <td><?= $cityData['distance_to_spot'] ?></td>
                <td><?= $cityData['weather_description'] ?> <img class="weather-icon" src="https://openweathermap.org/img/w/<?= $cityData['icon'] ?>.png" alt="Weather Icon"> </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php }?>

<?php if (isset($error)) { ?>
    <p><?php echo $error; ?></p>
<?php }?>

<div id="footer">
    All rights are reserved.
</div>


</body>
</html>


