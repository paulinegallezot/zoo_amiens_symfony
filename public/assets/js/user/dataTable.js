const initDatatable = function () {
    const roles = {
        'ROLE_VETO': 'Vétérinaire',
        'ROLE_EMPLOYE': 'Employé'
    };

    const datatableConfig = {
            filterByValue :{
                roles: "#filter_role_value",

            },
            order: [[0,'asc']],
            columns: [

                {data: 'firstname'},
                {data: 'lastname'},
                {data: 'email', orderSequence: ['asc', 'desc']},
                {data: 'roles'},
                {data: null},

            ],
            columnDefs: [
                {
                    targets: 0,
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
                    targets: 2,
                    orderable: true,
                    searchable: true,
                    render: function (data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    targets: 3,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {

                        if (data) {

                            return roles[data[0]];
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
                        const loginAsUrl = jsCustomConfig['editUrl'].replace('edit','login-as').replace('__ID__', row.id);
                        return ` <div class="d-flex flex-end">
                                <a title ="Login As" href="${loginAsUrl}" class="btn btn-icon btn-active-light btn-active-color-primary "><i class="las la-plug fs-1" ></i></a>
                                <a title ="Editer" href="${editUrl}" class="btn btn-icon btn-active-light btn-active-color-primary "><i class="las la-edit fs-1" ></i></a>
                                <a title ="Supprimer" href="#" class="btn btn-icon btn-active-light btn-active-color-primary action_delete"><i class="las la-trash fs-1" data-kt-docs-table-filter="delete_row"></i></a>
                            </div>`;
                    }
                },
            ]

    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
};