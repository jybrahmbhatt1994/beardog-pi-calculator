<?php
/*
Plugin Name: Beardog PI Calculator
Description: A plugin to add a personal injury settlement calculator to your WordPress site. [beardog_pi_calculator]
Version: 1.0.0
Author: Jainish Brahmbhatt
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue CSS and JS
function beardog_pi_calculator_enqueue_scripts() {
    wp_enqueue_style('beardog-pi-calculator-style', plugin_dir_url(__FILE__) . 'css/beardog-pi-calculator.css');
    wp_enqueue_script('beardog-pi-calculator-script', plugin_dir_url(__FILE__) . 'js/beardog-pi-calculator.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'beardog_pi_calculator_enqueue_scripts');

// Shortcode to display the calculator form
function beardog_pi_calculator_shortcode() {
    ob_start();
    ?>
    <div class="form-container">
        <form id="settlementCalculator">
          <div class="form-ttl">
            <h2>Personal Injury Settlement Calculator</h2>
          </div>
          <div class="calc-row">
            <div class="row">
              <div class="col-lg-6 col-sm-12">
                <div class="form-left">
                  <label for="medicalPast"><strong>Past Medical Expenses:</strong></label>
                  <div class="lab-desc">
                    <p>Enter the total of your medical bills, even if you didn't pay out of pocket.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-right">
                  <input type="number" id="medicalPast" placeholder="$0.00">
                </div>
              </div>
            </div>
          </div>
          <div class="calc-row">
            <div class="row">
              <div class="col-lg-6 col-sm-12">
                <div class="form-left">
                  <label for="medicalFuture"><strong>Estimated Future Medical Expenses:</strong></label>
                  <div class="lab-desc">
                    <p>(If you will require ongoing medical treatment for your injuries, enter an estimate of the cost of that treatment.)</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-right">
                  <input type="number" id="medicalFuture" placeholder="$0.00">
                </div>
              </div>
            </div>
          </div>
          <div class="calc-row">
            <div class="row">
              <div class="col-lg-6 col-sm-12">
                <div class="form-left">
                  <label for="lostWagesPast"><strong>Past Lost Wages:</strong></label>
                  <div class="lab-desc">
                    <p>(If you missed work because of your injuries, input the sum of your lost income here. If you used available time-off benefits -- like PTO -- enter dollar value lost as if it were unpaid.)</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-right">
                  <input type="number" id="lostWagesPast" placeholder="$0.00">
                </div>
              </div>
            </div>
          </div>
          <div class="calc-row">
            <div class="row">
              <div class="col-lg-6 col-sm-12">
                <div class="form-left">
                  <label for="lostWagesFuture"><strong>Estimated Future Lost Wages:</strong></label>
                  <div class="lab-desc">
                    <p>(If you'll be missing more work due to ongoing treatment, or an inability to continue working at your current job while you recover, enter an estimate of those lose earnings here.)</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-right">
                  <input type="number" id="lostWagesFuture" placeholder="$0.00">
                </div>
              </div>
            </div>
          </div>
          <div class="calc-row">
            <div class="row">
              <div class="col-lg-6 col-sm-12">
                <div class="form-left">
                  <label for="propertyDamage"><strong>Property Damage Costs:</strong></label>
                  <div class="lab-desc">
                    <p>(This field is commonly used for automotive damage in a car accident case. You'll leave this at zero for most other types of injury claims.)</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-right">
                  <input type="number" id="propertyDamage" placeholder="$0.00">
                </div>
              </div>
            </div>
          </div>
          <div class="calc-row">
            <div class="row">
              <div class="col-lg-6 col-sm-12">
                <div class="form-left">
                  <label for="painSuffering"><strong>Pain and Suffering Multiplier:</strong></label>
                  <div class="lab-desc">
                    <p>The multiplier is used to estimate your general damages -- your "pain and suffering". The more serious, long-lasting, and painful the injuries, the higher the multiplier. Scroll down to the multiplier below the calculator for tips on choosing a reasonable multiplier.</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12">
                <div class="form-right">
                  <input type="range" id="painSuffering" min="1.5" max="5" step="0.5" value="2" oninput="updateMultiplierDisplay(this.value)">
                  <span id="multiplierDisplay">2</span>
                </div>
              </div>
            </div>
          </div>
          <div class="calc-row">
            <div class="row">
              <div class="col-lg-12 col-sm-12">
                <div class="text-center">
                  <button type="button" class="btn-effect btn btn-primary" onclick="calculateSettlement()">Calculate Settlement</button>
                </div>
              </div>
            </div>
          </div>
          <div class="calc-res">
            <div class="calculator-results-title"><strong>Settlement Value Estimates</strong></div>
            <div class="calc-row">
              <div class="row">
                <div class="col-lg-6 col-sm-12">
                  <div class="form-left">
                    <div id="economicDamages">Economic Damages:</div>
                    <div class="lab-desc">
                      <p>This is the sum of your "special" damages, or economic losses.</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                  <div class="form-right">
                    <input type="text" id="economicDamagesOutput" disabled value=""> 
                  </div>
                </div>
              </div>
            </div>
            <div class="calc-row">
              <div class="row">
                <div class="col-lg-6 col-sm-12">
                  <div class="form-left">
                    <div id="nonEconomicDamages">Non-Economic Damages:</div>
                    <div class="lab-desc">
                      <p>This is a payment for your general damages (pain and suffering), based on the multiplier you've chosen.</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                  <div class="form-right">
                    <input type="text" id="nonEconomicDamagesOutput" disabled value=""> 
                  </div>
                </div>
              </div>
            </div>
            <div class="calc-row">
              <div class="row align-items-center">
                <div class="col-lg-6 col-sm-12">
                  <div class="form-left">
                    <div id="settlementAmount">Estimated Settlement:</div>
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                  <div class="form-right">
                    <input type="text" id="settlementAmountOutput" disabled value=""> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('beardog_pi_calculator', 'beardog_pi_calculator_shortcode');
?>
