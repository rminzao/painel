// page params
const parameters = {
    params: {
        sid: null,
        limit: 9999
    }
}

// invoice page manager
const invoice = {
    create() {
        const data = $("#invoice-create-form").serialize();
        var button = document.querySelector("#invoice-create-form-submit");
        changeButtonState(button, true);
        axios.post(
            `${baseUrl}/api/admin/invoice`, data
        ).then((res) => {
            var su = res.data;
            swMessage(su.state ? "success" : "warning", su.message);
            changeButtonState(button, false);
        }).catch((err) => {
            swMessage("error", "erro interno, verifique o console.");
            changeButtonState(button, false);
            console.error(err);
        });
    },
    loadUsers() {
        return axios.get(`${baseUrl}/api/admin/user`, parameters)
    },
    loadProducts() {
        return axios.get(`${baseUrl}/api/admin/product`, parameters)
    },
    populateUser(data) {
        let userList = $('#user-list')
        userList.empty()

        $.each(data.users, function (_, user) {
            //check is user character is not null
            if (user.character != null) {
                userList.append(`<option value="${user.id}" data-kt-select2-user="${user.avatar}">${user.character?.NickName}</option>`)
            }
        })
    },
    populateProduct(data) {
        let productList = $('#product-list')
        productList.empty()

        $.each(data.data, (_, product) => {
            //check is user character is not null
            productList.append(`<option value="${product.id}" data-price="${product.value}">${product.name} - R$${product.value}</option>`)
        })

        productList.trigger('change')
    },
    init() {
        helper.loader('#card_create')
        Promise.all([
            invoice.loadUsers(),
            invoice.loadProducts(),
        ]).then((res) => {
            const [users, products] = res;

            invoice.populateUser(users.data);
            invoice.populateProduct(products.data);
            helper.loader('#card_create', false)
        })
    }
}

// create listeners
const listener = {
    init() {
        //get server id by select
        parameters.params.sid = $('select[name="sid"]').val()

        //set sid from select change
        $('select[name="sid"]').on('change', function () {
            parameters.params.sid = $(this).val()
            invoice.init()
        })

        //create listener for product select
        $('#product-list').on('change', function () {
            let product = $(this).find(':selected')
            let price = product.data('price')

            $('input[name="price"]').val(price)
        })

        //send form invoice create
        $('#invoice-create-form-submit').on('click', () => {
            invoice.create()
        })

        //[payment, users] options format
        var optionFormat = function(item) {
            if (!item.id) {
                return item.text;
            }

            var span = document.createElement('span');
            var imgUrl = item.element.getAttribute('data-kt-select2-user');
            var template = '';

            template += '<img src="' + imgUrl + '" class="symbol bg-light h-20px me-2" alt="image"/>';
            template += item.text;

            span.innerHTML = template;

            return $(span);
        }

        $('#payment_list, #user-list').select2({
            templateSelection: optionFormat,
            templateResult: optionFormat
        });
    }
}

//start invoice page functions
$(document).ready(function () {
    listener.init()
    invoice.init()
})
