<div xmlns:ng="http://angularjs.org" class="ng-app:TlapnetKonfigurator" id="ng-app" ng-app="TlapnetKonfigurator">
  <script src="<?= TLAPNET_KONFIGURATOR_PLUGIN_URL ?>js/angular-1.2.0rc1.min.js"></script>
  <script src="<?= TLAPNET_KONFIGURATOR_PLUGIN_URL ?>js/angular-route.min.js"></script>
  <script src="<?= TLAPNET_KONFIGURATOR_PLUGIN_URL ?>js/ui-bootstrap-custom-tpls-0.5.0.min.js"></script>

  <script>
    REQUEST_URI = '<?= $_SERVER['REQUEST_URI'] ?>';
    TLAPNET_KONFIGURATOR_PLUGIN_URL = '<?= TLAPNET_KONFIGURATOR_PLUGIN_URL ?>';
    TLAPNET_KONFIGURATOR_PLUGIN_URL_PATH = '<?= TLAPNET_KONFIGURATOR_PLUGIN_URL_PATH ?>';
  </script>
  <!--[if lte IE 8]>
    <script src="<?= TLAPNET_KONFIGURATOR_PLUGIN_URL ?>js//json3.min.js"></script>
  <![endif]-->  
  <script src="<?= TLAPNET_KONFIGURATOR_PLUGIN_URL ?>js/tlapnet-konfigurator.js?t=<?= time() ?>"></script>
 
  <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
  
    <div ng-controller="TlapnetKonfiguratorCtrl">     
      <div class="content-left">
        <div ng-view></div>
      </div>

      <div class="content-right">
      <h2 class="tksum-heading">Shrnutí</h2>        
      <div class="tksum" ng-if="payments.totalPayments">
        <div class="tksbox" ng-repeat="service in data.services | filter:{selected:true}">
          <h3 ng-click="toggleService(service)">
            {{service.Name}}
            <span ng-repeat="tariff in service.Tariffs | filter:{selected:true}">
              <span ng-repeat="package in tariff.Packages | filter:{selected:true}">
                &ndash; {{package.Name}}
              </span>
            </span>
          </h3>
          <div ng-repeat="tariff in service.Tariffs | filter:{selected:true}">
            <div ng-repeat="package in tariff.Packages | filter:{selected:true}">
              <div ng-repeat="price in package.Prices | filter:{selected:true}">
                <input type="hidden" name="services[{{service.Id}}][{{tariff.Id}}][{{package.Id}}][price]" value="{{price.Type}}">
                <p class="bo"><span class="tksdesc">{{price.Description}}</span> <span class="tksprice">{{price.MonthlyPayment}},- Kč</span><span class="cleaner"></span></p>
                <p><span class="tksdesc">Instalace</span> <span class="tksprice">{{price.InstallPayment}},- Kč</span><span class="cleaner"></span></p>
              </div>

              <div ng-if="package.channelSelected" class="tkschan">
                <h3>Tématické balíčky</h3>
                <div class="tkschan-it" ng-repeat="channel in package.Channels | filter:{selected:true}">
                  <input type="hidden" name="services[{{service.Id}}][{{tariff.Id}}][{{package.Id}}][channels][]" value="{{channel.Id}}">
                  <p><span class="tksdesc">{{channel.Name}}</span> <span class="tksprice">{{channel.Price}},- Kč</span><span class="cleaner"></span></p>
                </div>
                <div class="tksremove" title="Zrušit Tématické balíčky" ng-if="package.channelSelected" ng-click="removeChannelsOfPackage(package)">Zrušit Tématické balíčky</div>
              </div>
            </div>
          </div>
          <div class="tksremove" ng-click="removeService(service)" title="Zrušit službu">Zrušit službu</div>
        </div>

        <div class="tksall">
          <p class="bo"><span class="tksdesc">Celkem měsíčně</span> <span class="tksprice">{{payments.monthlyPayments}},- Kč</span><span class="cleaner"></span></p>
          <p><span class="tksdesc">Celkem instalace</span> <span class="tksprice">{{payments.installPayments}},- Kč</span><span class="cleaner"></span></p>
          <p class="bo"><span class="tksdesc">Celkem</span> <span class="tksprice">{{payments.totalPayments}},- Kč</span><span class="cleaner"></span></p>
        </div>

        <div class="tsorder">
          <a title="Objednat služby online emailem" href="#Objednavka">Objednat</a>
        </div> 
        
      </div>
      </div>
      <div class="cleaner"></div>
           
    </div>
  </form>
  
</div>
