export const newPurchaseModel = () => {
    const pm = {};
    return pm;
}

export const purchaseViewModel = (s,c) => {
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
        headerName: "",
        cellRenderer: (p) => `<a href="${url}/index.php#!/stockentry-viewi/${p.data.purchaseUniqNo}"><i class="fa fa-eye"></i></a>`,
        width:40,
    })
    if (useraccess.purchaseedit) {
        xc.push({
            headerName: "",
            cellRenderer: (p) => c(`<img ng-click="editbill('${p.data.purchaseUniqNo}')" src="${url}assets/img/icons/edit-5.svg" alt="edit_img"/>`)(s)[0],
            width: 40
        })
    }
   
    xc.push({
        headerName: 'Invoice No',
        field: 'purchaseInvoiceno',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Date',
        field: 'purchaseDate',
        cellRenderer: function (params) {
            var dips = params.data.purchaseDates.display;
            return `<div>${dips}</div>`
        },
        width: 110,
        sortingOrder: ['asc', 'desc'],
        filter: 'agDateColumnFilter',
        filterParams: filterParams,
        sort: 'asc',
        sortIndex: 0
    });
    xc.push({
        headerName: 'Supplier',
        field: 'supplierName',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    })
    xc.push({
        headerName: 'WO/GST',
        field: 'totpurchase',
        cellRenderer : (p) => (+p.data.totpurchase).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    })
    xc.push({
        headerName: 'W.GST',
        field: 'totgst',
        cellRenderer : (p) => (+p.data.totgst).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    })
    xc.push({
        headerName: 'Discount',
        field: 'dis',
        cellRenderer : (p) => (+p.data.dis).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    })
    xc.push({
        headerName: 'Sub Total',
        field: 'subtotal',
        cellRenderer : (p) => (+p.data.subtotal).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    })
   
    return xc;
}