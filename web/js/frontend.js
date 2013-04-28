var Frontend = {
    init : function(){
        this.slider();
        this.showMap();
        this.submitCallback();
        this.submitReview();
        this.heightRightSidebar();
    },
    heightRightSidebar : function(){
        var hSidebar = $('#siderbar_right').height();
        var hContent = $('#content').height();
        if(hContent > hSidebar)
            $('#siderbar_right').css('min-height', hContent);
    },
    video : function(){
        Shadowbox.init({
            modal: true,
            overlayColor: "#000",
            overlayOpacity: 0.75,
            player: 'iframe'
        });
    },
    slider : function(){
        var c = $('#carousel').carousel()
        $('#carousel_left').click(function(){
            $('#carousel').carousel('next');
        });
        $('#carousel_right').click(function(){
            $('#carousel').carousel('prev');
        });
    },
    InitCarouselBrands : function(){
        $('.flexslider').flexslider({
          animation: "slide",
          animationLoop: false,
          controlNav: false,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
          directionNav: true,
          itemWidth: 110,
          itemMargin: 5
        });
    },
    initializeMap : function() { 
        var latLng = new google.maps.LatLng(55.7922743226, 37.6760267105);

        var mapOptions = { 
          center: latLng ,
          zoom: 16,
          mapTypeId: google.maps.MapTypeId.ROADMAP 
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

        var marker = new google.maps.Marker({
            position: latLng ,
            map: map,
            title:"г. Москва, ул. Сокольнический вал, дом 1А, 4й этаж"
        }); 
    },
    showMap : function(){
        $('#mapModal').on('shown', function () {
            Frontend.initializeMap();
        });
    },
    singlePrettyPhoto : function(id){
        $("a[rel^='prettyPhoto[" + id + "]']").prettyPhoto({
            show_title: true,
	    allow_resize: true, 
            social_tools: false
        });
    },
    submitCallback : function(){
        $("#button_callback_form").click(function(event) {
            var form = $('form#callback_form');
            $.ajax({
                type: "POST",
                url: form.attr("action"), // Or your url generator like Routing.generate('discussion_create')
                data: form.serialize(),
                dataType: "html",
                success: function(msg){
                    $("#callbackModal .modal-body").html(msg);
                }
            });
        })
    },
    submitReview : function(){
        $("#button_review_form").click(function(event) {
            var form = $('form#review_form');
            $.ajax({
                type: "POST",
                url: form.attr("action"), // Or your url generator like Routing.generate('discussion_create')
                data: form.serialize(),
                dataType: "html",
                success: function(msg){
                    $("#reviewModal .modal-body").html(msg);
                }
            });
        })
    }
}

$().ready(function(){
    Frontend.init();
})
