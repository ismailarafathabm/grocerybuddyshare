export const newSupplierModel = () => {
    const sup = {
        _id: 0,
        supplierName: "",
        supplierCode: "",
        supplierPhone: "",
        supplierAddress: "",
        supplierGstNo: "",
        supplierPanNO: "",
        cBy: "",
        eBy: "",
        cDate: "",
        eDate: "",
        status : "",
    }
    return sup;
}

export const supplierListModel = (s,c) => {
    let xc = [];
    if (useraccess.suppliernew) {
        xc.push({
            headerName: "Edit",
            cellRenderer: (p) => c(`<img ng-click="getsupplierinfo('${p.data._id}')" src="${url}/assets/img/icons/edit-5.svg"/>`)(s)[0],
            width: 50
        })
    }
    xc.push({
        headerName: 'Code',
        field: 'supplierCode',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Name',
        field: 'supplierName',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Phone',
        field: 'supplierPhone',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'Address',
        field: 'supplierAddress',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'GST No',
        field: 'supplierGstNo',
        filter: 'agMultiColumnFilter',
        sortingOrder: ['asc', 'desc'],
        width: 140,
    })
    xc.push({
        headerName: 'GST No',
        field: 'supplierPanNo',
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