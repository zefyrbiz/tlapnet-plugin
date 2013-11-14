<?php if($tlapnet_konfigurator_settings_updated): ?>
  <div class="updated"><p><strong><?php _e('Uloženo.' ); ?></strong></p></div>  
<?php endif; ?>

<div class="wrap">  
  <?php echo "<h2>" . __( 'Tlapnet Konfigurátor', 'tlapnet_konfigurator' ) . "</h2>"; ?>  

  <form name="tlapnet_konfigurator_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
    <?php echo "<h4>" . __( 'Nastavení', 'tlapnet_konfigurator' ) . "</h4>"; ?>  
    <table class="form-table">
      <tr>
        <th><?php _e("E-mail pro objednávky: " ); ?></th>
        <td>
          <input type="text" name="tlapnet_konfigurator_order_email" 
                 value="<?php echo $orderEmail; ?>" 
                 placeholder="emaily oddělené čárkou" class="regular-text" />
        </td>
      </tr>
    </table>

    <p class="submit">  
      <input type="submit" name="submit" value="<?php _e('Uložit', 'tlapnet_konfigurator' ) ?>" />  
    </p>  
  </form>  
</div>  