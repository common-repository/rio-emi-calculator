<?php
function rio_options_page() {
  global $rio_options;
  ob_start(); ?>
  <div class="wrap">
    <b><h1><?php _e('Rio EMI Calculator Plugin','rio_domain'); ?></h1></b>
    <form action="options.php" method="post">
      <?php settings_fields('rio_settings_group'); ?> <!-- gives access to the fields -->
      <table class="rio-table">
        <tr>
          <td><h4>Shortcode</h4></td>
          <td>:</td>
          <td>[rio-emi-calculator] </td>
        </tr>
        <tr>
          <td><h4><b><?php _e('Select Currency','rio_domain'); ?></b></h4></td>
          <td>:</td>
          <td>


            <?php $currencies = array('INR - Indian Rupee','USD - US Dollar', 'GBP - Pound Sterling', 'EUR - Euro', 'JPY - Japanese Yen', 'RMB - Chinese Yuan'); ?>


            <select id="rio_settings[currency]" class="" name="rio_settings[currency]">
              <?php foreach($currencies as $currency) {
                var_dump($currencies);
                ?>
                <?php if($rio_options['currency'] == $currency) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
                <option value="<?php echo $currency; ?>" <?php echo $selected; ?>><?php echo $currency; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><h4><?php _e('Show currency as', 'rio_domain'); ?></h4></td>
          <td>:</td>
          <td>
            <input type="radio" name="rio_settings[curr_display]" value="text" <?php checked('text', $rio_options['curr_display'] ?? ''); ?> checked="checked" id="radio_text" /><label for="radio_text" style="padding-right: 10px;">Text</label>

            <input type="radio" name="rio_settings[curr_display]" value="symbol" <?php checked('symbol', $rio_options['curr_display'] ?? ''); ?> id="radio_symbol" /><label for="radio_symbol">Symbol</label>
          </td>
        </tr>
        <tr>
          <td><h2><?php _e('How To Use?','rio_domain'); ?></h2></td>
        </tr>
        <tr>
          <td><h4>Step 1</h4></td>
          <td>:</td>
          <td><p>Make sure you enabled the plugin.</p></td>
        </tr>
        <tr>
          <td><h4>Step 2</h4></td>
          <td>:</td>
          <td><p>To display the EMI calculator widget call shortcode [rio-emi-calculator] in any page/post content.</p></td>
        </tr>
        <tr>
          <td><h4>Step 3</h4></td>
          <td>:</td>
          <td><p>You can change the currency from the dropdown menu and it will change in the front-end accordingly.</p></td>
        </tr>
      </table>
      <p class="submit"><input type="submit" class="button-primary" name="" value="<?php _e('Save Options','rio_domain'); ?>"></p>
    </form>
  </div>
  <?php echo ob_get_clean();
}

function rio_add_options_link(){
  add_menu_page('Rio EMI Calculator', 'Rio EMI Calculator', 'manage_options','rio-emi-calculator-options','rio_options_page', 'dashicons-calculator');
}
add_action('admin_menu','rio_add_options_link');  //activate the function with a hook

function rio_register_settings(){
  register_setting('rio_settings_group','rio_settings'); //creates an option inside wordpress options table
}
add_action('admin_init','rio_register_settings');
?>
