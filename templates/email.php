<h1>Objednávka</h1>

<h2>Zákazník</h2>

<p>
  <?= htmlentities($customer['name'], ENT_COMPAT | ENT_HTML401, 'UTF-8') ?><br />
  <?= htmlentities($customer['street'], ENT_COMPAT | ENT_HTML401, 'UTF-8') ?> <?= htmlentities($customer['houseNumber'], ENT_COMPAT | ENT_HTML401, 'UTF-8') ?> 
  <?= htmlentities($customer['city'], ENT_COMPAT | ENT_HTML401, 'UTF-8') ?><br />
  E-mail: <?= htmlentities($customer['email'], ENT_COMPAT | ENT_HTML401, 'UTF-8') ?><br />
  Telefon: <?= htmlentities($customer['phone'], ENT_COMPAT | ENT_HTML401, 'UTF-8') ?><br />
</p>


<h2>Objednané služby</h2>

<?php foreach ($services as $service) : ?>
  <?php if ($service->selected) : ?>
    <?php foreach ($service->Tariffs as $tariff) : ?>
      <?php if ($tariff->selected) : ?>
        <h3><?= htmlentities($service->Name, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?> - <?= htmlentities($tariff->Name, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?></h3>
        <?php foreach ($tariff->Packages as $package) : ?>
          <?php if ($package->selected) : ?>
            <?= htmlentities($package->Name, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?><br />
            <?php foreach ($package->Prices as $price) : ?>
              <?php if ($price->selected) : ?>
                <?= htmlentities($price->Description, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?>, <?= htmlentities($price->MonthlyPayment, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?>,- Kč měsíčně<br />
              <?php endif; ?>
            <?php endforeach; ?>
            <?php if (isset($package->Channels) && is_array($package->Channels)) : ?>
              <?php foreach ($package->Channels as $channel) : ?>
                <?php if ($channel->selected) : ?>
                  <?= htmlentities($channel->Name, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?>, <?= htmlentities($channel->Price, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?>,- Kč měsíčně<br />
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
<?php endforeach; ?>
                  
<?php if ($payments['subscription']): ?>
  <p>Předplatit služby na 12 měsíců</p>
<?php endif; ?>

<h2>Cena celkem</h2>

<strong>Měsíčně: </strong><?= $payments['monthlyPayments'] ?>,- Kč<br />
<strong>Celkem: </strong><?= $payments['totalPayments'] ?>,- Kč<br />

Není započítána cena instalace. Liší se individuálně. Ceny jsou uvedeny s DPH.
