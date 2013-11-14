<?php 

namespace Tlapnet\Konfigurator;

class Plugin {
  
  public function init() {
    require_once TLAPNET_KONFIGURATOR_DIR . '/classes/Exception.php';
    
    add_action("init", array($this, "AddCss"));
    add_shortcode('tlapnet-konfigurator', array($this, 'Run_Configurator'));
    add_action( 'widgets_init', array($this, 'widgets_init'));
    add_action('wp_head', array($this, 'wp_head'));
    
    if (is_admin()) {
      add_action("admin_menu", array($this, 'AddOptionPage'));
    }
  }
  
  function widgets_init() {
    include_once TLAPNET_KONFIGURATOR_DIR . '/classes/widget/Simple.php';
    register_widget( '\Tlapnet\Konfigurator\Widget\Simple' );
  }
  
  function wp_head() {
    echo '<base href="' . $_SERVER["REQUEST_URI"] . '" />';
  }
  
  function wp_mail_content_type() {
    return 'text/html';
  }
  
  function AddOptionPage () {
    add_options_page("Tlapnet Konfigurátor - nastavení", "Tlapnet Konfigurátor", 'manage_options', "tlapnet-konfigurator-nastaveni", array($this, "Show_Settings_Page"));
  }
  
  function Show_Settings_Page() {
    if(isset($_REQUEST['submit'])) {  
      //Form data sent  
      $orderEmail = isset($_REQUEST['tlapnet_konfigurator_order_email']) ? $_REQUEST['tlapnet_konfigurator_order_email'] : '';
      update_option('tlapnet_konfigurator_order_email', $orderEmail);  
      $tlapnet_konfigurator_settings_updated = true;
    } else {
      $orderEmail = get_option('tlapnet_konfigurator_order_email');  
    }
    
    include TLAPNET_KONFIGURATOR_TEMPLATE_DIR . '/settings.php';
  }
  
  function AddCss() {
    wp_enqueue_style("ukazkovy-plugin-css", TLAPNET_KONFIGURATOR_PLUGIN_URL . "css/style.css");
  }
  
  function Run_Configurator($atts) {
    if (isset($_REQUEST['send'])) {
      $services = $this->getServices();
      $payments = $this->getPayments($services);
      $customer = $this->getCustomerInfo();
      
      ob_start();
      include TLAPNET_KONFIGURATOR_TEMPLATE_DIR . '/email.php';
      $emailTemplate = ob_get_clean();
      
      add_filter( 'wp_mail_content_type', array($this, 'wp_mail_content_type'));
      $emails = explode(',', get_option('tlapnet_konfigurator_order_email'));
      $emails = array_filter($emails); // deleting empty values
      if (empty($emails)) {
        $emails = array(get_option('admin_email'));
      }
      
      if (!wp_mail($emails, 'Objednávka', $emailTemplate)) {
        echo 'CHYBA PRI ODESILANI EMAILU';
        return $emailTemplate;
        // throw new Exception('Nepodařilo se odeslat email');
      }
      
      return $emailTemplate;
    }
    
    ob_start();
    include TLAPNET_KONFIGURATOR_TEMPLATE_DIR . '/tlapnet-konfigurator.php';
    $template = ob_get_clean();
    
    return $template;
  }
  
  private function getCustomerInfo() {
    $customerInfo = $_REQUEST['customer'];
    return $customerInfo;
  }
  
  private function getServices() {
    $selectedServices = $_REQUEST['services'];
    
    $servicesJSON = file_get_contents(TLAPNET_KONFIGURATOR_DIR . '/data/services.json');
    $services = json_decode($servicesJSON);

    foreach ($services as $service) {
      $service->selected = isset($selectedServices[$service->Id]);
      if ($service->selected) {
        $selectedService = $selectedServices[$service->Id];
        foreach ($service->Tariffs as $tariff) {
          $tariff->selected = isset($selectedService[$tariff->Id]);
          if ($tariff->selected) {
            $selectedTariff = $selectedService[$tariff->Id];
            foreach ($tariff->Packages as $package) {
              $package->selected = isset($selectedTariff[$package->Id]);
              if ($package->selected) {
                $selectedPackage = $selectedTariff[$package->Id];
                foreach ($package->Prices as $price) {
                  $price->selected = ($selectedPackage['price'] == $price->Type);
                }
                if (isset($package->Channels) && is_array($package->Channels) && isset($selectedPackage['channels'])) {
                  foreach ($package->Channels as $channel) {
                    $channel->selected = in_array($channel->Id, $selectedPackage['channels']);
                  }
                }
              }
            }
          }
        }
      }
    }
    return $services;
  }
  
  private function getPayments($services) {
    $monthlyPayments = 0;

    foreach ($services as $service) {
      if ($service->selected) {
        foreach ($service->Tariffs as $tariff) {
          if ($tariff->selected) {
            foreach ($tariff->Packages as $package) {
              if ($package->selected) {
                foreach ($package->Prices as $price) {
                  if ($price->selected) {
                    $monthlyPayments += $price->MonthlyPayment;
                  }
                }
                if (isset($package->Channels) && is_array($package->Channels)) {
                  foreach ($package->Channels as $channel) {
                    if ($channel->selected) {
                      $monthlyPayments += $channel->Price;
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
    
    return array(
      'monthlyPayments' => $monthlyPayments,
      'totalPayments' => $monthlyPayments,
      'subscription' => $this->getSubscription()
    );
  }
  
  private function getSubscription() {
    return isset($_REQUEST['subscription']) && $_REQUEST['subscription'];
  }
}