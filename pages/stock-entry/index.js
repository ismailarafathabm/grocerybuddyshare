import StockEntryController from "./controllers/stockentry_ctrl.js";
app.controller('stockentry_ctrl', StockEntryController);

import StockEntryViewController from "./controllers/viewentry_ctrl.js";
app.controller('entryview_ctr', StockEntryViewController);

import StockEntryControllerView from "./controllers/stockentryi_ctrl.js";
app.controller('entryviewi_ctr', StockEntryControllerView);