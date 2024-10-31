<?php
/*
Plugin Name: EMI Calculator
Plugin URI: https://www.calculator.io/emi-calculator/
Description: A simple to use EMI Calculator widget.
Author: EMI Calculator
Author URI: https://www.calculator.io/emi-calculator/
Version: 1.4.0
*/
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
/**************************
global variables
***************************/
$my_plugin_name = "Rio EMI Calculator";
$rio_options = get_option('rio_settings');
/**************************
includes
***************************/
include('rio-plugin-options.php');

function rio_load_scripts() {
  if(is_singular()) {
    wp_enqueue_style('rio-styles', plugin_dir_url( __FILE__ ) . 'includes/css/styles.css');   
    wp_enqueue_style('rio-style2', plugin_dir_url( __FILE__ ) . 'includes/css/jquery-ui-min.css');
    wp_enqueue_style('rio-styles3', plugin_dir_url( __FILE__ ) . 'includes/css/icon-font.min.css');
    wp_register_style('rio-bootstrap', plugin_dir_url( __FILE__ ) . 'includes/css/bootstrap.min.css');
  }
}
add_action('wp_enqueue_scripts', 'rio_load_scripts');

function rio_admin_style() {
  $current_screen = get_current_screen();
  if ( strpos($current_screen->base, 'rio-emi-calculator-options') == true ) {
    wp_enqueue_style('rio-admin-style', plugin_dir_url( __FILE__ ) . 'includes/css/admin-style.css');
  }
}
add_action('admin_enqueue_scripts', 'rio_admin_style');

function rio_footer_scripts(){
  if(is_singular()) {
    wp_enqueue_script('jquery');
    wp_enqueue_script( 'rio-script1', plugins_url( 'includes/js/main.js', __FILE__ ));
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_script( 'rio-script3', plugins_url( 'includes/js/chart-loader.js', __FILE__ ));
    wp_enqueue_script( 'rio-script4', plugins_url( 'includes/js/bootstrap.min.js', __FILE__ ));
  }
}
function get_backup_link(){
    $help_l = substr(get_bloginfo('language'), 0, 2);
    $help_bl = get_option("lang_emi_calc_BackupLink");
    if (!$help_bl){
        $help_bls = ['es' => '/es/calculadora-de-emi/','fr' => '/fr/calculatrice-du-vme/','de' => '/de/emi-rechner/','pt' => '/pt/calculadora-emi/','it' => '/it/calcolatore-emi/','hi' => '/hi/emi-कैलकुलेटर/','id' => '/id/kalkulator-emi/','ar' => '/ar/حاسبة-الاقساط-الشهرية-المتساوية/','ru' => '/ru/калькулятор-эквивалентного-ежемесячного-платежа/','ja' => '/ja/emi計算機/','zh' => '/zh/emi-计算器/','pl' => '/pl/kalkulator-emi/','fa' => '/fa/محاسبه‌گر-emi/','nl' => '/nl/emi-calculator/','ko' => '/ko/emi-계산기/','th' => '/th/เครื่องคำนวณ-emi/','tr' => '/tr/emi-hesaplayıcısı/','vi' => '/vi/máy-tính-emi/'];
        $help_bl = 'https://www.calculator.io' . (isset($help_bls[$help_l]) ? $help_bls[$help_l] : '/emi-calculator/');
        update_option("lang_emi_calc_BackupLink", $help_bl);
    }
    return $help_bl;
}

function get_backup_text(){
    $help_bl = get_option("lang_emi_calc_BackupText");
    if (!$help_bl){
        $help_bls = ['EMI', 'Calculate EMI', 'Equated Monthly Installment', 'Equated Monthly Installment (EMI)'];
        $help_bl = $help_bls[array_rand($help_bls)];
        update_option("lang_emi_calc_BackupText", $help_bl);
    }
    return $help_bl;
}

add_action('wp_footer', 'rio_footer_scripts');

