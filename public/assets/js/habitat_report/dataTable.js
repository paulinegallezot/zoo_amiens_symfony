const initDatatable = function () {
    const datatableConfig = {
        filtermatch :{
            habitat: "#filter_habitat_id",
            user: "#filter_user_id",
        },
        columns: [

            {data: 'createdAt'},
            {data: 'habitat'},
            {data: 'review'},
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
                   if (data) {
                       return `${data}`;
                   }
                   return '';
                }
            },


            {
                targets: 3,
                orderable: false,
                render: function (data, type, row) {
                    if (currentUser.id !== row.user.id) {
                        return `<span class="badge badge-light-success fs-base">${data.firstname} ${data.lastname}</span>`;
                    }else{
                        return `<span class="badge badge-success fs-base">${data.firstname} ${data.lastname}</span>`;
                    }
                }
            },
            {
                targets: -1,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    const editUrl = jsCustomConfig['editUrl'].replace('__ID__', row.id);

                    if (currentUser.role === 'ADMIN'  || (currentUser.id === row.user.id && (currentUser.role === 'VETO' ))) {
                        return `<div class="d-flex flex-end">
                        <a title="Editer"    href="${editUrl}" class="btn btn-icon btn-active-light btn-active-color-primary"><i class="las la-edit fs-1"></i></a>
                        <a title="Supprimer" href="#"          class="btn btn-icon btn-active-light btn-active-color-primary action_delete"><i class="las la-trash fs-1" ></i></a>
                        </div>`;
                    }else if (currentUser.id !== row.user.id && currentUser.role === 'VETO' ){
                        return `<div class="d-flex flex-end">
                        <a title="Editer"    href="#" class="btn btn-icon btn-color-gray-400 btn-active-color-danger"><i class="las la-edit fs-1"></i></a>
                        <a title="Supprimer" href="#" class="btn btn-icon btn-color-gray-400 btn-active-color-danger"><i class="las la-trash fs-1" ></i></a>
                        </div>`;
                    }
                    return '';
                }
            }

        ],
        order: [[0, 'desc']]
    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
    DateRanges.init();
};