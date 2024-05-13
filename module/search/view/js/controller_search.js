var selectedCategory;
function load_search_city() {
    ajaxPromise('POST', 'JSON',friendlyURL('?module=search'),{op:'dynamic_search_city'})
        .then(function (data) {
            // console.log(data);
            // return data;
            for (let row in data) {
                let city = data[row];
                $('#search_city').append('<option value="' + city.id_city + '">' + city.name_city + '</option>');
            }
            // console.log(data);
        }).catch(function (e) {
            console.error(e);
        });
}
function load_search_type(data = undefined) {
    $('#search_type').empty();
    let ajaxData = {};
    let ajaxUrl = friendlyURL('?module=search');

    if (data !== undefined) {
        ajaxData = {
            id_city: data['id_city'],
            op: 'dynamic_search_type'
        };
    } else {
        ajaxData = {
            op: 'search_type'
        };
    }

    ajaxPromise('POST', 'JSON', ajaxUrl, ajaxData)
        .then(function (data) {
            // console.log(data);
            // return;
            $('<option>Select Type</option>').attr('selected', true).attr('disabled', true).appendTo('#search_type');
            for (let row in data) {
                let type = data[row];
                $('#search_type').append('<option value="' + type.id_type + '">' + type.name_type + '</option>');
            }
        }).catch(function (e) {
            console.error(e);
        });
}
function launch_search(){
    load_search_city();
    load_search_type();
    $('#search_city').on('change', function(){
        let id_city = $(this).val();
        if (id_city === 0) {
            load_search_type();
        }else {
            load_search_type({id_city});
        }
    });
}
function autocomplete(){
    $("#autocom").on("keyup", function () {
        $('#search_results').css('display', 'block')
        let auto_complete_data = {complete: $(this).val()};
        // console.log(auto_complete_data);
        if (($('#search_type').val() != 0)){
            auto_complete_data.id_type = $('#search_type').val();
            // console.log(auto_complete_data.id_type);
            if(($('#search_type').val() != 0) && ($('#search_city').val() != 0)){
                auto_complete_data.id_city = $('#search_city').val();
                // console.log(auto_complete_data.id_city);
            }
        }
        if(($('#search_type').val() == 0) && ($('#search_city').val() != 0)){ 
            auto_complete_data.id_city = $('#search_city').val();
        }
        console.log(auto_complete_data);
        ajaxPromise('POST', 'JSON',friendlyURL('?module=search'),{op: 'autocomplete', auto_complete_data})
        .then(function(data) {
            $('#search_results').empty();
            $('#search_results').fadeIn(10000000);
            for (row in data) {
                $('<li></li>').appendTo('#search_results').html(data[row].name_category).attr({'class': 'list-group-item list-group-item-action search_element', 'id': data[row].id_category});
            }
            $(document).on('click', '.search_element', function() {
                $('#autocom').val($(this).text());
                selectedCategory = $(this).attr('id');
                $('#search_results').fadeOut(1000);
            });
        }).catch(function(e) {
            console.error(e);
            $('#search_results').fadeOut(500);
        });
    });
}
function search_button() {
    $('#search_button').on('click', function() {
        var search = {};
        var shop = {};
        let selectedCity = $('#search_city').val();
        let selectedType = $('#search_type').val();
        if(selectedCity && selectedCity != "") {
            search["id_city"] = selectedCity;
        }
        if(selectedType && selectedType != "") {
            search["id_type"] = selectedType;
        }
        if(selectedCategory && selectedCategory != "") {
            search["id_category"] = selectedCategory;
        }
        if(selectedCity && selectedCity != "") {
            shop["id_city"] = selectedCity;
        }
        if(selectedType && selectedType != "") {
            shop["id_type"] = selectedType;
        }
        if(selectedCategory && selectedCategory != "") {
            shop["id_category"] = selectedCategory;
        }
        if(selectedCity && selectedCity != "") {
            localStorage.setItem('selectedCity', selectedCity);
            $('#search_city').val(selectedCity);
            $('#search_city').addClass('selected_filters');
        } else {
            localStorage.removeItem('selectedCity');
            $('#search_city').removeClass('selected_filters');
        }
        if(selectedType && selectedType != "") {
            localStorage.setItem('selectedType', selectedType);
            $('#search_type').val(selectedType);
            $('#search_type').addClass('selected_filters');
        } else {
            localStorage.removeItem('selectedType');
            $('#search_type').removeClass('selected_filters');
        }
        if(selectedCategory && selectedCategory != "") {
            localStorage.setItem('selectedCategory', selectedCategory);
            $('#autocom').val($(this).text());
            $('#autocom').addClass('selected_filters');
        } else {
            localStorage.removeItem('selectedCategory');
            $('#autocom').removeClass('selected_filters');
        }
        if(Object.keys(search).length != 0){
            localStorage.setItem('filters_shop', JSON.stringify(shop));
            setTimeout(function(){ 
                window.location.href = 'index.php?page=shop';
            }, 1000);
        }
    });
}
function clear_localstorage() {
    $('.button_homepage').click(function(event) {
        remove_filters();
        location.reload();
    });
    if(window.location.href.indexOf('/home') != -1) {
        remove_filters();
    }
}
function remove_filters() {
    localStorage.removeItem('filters_shop');
    localStorage.removeItem('selectedCategory');
    localStorage.removeItem('selectedCity');
    localStorage.removeItem('selectedExtras');
    localStorage.removeItem('selectedOperation');
    localStorage.removeItem('selectedType');
    localStorage.removeItem('selectedLargePeople');
    localStorage.removeItem('currentPage');
    localStorage.removeItem('offset');
    localStorage.removeItem('order');
    localStorage.removeItem('details_home');
}
$(document).ready(function() {
    launch_search();
    autocomplete();
    search_button();
    clear_localstorage();
});