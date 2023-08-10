<?php
/*
Plugin Name: Test Widget Météo
Description: Affiche la météo d'une ville.
Version: Bêta
Author: NN
*/

class CNAlpsWeather extends WP_Widget
{

// Main constructor
    public function __construct()
    {
        parent::__construct(
            'CNAlpsWeather',
            'Widget-Météo',
            array('description' => 'Affiche la météo d\'une ville.')
        );
    }

// The widget form (for the backend )
    public function form($instance)
    {

        $city = isset($instance['city']) ? esc_attr($instance['city']) : 'Crest';
        $country = isset($instance['country']) ? esc_attr($instance['country']) : 'France';
        $language = isset($instance['language']) ? esc_attr($instance['language']) : 'french';

        // Afficher le formulaire
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city'); ?>">Ville:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('city'); ?>"
                   name="<?php echo $this->get_field_name('city'); ?>" type="text" value="<?php echo $city; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('country'); ?>">Pays:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('country'); ?>"
                   name="<?php echo $this->get_field_name('country'); ?>" type="text" value="<?php echo $country; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('language'); ?>">Langue:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('language'); ?>"
                   name="<?php echo $this->get_field_name('language'); ?>" type="text" value="<?php echo $language; ?>">
        </p>
        <?php
    }

// Update widget settings
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['city'] = isset($new_instance['city']) ? wp_strip_all_tags($new_instance['city']) : '';
        $instance['country'] = isset($new_instance['country']) ? wp_strip_all_tags($new_instance['country']) : '';
        $instance['language'] = isset($new_instance['language']) ? wp_strip_all_tags($new_instance['language']) : '';
        return $instance;
    }


    // Display the widget
    public function widget($args, $instance)
    {
        extract($args);

    // Check the widget options
        $city = $instance['city'] ?? 'Crest';
        $country = $instance['country'] ?? 'France';
        $language = $instance['language'] ?? 'french';

//        $api_url = "https://www.weatherwp.com/api/common/publicWeatherForLocation.php?city=$city&country=$country&language=$language";
//
//        $response = wp_remote_get($api_url);
//
//        $data = json_decode(wp_remote_retrieve_body($response));
//
//        $temperature = $data->temp;
//        $icon_url = $data->icon;
//        $description = $data->description;
        ?>
        <div id="<?php echo $this->get_field_id('weather-info'); ?>"></div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let city = "<?php echo $city; ?>";
                let country = "<?php echo $country; ?>";
                let language = "<?php echo $language; ?>";
                let apiUrl = "https://www.weatherwp.com/api/common/publicWeatherForLocation.php?city=" + city + "&country=" + country + "&language=" + language;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        let weatherInfoDiv = document.getElementById("<?php echo $this->get_field_id('weather-info'); ?>");
                        let temperature = data.temp;
                        let iconUrl = data.icon;
                        let description = data.description;

                        let content = city + " - " + temperature + " °C - " + description + "<br><img src='" + iconUrl + "' alt='Weather Icon'>";
                        weatherInfoDiv.innerHTML = content;
                    })
                    .catch(error => {
                        console.error("Error fetching weather data:", error);
                    });
            });
        </script>
<!--        <div>-->
<!--            --><?//
//            echo "$city - $temperature °C - $description";
//            echo "<img src='$icon_url' alt='Weather Icon'>";
//            ?>
<!--        </div>-->
        <?php

        echo $args['after_widget'];
    }
}

function register_weather_widget()
{
    register_widget('CNAlpsWeather');
}

add_action('widgets_init', 'register_weather_widget');
?>