var orderControllers = angular.module('orderControllers', []);

orderControllers.controller('OrderCtrl', function($scope, $rootScope, Order, Request, Catalog, CatalogSearch, Country, City, Institution) {
    $rootScope.$apply(function() {
        console.log('hola');
    });
    
    $scope.sortableOptions = {
        connectWith: '.catalogSortable',
        update: function(event, ui) {
            var id = ui.item.data('id');
            var result = $(ui.item.sortable.droptarget).parents('table.table').data('type');
            var catalog = _.first($scope.catalogsWithSearches.filter(function(item) {
                return !_.isUndefined(item.search) && item.search.id === id;
            }));
            catalog.search.result = result;
            $scope.updateCatalog(catalog);
        },
        items: ">*:not(.sort-disabled)"
    };

    $scope.select_count = 0;
    $scope.search_results = [
        {value: 'found', text: 'Found'},
        {value: 'partially_found', text: 'Partially found'},
        {value: 'not_found', text: 'Not found'},
        {value: 'non_searched', text: 'Non searched'}
    ];

    Order.get({id: document_id}, function(order) {
        $scope.order = order;
    });

    $scope.request = Request.get({order_id: document_id}, function(request) {
        $scope.catalogs = Catalog.query(function(catalogs) {
            CatalogSearch.query({request_id: request.id}, function(searches) {
                $scope.catalogsWithSearches = angular.copy(catalogs).map(function(item) {
                    item.search = _.first(searches.filter(function(search) {
                        return search.catalog.id === item.id;
                    }));
                    return item;
                });

                $scope.updateTables();
            });
        });
    });

    $scope.countries = Country.query();
    $scope.select = {
        country: {},
        city: {},
        tree: [{
                id: 'institution' + $scope.select_count,
                name: 'institution' + $scope.select_count,
                institutions: [],
                child: []
            }]
    };

    $scope.applySelect2 = function() {
        $("#country").select2();
        $("#city").select2();
        $(".institution").select2();
    }

    $scope.requestFormUrl = Routing.generate('admin_order_request_form', {id: document_id});

    $scope.updateTables = function() {
        $scope.filterFound = $scope.catalogsWithSearches.filter(function(catalog) {
            return !_.isUndefined(catalog.search) && catalog.search.result === 'found';
        });

        $scope.filterPartiallyFound = $scope.catalogsWithSearches.filter(function(catalog) {
            return !_.isUndefined(catalog.search) && catalog.search.result === 'partially_found';
        });

        $scope.filterNotFound = $scope.catalogsWithSearches.filter(function(catalog) {
            return !_.isUndefined(catalog.search) && catalog.search.result === 'not_found';
        });
    }

    $scope.updateCatalog = function(catalog) {
        catalog.search.catalog = _.first($scope.catalogs.filter(function(c) {
            return c.id === catalog.id;
        }));
        catalog.search = CatalogSearch.save({request_id: $scope.request.id}, catalog.search);
        $scope.updateTables();
    }

    $scope.submit = function() {
        // Add your own logic, for example show the response your received from Symfony2
        // We have to explictly compile the data received, to parse AngularJS tags
        $scope.formResponse = $compile(data)($scope);
    }

    $scope.countryChanged = function() {
        $scope.cities = City.query({country_id: $scope.select.country.id});
        $scope.institutions = Institution.query({country_id: $scope.select.country.id}, function(institutions) {
            _.first($scope.select.tree).institutions = institutions;
        });
    }

    $scope.cityChanged = function() {
        $scope.institutions = Institution.query({country_id: $scope.select.country.id, city_id: $scope.select.city.id}, function(institutions) {
            _.first($scope.select.tree).institutions = institutions;
        });
    }

    $scope.institutionChanged = function(data) {
        $scope.select_count++;
        Institution.parent({parent_id: data.institution.id}, function(institutions) {
            data.child = [{
                    id: 'institution' + $scope.select_count,
                    name: 'institution' + $scope.select_count,
                    institutions: institutions,
                    child: []
                }];
        });
    }
});