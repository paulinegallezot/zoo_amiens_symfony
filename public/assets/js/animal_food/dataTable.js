const initDatatable = function () {
    const roles = {
        'ROLE_VETO': 'Vétérinaire',
        'ROLE_EMPLOYE': 'Employé'
    };

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
                    //return `${data.firstname} ${data.lastname}<br><span class="badge badge-light-success fs-base">${roles[data.roles]}</span>`;
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
                    if (currentUser.role === 'ADMIN'  || (currentUser.id === row.user.id && (currentUser.role === 'EMPLOYE' ))) {
                        return `<div class="d-flex flex-end">
                        <a title="Editer"    href="${editUrl}" class="btn btn-icon btn-active-light btn-active-color-primary"><i class="las la-edit fs-1"></i></a>
                        <a title="Supprimer" href="#"          class="btn btn-icon btn-active-light btn-active-color-primary action_delete"><i class="las la-trash fs-1" ></i></a>
                        </div>`;

                    }else if (currentUser.id !== row.user.id && currentUser.role === 'EMPLOYE' ){
                        {
                            return `<div class="d-flex flex-end">
                        <a title="Editer"    href="javascript:;" class="btn btn-icon btn-color-gray-400 btn-active-color-danger"><i class="las la-edit fs-1"></i></a>
                        <a title="Supprimer" href="javascript:;" class="btn btn-icon btn-color-gray-400 btn-active-color-danger"><i class="las la-trash fs-1" ></i></a>
                        </div>`;
                        }
                    }
                    return '';
                }
            }

        ],
        order: [[0, 'asc']]
    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
    DateRanges.init();
};