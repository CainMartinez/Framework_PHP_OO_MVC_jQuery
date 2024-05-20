// ------------------- LOGIN ------------------------ //
function click_login(){
    $("#login_form").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13){
            e.preventDefault();
            login();
        }
    });
    
    $('#button_login').on('click', function(e) {
        e.preventDefault();
        login();
    }); 

    $('#forget_pass').on('click', function(e) {
        e.preventDefault();
        load_form_recover_password();
    }); 

    $('#google').on('click', function(e) {
        social_login('google');
    }); 

    $('#github').on('click', function(e) {
        social_login('github');
    }); 
    $(document).ready(function() {
    $("#showRegisterForm").click(function() {
        $("#title_property_login").hide();
        $("#title_property_register").show();
    });

    $("#showLoginForm").click(function() {
        $("#title_property_register").hide();
        $("#title_property_login").show();
    });
    $(document).ready(function() {
    $("#showRecoverForm").click(function() {
        $("#title_property_login").hide();
        $("#title_property_recover").show();
    });

    $("#showLoginFormFromRecover").click(function() {
        $("#title_property_recover").hide();
        $("#title_property_login").show();
    });
});
});
}

function validate_login(){
    var error = false;

	if(document.getElementById('username').value.length === 0){
		document.getElementById('error_username').innerHTML = "You have to type the user";
		error = true;
	}else{
        document.getElementById('error_username').innerHTML = "";
    }
	
	if(document.getElementById('pass').value.length === 0){
		document.getElementById('error_password').innerHTML = "You have to type the password";
		error = true;
	}else{
        document.getElementById('error_password').innerHTML = "";
    }
	
    if(error == true){
        return 0;
    }
}

function login(){
    if(validate_login() != 0){
        var data = $('#login_form').serialize();
        $.ajax({
            url: friendlyURL("?module=login&op=login"),
            dataType: "JSON",
            type: "POST",
            data: data,
        }).done(function(result) {
            if(result == "user error"){		
                $("#error_username").html("The email or username does't exist");
            } else if (result == "error"){
                $("#error_password").html('Wrong password');
            } else if (result == "activate error"){
                toastr.options.timeOut = 3000;
                toastr.error("Verify the email");            
            } else {
                localStorage.setItem("token", result);
                toastr.options.timeOut = 3000;
                toastr.success("Inicio de sesión realizado");
                if(localStorage.getItem('likes') == null) {
                    setTimeout('window.location.href = friendlyURL("?module=home&op=view")', 1000);
                } else {
                    console.log(localStorage.getItem('product'));
                    setTimeout('window.location.href = friendlyURL("?module=shop&op=view")', 1000);
                }
            }	
        }).fail(function() {
            console.log('Error: Login error');
        });     
    }
}

function social_login(param){
    authService = firebase_config();
    authService.signInWithPopup(provider_config(param))
    .then(function(result) {
        console.log('Hemos autenticado al usuario ', result.user);
        email_name = result.user.email;
        let username = email_name.split('@');
        console.log(username[0]);

        social_user = {id: result.user.uid, username: username[0], email: result.user.email, avatar: result.user.photoURL};
        if (result) {
            ajaxPromise(friendlyURL("?module=login&op=social_login"), 'POST', 'JSON', social_user)
            .then(function(data) {
                localStorage.setItem("token", data);
                toastr.options.timeOut = 3000;
                toastr.success("Inicio de sesión realizado");
                if(localStorage.getItem('likes') == null) {
                    setTimeout('window.location.href = friendlyURL("?module=home&op=view")', 1000);
                } else {
                    setTimeout('window.location.href = friendlyURL("?module=shop&op=view")', 1000);
                }
            })
            .catch(function() {
                console.log('Error: Social login error');
            });
        }
    })
    .catch(function(error) {
        var errorCode = error.code;
        console.log(errorCode);
        var errorMessage = error.message;
        console.log(errorMessage);
        var email = error.email;
        console.log(email);
        var credential = error.credential;
        console.log(credential);
    });
}

