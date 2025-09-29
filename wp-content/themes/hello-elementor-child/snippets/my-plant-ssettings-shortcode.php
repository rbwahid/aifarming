<?php
function my_plant_settings()
{
    $settings = get_field("settings", "option");
    ob_start();
?>
    <form class="my_plant_settings">
        <?php
        foreach ($settings as $item) {
            $currentData = get_field($item['slug'], get_the_ID());
        ?>
            <div class="form-item-wrap">
                <label><?php echo $item['name'] ?></label>
                <?php
                if ($item['type'] == 'radio') {
                ?>
                    <select name="<?php echo $item['slug'] ?>">
                        <option value="">- Select -</option>
                        <?php
                        foreach ($item['options'] as $option) {
                            $select = ($currentData == $option['short_title']) ? 'selected="selected"' : '';
                            echo '<option value="' . $option['short_title'] . '" ' . $select . '>' . $option['short_title'] . '</option>';
                        }
                        ?>
                    </select>
                <?php
                }
                if ($item['type'] == 'range') {
                ?>
                    <input type="number" name="<?php echo $item['slug'] ?>" min="<?php echo $item['range_option']['min'] ?>" max="<?php echo $item['range_option']['max'] ?>" value="<?php echo $currentData; ?>" /> <?php echo $item['range_option']['unit'] ?>
                <?php
                }
                ?>
            </div>
        <?php
        }
        ?>
        <input type="button" class="btn my_plant_settings_btn" name="submit" value="submit" />
    </form>
<?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode('my_plant_settings', 'my_plant_settings');
?>