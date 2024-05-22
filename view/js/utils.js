function ajaxPromise(sType, sTData, sUrl, sData = undefined) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: sUrl,
            type: sType,
            dataType: sTData,
            data: sData
        }).done((data) => {
            resolve(data);
        }).fail((jqXHR, textStatus, errorThrow) => {
            reject(errorThrow);
        }); 
    });
};
function load_menu() {
    var refresh_token = localStorage.getItem('refresh_token');
    if (refresh_token) {
        console.log(refresh_token);
        ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=data_user', { 'refresh_token': refresh_token })
        .then(function(data) {
            console.log("Client logged");
            // Ocultar los botones de registro y login
            $('#register_button').hide();
            $('#login_button').hide();
            // Agregar el nombre de usuario, la imagen y el botón de logout al menú
            $('<li></li>').attr({'class' : 'rd-nav-item'}).html('<a href="' + friendlyURL("?module=home") + '" class="rd-nav-link button_homepage">Home</a>').appendTo('.rd-navbar-nav');
            $('<li></li>').attr({'class' : 'rd-nav-item'}).html('<a href="' + friendlyURL("?module=shop") + '" class="rd-nav-link">Shop</a>').appendTo('.rd-navbar-nav');
            $('<li></li>').attr({'id' : 'login_ok', 'class' : 'rd-nav-item'}).html(
                '<img src="' + data.avatar + '" alt="User Avatar" class="img-thumbnail" style="width:50px; height:50px;">&nbsp;&nbsp;&nbsp;' + 
                '<span class="username btn btn-info">' + data.username + '</span>&nbsp;&nbsp;&nbsp;' + 
                '<a id="logout" class="btn btn-warning ml-auto">Logout</a>'
            ).appendTo('.rd-navbar-nav');
        }).catch(function(e) {
            console.error(e);
        });
    } else {
        $('<li></li>').attr({'class' : 'rd-nav-item'}).html('<a href="' + friendlyURL("?module=shop") + '" class="rd-nav-link">Shop</a>').prependTo('.rd-navbar-nav');
        $('<li></li>').attr({'class' : 'rd-nav-item'}).html('<a href="' + friendlyURL("?module=home") + '" class="rd-nav-link button_homepage">Home</a>').prependTo('.rd-navbar-nav');
        $('<a></a>').attr({'id' : 'login_button', 'type' : 'button', 'class' : 'btn btn-success', 'href' : friendlyURL("?module=auth")}).html('Login').appendTo('#search_auto');
    }
}
function click_logout() {
    $(document).on('click', '#logout', function() {
        Swal.fire({
            icon: 'success',
            title: 'Logout successfully',
            text: "Come back soon!",
            showConfirmButton: true,
            confirmButtonText: 'OK',
            timer: 3000
        }).then(() => {
            logout();
        })
    });
}
function logout() {
    ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=logout')
        .then(function(data) {
            localStorage.removeItem('refresh_token');
            localStorage.removeItem('access_token');
            window.location.href = "index.php?page=homepage";
        }).catch(function(d) {
            console.log(d);
        });
}
function protecturl() {
    var refresh_token = localStorage.getItem('refresh_token');
    ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=controluser', { 'refresh_token': refresh_token })
        .then(function(data) {
            if (data == "Correct_User") {
            } else if (data == "Wrong_User") {
                checkToken();
            }
        })
        .catch(function() { console.log("ANONYMOUS_user") });
}
function checkToken() {
    var refresh_token = localStorage.getItem('refresh_token');
    ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=check_token', { 'refresh_token': refresh_token })
        .then(function(data) {
            if (data == "Token_Expired") {
                console.log("Token has expired.");
                logout_auto();
            } else {
                console.log("Token is valid.");
            }
        })
        .catch(function() { console.log("Error checking token") });
}
function control_activity() {
    var refresh_token = localStorage.getItem('refresh_token');
    if (refresh_token) {
        ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=activity')
            .then(function(response) {
                if (response == "inactivo") {
                    console.log("usuario INACTIVO");
                    logout_auto();
                } else {
                    console.log("usuario ACTIVO")
                }
            });
    } else {
        console.log("No hay usario logeado");
    }
}
function refresh_token() {
    var token = localStorage.getItem('refresh_token');
    if (token) {
        ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=refresh_token', { 'refresh_token': refresh_token })
            .then(function(data_token) {
                console.log("Refresh token correctly");
                localStorage.setItem("token", data_token);
                load_menu();
            });
    }
}
function check_tokens() {
    let token_refresh = localStorage.getItem('refresh_token');
    let token_large = localStorage.getItem('access_token');
    ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=check_tokens', { 'access_token': access_token, 'refresh_token': refresh_token })
    .then(function(data) {
        data == "not_exp" ? undefined : ( data == "refresh_token_exp" ? (console.log("Token refresh expired"), refresh_token()) : logout_auto() );
    }).catch(function(e) {
        console.log(e);
    });
}
function refresh_cookie() {
    ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=refresh_cookie')
        .then(function(response) {
            console.log("Refresh cookie correctly");
        });
}
function logout_auto() {
    ajaxPromise('POST', 'JSON','module/auth/controller/controller_auth.php?op=logout')
        .then(function(data) {
            localStorage.removeItem('refresh_token');
            localStorage.removeItem('access_token');
            Swal.fire({
                icon: 'warning',
                title: 'Account closed for security reasons!',
                showConfirmButton: true,
                confirmButtonText: 'Log in again',
                timer: 3000
            }).then(() => {
                window.location.href = friendlyURL("?module=auth");
            });;
        }).catch(function(d) {
            console.log(d);
        });
}
function friendlyURL(url) {
    var link = "";
    url = url.replace("?", "");
    url = url.split("&");
    cont = 0;
    for (var i = 0; i < url.length; i++) {
    	cont++;
        var aux = url[i].split("=");
        if (cont == 2) {
        	link += "/" + aux[1] + "/";	
        }else{
        	link += "/" + aux[1];
        }
    }
    link = "http://localhost/living_mobility" + link;
    // console.log(link);
    return link;
}
function load_content() {
    let path = window.location.pathname.split('/');
    
    if(path[3] === 'recover'){
        window.location.href = friendlyURL("?module=auth&op=recover_view");
        localStorage.setItem("token_email", path[4]);
    }else if (path[3] === 'verify') {
        console.log('verify email');
        ajaxPromise( 'POST', 'JSON',friendlyURL("?module=auth"), {token_email: path[4],op: 'verify'})
        .then(function(data) {
            Swal.fire({
                icon: 'success', 
                title: 'Email verified',
                text: "You can now log in",
                showConfirmButton: true,
                timer: 3000
            }).then(() => {
                window.location.href = friendlyURL("?module=auth");
            });
        })
        .catch(function(e) {
            console.error(e);
        });
    }else if (path[3] === "recover_view") {
		load_form_new_password();
	}
}
$(document).ready(function() {
    load_menu();
    load_content();
    // click_logout();
    // setInterval(function() { control_activity() }, 60000); //1min = 60000ms
    // protecturl();
    // setInterval(function() { refresh_token() }, 600000);
    // setInterval(function() { refresh_cookie() }, 600000);
});