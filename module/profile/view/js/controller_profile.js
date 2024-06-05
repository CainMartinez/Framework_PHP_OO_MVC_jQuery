function clicks_profile() {  

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
$(document).ready(function () {
    // clicks_profile();
    profile_data();
});