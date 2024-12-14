export const productModel = () => {
    const pm = {
        _id: "",
        productBarcode: "",
        productBrand: "",
        productType: "",
        productName: "",
        productNametamil : "",
        productSku: "",
        productUnitType: "",
        productStatus: 1,
        productMinQty: "0",
        productPPrice: "0",
        productSPrice: "0",
        productMrp: "0",
        productCgst: "0",
        productSgst: "0",
        productDiscount: "0",
        productLocationRack: "",
        cBy: "",
        eBy: "",
        peBy: "",
        cDate: "",
        eDate: "",
        cpUpdate: "",
        productOpeningstock :"",
    };

    return pm;
}

export const productListModel = (s,c) => {
    const xc = [];
    if (useraccess.productedit) {
        xc.push({
            headerName: "Edit",
            cellRenderer: (p) => c(`<img ng-click="getProductInfo('${p.data.productBarcode}')" src="${url}/assets/img/icons/edit-5.svg"/>`)(s)[0],
            width: 50
        })
    }
    if (useraccess.productremove) {
        xc.push({
            headerName: "Remove",
            cellRenderer: (p) => (+p.data.pqty) === 0 && (+p.data.sqty) === 0 ? c(`<img ng-click="deleteitem('${p.data._id}')" src="${url}/assets/img/icons/delete-2.svg"/>`)(s)[0] : '',
            width: 50
        })
    }
    
    xc.push({
        headerName: 'Barcode',
        field: 'productBarcode',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Name',
        field: 'productName',
        cellRenderer : (p) => p.data.productName.toUpperCase(),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
       
    })
    xc.push({
        headerName: 'Display Name',
        field: 'productNametamil',
       
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
       
    })
    xc.push({
        headerName: 'Brand',
        field: 'productBrandName',
        cellRenderer : (p) => p.data.productBrandName.toUpperCase(),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Type',
        field: 'productTypeNamedisp',
        cellRenderer : (p) => p.data.productTypeNamedisp.toUpperCase(),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Available',
        field: 'balqty',
        cellRenderer : (p) => (+p.data.balqty) === 0 ? "-" : (+p.data.balqty).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: "Purchase",
        field : 'pqty',
        cellRenderer: (p) => (+p.data.pqty) >0 ? c(`<button class="btn btn-rounded btn-outline-danger gridbtn" style="padding:0px 10px" ng-click="getallpurchase('${p.data._id}')">${p.data.pqty}</button>`)(s)[0] : '-',
        width: 100,
    })
    xc.push({
        headerName: 'Total Purchase Val',
        field: 'totpurcahseval',
        cellRenderer: p => (+p.data.totpurcahseval) > 0 ? `<div style="color:red;font-weight:bold;font-size:16px">${(+p.data.totpurcahseval).toLocaleString(undefined, { maximumFractionDigits: 2 })}</div>` : "-",
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 180,
    })
    xc.push({
        headerName: "Sales",
        field : 'sqty',
        cellRenderer: (p) => (+p.data.sqty) > 0 ? c(`<button class="btn btn-rounded btn-outline-success gridbtn" style="padding:0px 10px" ng-click="getallsales('${p.data._id}')">${p.data.sqty}</button>`)(s)[0] : "-",
        width: 100,
    })
    xc.push({
        headerName: 'Total Sales Val',
        field: 'totsalesval',
        cellRenderer: p => (+p.data.totsalesval) > 0 ? `<div style="color:green;font-weight:bold;font-size:16px">${(+p.data.totsalesval).toLocaleString(undefined, { maximumFractionDigits: 2 })}</div>` : "-",
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 180,
    })
    xc.push({
        headerName: 'Total Profit',
        field: 'salesprofitval',
        cellRenderer: p => `<div style="color:green;font-weight:bold;font-size:16px">${(+p.data.salesprofitval).toLocaleString(undefined, { maximumFractionDigits: 2 })}</div>`,
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 180,
    })
    xc.push({
        headerName: 'Type',
        field: 'productUnitTypeName',
        cellRenderer : (p) => p.data.productUnitTypeName.toUpperCase(),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'HSN',
        field: 'productSku',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'P.Price',
        field: 'productPPrice',
        cellRenderer : (p) => (+p.data.productPPrice) === 0 ? "-" : (+p.data.productPPrice).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'S.Price',
        field: 'productSPrice',
        cellRenderer : (p) => (+p.data.productSPrice) === 0 ? "-" : (+p.data.productSPrice).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'MR.Price',
        field: 'productMrp',
        cellRenderer : (p) => (+p.data.productMrp) === 0 ? "-" : (+p.data.productMrp).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'CGST',
        field: 'productCgst',
        cellRenderer: (p) => p.data.productCgst + " %",
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'SGST',
        field: 'productSgst',
        cellRenderer: (p) => p.data.productSgst + " %",
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Discount',
        field: 'productDiscount',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Location/Rack',
        field: 'productLocationRack',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
   
   
   
    xc.push({
        headerName: 'Status',
        field: 'statusTxt',
        cellRenderer : (p) => (+p.data.status) === 1 ? `<span class="badges bg-lightgreen">${p.data.statusTxt}</span>` :  `<span class="badges bg-lightred">${p.data.statusTxt}</span>`,
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
   
    return xc;
}

