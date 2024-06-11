// ------------------- LOGIN ------------------------ //
function click_login(){
    $("#loginForm").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13){
            e.preventDefault();
            login();
        }
    });
    $("#loginForm").on('submit', function(e) {
        e.preventDefault();
        login();
    });
    $('#google').on('click', function(e) {
        e.preventDefault();
        social_login('google');
    }); 
    $('#github').on('click', function(e) {
        e.preventDefault();
        social_login('github');
    }); 
    $("#showRegisterForm").click(function() {
        $("#title_property_login").hide();
        $("#title_property_register").show();
    });
    $("#showLoginForm").click(function() {
        $("#title_property_register").hide();
        $("#title_property_login").show();
    });
    $("#showRecoverForm").click(function() {
        $("#title_property_login").hide();
        $("#title_property_recover").show();
    });
    $("#showLoginFormFromRecover").click(function() {
        $("#title_property_recover").hide();
        $("#title_property_login").show();
    });
}

function validate_login(){
    var error = false;
	if(document.getElementById('usernameLogin').value.length === 0){
		document.getElementById('errorUsernameLogin').innerHTML = "Enter your username, please.";
		error = true;
	}else{
        document.getElementById('errorUsernameLogin').innerHTML = "";
    }
	if(document.getElementById('passwordLogin').value.length === 0){
		document.getElementById('errorPasswordLogin').innerHTML = "Enter your password, please.";
		error = true;
	}else{
        document.getElementById('errorPasswordLogin').innerHTML = "";
    }
    if(error == true){
        return 0;
    }
}