function rio_display_widget(){  // displays the front end widget
  global $rio_options;
  if(is_singular()) {
    ?>
    <div class="container emi-container-rio">
      <div class="row">
        <div class="col-md-6 rio-calculator-section">
          <div class="form-group clc-sec">
            <label for="">Loan Amount</label>
            <div class="inpt-clc">
              <span class="rio-icon">
                <?php if($rio_options['currency'] == 'INR - Indian Rupee') { ?>
                  <i class="fa fa-inr"></i>
                <?php } else if ($rio_options['currency'] == 'USD - US Dollar') { ?>
                  <i class="fa fa-dollar"></i>
                <?php } else if ($rio_options['currency'] == 'GBP - Pound Sterling') { ?>
                  <i class="fa fa-gbp"></i>
                <?php } else if ($rio_options['currency'] == 'EUR - Euro') { ?>
                  <i class="fa fa-euro"></i>
                <?php } else if ($rio_options['currency'] == 'JPY - Japanese Yen') { ?>
                  <i class="fa fa-yen"></i>
                <?php } else if ($rio_options['currency'] == 'RMB - Chinese Yuan') { ?>
                  <i class="fa fa-yen"></i>
                <?php } ?>
              </span>
              <input class="rio-element-disabled number" id="amount" type="text" name="" value="0" autocomplete="off">
            </div>
            <div class="slidecontainer">
              <div id="prncipleAmount"></div>
            </div>
          </div>
          <div class="form-group  clc-sec">
            <label for=""> Interest Rate</label>
            <div class="inpt-clc">
              <span class="rio-icon">
                <i class="fa fa-percent"></i>
              </span>
              <input class="rio-element-disabled number" id="inrate" type="text" name="" value="0" autocomplete="off">
            </div>
            <div class="slidecontainer">
              <div id="InterestRate"></div>
            </div>
          </div>
          <div class="form-group clc-sec">
            <label for=""> Tenure </label>
            <div class="inpt-clc">
              <input class="rio-element-disabled number" id="emiMonth" type="text" name="" value="0" autocomplete="off">
            </div>
          </div>
          <form id="radio-group">
            <div class="form-group">
              <input id="months" type="radio" name="tenure_radio" value="months" checked><label for="months">Months</label>
            </div>
            <div class="form-group">
              <input id="years" type="radio" name="tenure_radio" value="years"><label for="years">Years</label>
            </div>
          </form>
          <button type="button" id="rio-emi-calculate" class="btn btn-success">Calculate EMI</button>
        </div>
        <div class="col-md-6 text-left result-ri">
          <div class="rs-sc">
            <a href="<?php echo get_backup_link(); ?>" target="_blank"><?php echo get_backup_text(); ?></a>:
            <?php
            $currency = substr($rio_options['currency'], 0, 3);
            $currency_symbol = $rio_options['curr_display'];
            if($currency_symbol == 'symbol') {
             if($rio_options['currency'] == 'INR - Indian Rupee') { 
              $currency = "<i class='fa fa-inr'></i>";
            } else if($rio_options['currency'] == 'USD - US Dollar') {
              $currency = "<i class='fa fa-dollar'></i>";
            } else if($rio_options['currency'] == 'GBP - Pound Sterling') {
              $currency = "<i class='fa fa-gbp'></i>";
            } else if($rio_options['currency'] == 'EUR - Euro') {
              $currency = "<i class='fa fa-euro'></i>";
            } else if($rio_options['currency'] == 'JPY - Japanese Yen') {
              $currency = "<i class='fa fa-yen'></i>";
            } else if($rio_options['currency'] == 'RMB - Chinese Yuan') {
              $currency = "<i class='fa fa-yen'></i>";
            } ?>
            <h2><div id="result" align="center" class="emi-result"> <?php echo $currency; ?> 0 
            </div></h2>
          <?php } else { ?>
            <h2><div id="result" align="center" class="emi-result">0 <?php echo $currency; ?>
          </div></h2>
        <?php } ?>
        <input id="current_currency" type="hidden" name="" value="<?php echo $currency; ?>">
        <input id="current_symbol" type="hidden" name="" value="<?php echo $currency_symbol; ?>">
      </div>
      <div class="emi-total-interest rs-sc">
        Total Interest Payable:
        <?php if($currency_symbol == 'symbol') { ?>
          <h2><div id="total_interest" align="center" class="emi-result"> <?php echo $currency; ?> 0 
          </div></h2>
        <?php } else { ?>
         <h2><div id="total_interest" align="center" class="emi-result">0 <?php echo $currency; ?>
         </div></h2>  <?php } ?>
       </div>
       <div class="emi-downPayment rs-sc">
        Total of Payments (Principal + Interest):
        <?php if($currency_symbol == 'symbol') { ?>
          <h2><div id="downPayment" align="center" class="emi-result"><?php echo $currency; ?> 0 
          </div></h2>
        <?php } else { ?>
         <h2><div id="downPayment" align="center" class="emi-result">0 <?php echo $currency; ?>
         </div></h2> <?php } ?>
         <button type="button" id="charts" class="btn btn-secondary" autocomplete="off" style="display:none;">View Chart</button>
       </div>
     </div>
   </div>
 </div>
 <div id="chart-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false" style="display:none;">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="chart_wrap">
          <div id="piechart"></div>
        </div>
      </div>
    </div>
  </div>
</div> 
<?php
}
}
add_shortcode( 'rio-emi-calculator', 'rio_display_widget' );
?>
