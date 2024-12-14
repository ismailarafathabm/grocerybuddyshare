export const newSalesModel = () => {
    const x = {
        salesInvoice: "",
        salesDate: "",
        salesCustomerName: "",
        salesCustomerPhone: "",
        salesCustomerAdress : "",
    }

    return x;
}

export const newBillModel = {
    salesCustomerName: "",
    salesCustomerPhone : "",
    salesCustomerAddress : "",
    salessubtot : "0",
    salesCustomerPaid : "0",
    salesCustomerBalance : "0",
}

export const salesViewModel = (s,c) => {
    var filterParams = {
        comparator: function (filterLocalDateAtMidnight, cellValue) {
            var dateAsString = cellValue;
            var dateParts = dateAsString.split('-');
            var cellDate = new Date(
                Number(dateParts[0]),
                Number(dateParts[1]) - 1,
                Number(dateParts[2])
            );

            if (filterLocalDateAtMidnight.getTime() === cellDate.getTime()) {
                return 0;
            }

            if (cellDate < filterLocalDateAtMidnight) {
                return -1;
            }

            if (cellDate > filterLocalDateAtMidnight) {
                return 1;
            }
        },
    };
    let xc = [];
    xc.push({
        headerName: "Print",
        width : 60,
        cellRenderer : (p) => `<a target="_blank" href="${url}/salesbill.php?salesrefno=${p.data.salesRefNo}"><img src="${url}/assets/img/icons/printer.svg"/></a>`
    })
    if (useraccess.salesedit) {
        xc.push({
            headerName: "edit",
            width: 60,
            cellRenderer: (p) => c(`<a ng-click="editbill('${p.data.salesRefNo}','${p.data.salesCustomerPhone}')"><img src="${url}/assets/img/icons/edit-5.svg"/></a>`)(s)[0]
        })
    }
    xc.push({
        headerName: 'Status',
        field: 'statusTxt',
        cellRenderer: (p) => {
            switch ((+p.data.status)) {
                case 0: 
                    return `<span class="badges btn-danger">${p.data.statusTxt}</span>`
                case 2:
                    return `<span class="badges btn-primary">${p.data.statusTxt}</span>`
                default:
                    return `<span class="badges btn-success">${p.data.statusTxt}</span>`
            }  
        },
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })

    xc.push({
        headerName: 'Bill No',
        field: 'salesInvoice',
        cellRenderer : p => `<span style="margin-right:5px"><i class="fa fa-eye"></i></span>${p.data.salesInvoice}`,
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Date',
        field: 'salesDate',
        cellRenderer: function (params) {
            var dips = params.data.salesDates.display;
            return `<div>${dips}</div>`
        },
        width: 110,
        sortingOrder: ['asc', 'desc'],
        filter: 'agDateColumnFilter',
        filterParams: filterParams,
        sort: 'desc',
        sortIndex: 0
    });
    xc.push({
        headerName: 'Customer Name',
        field: 'salesCustomerName',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Phone',
        field: 'salesCustomerPhone',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Address',
        field: 'salesCustomerAddress',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    })
    xc.push({
        headerName: 'Qty',
        field: 'sqty',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 90,
    })
    xc.push({
        headerName: 'Sub Total',
        field: 'stot',
        cellRenderer: p => (+p.data.stot).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Tot Gst',
        field: 'gstval',
        cellRenderer: p => (+p.data.gstval).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Grand Total',
        field: 'subtotval',
        cellRenderer: p => (+p.data.subtotval).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    
   
    return xc;
}

export const salesRptModel = () => {
    var filterParams = {
        comparator: function (filterLocalDateAtMidnight, cellValue) {
            var dateAsString = cellValue;
            var dateParts = dateAsString.split('-');
            var cellDate = new Date(
                Number(dateParts[0]),
                Number(dateParts[1]) - 1,
                Number(dateParts[2])
            );

            if (filterLocalDateAtMidnight.getTime() === cellDate.getTime()) {
                return 0;
            }

            if (cellDate < filterLocalDateAtMidnight) {
                return -1;
            }

            if (cellDate > filterLocalDateAtMidnight) {
                return 1;
            }
        },
    };
    let xc = [];
    xc.push({
        headerName: 'Bill#',
        field: 'salesInvoice',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Date',
        field: 'salesDate',
        cellRenderer: function (params) {
            var dips = params.data.salesDates.display;
            return `<div>${dips}</div>`
        },
        width: 110,
        sortingOrder: ['asc', 'desc'],
        filter: 'agDateColumnFilter',
        filterParams: filterParams,
        sort: 'desc',
        sortIndex: 0
    });
   
    xc.push({
        headerName: 'Customer Name',
        field: 'salesCustomerName',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Phone',
        field: 'salesCustomerPhone',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Address',
        field: 'salesCustomerAddress',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    }) 
    xc.push({
        headerName: 'Barcode',
        field: 'productBarcode',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Item',
        field: 'productNametamil',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Brand',
        field: 'brandName',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Category',
        field: 'productTypeName',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Qty',
        field: 'salesQty',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Unit',
        field: 'unitType',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'P.Price',
        field: 'subtotval',
        cellRenderer: p => (+p.data.salesPPrice).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'S.Price',
        field: 'subtotval',
        cellRenderer: p => (+p.data.salesSPrice).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'MRP',
        field: 'salesMrp',
        cellRenderer: p => (+p.data.salesMrp).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Sub.Total',
        field: 'subtotval',
        cellRenderer: p => (+p.data.salessubtot).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Profit',
        field: 'profilt',
        cellRenderer: p => (+p.data.profilt).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    return xc;
}