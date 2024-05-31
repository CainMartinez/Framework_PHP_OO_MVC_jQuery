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
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Service added correctly to the cart'
                }).then(function() {
                    location.reload();
                });
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
                        '<button id="'+ data[row].id+'_min" class="btn btn-secondary">-1</button>'+
                        '<button id="'+ data[row].id +'_plus" class="btn btn-success">+1</button>'+
                        '<button id="'+ data[row].id +'_del"class="btn btn-danger">Delete</button>'+
                    '</td>';
            html += '</tr>';
        }
        html += '<tr class="table-secondary"><td colspan="3">Total</td><td>' + total.toFixed(2) + '€</td></tr>';
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
    
}
$(document).ready(function(){
    select_services();
    cart_user();
    click_cart();
});