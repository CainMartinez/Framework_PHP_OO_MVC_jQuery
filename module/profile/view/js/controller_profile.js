function clicks_profile() {  
    $(".settings").click(function() {
        $("#profile_info").hide();
        $("#profile_orders").hide();
        $("#profile_likes").hide();
        $("#profile_settings").show();
        $('html, body').animate({
            scrollTop: $('.page-header').offset().top
        }, 0);
    });
    $(".orders").click(function() {
        $("#profile_info").hide(); 
        $("#profile_settings").hide();
        $("#profile_likes").hide();
        $("#profile_orders").show();
        $('html, body').animate({
            scrollTop: $('.page-header').offset().top
        }, 0);
        profile_orders();
    });
    $(".likes").click(function() {
        $("#profile_settings").hide();
        $("#profile_orders").hide();
        $("#profile_info").hide();
        $("#profile_likes").show();
        $('html, body').animate({
            scrollTop: $('.page-header').offset().top
        }, 0);
        $("#wish_list_properties").empty();
        wish_list();
    });
    $(document).on('click', '.like_button', function() {
        var id_property = $(this).attr('id');
        token = localStorage.getItem("access_token");
        social = localStorage.getItem("social");
        data = { 'token': token, 'social': social, 'op': 'like', 'id_property': id_property}
        ajaxPromise(
            'POST',
            'JSON',
            friendlyURL('?module=profile'),
            data
        ).then(function(data) {
            console.log(data);
        }).catch(function(e) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'The property has been removed from your wish list.',
                showConfirmButton: true,
                timer: 30000
            }).then(function() {
                location.reload();
            });
        });
    });
    $(document).on('click', '.details_profile', function() {
        var id_property = $(this).attr('id');
        localStorage.setItem('id_property_profile', id_property);
        window.location.href = friendlyURL('?module=shop');
    });
    $(".cart_shop").click(function() {
    });
}
function wish_list() {
    token = localStorage.getItem("access_token");
    social = localStorage.getItem("social");
    data = { 'token': token, 'social': social, 'op': 'check_fav'}
    ajaxPromise(
        'POST', 
        'JSON', 
        friendlyURL('?module=shop'), 
        data
    ).then(function (data) {
        console.log(data);
        if (data.length === 0) {
            // console.log('No properties found in your wish list');
            $('<div></div>').attr({ 'class': 'col wow-outer carrousel_list' }).appendTo('#wish_list_properties');
            $('#wish_list_properties').html('<h4 style="color: black;">No properties found in your wish list</h4>').appendTo('#wish_list_properties');
        } else {
            $('#images_properties').empty();
            for (let row in data) {
                let property = data[row][0];
                let propertyDiv = $('<div></div>').attr({ 'class': 'col wow-outer carrousel_list' }).appendTo('#wish_list_properties');
                let owlCarouselDiv = $('<div></div>').addClass('owl-carousel owl-theme carrousel_details').appendTo(propertyDiv);
                for (let image of property.images) {
                    $("<div></div>").addClass("item").appendTo(owlCarouselDiv).html(
                        "<article class='thumbnail-light'>" +
                        "<a class='thumbnail-light-media' href='#'><img class='thumbnail-light-image' src='" +
                        image.path_images +
                        "' alt='Image " + (parseInt(row) + 1) + "' width='100%' heiht='100%'/></a>" +
                        "</article>"
                    );
                }
                owlCarouselDiv.owlCarousel({
                    loop: true,
                    margin: 100,
                    nav: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                    }
                });
                propertyDiv.append(`
                    <article class='post-modern wow slideInLeft '><br>
                        <h4 class='post-modern-title'>
                            <a class='post-modern-title' href='#'>${property.property_name}</a>
                        </h4>
                        <ul class='post-modern-meta'>
                            <li><a class='button-winona' href='#'>${property.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} €</a></li>
                            <li>City: ${property.name_city}</li>
                            <li>Square meters: ${property.square_meters}</li>
                        </ul>
                        <p>${property.description}</p><br>
                        <div class='buttons'>
                            <table id='table-shop'> 
                                <tr>
                                    <td>
                                        <button id='${property.id_property}' class='details_profile button button-primary button-winona button-md'>Details</button><br>
                                    </td>
                                    <td>
                                        <button id='${property.id_property}' class='cart_shop button button-secondary button-winona button-md cart_shop'>
                                            <i class="fas fa-shopping-cart fa-lg"></i>
                                        </button><br>  
                                    </td>                                      
                                    <td>
                                        <button id='${property.id_property}' class="like_button">
                                            <i class='fas fa-heart'></i>&nbsp;${property.likes}                                            
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </article><hr>
                `);
            }
        }
    }).catch(function (error) {
        console.error(error);
    });
}
function profile_data() {
    // console.log('carga profile_data');
    token = localStorage.getItem("access_token");
    social = localStorage.getItem("social");
    data = { 'token': token, 'social': social, 'op': 'profile_data'}
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL('?module=profile'),
        data
    ).then(function(data) {
        // console.log(data);
        $("#username").html(data[0].username);
        $("#email").html('<strong>Email:</strong> ' + data[0].email);
        $("#avatar").attr('src', data[0].avatar);
    }).catch(function(e) {
        console.log(e);
    });
}
function profile_orders() {
    // console.log('carga profile_orders');
    token = localStorage.getItem("access_token");
    social = localStorage.getItem("social");
    data = { 'token': token, 'social': social, 'op': 'profile_orders'}
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL('?module=profile'),
        data
    ).then(function(data) {
        var ordersTable = document.getElementById('orders_table');
        ordersTable.innerHTML = '';
        if (data === 'error') {
            var row = document.createElement('tr');
            var cell = document.createElement('td');
            cell.textContent = 'No orders found';
            cell.colSpan = 2;
            cell.style.textAlign = 'center';
            row.appendChild(cell);
            ordersTable.appendChild(row);
        } else {
            console.log(data);
            data.forEach(function(order) {
                var row = document.createElement('tr');
                row.id = 'order-' + order.id;

                var numberCell = document.createElement('td');
                numberCell.textContent = order.id;
                row.appendChild(numberCell);

                var dateCell = document.createElement('td');
                dateCell.textContent = order.time;
                row.appendChild(dateCell);

                // Add a click event listener to the row
                row.addEventListener('click', function() {
                    // Load the view for this order
                    loadOrderView(order.id);
                });
                ordersTable.appendChild(row);
            });
        }
    }).catch(function(e) {
        console.log(e);
    });
}
function loadOrderView(order_id) {
    // console.log('carga loadOrderView');
    token = localStorage.getItem("access_token");
    social = localStorage.getItem("social");
    console.log(order_id);
    data = { 'token': token, 'social': social, 'op': 'order_detail', 'order_id': order_id}
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL('?module=profile'),
        data
    ).then(function(data) {
        console.log(data);
        $("#profile_info").hide();
        $("#profile_settings").hide();
        $("#profile_likes").hide();
        $("#profile_orders").hide();
        $("#invoice_profile").show();
        // Fill billing information
        document.getElementById('name_inv').innerHTML = "<strong>Name:</strong> " + data.billing[0].name;
        document.getElementById('surname_inv').innerHTML = "<strong>Surname:</strong> " + data.billing[0].surname;
        document.getElementById('address_inv').innerHTML = "<strong>Address:</strong> " + data.billing[0].address;
        document.getElementById('city_inv').innerHTML = "<strong>City:</strong> " + data.billing[0].city;
        document.getElementById('zip_inv').innerHTML = "<strong>Zip:</strong> " + data.billing[0].zip;

        // Fill purchased services
        var servicesTable = document.getElementById('services-table').getElementsByTagName('tbody')[0];
        data.lines.forEach(function(line) {
            var newRow = servicesTable.insertRow();
            newRow.insertCell(0).innerHTML = line.service;
            newRow.insertCell(1).innerHTML = line.quantity;
            newRow.insertCell(2).innerHTML = line.price;
        });

        // Fill total price
        document.getElementById('total-price').innerHTML = "<strong>Total Price:</strong> " + data.billing[0].total;

    }).catch(function(e) {
        console.error(e);
    });
}
$(document).ready(function () {
    profile_data();
    clicks_profile();
});