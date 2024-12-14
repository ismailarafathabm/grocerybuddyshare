export const CustomerModelGrid = (s, c) => {
    let xc = [];
    xc.push({
        headerName: 'Name',
        field: 'customerName',
        cellRenderer : (p) => `<span style="margin-right:5px"><i class="fa fa-edit"></i></span> ${p.data.customerName.toUpperCase()}`,
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 150,
    })
    xc.push({
        headerName: 'Phone',
        field: 'customerPhone',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 110,
    })
    xc.push({
        headerName: 'Address',
        field: 'customerAddress',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 170,
    })
    xc.push({
        headerName: 'Points',
        field: 'bpoint',
        cellRenderer : p => (+p.data.bpoint).toLocaleString(undefined,{maximumFractionDigits:2}),
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 120,
    })
    xc.push({
        headerName: 'Total Points',
        field: 'getpoints',
        cellRenderer: p => (+p.data.getpoints) > 0 ?  c(`<div class="btn btn-rounded btn-outline-success gridbtn" ng-click="showGetPoinst('${p.data.customerPhone}','${p.data.getpoints}')">${(+p.data.getpoints).toLocaleString(undefined,{maximumFractionDigits:2})}</div>`)(s)[0] : '-',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Used Points',
        field: 'usedpoints',
        //cellRenderer: p => c(`<div class="btn btn-rounded btn-outline-danger gridbtn" ng-click="showUsedPoinst('${p.data.customerPhone}','${p.data.usedpoints}')">${(+p.data.usedpoints).toLocaleString(undefined,{maximumFractionDigits:2})}</div>`)(s)[0] ,
        cellRenderer: p => (+p.data.usedpoints) > 0 ? c(`<div class="btn btn-rounded btn-outline-danger gridbtn" ng-click="showUsedPoinst('${p.data.customerPhone}','${p.data.usedpoints}')">${(+p.data.usedpoints).toLocaleString(undefined,{maximumFractionDigits:2})}</div>`)(s)[0] : '-',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    
    return xc;
}