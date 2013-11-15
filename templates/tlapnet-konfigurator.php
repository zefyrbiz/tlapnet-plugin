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
    <script src="<?= TLAPNET_KONFIGURATOR_PLUGIN_URL ?>js/json3.min.js"></script>
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
            
            <div ng-repeat="service in services">
              <div ng-if="service.selectedPackage != null">
                <div class="tksbox">
                <h3 ng-click="toggleService(service)">
                  {{service.Name}}
                  <span>
                    &ndash; {{service.selectedPackage.Name}}
                  </span>
                </h3>

                <div>
                  <input type="hidden" name="services[{{service.Id}}][{{service.selectedPackage.parent.Id}}][{{service.selectedPackage.Id}}][price]" value="{{service.selectedPackage.salePrice.Type}}">
                  <p class="bo"><span class="tksdesc">{{service.selectedPackage.salePrice.Description}}</span> <span class="tksprice">{{service.selectedPackage.salePrice.MonthlyPayment}},- Kč</span><span class="cleaner"></span></p>
                </div>

                <div ng-if="service.selectedPackage.channelSelected" class="tkschan">
                  <h3>Tématické balíčky</h3>
                  <div class="tkschan-it" ng-repeat="channel in service.selectedPackage.Channels | filter:{selected:true}">
                    <input type="hidden" name="services[{{service.Id}}][{{service.selectedPackage.parent.Id}}][{{service.selectedPackage.Id}}][channels][]" value="{{channel.Id}}">
                    <p><span class="tksdesc">{{channel.Name}}</span> <span class="tksprice">{{channel.Price}},- Kč</span><span class="cleaner"></span></p>
                  </div>
                  <div class="tksremove" title="Zrušit Tématické balíčky" ng-if="service.selectedPackage.channelSelected" ng-click="removeChannelsOfPackage(service.selectedPackage)">Zrušit Tématické balíčky</div>
                </div>
                <div class="tksremove" ng-click="removeService(service)" title="Zrušit službu">Zrušit službu</div>
                </div>
              </div>
            </div>

            <div class="tksall">
              <p class="bo">
                <input type="checkbox" name="subscription" id="tlapnet-subscription" value="1" ng-model="payments.subscription" ng-change="sumPayments()" /> 
                <label for="tlapnet-subscription">Předplatit služby na 12 měsíců</label>
              </p>
              <p class="bo"><span class="tksdesc">Celkem měsíčně</span> <span class="tksprice">{{payments.monthlyPayments}},- Kč</span><span class="cleaner"></span></p>
              <p class="bo"><span class="tksdesc">Celkem cena za 12 měsíců</span> <span class="tksprice">{{(payments.totalPayments)*12}},- Kč</span><span class="cleaner"></span></p>
              Není započítána cena instalace.<br>Ceny jsou uvedeny s DPH. 
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
