$(document).ready(function () {

    //alert('Script loaded, You may now continue');

    //detect mobile browser
    var isMobile = false; //initiate as false
// device detection
if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))){
      isMobile = true
      };

  $('[data-toggle="popover"]').popover();
  $('body').on('click', function (e) {
  $('[data-toggle="popover"]').each(function () {
    //the 'is' for buttons that trigger popups
    //the 'has' for icons within a button that triggers a popup
    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
      $(this).popover('hide');
    }
   });
  });


    //position bootstrap dropdown
    $('.top_menu_items').click(function(e){
      if('pageX' in e){
      var leftX = e.pageX;
        var offsetX = $( this ).offset();

      } else {
        var leftx = event.clientX + document.documentElement.scrollLeft;

        }
      if('pageY' in e){
      var leftY = e.pageY;
        var offsetY = $( this ).offset();

      } else {
        var lefty = event.clientY + document.documentElement.scrollDown;

        }
      var position = {
        "position" : "absolute",
        "left" : offsetX.left - 10,
        "top" : offsetY.top + 40
        }
      $('.dropdown-menu').css(position);
    });

    //new users  carousel
  $('.gallery-carousel').slick({
    autoplay: true,
    autoplaySpeed: 3000,
    accessibility: false,
    centerMode: false,
    dots: false,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
    mobileFirst: false
    });

    //new users  carousel
  $('.new-users-carousel').slick({
    autoplay: true,
    autoplaySpeed: 5000,
    accessibility: false,
    centerMode: false,
    dots: false,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
    mobileFirst: false
    });

    //ads  carousel
  $('.ads-carousel').slick({
    autoplay: true,
    autoplaySpeed: 7000,
    accessibility: false,
    centerMode: false,
    dots: false,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
    mobileFirst: false
    });


  // Upload titles
  $('#image_title').hide();
   $("#image_field").change(function (){
       var fileName = $(this).val();
       $("#image_title").show('slow');
     });

  //Sidebar rules
  $('.container').attr('data-xthreshold',"400");


	$('.place-order-button').click(function(e){
   // $(this).toggleClass('orangered white');
		$('.place-order-button').toggleClass('teal hidden');		
		$('.place-order-content').toggleClass('hidden');		
		});

  //~ $('.main-sidebar').hide();
  if($('#sidebar').hasClass('hidden')){
      $('.secondary').fadeOut();
    } else {
      $('.secondary').fadeIn();
       }
  $('#toggle-sidebar').click(function(e){
   // $(this).toggleClass('orangered white');
    $('#sidebar').toggleClass('hidden');
    if($('#sidebar').hasClass('hidden')){
      $('.secondary').fadeOut();
    } else {
      $('.secondary').fadeIn();
       }
    $('body').toggleClass('content-pushed');

    });


  $(document).swiperight(function(e){
    $('#sidebar').removeClass('hidden');
    $('#main-sidebar').removeClass('hidden');
     $('.secondary').fadeIn();
    $('body').addClass('content-pushed');

  });

  $(document).swipeleft(function() {
    $('#sidebar').addClass('hidden');
    $('#main-sidebar').addClass('hidden');
     $('.secondary').fadeOut();
    $('body').removeClass('content-pushed');

  });

  $('#close-sidebar').click(function(){
    $('#sidebar').addClass('hidden');
    $('body').removeClass('content-pushed');
    $('#search-toggle').show('slow');

    });
  $('.cd-panel').on('click', function(event){
    if( $(event.target).is('.cd-panel') || $(event.target).is('.cd-panel-close') ) {
      $('.cd-panel').removeClass('is-visible');
      event.preventDefault();
      $('.slider-wrapper.theme-default').show();
      $('#sidebar').addClass('hidden');
      $('body').removeClass('content-pushed');

    }
  });

  if($('body').hasClass('content-pushed')){

    }


  if ($('.center').html() ==''){
    $(".center").hide();
    }


  if ($('.payment-button').html() =='none'){
    $(".payment-button").hide();
    }

  if ($('.right-sidebar-region').html() ==''){
    $(".main-content-region").css('width', '97%');

    $('.right-sidebar-region').hide();
    }
  if ($('.top-right-sidebar-region').html() ==''){
    $('.top-right-sidebar-region').hide();
    }
  $('.sweet_title,.sweet_title_faded').click(function(){
      if($(this).attr('data-toggle') == 'collapse'){
      $(this).find('span:first').toggleClass('glyphicon-menu-down glyphicon-menu-up');
      $(this).toggleClass('sweet_title_faded sweet_title');
      }

    });


  //Toggle comments

  $('.comments-toggle').click(function(){
    $('#comments-thread').slideToggle();
    $('#comment-form').slideToggle();
    $('.comments-show-more').slideToggle();
  });


  // Add pictures to post -start
  $('.upload-no-edit-slideout').hide();
  $('#upload-attachment-content').hide();
  //$('#uploaded-attachment-content').hide();
  $('#upload-pic-toggle').click(function(){
    $(this).addClass('teal').removeClass('gainsboro');
    $('#upload-attachment-toggle').removeClass('teal');
    $('#upload-pic-content').fadeIn('slow');
    $('#upload-attachment-content').hide();
    $('#uploaded-attachment-content').hide();
    });
  $('#upload-attachment-toggle').click(function(){
     $(this).addClass('teal').removeClass('gainsboro');
     $('#upload-pic-toggle').addClass('gainsboro').removeClass('teal');
  $('#upload-pic-content').hide();
  $('#upload-attachment-content').fadeIn('slow');
    });

  $('#add-picture').click(function(){
    $('#upload-pic-toggle').addClass('teal').removeClass('gainsboro');
      $('.upload-no-edit-slideout').toggleClass('hidden').slideToggle('slow');
      $('#pic-close').toggleClass('hidden').show();
      $('#add-picture').hide('slow');
  });

  $('#pic-close').click(function(){
    $('#upload-pic-toggle').addClass('teal').removeClass('gainsboro');
      $('.upload-no-edit-slideout').slideToggle("slow").toggleClass('hidden');
      $(this).slideToggle('slow').toggleClass('hidden');
      $('#add-picture').toggle('slow');
  });

  $('fieldset p').click(function(){
      $(this).toggle("slow");
      $(this).parent().find('.content').slideToggle("slow");
      //$('legend').html("Click to close ");
    });

  //Add pictures - end


  //categorize start
  $('.categorize-holder').hide();
  $('.categorize-close').hide();

  $('.categorize-pullout').click(function(){
      //$(this).parent().find('.content').show("slow");
      $('.categorize-holder').slideToggle();
      $('.categorize-close').slideToggle();
      $('.categorize-pullout').hide('slow');
  });
  $('.categorize-close').click(function(){
      //$(this).parent().find('.content').show("slow");
      $('.categorize-holder').slideToggle();
      $('.categorize-close').slideToggle();
      $('.categorize-pullout').show('slow');
  });

  // Search region hide start

  //$('.search-region').hide();
  $('#search-toggle').click(function(){
    //$('.search-region').load(BasePath +'search/search-form.php');
    $('.search-region').slideToggle().toggleClass('opened').toggleClass('hidden');


    if($('.search-region').hasClass('opened')){
      $('#search-toggle').text('Close search');
    } else {
      $('#search-toggle').text('Search');
      }

    });

  //Show search in search page

    if (window.location.href.indexOf("search.php") > -1) {
    $('.search-region').slideToggle().toggleClass('opened').toggleClass('hidden');
    }


  // Top notices toggle start

  $('#notices-toggle').click(function(){
    //$('.menu-wrapper').load(BasePath +'menus/get_top_menu_items.php');
    $('#notices-dropdown').toggleClass('opened hidden');
    $('.secondary').slideToggle('slow');
    //$('.welcome-message').toggleClass('hidden');
    $('.search-region').fadeOut();
    if($('.menu-wrapper').hasClass('opened')){
      $('#menus-toggle').text('Hide menus');
    } else {
      $('#menus-toggle').text('Show menus');
      }
    });


  $('.back-to-top').click(function(){
    $('#notices-dropdown').removeClass('opened').addClass('hidden');
    window.location.replace('#top');
    });
  //togle interswitch pay for fundraisers
  $('.interswitch-pay').hide();
  $('.site-funds-pay').hide();
  $('.toggle-interswitch').click(function(){
      $('.interswitch-pay').show('slow');
      $('.site-funds-pay').hide('slow');

  });
  $('.toggle-funds').click(function(){
      $('.site-funds-pay').show('slow');
      $('.interswitch-pay').hide('slow');
  });


  //~ show backtotop on page scroll
  $('.back-to-top').fadeOut();
  $(window).scroll(function() {
    if ($(this).scrollTop()) {
        $('.back-to-top:hidden').stop(true, true).fadeIn();
    } else {
        $('.back-to-top').stop(true, true).fadeOut();
    }
  });


  //push tamer
  $('.pushcrew-modal-branding').hide();

  //blinker
  function blink(){
      $('#blink').fadeIn(750).fadeOut(750);
      blinkVar = setTimeout(blink, 2000);
    }
  blink();

  $('#start-discussion').hide();
  $('#add-child').click(function(){
  $('#start-discussion').slideToggle('slow');
  });

  jQuery(".timeago").timeago();

  //$('textarea').val().replace("\n", "<br />", "g");
  //$('textarea').on('keyup', function() {
   // $('textarea#comment').val($('textarea').val().replace("\n", "<br />", "g"));
  //})

  //fire tooltips
   //$("[data-toggle='tooltip']").tooltip();


  //Audio controls

  $('.audio').on('play',function(e){

    });
  $('.audio').on('ended',function(e){
    //window.location.replace(BasePath +'addons/payplay/?action=save_task&progress=1');
    $('#fetched-content').load(BasePath +'addons/payplay/?action=save_task&progress=2 #fetchable',
      function(response, status, xhr){
        if(status === "error"){
          var msg = 'Sorry but there was an error ';
          $('#fetched-content').html(msg + xhr.status + " " + xhr.statusText);
          }
        });


    });

    //Hide empty elements
    $.expr[':'].blank = function(obj){
      return obj.innerHTML.trim().length === 0;
      };
   $('.right-sidebar-region:blank').hide();
   $('.main-content-region:blank').hide();

  //~ Controls hiding and showing of enrollment fee input in add page
  $('#enrollment_fee_input').hide();
  $('input[name="is_training_course"]').on('click',function(){
    if($(this).is(':checked')){
      $('#enrollment_fee_input').show();
      }
    else {
      $('#enrollment_fee_input').hide();
      }
  });
  //~ End


  $('#judges').click(function(){
    $('#judge-area').toggleClass('hidden');
  });
  
  $(function(){
    $(".chzn-select").chosen();
  });


  //~ CKEDITOR.replace( '#content-area' );
});
// Replace the <textarea id="editor1"> with a CKEditor
  // instance, using default configuration.


//~ $('.bxslider').bxSlider({
  //~ video: true,
  //~ adaptiveHeight:true,
  //~ touchEnabled: true,
  //~ mode: 'fade'
//~ });

 //~ $(window).load(function() {
        //~ $('#slider').nivoSlider();
    //~ controlNav: true;
    //~ });



