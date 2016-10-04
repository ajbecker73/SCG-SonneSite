$(document).ready(function(){
	
		$('#attbtn').click(function(event){
			event.preventDefault();
			$('#atts').prepend(
				'<p><b>Name:</b> <input style="width:130px; margin:0 20px 0 0;" type="text" name="pOptNames[]" value="" autofocus="autofocus" />'
				+ '<b>Value:</b> <input style="width:130px; margin:0 20px 0 0;" type="text" name="pOptValues[]" value="" />'
				+ '<b>Price +-:</b> <input style="width:50px; margin:0 20px 0 0;" type="number" min="-9999" step=".01" name="pOptPrices[]" value="0.00" /></p>'
			);
		 
		 });
		 
			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});

		$(".selectRow").click(function() {
			$("tr").removeClass("rowSelected");
			$(this).closest('tr').addClass("rowSelected");
			$(this).find('input:radio').prop('checked', true);
		});
		
		//GENERATE RANDOM PASSWORD
		$.extend({
		  password: function (length) {
		    var chString = "";
		    var build = 0;
		    var password = "";
		    var randNum = "";
		    chString += "ABCDEFGHJKMNPQRSTUVWXYZ";
		    chString += "abcdefghjkmnpqrstuvwxyz";
		    chString += "23456789";
		    chString += "!@#*$%&";
		    
		    while(build < length){
			   build++;
			   randNum = Math.floor((Math.random()*chString.length));
			   password += chString.substring(randNum,randNum+1);
		    }
		    return password;
		  }
		});
		
		$("#passBtn").click(function() {
			gPass = $.password(10);
			$('#genPass').text(gPass);
			$('#pass1').val(gPass);
			$('#pass2').val(gPass);
		});
		//////////////////////////////

		$( ".datepicker" ).datepicker(
			{
				dateFormat: "yy-mm-dd",
				showOn: "button",
				 buttonImage: "img/calendar.png",
				 buttonImageOnly: true
			}
		);
		$( ".timepicker" ).timepicker(
			{
				timeFormat: "hh:mm tt",
				ampm: true
			}
		);
		
		$('#addAttendee').click(function(){
				$.post('js/ajax/addAttendee.php', function(data) {
					$('#extraAttendees').append(data);
					$("#extraAttendees").scrollTo( '100%' );
					console.log(data);
				});
			
		 });
		 			    
		$('#addMenuItems').click(function(){
			
				$.post('js/ajax/menu.php', function(data) {
					$('#addMenuItemsDiv').prepend(data);
					console.log(data);
				});
			
		 });
		 			    
		$('#addCalOpt').click(function(){
			
				$.post('js/ajax/addCalOpt.php', function(data) {
					$('#CalOptions').prepend(data);
					console.log(data);
				});
			
		 });
		 
			    $('.deleteSlide').click(function() {
					if(confirm('Are you sure you want to delete this image'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteSlide.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteObituary').click(function() {
					if(confirm('Are you sure you want to delete this obituary'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteObituary.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteOrder').click(function() {
					if(confirm('Are you sure you want to delete this order'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteOrder.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteProduct').click(function() {
					if(confirm('Are you sure you want to delete this product'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteProduct.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteGallery').click(function() {
					if(confirm('Are you sure you want to delete this gallery'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteGallery.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteGalleryPic').click(function() {
					if(confirm('Are you sure you want to delete this image'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteGalleryPic.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteCategory').click(function() {
					if(confirm('Are you sure you want to delete this category'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteCategory.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deletePage').click(function() {
					if(confirm('Are you sure you want to delete this page'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deletePage.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteMember').click(function() {
					if(confirm('Are you sure you want to delete this member'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteMember.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteUser').click(function() {
					if(confirm('Are you sure you want to delete this user'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteUser.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteProspect').click(function() {
					if(confirm('Are you sure you want to delete this prospect'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteProspect.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteEvent').click(function() {
					if(confirm('Are you sure you want to delete this event'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteEvent.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteBox').click(function() {
					if(confirm('Are you sure you want to delete this box'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteBox.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });
			    
			    $('.deleteNavItemTop').click(function() {
					if(confirm('Are you sure you want to delete this Nav Item?'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteNavItem.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });

			    $('.deleteNavItemTopDep').click(function() {
					if(confirm('Are you sure you want to delete this Nav Item?\nThere are items dependant on this item.\nIf you delete it, you will have to re-assign the child items.'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteNavItem.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });

			    $('.deleteNavItemSec').click(function() {
					if(confirm('Are you sure you want to delete this Nav Item?'))
					{
					   var parent = $(this).closest('div');
					   $.ajax({
						  type: 'get',
						  url: 'backoffice/deleteNavItem.php',
						  data: 'ajax=1&delete=' + $(this).attr('id'),
						  beforeSend: function() {
							 parent.animate({'backgroundColor':'#fb6c6c'},300);
						  },
						  success: function() {
							 parent.fadeOut(300,function() {
								parent.remove();
							 });
						  }
					   });        
					}
			    });

			    $('#altDate').change(function() {
					$.post('js/ajax/getScheduleTimes.php?d='+$(this).attr('value'), function(data) {
						$('#SchTimes').empty();
						$('#SchTimes').prepend(data);
						console.log(data);
					});
			    });

});

function showDebug(){
	document.getElementById('debugger').style.height = "90%";
	document.getElementById('debugger').style.overflow = "auto";
}
function hideDebug(){
	document.getElementById('debugger').style.height = "20px";
	document.getElementById('debugger').style.overflow = "hidden";
}






