export const productTypeList = () => {
    let xc = [];
    xc.push({
        headerName: 'Prdouct Type',
        field: 'productTypeName',
        filter: 'agMultiColumnFilter',
        cellRenderer : (p) => p.data.productTypeName.toUpperCase(),
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