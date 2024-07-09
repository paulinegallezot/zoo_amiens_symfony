const initDatatable = function () {
    const datatableConfig = {
        filtermatch :{
            food: "#filter_food_id",
            user: "#filter_user_id",
        },
        columns: [
            {data: 'setAt', orderSequence: ['asc', 'desc']},
            {data: 'food'},
            {data: 'quantityInGrams', orderSequence: ['asc', 'desc']},
            {data: 'user'},

        ],
        columnDefs: [

            {
                targets: 0,
                orderable: true,
                searchable: false,
                render: function (data) {
                    return displayDate(data);
                }
            },
            {
                targets: 1,
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `${data.name}`;
                }
            },
            {
                targets: 2,
                orderable: true,
                searchable: true,
                render: function (data) {
                    return `${data}g`;
                }
            },

            {
                targets: 3,
                orderable: false,
                searchable: false,
                render: function (data) {
                    return `${data.firstname} ${data.lastname}`;
                }
            },

        ],
        order: [[0, 'desc']]
    };
    KTDatatablesServerSide.init(datatableConfig);
    DateRanges.init();

};