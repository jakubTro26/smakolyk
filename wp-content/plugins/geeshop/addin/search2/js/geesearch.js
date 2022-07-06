(function($){
	//alert("dddd");
	jQuery(document).ready(function(){
		var close_search = true;
		jQuery('body').append('<ul id="geeshop-lista"></ul>');
		
		
		jQuery('.search_wrapper .field').addClass('search-field');
		
		function search_box_setup(){
			jQuery('.search-field').each(function(index) {
				var $this = jQuery(this);
				if ($this.is(':focus'))
				{
				
					var element = $this.offset();
					$this.attr( 'autocomplete', 'off' );
					//var height = jQuery( ".search-field" ).parent().top() + 2;
					var height = $this.innerHeight() + 3;
					//alert(height);
					var top = element.top + height;
					var left = element.left ;
					//var left = $('.search-field').position.left() ;
					jQuery('#geeshop-lista').css({position: 'absolute'}); 
					jQuery('#geeshop-lista').css({top: top}); 
					jQuery('#geeshop-lista').css({left: left});
				}					
			});
			
		}
		jQuery(window).scroll(function() {
			//if (jQuery(this).scrollTop() > 0.0)
			{ 
			search_box_setup();
			/*
			
				var element = jQuery( ".search-field" ).position();
				//var height = jQuery( ".search-field" ).parent().top() + 2;
				var height = jQuery( ".search-field" ).innerHeight() + 2;
				//alert(height);
				var top = element.top + height;
				var left = element.left ;
				//var left = $('.search-field').position.left() ;
				jQuery('#geeshop-lista').css({position: 'absolute'}); 
				jQuery('#geeshop-lista').css({top: top}); 
				jQuery('#geeshop-lista').css({left: left});
			*/
			}
		});
		jQuery('body').on('focusin', ".search-field", function () {
    		search_box_setup();
			jQuery('#geeshop-lista li').remove();
	    })
        .on('focusout', ".search-field", function () {
    		search_box_setup();
			if (close_search)
				jQuery('#geeshop-lista').css({display: 'none'}); 
			
	});

	jQuery('body').on('mouseover', "#geeshop-lista li", function () {
        close_search = false;
	}).on('mouseout', "#geeshop-lista li", function () {
        close_search = true;
	});
		
		
		//jQuery(".search-field").click(function() {
		jQuery( "body" ).on( "keyup", ".search-field", function() { 

			search_box_setup();
			var find_text = jQuery(this).val();
			//jQuery('#geeshop-lista').css({display: 'none'}); 
			var currentURL = jQuery(location).attr('href').replace(jQuery(location).attr('pathname'),'');;//jQuery(location).attr('href');//window.location;
			currentURL =jQuery(location).attr('protocol')+"//"+jQuery(location).attr('host')+jQuery(location).attr('pathname');
			//alert(currentURL+"wp-content/plugins/geeshop/addin/search/search_now.class.php");
			if (find_text.length > 1){
				jQuery.ajax({
					type: "POST",
					url: urlVar,
					dataType : 'json',
					data: {
						find : find_text
					},
					success : function(json) {
						jQuery('#geeshop-lista li').remove();			
						var $li = json['result'] ;
						if ($li.length > 1){
							jQuery('#geeshop-lista').append($li);
							jQuery('#geeshop-lista').css({display: 'block'}); 
						} else {
							jQuery('#geeshop-lista').css({display: 'none'}); 
						}
					}
				});
			}else {
				jQuery('#geeshop-lista li').remove();
				jQuery('#geeshop-lista').css({display: 'none'}); 
	
			}
		});
		
		geesearch_result();
		
		function geesearch_result(){
			var doc = $( document ).width();;
			var rightAdd = $('.header-wrapper-add-sharp').innerWidth() ;
			var menuwidth = $('.header-menu-sharp').innerWidth() ;
			//var top = $('.search-field').position.top() ;
			//var left = $('.search-field').position.left() ;
			//jQuery('#geeshop-lista').css({top: top}); 
			//jQuery('#geeshop-lista').css({left: left}); 
							
				//jQuery(this).css({left: leftW}); 
			/*jQuery('.gees-megamenu').each(function(index) {
				var $this = jQuery(this);
				var left_parent = jQuery(this).parent().position().left;
				var left_this = jQuery(this).parent().position().left;
				

		
				var leftW = 0;
				var leftW2 = 1*(doc - (jQuery(this).innerWidth()+rightAdd ));

				leftW = -1 * (left_this - leftW2);					
				jQuery(this).css({left: leftW}); 					
			});*/
			
		}	
		
		
		$(".search-field2").click(function() {
			$.ajax({
				type: "POST",
				url: "http://localhost/_wp/geeshop_ceneo_xml.xml",
				dataType: "xml",
				success: function(xml) {
					var xml_doc = xml;
					//alert(jQuery('#geeshop-lista').html());
					var $ul = $('#geeshop-lista').empty();
					//pobieramy węzły z dokumentu xml
					var ksiazki = $(xml_doc).find('o');
				 
					ksiazki.each(function() {
						var tytul = $(this).find('name').text();
						var opis = $(this).find('desc').text();
						//var ocena = $(this).attr('ocena');
				 
						var $li = '<li><h3 class="title">'+tytul+'</h3><div class="description">'+opis+'</div><div class="note">Ocena: <strong>'+tytul+'</strong></div></li>';
						jQuery('#geeshop-lista').append($li);
					});
				},
				error: function(xml) {
					alert( "Wystąpił błąd: \n" + xml );
				}
			});
});
		
		jQuery( "body" ).on( "click", ".gees-search-menu .kth-search-button", function() { 
			jQuery("form[id='search-prd']").submit();
		});

		$('.gees-search-menu .search-kth-field ').bind('keypress', function(e) {
			if(e.keyCode==13){
				jQuery("form[id='search-prd']").submit();
			}
		});
		jQuery( "body" ).on( "click", "#gees-search-menu .kth-search-clear", function() { 
			jQuery("input[id='s-prd']").val('');
		});	

/* cart */
		jQuery( "body" ).on( "click", "#geeshop-lista .geeserach-cart2 .quantity .plus", function() { 
		alert('plus');
			$val = jQuery(this).parent().find('input[name="quantity"]').val();			
			jQuery(this).parent().find('input[name="quantity"]').val(++$val);
			
		});

		jQuery( "body" ).on( "click", "#geeshop-lista .geeserach-cart2 .quantity .minus", function() { 
		alert('plus');
			$val = jQuery(this).parent().find('input[name="quantity"]').val();
			if ($val > 1) 
				jQuery(this).parent().find('input[name="quantity"]').val(--$val);
			
		});

		jQuery( "body" ).on( "click", "#geeshop-lista .geeserach-cart .quantity .plus", function() { 
			$val = jQuery(this).parent().find('.qty').val();			
			jQuery(this).parent().find('.qty').val(++$val);
			jQuery(this).parent().parent().find('.single_add_to_cart_button ').attr('data-quantity',jQuery(this).parent().find('.qty').val());
			
		});

		jQuery( "body" ).on( "click", "#geeshop-lista .geeserach-cart .quantity .minus", function() { 
			$val = jQuery(this).parent().find('.qty').val();			
			if ($val > 1) 
				jQuery(this).parent().find('.qty').val(--$val);
			//$(this).parent().parent().find('.single_add_to_cart_button ').attr('data-quantity').val(jQuery(this).parent().find('.qty').val());
			jQuery(this).parent().parent().find('.single_add_to_cart_button ').attr('data-quantity',jQuery(this).parent().find('.qty').val());
	//		alert(jQuery(this).parent().find('.qty').val());
//			alert(jQuery(this).parent().parent().find('.single_add_to_cart_button ').attr('data-quantity',jQuery(this).parent().find('.qty').val()));
			
		});		
		
		
		jQuery( "body" ).on( "keyup", ".search-field-content", function() { 
			var find_text = jQuery(this).val();			
			var currentURL = jQuery(location).attr('href').replace(jQuery(location).attr('pathname'),'');;//jQuery(location).attr('href');//window.location;
			currentURL =jQuery(location).attr('protocol')+"//"+jQuery(location).attr('host')+jQuery(location).attr('pathname');
			//alert(currentURL+"wp-content/plugins/geeshop/addin/search/search_now.class.php");
			jQuery('#gees-search-content').find('#geeshop-content').css({display: 'block'}); 
			if (find_text.length > 1){
				jQuery.ajax({
					type: "POST",
					url: urlVar,
					dataType : 'json',
					data: {
						type : 'addv',
						find : find_text
					},
					success : function(json) {
						jQuery('#gees-search-content').find('#geeshop-content li').remove();			
						var $li = json['result'] ;
						if ($li.length > 1){
							jQuery('#gees-search-content').find('#geeshop-content').append($li);
							jQuery('#gees-search-content').find('#geeshop-content').css({display: 'block'}); 
						} else {
							//jQuery('#geeshop-lista').css({display: 'none'}); 
						}
					}
				});
			}else {
				jQuery('#gees-search-content').find('#geeshop-content li').remove();
				//jQuery('#geeshop-lista').css({display: 'none'}); 
	
			}
		});
		
	});
})(jQuery);