function login(){
    if(validate_login() != 0){
        var username = document.getElementById("usernameLogin").value;
        var password = document.getElementById("passwordLogin").value; 
        var data = {username,password, op: "login"};
        ajaxPromise(
            'POST',
            'JSON',
            friendlyURL("?module=auth"),
            data
        ).then(function(result) {
            if(result == "error_username"){	
                $("#errorUsernameLogin").html("The username does't exist");
            } else if (result == "error_password"){
                $("#errorPasswordLogin").html('Wrong password');
            } else if (result == "error_active"){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Your account is not activate. Verify your spam folder or send the email again',
                    showConfirmButton: true,
                    timer: 30000
                })
            } else if(result === "error_count"){    
                Swal.fire({
                    icon: 'error',
                    title: 'Account closed for security reasons!',
                    showConfirmButton: true,
                    confirmButtonText: 'Please, check WhatsApp for recover your account',
                    timer: 3000
                }).then(() => {
                    window.location.href = friendlyURL("?module=auth&op=otp_view");
                });
            }else if(result === "error_otp_send"){
                Swal.fire({
                    icon: 'error',
                    title: 'Error sending the OTP',
                    showConfirmButton: true,
                    timer: 3000
                });
            }else if(result === "error_otp_insert"){
                Swal.fire({
                    icon: 'error',
                    title: 'Error inserting the OTP',
                    showConfirmButton: true,
                    timer: 3000
                });
            }else {
                var token = JSON.parse(result);
                localStorage.setItem("access_token", token[0]);
                localStorage.setItem("refresh_token", token[1]);
                Swal.fire({
                    icon: 'success',
                    title: 'Login success',
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    localStorage.setItem("username", username);
                    console.log(username);
                    setTimeout('window.location.href = friendlyURL("?module=home")', 1000);
                });
            }	
        }).catch(function(e) {
            console.error("Error en la promesa:", e);
        });     
    }
}
// ------------------ SOCIAL LOGIN ----------------- //
function social_login(param){
    authService = firebase_config();
    authService.signInWithPopup(provider_config(param))
    .then(function(result) {
        console.log('Hemos autenticado al usuario ', result.user);
        email_name = result.user.email;
        let username = email_name.split('@');
        console.log(username[0]);

        data = {id: result.user.uid, username: username[0], email: result.user.email, avatar: result.user.photoURL, op: "social_auth", social: param};
        console.log(data);
        if (result) {
            localStorage.setItem("social", param);
            localStorage.setItem("username_profile", username[0])
            ajaxPromise(
                'POST',
                'JSON',
                friendlyURL("?module=auth"),
                data
            ).then(function(data) {
                var token = JSON.parse(data);
                localStorage.setItem("access_token", token[0]);
                localStorage.setItem("refresh_token", token[1]);
                Swal.fire({
                    icon: 'success',
                    title: 'Login success',
                    showConfirmButton: true,
                    timer: 3000
                }).then(() => {
                    setTimeout('window.location.href = friendlyURL("?module=home")', 1000);
                });
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

    var config = window.firebaseConfig;
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
            friendlyURL("?module=auth"),
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
                    window.location.href = friendlyURL("?module=auth");
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
            friendlyURL('?module=auth'),
            data
        ).then(function(data) {
            console.log(data);
            // return false;
            if(data == "error"){		
                $("#errorEmailRecover").html("The email doesn't exist");
            }else if(data == "fatal error"){
                Swal.fire({
                    title: 'Fatal Error',
                    text: 'Contact with the administrator',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                })
            } else{
                Swal.fire({
                    title: 'Success',
                    text: 'Email sended',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = friendlyURL("?module=auth");
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
    data = {token_email: token_email, op: "verify_token"};
    ajaxPromise(
        'POST',
        'JSON',
        friendlyURL('?module=auth'),
        data
    ).then(function(data) {
        if(data == "verify"){
            click_new_password(token_email); 
        }else {
            console.log("error");
        }
    }).catch(function(e) {
        console.error("Error en la promesa:", e);
    });    
}

function click_new_password(token_email){
    $("#new_password").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
        	e.preventDefault();
            send_new_password(token_email);
        }
    });

    $('#new_password').on('click', function(e) {
        e.preventDefault();
        send_new_password(token_email);
    }); 
}

function validate_new_password(){
    var error = false;

    if(document.getElementById('newpassword').value.length === 0){
		document.getElementById('errorNewpassword').innerHTML = "You have to write a password";
		error = true;
	}else{
        if(document.getElementById('newpassword').value.length < 8){
            document.getElementById('errorNewpassword').innerHTML = "The password must be longer than 8 characters";
            error = true;
        }else{
            document.getElementById('errorNewpassword').innerHTML = "";
        }
    }

    if(document.getElementById('passwordRecoverRepeat').value != document.getElementById('newpassword').value){
		document.getElementById('errorpasswordRecoverRepeat').innerHTML = "Passwords don't match";
		error = true;
	}else{
        document.getElementById('errorpasswordRecoverRepeat').innerHTML = "";
    }

    if(error == true){
        return 0;
    }
}

function send_new_password(token_email){
    if(validate_new_password() != 0){
        var data = {
            token_email: token_email, 
            op : "new_password",
            password : $('#newpassword').val()};
        ajaxPromise(
            'POST',
            'JSON',
            friendlyURL("?module=auth"),
            data
        ).then(function(data) {
            if(data == "success"){
                Swal.fire({
                    title: 'Success',
                    text: 'New password changed',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then((result) => {
                    window.location.href = friendlyURL("?module=auth");
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Error setting new password',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                })
            }
        }).catch(function(e) {
            console.error("Error en la promesa:", e);
        });    
    }
}
// --------------------------- OTP --------------------------- //
function click_otp(){
    $("#otp_form").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
        	e.preventDefault();
            send_otp();
        }
    });

    $('#otp_form').on('click', function(e) {
        e.preventDefault();
        send_otp();
    }); 
}
function send_otp(){
    if(validate_otp() != 0){
        var code1 = document.getElementById('code1').value;
        var code2 = document.getElementById('code2').value;
        var code3 = document.getElementById('code3').value;
        var code4 = document.getElementById('code4').value;
        var otp_code = code1 + code2 + code3 + code4;
        var data = {
            otp_code : otp_code,
            op : "otp"
        };
        console.log(data);
        ajaxPromise(
            'POST',
            'JSON',
            friendlyURL("?module=auth"),
            data
        ).then(function(data) {
            if(data == "error"){
                Swal.fire({
                    title: 'Error',
                    text: 'The OTP is incorrect',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                })
            }else {
                Swal.fire({
                    title: 'Success',
                    text: 'OTP correct, account activated successfully but we recommend to change the password in the recover password section',
                    icon: 'success',
                    timer: 30000,
                    showConfirmButton: true
                }).then((result) => {
                    window.location.href = friendlyURL("?module=auth");
                });
            }
        }).catch(function(e) {
            console.error("Error en la promesa:", e);
        });    
    }
}
function validate_otp(){
    var error = false;
    if(document.getElementById('code1').value.length === 0 && document.getElementById('code2').value.length === 0 && document.getElementById('code3').value.length === 0 && document.getElementById('code4').value.length === 0){
        document.getElementById('errorOtp').innerHTML = "Enter the OTP";
        error = true;
    }else{
        document.getElementById('errorOtp').innerHTML = "";
    }
    if(error == true){
        return 0;
    }
}
$(document).ready(function(){
    click_login();
    click_register();
    click_recover_password();
    click_otp();
});