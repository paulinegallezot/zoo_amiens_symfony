const initDatatable = function () {
    const datatableConfig = {
            order: [[0,'asc']],
            columns: [
                {data: 'name', orderSequence: ['asc', 'desc']},
                {data: 'description'},
                {data: null},

            ],
            columnDefs: [


                {
                    targets: 0,
                    orderable: true,
                    searchable: true,
                    search2: true,
                    render: function (data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    targets: 1,
                    orderable: false,
                    searchable: true,
                    render: function (data, type, row) {
                        if (data) {
                            return `${data}`;
                        }
                        return '';
                    }
                },

                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        const editUrl = jsCustomConfig['editUrl'].replace('__ID__', row.id);
                        return ` <div class="d-flex flex-end">
                                <a title ="Editer" href="${editUrl}" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px "><i class="las la-edit fs-2" ></i></a>
                                <a title ="Supprimer" href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px action_delete"><i class="las la-trash fs-2" data-kt-docs-table-filter="delete_row"></i></a>
                            </div>`;
                    }
                },
            ]

    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
};