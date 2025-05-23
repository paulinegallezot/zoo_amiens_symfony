const initDatatable = function () {
    const datatableConfig = {
        filtermatch :{
            race: "#filter_race_id",
            habitat: "#filter_habitat_id"
        },
        columns: [
            {data: 'images'},
            {data: 'name', orderSequence: ['asc', 'desc']},
            {data: 'race'},
            {data: 'habitat'},
            {data: null},
        ],
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if (data?.[0]) {
                        return `<img src="/images/${data[0].thumbnail}">`;
                    }
                    return null;
                }
            },
            {
                targets: 1,
                orderable: true,
                searchable: true,
                render: function (data) {
                    return `${data}`;
                }
            },
            {
                targets: 2,
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `${data.name}`;
                }
            }, {
                targets: 3,
                orderable: false,
                searchable: false,
                render: function (data) {
                    if (data) {
                        return `${data.name}`;
                    }
                    return null;
                }
            },
            {
                targets: -1,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {

                    const editUrl = jsCustomConfig['editUrl'].replace('__ID__', row.id);
                    if (currentUser.role==='VETO') {
                        const viewFoodsUrl = jsCustomConfig['editUrl'].replace('edit','foods').replace('__ID__', row.id);
                        return ` <div class="d-flex flex-end">
                                <a title ="Editer" href="${viewFoodsUrl}" class="btn btn-icon btn-active-light btn-active-color-primary "><i class="las la-utensils fs-1" ></i></a>
                                </div>`;
                    }else {
                        return ` <div class="d-flex flex-end">
                                <a title ="Editer" href="${editUrl}" class="btn btn-icon btn-active-light btn-active-color-primary "><i class="las la-edit fs-1" ></i></a>
                                <a title ="Supprimer" href="#" class="btn btn-icon btn-active-light btn-active-color-primary action_delete"><i class="las la-trash fs-1" data-kt-docs-table-filter="delete_row"></i></a>
                            </div>`;
                    }
                }
            },
        ],
        order: [[1, 'asc']]
    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();

};