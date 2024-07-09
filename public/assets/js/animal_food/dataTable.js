const initDatatable = function () {
    const datatableConfig = {
        filtermatch :{
            animal: "#filter_animal_id",
            food: "#filter_food_id",
            user: "#filter_user_id",

        },
        columns: [

            {data: 'setAt'},
            {data: 'animal'},
            {data: 'food'},
            {data: 'quantityInGrams'},
            {data: 'user'},
            {data: null}

        ],
        columnDefs: [
            {
                targets: 0,
                orderable: true,
                render: function (data, type, row) {
                    let date = new Date(data);
                    // Utilisez toLocaleString pour formater la date
                    return date.toLocaleString('fr-FR', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }
            },
            {
                targets: 1,
                orderable: false,
                render: function (data, type, row) {
                    return `${data.name}`;
                }
            },
            {
                targets: 2,
                orderable: false,
                render: function (data, type, row) {
                    return `${data.name}`;
                }
            },
            {
                targets: 3,
                orderable: false,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: 4,
                orderable: false,
                render: function (data, type, row) {
                    return `${data.firstname} ${data.lastname}`;
                }
            },
            {
                targets: -1,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    const editUrl = jsCustomConfig['editUrl'].replace('__ID__', row.id);
                    if (currentUser.role=='VETO') {
                        return '';
                    }else {
                        return `<div class="d-flex flex-end">
                    <a title="Editer" href="${editUrl}" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"><i class="las la-edit fs-2"></i></a>
                    <a title="Supprimer" href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px action_delete"><i class="las la-trash fs-2" data-kt-docs-table-filter="delete_row"></i></a>
                </div>`;
                    }
                }
            }

        ],
        order: [[0, 'asc']]
    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
    DateRanges.init();
};