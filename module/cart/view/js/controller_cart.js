function select_services(){
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL("?module=cart"),
        { 'op': 'services'}
    ).then(function(data) {
        var html = '';
        for (var row in data) {
            html += '<tr>';
            html += '<td class="service">' + data[row].service + '</td>';
            html += '<td class="price">' + data[row].price + '€</td>';
            html += '<td><button id="'+ data[row].id +'"class="btn btn-primary cart_service">Add to Cart</button></td>';
            html += '</tr>';
        }
        $('.servicesTable').html(html);
        $(document).on('click', '.cart_service', function(){
            var token = localStorage.getItem('access_token');
            var social = localStorage.getItem('social');
            if (social === null) {
                social = "";
            }
            var service = $(this).closest('tr').find('.service').text();
            var price = $(this).closest('tr').find('.price').text().replace('€', '');
            data = { 'service': service,'price':price,'token':token,'social':social, 'op': 'cart_add_service' };
            console.log(data);
            ajaxPromise(
                'POST', 
                'JSON', 
                friendlyURL('?module=cart'),
                data
            ).then(function (data) {
                // console.log(data);
                // return false;
                if (data === 'error_stock') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Service out of stock'
                    });
                }else{
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Service added correctly to the cart'
                    }).then(function() {
                        location.reload();
                    });
                }
            }).catch(function (e) {
                console.error(e);
            });
        });
    }).catch(function(e) {
        console.error(e);
    });
}
function cart_user(){
    token = localStorage.getItem('access_token');
    social = localStorage.getItem('social');
    if (social === null) {
        social = "";
    }
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL("?module=cart"),
        { 'op': 'cart_user', 'token':token,'social':social}
    ).then(function(data) {
        console.log(data);
        // return false;
        var html = '';
        var total = 0;
        for (var row in data) {
            var price = parseFloat(data[row].price);
            var quantity = parseInt(data[row].quantity);
            total += price * quantity;

            html += '<tr>';
            html += '<td>' + data[row].service + '</td>';
            html += '<td>' + price.toFixed(2) + '€</td>';
            html += '<td>' + quantity + '</td>';
            html += '<td>' +
                        '<button id="'+ data[row].id+'_min" class="btn btn-secondary substract">-1</button>'+
                        '<button id="'+ data[row].id +'_plus" class="btn btn-success plus">+1</button>'+
                        '<button id="'+ data[row].id +'_del"class="btn btn-danger delete">Delete</button>'+
                    '</td>';
            html += '</tr>';
        }
        let totalup = total.toFixed(2);
        localStorage.setItem('total', totalup);
        html += '<tr class="table-secondary"><td colspan="3">Total</td><td>' + totalup + '€</td></tr>';        
        $('.cartTable').html(html);
    }).catch(function(e) {
        var html = '';
        html += '<tr>';
        html += '<td colspan="4">Empty Cart</td>';
        html += '</tr>';
        $('.cartTable').html(html);
    });
}
function click_cart(){
    $(document).on('click', '.substract', function(){
        var service = $(this).closest('tr').find('td').eq(0).text();
        var price = $(this).closest('tr').find('td').eq(1).text().replace('€', '');
        var token = localStorage.getItem('access_token');
        var social = localStorage.getItem('social');
        if (social === null) {
            social = "";
        }
        data = { 'service': service,'token':token,'social':social,'price': price, 'op': 'cart_minus' };
        ajaxPromise(
            'POST', 
            'JSON', 
            friendlyURL('?module=cart'),
            data
        ).then(function (data) {
            location.reload();
        }).catch(function (e) {
            console.error(e);
        });
    });
    $(document).on('click', '.plus', function(){
        var service = $(this).closest('tr').find('td').eq(0).text();
        var price = $(this).closest('tr').find('td').eq(1).text().replace('€', '');
        var token = localStorage.getItem('access_token');
        var social = localStorage.getItem('social');
        if (social === null) {
            social = "";
        }
        data = { 'service': service,'token':token,'social':social,'price': price, 'op': 'cart_plus' };
        console.log(data);
        ajaxPromise(
            'POST', 
            'JSON', 
            friendlyURL('?module=cart'),
            data
        ).then(function (data) {
            if (data === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Only one appointment may be requested per property'
                });
            }else{
                location.reload();
            }
        }).catch(function (e) {
            console.error(e);
        });
    });
    $(document).on('click', '.delete', function(){
        var service = $(this).closest('tr').find('td').eq(0).text();
        var token = localStorage.getItem('access_token');
        var social = localStorage.getItem('social');
        if (social === null) {
            social = "";
        }
        data = { 'service': service,'token':token,'social':social, 'op': 'cart_delete' };
        // console.log(data);
        // return false;
        ajaxPromise(
            'POST', 
            'JSON', 
            friendlyURL('?module=cart'),
            data
        ).then(function (data) {
            location.reload();
        }).catch(function (e) {
            console.error(e);
        });
    });
    $(document).on('click', '#purchase', function(){
        check_cart();
    });
    click_form_inv();
}
function validate_invoicement_form() {
    var error = false;

    if(document.getElementById('name').value.length === 0){
        document.getElementById('nameError').innerHTML = "Enter your name";
        error = true;
    } else {
        document.getElementById('nameError').innerHTML = "";
    }

    if(document.getElementById('surname').value.length === 0){
        document.getElementById('surnameError').innerHTML = "Enter your surname";
        error = true;
    } else {
        document.getElementById('surnameError').innerHTML = "";
    }

    if(document.getElementById('address').value.length === 0){
        document.getElementById('addressError').innerHTML = "Enter your address";
        error = true;
    } else {
        document.getElementById('addressError').innerHTML = "";
    }

    if(document.getElementById('city').value.length === 0){
        document.getElementById('cityError').innerHTML = "Enter your city";
        error = true;
    } else {
        document.getElementById('cityError').innerHTML = "";
    }

    if(document.getElementById('zip').value.length === 0){
        document.getElementById('zipError').innerHTML = "Enter your zip code";
        error = true;
    } else {
        document.getElementById('zipError').innerHTML = "";
    }

    if(document.getElementById('country').value.length === 0){
        document.getElementById('countryError').innerHTML = "Enter your country";
        error = true;
    } else {
        document.getElementById('countryError').innerHTML = "";
    }

    if(document.getElementById('pay_method').value.length === 0){
        document.getElementById('pay_methodError').innerHTML = "Enter your pay method";
        error = true;
    } else { 
        document.getElementById('pay_methodError').innerHTML = "";
    }

    if(error == true){
        return 0;
    }
}
function click_form_inv(){
    $("#form_inv_button").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
        	e.preventDefault();
            purchase();
        }
    });
    $('#form_inv_button').on('click', function(e) {
        e.preventDefault();
        purchase();
    }); 
}
function purchase(){
    var token = localStorage.getItem('access_token');
    var social = localStorage.getItem('social');
    if (social === null) {
        social = "";
    }
    if(validate_invoicement_form() != 0){
        var name = $('#name').val();
        var surname = $('#surname').val();
        var address = $('#address').val();
        var city = $('#city').val();
        var zip = $('#zip').val();
        var country = $('#country').val();
        var pay_method = $('#pay_method').val();
        var total = localStorage.getItem('total');
        var form = { 'name':name, 'surname':surname, 'address':address, 'city':city, 'zip':zip, 'country':country, 'pay_method':pay_method, 'total':total};
        var data = { 'token':token,'social':social, 'op': 'purchase', form };
        ajaxPromise(
            'POST',
            'JSON',
            friendlyURL("?module=cart"),
            data
        ).then(function(data) {
            console.log(data);
            if (data === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'You must add a service to the cart'
                });
                return false;
            }else{
                localStorage.removeItem('total');
                Swal.fire({
                    icon: 'success',
                    title: 'Congrats',
                    text: 'Your order has been processed, here you can see a preview. You will be redirected to your profile in 15 seconds where you can download it in pdf.',
                    showConfirmButton: true,
                    timer: 30000
                }).then(function() {
                    $('#form_inv').hide();
                    $('#name_inv').html('<strong>Name:</strong> ' + data.purchases[0].name);
                    $('#surname_inv').html('<strong>Surname:</strong> ' + data.purchases[0].surname);
                    $('#address_inv').html('<strong>Address:</strong> ' + data.purchases[0].address);
                    $('#city_inv').html('<strong>City:</strong> ' + data.purchases[0].city);
                    $('#zip_inv').html('<strong>Zip:</strong> ' + data.purchases[0].zip);
                    var servicesHtml = '';
                    data.orders.forEach(function(order) {
                        servicesHtml += '<tr><td>' + order.service + '</td><td>' + order.quantity + '</td><td>' + order.price + '€</td></tr>';
                    });
                    $('#services-table tbody').html(servicesHtml);
                    $('#total-price').html(data.purchases[0].total + '€');

                    $('#invoice').show();
                    $('html, body').animate({
                        scrollTop: $('#invoice').offset().top
                    }, 0);
                    setTimeout(function() {
                        window.location.href = friendlyURL('?module=profile');
                    }, 15000);
                });
            }
        }).catch(function(e) {
            console.error(e);
        });
    }
}
function check_cart(){
    var token = localStorage.getItem('access_token');
    var social = localStorage.getItem('social');
    if (social === null) {
        social = "";
    }
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL("?module=cart"),
        { 'op': 'check_cart', 'token':token,'social':social}
    ).then(function(data) {
        if (data === 'error') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'You must add a service to the cart'
            });
            return false;
        }else{
            console.log(data);
            $('#hiddenForm').submit();
        }
    }).catch(function(e) {
        console.error(e);
    });
}
$(document).ready(function(){
    select_services();
    cart_user();
    click_cart();
});