function firebase_config(){
    var config = {
        apiKey: "AIzaSyBOo5emMZXMi0T411OPKgoDGcvDl_IKSno",
        authDomain: "test-php-js-7fc12.firebaseapp.com",
        projectId: "test-php-js-7fc12",
        storageBucket: "test-php-js-7fc12.appspot.com",
        messagingSenderId: "495514694215",
        appId: "1:495514694215:web:b183cd7f513ce8b0d6f762",
        measurementId: "G-JXEGLTGLTC"
    };
    if(!firebase.apps.length){
        firebase.initializeApp(config);
    }else{
        firebase.app();
    }
    return authService = firebase.auth();
}

function provider_config(param){
    if(param === 'google'){
        var provider = new firebase.auth.GoogleAuthProvider();
        provider.addScope('email');
        return provider;
    }else if(param === 'github'){
        return provider = new firebase.auth.GithubAuthProvider();
    }
}

// ------------------- REGISTER ------------------------ //
function click_register(){
	$("#register_form").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13){
        	e.preventDefault();
            register();
        }
    });

	$('#register').on('click', function(e) {
        e.preventDefault();
        register();
    }); 
}

function validate_register(){
    var mail_exp = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var error = false;

    if(document.getElementById('usernameRegister').value.length === 0){
        document.getElementById('errorUsername').innerHTML = "Enter your user name";
        error = true;
    }else{
        if(document.getElementById('usernameRegister').value.length > 15 || document.getElementById('usernameRegister').value.length < 5){
            document.getElementById('errorUsername').innerHTML = "The username must be between 5 and 15 characters";
            error = true;
        }else{
            document.getElementById('errorUsername').innerHTML = "";
        }
    }

    if(document.getElementById('passwordRegister').value.length === 0){
        document.getElementById('errorPassword').innerHTML = "Enter your user password";
        error = true;
    }else{
        if(document.getElementById('passwordRegister').value.length < 8){
            document.getElementById('errorPassword').innerHTML = "The password must be longer than 8 characters";
            error = true;
        }else{
            document.getElementById('errorPassword').innerHTML = "";
        }
    }

    if(document.getElementById('passwordRepeatRegister').value != document.getElementById('passwordRegister').value){
        document.getElementById('errorRepeatPassword').innerHTML = "Passwords don't match";
        error = true;
    }else{
        document.getElementById('errorRepeatPassword').innerHTML = "";
    }

    if(document.getElementById('emailRegister').value.length === 0){
        document.getElementById('errorMail').innerHTML = "Enter your email";
        error = true;
    }else{
        if(!mail_exp.test(document.getElementById('emailRegister').value)){
            document.getElementById('errorMail').innerHTML = "The email format is invalid"; 
            error = true;
        }else{
            document.getElementById('errorMail').innerHTML = "";
        }
    }
    
    if(error == true){
        return 0;
    }
}

function register(){
    if(validate_register() != 0){
        var data = []
        var usernameRegister = document.getElementById("usernameRegister").value;
        var emailRegister = document.getElementById("emailRegister").value;
        var passwordRegister = document.getElementById("passwordRegister").value;
        var op = "register";
        data = {usernameRegister, emailRegister, passwordRegister, op};

        ajaxPromise(
            "POST",
            "JSON",
            friendlyURL("?module=login"),
            data
        ).then(function(result) {  
            console.log("entra en el then");
            console.log(result);
            if(result.message === "User already exists"){
                Swal.fire({
                    icon: 'error',
                    title: 'Email or username already exists',
                    text: 'Try again with another email or username',
                    showConfirmButton: true,
                    timer: 2000
                });
            }else {
                Swal.fire({
                    icon: 'success',
                    title: 'Email sent',
                    text: 'Verify your email to activate your account',
                    showConfirmButton: true,
                    timer: 2000
                }).then(() => {
                    window.location.href = friendlyURL("?module=login");
                });
            }   
        }).catch(function(e) {
            console.error("Error en la promesa:", e);
        }); 
    }
}

