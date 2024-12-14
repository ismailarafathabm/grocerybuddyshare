app.config(function($routeProvider)  { 
    $routeProvider
        .when('/', {
            templateUrl: "./pages/home-page/components/home-page.php",
            controller: 'dashboard_controller'
        })
        .when('/users-list', {
            templateUrl: "./pages/users/components/userslist.php",
            controller: 'userslist_controller'
        })
        .when('/brand-list', {
            templateUrl: './pages/brands/components/brand-list.php',
            controller : 'brandlist_ctrl'
        })
        .when('/producttype-list', {
            templateUrl: './pages/producttype/components/producttype_list.php',
            controller : 'producttype_ctrl'
        })
        .when('/unit-list', {
            templateUrl: './pages/productunit/components/unit-list.php',
            controller : "unitlist_ctrl"
        })
        .when('/product-list', {
            templateUrl: './pages/products/components/product-list.php',
            controller : "productlist_ctrl"
        })
        .when('/stock-entry', {
            templateUrl: "./pages/stock-entry/components/entry-new.php",
            controller : "stockentry_ctrl"
        })
        .when('/stockentry-view', {
            templateUrl: './pages/stock-entry/components/entry-view.php',
            controller : "entryview_ctr"
        })
        .when('/stockentry-viewi/:invoiceno', {
            templateUrl: './pages/stock-entry/components/entry-iview.php',
            controller : "entryviewi_ctr"
        })
        .when('/supplier-list', {
            templateUrl: "./pages/suppliers/components/supplier-list.php",
            controller : 'supplierlist_ctrl'
        })
        .when('/sales-entry', {
            templateUrl: "./pages/sales/components/sales-new.php",
            controller : "salesnew_ctrl"
        })
        .when('/sales-view', {
            templateUrl: "./pages/sales/components/sales-view.php",
            controller : "salesview_ctrl"
        })
        .when('/salesbill-view/:salrfno', {
            templateUrl: "./pages/sales/components/salesbillview.php",
            controller : "salesbillview"
        })
        .when('/sales-rpt', {
            templateUrl: "./pages/sales/components/sales-rpt.php",
            controller : "salesrpt_ctrl"
        })
        .when('/customer-list', {
            templateUrl: "./pages/customers/components/customer-list.php",
            controller : "customerlist_ctrl"
        })
        .when('/changepassword', {
            templateUrl: "./pages/users/components/changepassword.php",
            controller : 'changpasswordctrl'
        })
        // .when('/productunit-list', {
        //     templateUrl: './pages/productunit/components/unit-list.php.]',
        //     controller : "unitlist_ctrl"
        // })
        .otherwise({ redirectTo: '/' })
});