export const brandList = () => {
    let xc = [];
    xc.push({
        headerName: 'Brand Name',
        field: 'brandName',
        cellRenderer : (p) => p.data.brandName.toUpperCase(),
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