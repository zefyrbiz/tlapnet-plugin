var ngTlapnetKonfigurator = angular.module('TlapnetKonfigurator', ['ui.bootstrap', 'ngRoute'], function($routeProvider, $locationProvider) {
  $routeProvider.when('/', {
    templateUrl: TLAPNET_KONFIGURATOR_PLUGIN_URL + '/templates/configurator.html',
    controller: TlapnetKonfiguratorCtrl
  });
  $routeProvider.when('/Objednavka', {
    templateUrl: TLAPNET_KONFIGURATOR_PLUGIN_URL + '/templates/customer.html',
    controller: TlapnetKonfiguratorCtrl
  });
});

 
ngTlapnetKonfigurator.factory('theService', function($http) {
  var services = [];
  
  initData = function (services) {
    angular.forEach(services, function(service) {
      service.selectedPackage = null;
      angular.forEach(service.Tariffs, function(tariff) {
        tariff.parent = service;
        angular.forEach(tariff.Packages, function(package) {
          package.selectedChannels = {};
          package.parent = tariff;
          angular.forEach(package.Prices, function(price) {
            if (price.Type === 'BEZNA_CENA') {
              package.salePrice = price;
            }
            price.parent = package;
          });
          angular.forEach(package.Channels, function(channel) {
            channel.parent = package;
          });
        });
      });
    });
  };
  
  return {
    payments: {
      monthlyPayments: 0,
      totalPayments: 0,
      subscription: false
    },
    getServices: function(callback) { 
      if (services.length > 0) {
        return callback(services);
      }
      $http({method: 'GET', url: TLAPNET_KONFIGURATOR_PLUGIN_URL + '/data/services.json'}).
        success(function(data, status) {
          initData(data);
          services = data;
          callback(data);
        }).
        error(function(data, status) {
          alert("Request failed! Status: " + status + '; Data: ' + data);
      });
    }
  };
});
 

function TlapnetKonfiguratorCtrl($scope, theService) {
  $scope.customer = {
    name : '',
    email : '',
    phone : '',
    city : '',
    street : '',
    houseNumber : ''
  };
  
  $scope.payments = theService.payments;
  
  theService.getServices(function(data){
    $scope.services = data;
  });
  
  $scope.shoppingCart = [];
  
  $scope.toggleService = function (service) {
    service.visible = !service.visible;
  };
  
  $scope.selectTariff = function (tariff) {
    tariff.visible = true;
    
    angular.forEach(tariff.parent.Tariffs, function(item) {
      if (item !== tariff) {
        item.visible = !tariff.visible;
      }
    });
  };
  
  $scope.togglePackage = function (package) {
    if (package.parent.parent.selectedPackage !== null) {
      package.parent.parent.selectedPackage.selected = false;
    }
    
    package.selected = (package.parent.parent.selectedPackage !== package);
    
    if (package.selected) {
      package.parent.parent.selectedPackage = package;
    } else {
      angular.forEach(package.selectedChannels, function(channel) {
        $scope.toggleChannel(channel);
      });
      package.parent.parent.selectedPackage = null;
    }
            
   $scope.sumPayments();
  };
  
  
  $scope.toggleChannel = function (channel) {
    if (typeof channel.parent.selectedChannels[channel.Id] === "undefined") {
      channel.selected = true;
      channel.parent.selectedChannels[channel.Id] = channel;
    } else {
      channel.selected = false;
      delete channel.parent.selectedChannels[channel.Id];
    }
    channel.parent.channelSelected = (Object.keys(channel.parent.selectedChannels).length > 0);
    
    $scope.sumPayments();
  };
  
  $scope.removeService = function (service) {
    service.selected = false;
    if (service.selectedPackage !== null) {
      $scope.togglePackage(service.selectedPackage);
    }
    
    angular.forEach(service.Tariffs, function(tariff) {
      tariff.selected = false;
    });
    service.selectedPackage = null;
    $scope.sumPayments();
  };
  
  $scope.removeChannelsOfPackage = function (package) {
    angular.forEach(package.selectedChannels, function(channel) {
      $scope.toggleChannel(channel);
    });
    $scope.sumPayments();
  };
  
  $scope.sumPayments = function () {
    $scope.setSalePrices();
    $scope.payments.monthlyPayments = 0;
    
    angular.forEach($scope.services, function(service) {
      if (service.selectedPackage !== null) {
        $scope.payments.monthlyPayments += parseInt(service.selectedPackage.salePrice.MonthlyPayment);
        angular.forEach(service.selectedPackage.selectedChannels, function(channel) {
          $scope.payments.monthlyPayments += parseInt(channel.Price);
        });
      }
    });
    $scope.payments.totalPayments = $scope.payments.monthlyPayments;
  };
  
  $scope.setSalePrices = function () {
    var selectedPackages = [];
    angular.forEach($scope.services, function(service) {
      if (service.selectedPackage !== null) {
        selectedPackages.push(service.selectedPackage);
      }
    });
    
    var priceType = 'BEZNA_CENA';
    if ($scope.payments.subscription) {
      if (selectedPackages.length > 1) {
        priceType = "PREDPLATNE_V_BALICKU";
      } else {
        priceType = "PREDPLATNE";
      }
    } else {
      if (selectedPackages.length > 1) {
        priceType = "BEZNA_CENA_V_BALICKU";
      } else {
        priceType = "BEZNA_CENA";
      }
    }
    
    angular.forEach(selectedPackages, function(package) {
      angular.forEach(package.Prices, function(price) {
        if (price.Type === priceType) {
          package.salePrice = price;
        }
        price.parent = package;
      });
    });
    
  };
  
  
}