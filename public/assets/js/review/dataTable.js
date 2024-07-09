const initDatatable = function () {
    const datatableConfig = {
        filterByValue :{
            published: "#filter_published_value",

        },
            order: [[2,'desc']],
            columns: [
                {data: 'pseudo', orderSequence: ['asc', 'desc']},
                {data: 'content'},
                {data: 'published', orderSequence: ['asc', 'desc']},
                {data: 'publishedAt', orderSequence: ['asc', 'desc']},
                {data: 'createdAt', orderSequence: ['asc', 'desc']},
                {data: null},

            ],
            columnDefs: [


                {
                    targets: 0,
                    orderable: true,
                    searchable: true,
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
                    targets: 2,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (data) {
                            return '<span class="badge badge-light-success fs-base">Publié</span>';
                        }
                        return '<span class="badge badge-light-secondary fs-base text-body-tertiary">Non publié</span>';
                    }
                },
                {
                    targets: 3,
                    orderable: true,
                    searchable: false,
                    render: function (data, type, row) {
                        if (data && row['published'] === true) {
                            return displayDate(data);
                        }
                        return '<span class="badge badge-light-secondary fs-base text-body-tertiary">Non publié</span>';
                    }
                },
                {
                    targets: 4,
                    orderable: true,
                    searchable: false,
                    render: function (data, type, row) {
                        if (data) {
                            return displayDate(data);
                        }
                        return '';
                    }
                },


                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        let links = [];
                        let url = jsCustomConfig['editUrl'].replace('__ID__', row.id);
                        links.push(`<a title="Editer" href="${url}" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"><i class="las la-edit  fs-2hx "></i></a>`);

                        if (currentUser.role=='ADMIN') {
                            links.push(`<a title="Supprimer" href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px action_delete"><i class="las la-trash fs-2"></i></a>`);

                        }
                        return `<div class="d-flex flex-end">${links.join('')}</div>`;
                    }
                },
            ] };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
    DateRanges.init();
};