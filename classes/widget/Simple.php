<?php

namespace Tlapnet\Konfigurator\Widget;

class Simple extends \WP_Widget {

  public function __construct() {
    parent::__construct(false, 'Tlapnet Konfigurátor', 'description=Description Widget');
  }

  public function form($instance) {
    // outputs the options form on admin
  }

  public function update($new_instance, $old_instance) {
    // processes widget options to be saved
  }

  public function widget($args, $instance) {
    // outputs the content of the widget
    echo 'Tlapnet_Konfigurator_Widget';
  }
}