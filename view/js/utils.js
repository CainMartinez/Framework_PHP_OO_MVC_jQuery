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
    var access_token = localStorage.getItem('access_token');
    if (access_token) {
        var social = localStorage.getItem('social');
        if (social === null) {
            social = "";
        }
        var username = localStorage.getItem('username');
        if (username === null) {
            username = "";
        }
        data = { 'access_token': access_token, 'op': 'data_user', 'social': social, 'username': username};
        console.log(data);
        ajaxPromise(
            'POST',
            'JSON',
            friendlyURL("?module=auth"),
            data
        ).then(function(data) {
            console.log(data);
            // return false;
            console.log("Client logged");
            $('#login_button').hide();
            $('<li></li>').attr({'class' : 'rd-nav-item'}).html('<a href="' + friendlyURL("?module=shop") + '" class="rd-nav-link">Shop</a>').prependTo('.rd-navbar-nav');
            $('<li></li>').attr({'class' : 'rd-nav-item'}).html('<a href="' + friendlyURL("?module=home") + '" class="rd-nav-link button_homepage">Home</a>').prependTo('.rd-navbar-nav');
            $('<li></li>').attr({'id' : 'login_ok', 'class' : 'rd-nav-item'}).html(
                '<img src="' + data[0].avatar + '" alt="User Avatar" class="img-thumbnail" style="width:50px; height:50px;">&nbsp;&nbsp;&nbsp;' + 
                '<span class="username btn btn-info">' + data[0].username + '</span>&nbsp;&nbsp;&nbsp;' + 
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
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL('?module=auth'),
        {op: 'logout'}
    ).then(function(data) {
        localStorage.removeItem('refresh_token');
        localStorage.removeItem('access_token');
        localStorage.removeItem('username');
        localStorage.removeItem('social');
        window.location.href = friendlyURL("?module=home");
    }).catch(function(e) {
        console.error(e);
    });
}
function protecturl() {
    var access_token = localStorage.getItem('access_token');
    var refresh_token = localStorage.getItem('refresh_token');
    data = { 'access_token': access_token, 'refresh_token': refresh_token, 'op': 'controluser'};
    ajaxPromise(
        'POST', 
        'JSON',
        friendlyURL('?module=auth'),
        data
    ).then(function(data) {
        if (data == "Correct_User") {
        } else if (data == "Wrong_User") {
            logout_auto();
        }
    }).catch(function(e){
        // console.error(e); 
    });
}
function control_activity() {
    var access_token = localStorage.getItem('access_token');
    if (access_token) {
        ajaxPromise(
            'POST', 
            'JSON',
            friendlyURL('?module=auth'),
            {'op': 'activity'}
        ).then(function(response) {
            if (response == "inactivo") {
                console.log("usuario INACTIVO");
                logout_auto();
            } else {
                console.log("usuario ACTIVO")
            }
        }).catch(function(e) {
            // console.error(e);
        });
    } else {
        console.log("No hay usario logeado");
    }
}
function refresh_cookie() {
    ajaxPromise(
        'POST', 
        'JSON',
        friendlyURL('?module=auth'),
        {'op': 'refresh_cookie'}
    ).then(function(response) {
        console.log("Refresh cookie correctly");
    }).catch(function(e) {
        // console.error(e);
    });
}
function logout_auto() {
    ajaxPromise(
        'POST', 
        'JSON',
        friendlyURL('?module=auth'),
        {op:'logout'}
    ).then(function(data) {
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
    }).catch(function(e) {
        console.error(e);
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
    click_logout();
    protecturl();
    setInterval(function () { control_activity() }, 6000); 
    setInterval(function () { refresh_cookie() }, 6000);
});