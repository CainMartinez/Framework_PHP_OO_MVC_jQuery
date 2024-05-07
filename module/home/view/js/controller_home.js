function carousel_people() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=carrousel_people"
  )
    .then(function (data) {
      let html = "";
      for (row in data) {
        html += `
                <div class="swiper-slide swiper-slide_video carrousel_home" id='${data[row].id_large_people}' style="background-image: url('${data[row].image_people}')">
                    <div class="container">
                        <div class="jumbotron-classic-content">
                            <div class="wow-outer">
                                <div class="title-docor-text font-weight-bold title-decorated text-uppercase wow slideInLeft text-white">${data[row].name_large_people}</div>
                            </div>
                            <h1 class="text-uppercase text-white font-weight-bold wow-outer"><span class="wow slideInDown" data-wow-delay=".2s">Properties</span></h1>
                            <div class="wow-outer button-outer">
                                <a class="button button-md button-primary button-winona wow slideInDown" href="#" data-wow-delay=".4s">View Properties</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
      }
      $(".swiper-wrapper").html(html);
    })
    .then(function () {
      let swiper = new Swiper(".swiper-container", {
        effect: "fade",
        loop: true,
        speed: 3000,
        autoplay: {
          delay: 4000,
        },
      });
    })
    .catch(function (error) {
      console.error(error);
    });
}
function categories() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=categories"
  )
    .then(function (data) {
      console.log(data);
      var rowContainer = $("<div></div>").attr(
        "class",
        "owl-carousel owl-theme"
      );
      for (row in data) {
        $("<div></div>")
          .attr("class", "item")
          .appendTo(rowContainer)
          .html(
            "<article class='thumbnail-light' id= "+ data[row].id_category+">" +
              "<a class='thumbnail-light-media' href='#'><img class='thumbnail-light-image' src='" +
              data[row].image_category +
              "' alt='' width='270' height='300' /></a>" +
              "<h4 class='thumbnail-light-title title-category'><a href='#'>" +
              data[row].name_category +
              "</a></h4>" +
              "</article>"
          );
      }
      $("#category_html").append(rowContainer);

      $(".owl-carousel").owlCarousel({
        loop: true,
        margin: 50,
        nav: true,
        responsive: {
          0: {
            items: 1,
          },
          600: {
            items: 3,
          },
          1000: {
            items: 5,
          },
        },
      });
    })
    .catch(function () {
    });
}
function type() {
  ajaxPromise( 
    "GET",
    "JSON",
    "?module=home&op=type"
  )
  .then(function (data) {
    let html = "";
    for (row in data) {
      html += `
              <div class="col-md-6 wow-outer type_home" id='${data[row].id_type}'>
                  <article class="post-modern wow slideInLeft">
                      <a class="post-modern-media" href="#">
                          <img class="propertyImage" src="${data[row].image_type}" alt="" width="571" height="353"/>
                      </a>
                      <h4 class="post-modern-title title-city">
                          <a class="post-modern-title" href="#">${data[row].name_type}</a>
                      </h4>
                      <ul class="post-modern-meta"></ul>
                  </article>
              </div>
          `;
    }
    $("#propertyType").html(html);
  })
  .catch(function () {
    // window.location.href =
    //   "index.php?module=ctrl_exceptions&page=503&type=503&lugar= Type HOME";
  });
}
function operation() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=operation"
  )
    .then(function (data) {
      var table = $("<table></table>");
      var row = $("<tr></tr>");
      for (var i = 0; i < data.length; i++) {
        var a = $("<a></a>");
        var article = $("<article></article>").attr("class", "box-minimal");
        var imgIcon = $("<img>")
          .attr("src", data[i].image_operation)
          .attr("class", "box-chloe__icon novi-icon");
        var divMain = $("<div></div>").attr("class", "box-minimal-main");
        var h4 = $("<h4></h4>")
          .attr("class", "box-minimal-title")
          .text(data[i].name_operation);

        divMain.append(h4);
        article.append(imgIcon, divMain);
        a.append(article);

        var cell = $("<td></td>").append(a).attr("class", "link-operation").attr("id", data[i].id_operation);
        row.append(cell);

        // Si hemos añadido 4 celdas a la fila, añadimos la fila a la tabla y creamos una nueva fila
        if ((i + 1) % 4 === 0) {
          table.append(row);
          row = $("<tr></tr>");
        }
      }

      // Añadimos la última fila a la tabla, en caso de que no tenga 4 celdas
      if (row.children().length > 0) {
        table.append(row);
      }

      $("#containerOperation").append(table);
    })
    .catch(function (e) {
      console.log(e);
    });
}
function city() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=city"
  )
    .then(function (data) {
      let html = "";
      for (row in data) {
        html += `
                <div class="col-md-6 wow-outer city_home" id='${data[row].id_city}'>
                    <article class="post-modern wow slideInLeft">
                        <a class="post-modern-media" href="#">
                            <img class="propertyImage" src="${data[row].image_city}" alt="" width="571" height="353"/>
                        </a>
                        <h4 class="post-modern-title title-city">
                            <a class="post-modern-title" href="#">${data[row].name_city}</a>
                        </h4>
                        <ul class="post-modern-meta"></ul>
                    </article>
                </div>
            `;
      }
      $("#propertyContainer").html(html);
    })
    .catch(function (e) {
      console.error(e);
    });
}
function extras() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=extras"
  )
    .then(function (data) {
      let html = "";
      for (row in data) {
        html += `
                <div class="col-md-10 col-lg-6 wow-outer extras_home" id='${data[row].id_extras}'>
                    <article class="profile-creative wow slideInLeft">
                        <figure class="profile-creative-figure">
                            <img class="profile-creative-image" src="${data[row].image_extras}" alt="" width="270" height="273" />
                        </figure>
                        <div class="profile-creative-main">
                            <h5 class="profile-creative-title">
                                <a href="#">${data[row].name_extras}</a>
                            </h5>
                            <p class="profile-creative-position"></p>
                        </div>
                    </article>
                </div>
            `;
      }
      $(".row-lg-50.row-35.row-xxl-70").html(html);
    })
    .catch(function () {
      console.error("Error");
    });
}
function recomendation() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=recomendation"
  )
    .then(function (data) {
      let html = "";
      for (row in data) {
        html += `
                <div class="col-md-6 wow-outer property_recomendation" id="${data[row].id_property}">
                    <article class="post-modern wow slideInLeft">
                        <a class="post-modern-media">
                            <img src="${data[row].path_images}" alt="" width="571" height="353"/>
                        </a>
                        <h4 class="post-modern-title">
                            <a class="post-modern-title">${data[row].property_name}</a>
                        </h4>
                        <ul class="post-modern-meta">
                            <li><a class="button-winona">${data[row].price} €</a></li>
                            <li>${data[row].square_meters} Sq. Meters</li>
                            <li>${data[row].number_of_rooms} Rooms</li>
                        </ul>
                        <p>${data[row].description}</p>
                    </article>
                </div>
                `;
      }
      $("#propertyRecomendation").html(html);
    })
    .catch(function (e) {
      console.error(e);
    });
}
function loadMostVisited() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=mostVisited"
  )
    .then(function(mostVisited) {
        let html = "";
        for (let i = 0; i < mostVisited.length; i++) {
            let property = mostVisited[i];
            html += `
                <div class="col-md-6 wow-outer property_recomendation" id="${property.id_property}">
                    <article class="post-modern wow slideInLeft">
                        <a class="post-modern-media">
                            <img src="${property.path_images}" alt="" width="571" height="353"/>
                        </a>
                        <h4 class="post-modern-title">
                            <a class="post-modern-title">${property.property_name}</a>
                        </h4>
                        <ul class="post-modern-meta">
                            <li><a class="button-winona">${property.price} €</a></li>
                            <li>${property.square_meters} Sq. Meters</li>
                            <li>${property.number_of_rooms} Rooms</li>
                        </ul>
                        <p>${property.description}</p>
                    </article>
                </div>
                `;
        }
        $("#mostVisited").html(html);
    })
    .catch(function(error) {
        console.error(error);
    });
}
function loadLastVisited() {
  ajaxPromise(
    "GET",
    "JSON",
    "?module=home&op=lastVisited"
  )
    .then(function(lastVisited) {
        let html = "";
        for (let i = 0; i < lastVisited.length; i++) {
            let property = lastVisited[i];
            html += `
                <div class="col-md-6 wow-outer property_recomendation" id="${property.id_property}">
                    <article class="post-modern wow slideInLeft">
                        <a class="post-modern-media">
                            <img src="${property.path_images}" alt="" width="571" height="353"/>
                        </a>
                        <h4 class="post-modern-title">
                            <a class="post-modern-title">${property.property_name}</a>
                        </h4>
                        <ul class="post-modern-meta">
                            <li><a class="button-winona">${property.price} €</a></li>
                            <li>${property.square_meters} Sq. Meters</li>
                            <li>${property.number_of_rooms} Rooms</li>
                        </ul>
                        <p>${property.description}</p>
                    </article>
                </div>
                `;
        }
        $("#lastVisited").html(html);
    })
    .catch(function(error) {
        console.error(error);
    });
}
function clicks_home() {
  $(document).on("click",'div.property_recomendation', function (){
    // console.log('click_OK_Recomendation');
    localStorage.setItem('details_home', this.getAttribute('id'));
      setTimeout(function(){
        window.location.href = 'index.php?page=shop';
      }, 1000);
  });
  $(document).on("click",'article.thumbnail-light', function (){
    var filters_shop = JSON.parse(localStorage.getItem('filters_shop')) || {};
    if (!filters_shop.id_category) {
      filters_shop.id_category = this.getAttribute('id');
      localStorage.setItem('filters_shop', JSON.stringify(filters_shop));
      let selectedCategory = this.getAttribute('id');
      localStorage.setItem('selectedCategory', selectedCategory);
    }
    setTimeout(function(){ 
      window.location.href = 'index.php?page=shop';
    }, 1000);
  });
  $(document).on("click",'div.carrousel_home', function (){
    var filters_shop = JSON.parse(localStorage.getItem('filters_shop')) || {};
    if (!filters_shop.id_large_people) {
      filters_shop.id_large_people = this.getAttribute('id');
      localStorage.setItem('filters_shop', JSON.stringify(filters_shop));
      let selectedPeople = this.getAttribute('id');
      localStorage.setItem('selectedLargePeople', selectedPeople);
    }
    setTimeout(function(){ 
      window.location.href = 'index.php?page=shop';
    }, 1000);
  });
  $(document).on("click",'div.type_home', function (){
    var filters_shop = JSON.parse(localStorage.getItem('filters_shop')) || {};
    if (!filters_shop.id_type) {
      filters_shop.id_type = this.getAttribute('id');
      localStorage.setItem('filters_shop', JSON.stringify(filters_shop));
      let selectedType = this.getAttribute('id');
      localStorage.setItem('selectedType', selectedType);
    }
    setTimeout(function(){ 
      window.location.href = 'index.php?page=shop';
    }, 1000);
  });
  $(document).on("click",'td.link-operation', function (){
    var filters_shop = JSON.parse(localStorage.getItem('filters_shop')) || {};
    if (!filters_shop.id_operation) {
      filters_shop.id_operation = this.getAttribute('id');
      localStorage.setItem('filters_shop', JSON.stringify(filters_shop));
      let selectedOperation = this.getAttribute('id');
      localStorage.setItem('selectedOperation', selectedOperation);
    }
    setTimeout(function(){ 
      window.location.href = 'index.php?page=shop';
    }, 1000);
  });
  $(document).on("click",'div.city_home', function (){
    var filters_shop = JSON.parse(localStorage.getItem('filters_shop')) || {};
    if (!filters_shop.id_city) {
      filters_shop.id_city = this.getAttribute('id');
      localStorage.setItem('filters_shop', JSON.stringify(filters_shop));
      let selectedCity = this.getAttribute('id');
      localStorage.setItem('selectedCity', selectedCity);
    }
    setTimeout(function(){ 
      window.location.href = 'index.php?page=shop';
    }, 1000);
  });
  $(document).on("click",'div.extras_home', function (){
    var filters_shop = JSON.parse(localStorage.getItem('filters_shop')) || {};
    filters_shop.id_extras = [Number(this.getAttribute('id'))];
    localStorage.setItem('filters_shop', JSON.stringify(filters_shop)); 

    let selectedExtras = Number(this.getAttribute('id'));
    localStorage.setItem('selectedExtras', selectedExtras);

    setTimeout(function(){ 
      window.location.href = 'index.php?page=shop';
    }, 1000);
  });
  $(document).on("click", '#filter_price_submit', function() {
    var filters_shop = JSON.parse(localStorage.getItem('filters_shop')) || {};
    var minPrice = $('#minPrice').val();
    var maxPrice = $('#maxPrice').val();

    if (minPrice !== "") {
      filters_shop['minPrice'] = minPrice;
    } else {
      delete filters_shop['minPrice'];
    }

    if (maxPrice !== "") {
      filters_shop['maxPrice'] = maxPrice;
    } else {
      delete filters_shop['maxPrice'];
    }
    if (Object.keys(filters_shop).length === 0) {
        localStorage.removeItem('filters_shop');
    } else {
        localStorage.setItem('filters_shop', JSON.stringify(filters_shop));
    }
    setTimeout(function(){ 
      window.location.href = 'index.php?page=shop';
    }, 1000);
  });
}
function highlight_home() {
  var highlight_filters = JSON.parse(localStorage.getItem('filters_shop'));
  if (highlight_filters) {
    if (highlight_filters['id_category']) {
        $('#id_category').val(highlight_filters['id_category']);
        if ($('#id_category').val()) {
            $('#id_category').addClass('selected_filters');
        }
    }
    if (highlight_filters['id_city']) {
        $('#id_city').val(highlight_filters['id_city']);
        if ($('#id_city').val()) {
            $('#id_city').addClass('selected_filters');
        }
    }
    if (highlight_filters['id_extras']) {
        $('#id_extras input[type="checkbox"]').each(function() {
            if (highlight_filters['id_extras'].includes(this.value)) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
        if (highlight_filters['id_extras'].length > 0) {
            $('#button_extras').addClass('selected_filters');
        }
    }
    if (highlight_filters['id_operation']) {
        $('#id_operation').val(highlight_filters['id_operation']);
        if ($('#id_operation').val()) {
            $('#id_operation').addClass('selected_filters');
        }
    }
    if (highlight_filters['id_type']) {
        $('#id_type').val(highlight_filters['id_type']);
        if ($('#id_type').val()) {
            $('#id_type').addClass('selected_filters');
        }
    }
    if (highlight_filters['id_large_people']) {
        $('#id_large_people').val(highlight_filters['id_large_people']);
        if ($('#id_large_people').val()) {
            $('#id_large_people').addClass('selected_filters');
        }
    }
    if (highlight_filters['minPrice']) {
        $('#minPrice').val(highlight_filters['minPrice']);
        if ($('#minPrice').val()) {
            $('#button_price .tick-icon').show();
        }else{
            $('#button_price .tick-icon').hide();
        }
    }
    if (highlight_filters['maxPrice']) {
        $('#maxPrice').val(highlight_filters['maxPrice']);
        if ($('#maxPrice').val()) {
            $('#button_price .tick-icon').show();
        }else{
            $('#button_price .tick-icon').hide();
        }
    }
  }
}
$(document).ready(function () {
  carousel_people();
  categories();
  operation();
  city();
  type();
  extras();
  recomendation();
  clicks_home();
  // loadMostVisited();
  // loadLastVisited();
  highlight_home();
});
