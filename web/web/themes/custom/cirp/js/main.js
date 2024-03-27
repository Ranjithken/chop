/**
 * @file
 * Bootstrap-Cirp behaviors.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Behavior description.
   */
  Drupal.behaviors.bootstrapCirp = {
    attach: function (context, settings) {

     $(document).ready(function(){
		 var footerlogo;
		 var oldfooterlogo = $('.footer-logo>img').attr('src'); 
		 var printfooterlogo = '/sites/default/files/footer-color.png';
		 
		 var matches = ['Feed','facebook','linkedin','twitter']; 
		var list =$( ".footer-icons" ).children();

		console.log(matches);
		$( ".footer-icons span" ).each(function( index ) { 
		  $( this ).attr( "id", 'icon-'+index);
		});
		
window.addEventListener("beforeprint", function(){
	$('.footer-logo>img').attr('src',printfooterlogo); 
	
	for(var i = 0; i < list.length; i++) { 	
	 console.log('#icon-'+i+'> a >img');
		$('#icon-'+i+'> a >img').attr('src','/sites/default/files/'+matches[i]+'-color.png'); 
	} 
    let printStyleSheet = document.createElement("style");
    printStyleSheet.classList.add("print-stylesheet");
    document.head.appendChild(printStyleSheet)

    let printRulesText = "";
    Array.from(document.styleSheets).forEach(styleSheet => {
        Array.from(styleSheet.cssRules)
        .filter(rule => rule.media && /(min-width: 992px)/g.test(rule.media.mediaText))
        .forEach(rule => {
            Array.from(rule.cssRules).forEach(rule => printRulesText += rule.cssText)
        })
    })
    printStyleSheet.innerHTML = printRulesText;
})
window.addEventListener("afterprint", function(){
    Array.from(document.querySelectorAll(".print-stylesheet")).forEach(style => style.remove())
	
	$('.footer-logo>img').attr('src');
	$('.footer-logo>img').attr('src',oldfooterlogo);
	for(var i = 0; i < matches.length; i++) {  
		$('#icon-'+i+'> a >img').attr('src','/themes/custom/cirp/images/'+matches[i]+'.png');  
	} 
	location.reload();
})


		  jQuery("#views-exposed-form-publications-page-1").clone().appendTo(".years-list");

		   $('[data-toggle="tooltip"]').tooltip();

			jQuery('ul.selectdropdown').each(function(){
				var list=jQuery(this),
				select=jQuery(document.createElement('select')).insertBefore(jQuery(this).hide()).change(function(){
					window.open(jQuery(this).val(),'_self')
				});
				jQuery('>li a', this).each(function(){
					var option=jQuery(document.createElement('option'))
					.appendTo(select)
					.val(this.href)
					.html(jQuery(this).html());
					if(jQuery(this).attr('class') === 'selected'){
						option.attr('selected','selected');
					}
				});
				list.remove();
			});

    jQuery('.subchild-li .subchild-link').each(function (){
      if(jQuery(this).hasClass("active")){
        jQuery(this).addClass("is-active");
      }
      if ($('.subchild-link').hasClass('is-active')){
        $('.subchild-link').parent().parent().prev().removeClass('is-active');
      }
      if ($('.subchildchild-link').hasClass('is-active')){
        $('.subchildchild-link').parent().parent().prev().removeClass('is-active');
      }
    });
	
	jQuery('#block--Body p  a').click(function(e){
    // e.preventDefault();
    var selectedId='';
    selectedId= '#'+window.location.href.split('/').at(-1).split('#').at(-1);
    var selected= jQuery(this).attr('href').split()[0];
    var length= jQuery(this).attr('href').split().length;
    console.log(length);
    if(length==1){
        var height= jQuery(selected.split()[0]).position().top + 100 +jQuery('.fixed-top').height();
        jQuery('html, body').animate({scrollTop:height}, 'slow');
    }
     })

        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
        	$(this).prev(".custom-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });


        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){

        	$(this).prev(".custom-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        	$(this).prev(".custom-header").parent().removeClass("nobg-color").addClass("bg-color");
        }).on('hide.bs.collapse', function(){
          $(this).prev(".custom-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        	$(this).prev(".custom-header").parent().removeClass("bg-color").addClass("nobg-color");
        });
        // Creating Svg
          addSVG();
          console.log('init');
          var observer = new MutationObserver(addSVG);
          observer.observe(document, { childList: true, subtree: true });

       $(".mobile-menu .we-mega-menu-ul>.we-mega-menu-li").each(function () {
          $(this).append('<span class="fa fa-2x font-weight-bold text-primary fa-angle-right menu-toggle" style="position:absolute;right:15px;"></span>');
       });

       $('.menu-toggle').click(function (e) {
         e.preventDefault();
         $('.mobile-menu .dropdown-menu').removeClass('active active-trail')
         //$('.menu-toggle').addClass('fa-angle-right').removeClass('fa-angle-down');
         //$(this).removeClass('active-trail');
         $(this).parent().addClass('active active-trail')
         if ($(this).hasClass("fa-angle-down")) {
          $('.menu-toggle').addClass('fa-angle-right').removeClass('fa-angle-down');
          console.log($(this).hasClass('fa-angle-down'));
          $(this).removeClass('fa-angle-down').addClass('fa-angle-right');
           $(this).parent().removeClass('active active-trail');

        }
         else {
         $('.menu-toggle').addClass('fa-angle-right').removeClass('fa-angle-down');
         $(this).removeClass('fa-angle-right').addClass('fa-angle-down');
        }
      })


       var scrolltotop = document.getElementById("go-to-top");
       $('.sidebar .block-menu.navigation .dropdown-toggle').append('<span class="fa fa-2x font-weight-bold  text-primary fa-angle-right custom-toggle"></span>');

       $('.active > span').removeClass('fa-angle-right').addClass('fa-angle-down');
       $('.active + .parent-nav').addClass('d-flex');
       $('.active + .sub-nav').addClass('d-flex');
      $('.custom-toggle').click(function (e) {
         e.preventDefault();
         if ($(this).hasClass('fa-angle-down')) {
          //  $('.custom-toggle').removeClass('fa-angle-down').addClass('fa-angle-right');
           $(this).removeClass('fa-angle-down').addClass('fa-angle-right');
           $(this).parent().next().removeClass('d-flex');
         }
         else {
          // $('.custom-toggle').removeClass('fa-angle-down').addClass('fa-angle-right');
          $(this).removeClass('fa-angle-right').addClass('fa-angle-down');
          // $('.sidebar .parent-nav').removeClass('d-flex');
          // $('.sidebar .sub-nav').removeClass('d-flex');
          // $('.child-link + .parent-nav').next().hide();
          $(this).parent().next().addClass('d-flex');
         }

       })

       $('#expandbuttons').click(function () {
        $('.reports .panel-collapses ').addClass('show');
      });
      $('#collapsebuttons').click(function () {
        $('.reports .panel-collapses ').removeClass('show');
		$('.panel').removeClass('bg-color').addClass('nobg-color');
      });
      $('#expandbutton').click(function () {
        $('.reports .panel-collapse ').addClass('show');
      });
      $('#collapsebutton').click(function () {
		$('.panel').removeClass('bg-color').addClass('nobg-color');
        $('.reports .panel-collapse ').removeClass('show');
      });
      if (jQuery().colorbox) {
        $('.youtube').colorbox({iframe: true, width: 640, height: 390, href:function(){
          var videoId = new RegExp('[\\?&]v=([^&#]*)').exec(this.href);
            if (videoId && videoId[1]) {
                return 'https://youtube.com/embed/'+videoId[1]+'?rel=0&wmode=transparent';
            }
        }});
      }
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
    scrolltotop.style.display = "block";
  } else {
    scrolltotop.style.display = "none";
  }
}




// When the user clicks on the button, scroll to the top of the document
       $('#go-to-top').click(function(){
          document.documentElement.scrollTop = 0;
        })

        $( "#collapsebutton" ).hide();
        $( "#expandbutton" ).click(function() {
        $('div.panel-collapse').addClass('in').css("height", "");
        $('#collapsebutton i.fa').removeClass('fa-plus').addClass('fa-minus');
        $('.panel-collapse i.fa').removeClass('fa-plus').addClass('fa-minus');
        $( "#expandbutton" ).hide();
        $( "#collapsebutton" ).show();
        });
        $( "#collapsebutton" ).click(function() {
        $('div.panel-collapse').removeClass('in');
        $('#expandbutton i.fa').removeClass('fa-minus').addClass('fa-plus');
         $('.panel-collapse i.fa').removeClass('fa-minus').addClass('fa-plus');
        $( "#expandbutton" ).show();
        $( "#collapsebutton" ).hide();
        });
        $( "div.panel a" ).click(function() {
          $('div.panel-collapse').each(function( index ) {
           if($( this ).hasClass('in') ){
           $( "#expandbutton" ).show();
            $( "#collapsebutton" ).hide();
            }
         });
        });
        //accordion
        $( "#collapsebuttons" ).hide();
        $( "#expandbuttons" ).click(function() {
        $('div.panel-collapses').addClass('in').css("height", "");
        $('i.fa').removeClass('fa-plus').addClass('fa-minus');
        $( "#expandbuttons").hide();
        $( "#collapsebuttons").show();
        });
        $( "#collapsebuttons" ).click(function() {
        $('div.panel-collapses').removeClass('in');
        $('i.fa').removeClass('fa-minus').addClass('fa-plus');
        $( "#expandbuttons" ).show();
        $( "#collapsebuttons" ).hide();
        });
        $( "div.panel a" ).click(function() {
          $('div.panel-collapses').each(function( index ) {
            if($( this ).hasClass('in') ){
            $( "#expandbuttons").show();
            $( "#collapsebuttons").hide();
            }
          });
        });

    });


    // Creating Svg function
      function addSVG() {
        console.log('123');
        $.each($('[class^="svg-"]'), function( key, value ) {
          var oldCls = $(value).attr("class");
          var cls = $("#"+oldCls.slice(4, (oldCls.indexOf(' ')<0) ? oldCls.length : oldCls.indexOf(' ')));
          $(value).append(cls.parent().clone())
            .css({ "width": cls.attr("width"), "height": cls.attr("height") });
          $(this).attr("class", $(this).attr("class").replace("svg-", "svgd-"));
        });
      }




    }
  };

} (jQuery, Drupal));
