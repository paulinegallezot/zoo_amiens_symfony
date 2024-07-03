var initDatatable = function () {
   console.log('load habitat datatable')
    var datatableConfig = {
            columns: [
                {data: 'images'},
                {data: 'name', orderSequence: ['asc', 'desc']},
                {data: 'description'},
                {data: 'counterAnimal', orderSequence: ['asc', 'desc']},
                {data: null},

            ],
            columnDefs: [

                {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {

                        if (data?.[0]){
                            return `<img src="/images/habitats/${data[0].thumbnail}">`;
                        }
                        return null;
                    }
                },
                {
                    targets: 1,
                    orderable: true,
                    searchable: true,
                    render: function (data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    targets: 2,
                    orderable: false,
                    searchable: true,
                    render: function (data, type, row) {
                        return `${data}`;
                    }
                },
                {
                    targets: 3,
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
                        //pour respecter le format du path
                        var editUrl = jsCustomConfig['editUrl'].replace('__ID__', row.id);
                        return ` <div class="d-flex flex-end">
                                <a title ="Editer" href="${editUrl}" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px "><i class="las la-edit fs-2" ></i></a>
                                <a title ="Supprimer" href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px action_delete"><i class="las la-trash fs-2" data-kt-docs-table-filter="delete_row"></i></a>
                            </div>`;
                    }
                },
            ],
            order: [[1,'asc']]
    };
    KTDatatablesServerSide.init(datatableConfig);
    DeleteItem.init();
};