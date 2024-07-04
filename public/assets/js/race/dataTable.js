const initDatatable = function () {
    const datatableConfig = {
        columns: [
            {data: 'name', orderSequence: ['asc', 'desc']},
            {data: 'counterAnimal', orderSequence: ['asc', 'desc']},
            {data: null}
        ],
        columnDefs: [
            {
                targets: 0,
                orderable: true,
                render: function (data, type, row) {
                    return `${data}`;
                }
            },
            {
                targets: 1,
                orderable: true,
                searchable: false,
                render: function (data, type, row) {
                    return `<span data-counter_animal="${data}">${data}</span>`;
                }
            },
            {
                targets: -1,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    const editUrl = jsCustomConfig['editUrl'].replace('__ID__', row.id);
                    return `<div class="d-flex flex-end">
                    <a title="Editer" href="${editUrl}" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"><i class="las la-edit fs-2"></i></a>
                    <a title="Supprimer" href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px action_delete"><i class="las la-trash fs-2" data-kt-docs-table-filter="delete_row"></i></a>
                </div>`;
                }
            }
        ],
        order: [[0, 'asc']]
    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
};