// ------------------- RECOVER PASSWORD ------------------------ //

function click_recover_password(){
    $("#recover_button").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
        	e.preventDefault();
            send_recover_password();
        }
    });

    $('#recover_button').on('click', function(e) {
        e.preventDefault();
        send_recover_password();
    }); 
}

function validate_recover_password(){
    var mail_exp = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var error = false;

    if(document.getElementById('emailRecover').value.length === 0){
		document.getElementById('errorEmailRecover').innerHTML = "You have to write an email";
		error = true;
	}else{
        if(!mail_exp.test(document.getElementById('emailRecover').value)){
            document.getElementById('errorEmailRecover').innerHTML = "The email format is invalid"; 
            error = true;
        }else{
            document.getElementById('errorEmailRecover').innerHTML = "";
        }
    }
	
    if(error == true){
        return 0;
    }
}

function send_recover_password(){
    if(validate_recover_password() != 0){
        var email = document.getElementById('emailRecover').value;
        var data = {
            email : email,
            op : "recover"
        };
        console.log(data);
        ajaxPromise(
            'POST',
            'JSON',
            friendlyURL('?module=login'),
            data
        ).then(function(data) {
            console.log(data);
            // return false;
            if(data == "error"){		
                $("#error_email_forg").html("The email doesn't exist");
            } else{
                Swal.fire({
                    title: 'Success',
                    text: 'Email sended',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = friendlyURL("?module=login");
                    }   
                });
            }
        }).catch(function(e) {
            console.error("Error en la promesa:", e);
        });    
    }
}

function load_form_new_password(){
    token_email = localStorage.getItem('token_email');
    localStorage.removeItem('token_email');
    $.ajax({
        url: friendlyURL('?module=login&op=verify_token'),
        dataType: 'json',
        type: "POST",
        data: {token_email: token_email},
    }).done(function(data) {
        if(data == "verify"){
            click_new_password(token_email); 
        }else {
            console.log("error");
        }
    }).fail(function( textStatus ) {
        console.log("Error: Verify token error");
    });    
}

function click_new_password(token_email){
    $(".recover_html").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
        	e.preventDefault();
            send_new_password(token_email);
        }
    });

    $('#button_set_pass').on('click', function(e) {
        e.preventDefault();
        send_new_password(token_email);
    }); 
}

function validate_new_password(){
    var error = false;

    if(document.getElementById('pass_rec').value.length === 0){
		document.getElementById('error_password_rec').innerHTML = "You have to write a password";
		error = true;
	}else{
        if(document.getElementById('pass_rec').value.length < 8){
            document.getElementById('error_password_rec').innerHTML = "The password must be longer than 8 characters";
            error = true;
        }else{
            document.getElementById('error_password_rec').innerHTML = "";
        }
    }

    if(document.getElementById('pass_rec_2').value != document.getElementById('pass_rec').value){
		document.getElementById('error_password_rec_2').innerHTML = "Passwords don't match";
		error = true;
	}else{
        document.getElementById('error_password_rec_2').innerHTML = "";
    }

    if(error == true){
        return 0;
    }
}

function send_new_password(token_email){
    if(validate_new_password() != 0){
        var data = {token_email: token_email, password : $('#pass_rec').val()};
        $.ajax({
            url: friendlyURL("?module=login&op=new_password"),
            type: "POST",
            dataType: "JSON",
            data: data,
        }).done(function(data) {
            if(data == "done"){
                toastr.options.timeOut = 3000;
                toastr.success('New password changed');
                setTimeout('window.location.href = friendlyURL("?module=login&op=view")', 1000);
            } else {
                toastr.options.timeOut = 3000;
                toastr.error('Error seting new password');
            }
        }).fail(function(textStatus) {
            console.log("Error: New password error");
        });    
    }
}
$(document).ready(function(){
    click_login();
    click_register();
    click_recover_password();
});