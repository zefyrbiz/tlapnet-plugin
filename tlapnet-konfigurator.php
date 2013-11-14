<?php

/*
  Plugin Name: Tlapnet konfigurátor
  Plugin URI:
  Description: Konfigurátor nabízených služeb a jejich tarifů, vložte do stránky shortcode <strong>[tlapnet-konfigurator]</strong> a v nastavení uložte <strong>emaily pro zasílání objednávek</strong>.
  Version: 1.0.0
  Author: Radek Žilka
  Author URI: http://www.radekzilka.cz/
  License: GPLv2 or later
 */

define('TLAPNET_KONFIGURATOR_DIR', dirname(__FILE__));
define('TLAPNET_KONFIGURATOR_TEMPLATE_DIR', TLAPNET_KONFIGURATOR_DIR . '/templates');
define('TLAPNET_KONFIGURATOR_PLUGIN_URL', plugin_dir_url(__FILE__));

$parsedUrl = parse_url(TLAPNET_KONFIGURATOR_PLUGIN_URL);
define('TLAPNET_KONFIGURATOR_PLUGIN_URL_PATH', $parsedUrl['path']);

add_action( 'plugins_loaded', 'Tlpanet_Konfigurator_Load_Plugin');

function Tlpanet_Konfigurator_Load_Plugin() {
  require_once TLAPNET_KONFIGURATOR_DIR . '/classes/Plugin.php';
  $Tlapnet_Konfigurator = new Tlapnet_Konfigurator_Plugin();
  $Tlapnet_Konfigurator->init();  
}

