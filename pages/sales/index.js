import SalesNewController from "./controllers/salesnew_ctrl.js";
app.controller('salesnew_ctrl', SalesNewController);

import SalesViewController from "./controllers/salesview_ctrl.js";
app.controller('salesview_ctrl', SalesViewController);

import SalesBillviewController from './controllers/salesbillview.js';
app.controller('salesbillview', SalesBillviewController);

import SalesReportController from './controllers/salesrpt_ctrl.js'
app.controller('salesrpt_ctrl',SalesReportController)