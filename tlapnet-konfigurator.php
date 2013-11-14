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

define('TLAPNET_KONFIGURATOR_DIR', __DIR__);
define('TLAPNET_KONFIGURATOR_TEMPLATE_DIR', __DIR__.'/templates');
define('TLAPNET_KONFIGURATOR_PLUGIN_URL', plugin_dir_url(__FILE__));

$parsedUrl = parse_url(TLAPNET_KONFIGURATOR_PLUGIN_URL);
define('TLAPNET_KONFIGURATOR_PLUGIN_URL_PATH', $parsedUrl['path']);

add_action( 'plugins_loaded', 'Tlpanet_Konfigurator_Load_Plugin');

function Tlpanet_Konfigurator_Load_Plugin() {
  require_once __DIR__ . '/classes/Plugin.php';
  $Tlapnet_Konfigurator = new Tlapnet\Konfigurator\Plugin();
  $Tlapnet_Konfigurator->init();  
}

