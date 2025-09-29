<?php
function fetch_weather($selectedUserID = null)
{
    return;
    $userID = get_current_user_id();
    if ($selectedUserID) {
        $userID = $selectedUserID;
    }
    $city = get_field('user_city', 'user_' .  $userID);
    $weather_id = post_exists($city, '', '', 'weather');
    if ($weather_id) {
        $url = get_field('fetch_url', $weather_id);
        update_field('last_fetch', date('Y-m-d H:i:s'), $weather_id);
        $cityData = simplexml_load_file($url);
        $province = (string) $cityData->location->province;
        $time = (string) $cityData->dateTime->timeStamp[0];
        update_field('province', $province, $weather_id);
        update_field('last_update', date('Y-m-d H:i:s', strtotime($time)), $weather_id);
        $temparatureArr = $cityData->currentConditions->temperature;
        $temparatireAttr = $temparatureArr->attributes();
        $tempUnit = (string) $temparatireAttr->units;
        $temp = (string) $temparatureArr;
        update_field('temperature', $temp . " " . $tempUnit, $weather_id);

        $dewpointArr = $cityData->currentConditions->dewpoint;
        $dewpointAttr = $dewpointArr->attributes();
        $dewpointUnit = (string) $dewpointAttr->units;
        $dewpoint = (string) $dewpointArr;
        update_field('dewpoint', $dewpoint . " " . $dewpointUnit, $weather_id);

        $conditionArr = $cityData->currentConditions->condition;
        $condition = (string) $conditionArr;
        update_field('condition', $condition, $weather_id);


        $pressureArr = $cityData->currentConditions->pressure;
        $pressureAttr = $pressureArr->attributes();
        $pressureUnit = (string) $pressureAttr->units;
        $pressure = (string) $pressureArr;
        update_field('pressure', $pressure . " " . $pressureUnit, $weather_id);

        $humidityArr = $cityData->currentConditions->relativeHumidity;
        $humidityAttr = $humidityArr->attributes();
        $humidityUnit = (string) $humidityAttr->units;
        $humidity = (string) $humidityArr;
        update_field('humidity', $humidity . " " . $humidityUnit, $weather_id);

        $windArr = $cityData->currentConditions->wind;
        $speedObj = $windArr->speed;
        $speedAttr = $speedObj->attributes();
        $speedUnit = (string) $speedAttr->units;
        $speed = (string) $speedObj;

        $dirObj = $windArr->direction;
        $dir = (string) $dirObj;
        update_field('wind', $speed . " " . $speedUnit . " " . $dir, $weather_id);

        $forcastSummary = (string) $cityData->forecastGroup->forecast[0]->textSummary;
        update_field('forecast_summary', $forcastSummary, $weather_id);

        $cloudPrecip = (string) $cityData->forecastGroup->forecast[0]->cloudPrecip->textSummary;
        $saveCloud = 0;
        if (strpos(strtolower($cloudPrecip), "chance of shower") !== false) {
            $saveCloud = 1;
        } else if (strpos(strtolower($cloudPrecip), "rain") !== false) {
            $saveCloud = 1;
        }
        if ($saveCloud) {
            update_field('cloud_precipitation', $cloudPrecip, $weather_id);
        }
    }
}
add_action('wp_login', 'fetch_weather');
