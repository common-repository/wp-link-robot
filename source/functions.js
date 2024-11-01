function lnkrob_removeLink(id, nonce) {
				//var url = '<?php echo $postUrl; ?>&lnkrob_ajax_action=removelink&id='+id;
				var container = '#link-'+id;
				//if( confirm('Are you sure you wish to remove this item? This action cannot be undone!') ) {
					jQuery.ajax({	type: "POST",
							url: "../wp-admin/admin-ajax.php",
							timeout: 3000,
							data: {id: id, action: 'lnkrob_ajax_action_remove', _ajax_nonce: nonce},
							success: function(data) {
  							if( data == '0') {
  						    console.log( "Error?" );
  					     }
  								else {
  									jQuery('#link-'+id).fadeOut('normal', function() { });
  								}
  							}
						} );
					
					/*jQuery.get(url, function(data) {
						if( data == '1') {
							jQuery(container).remove();
						}
					});*/
		//}

}
function lnkrob_removeBackLink(id, counter, nonce) {
				var container = '#link-'+id;
					jQuery.ajax({	type: "POST",
							url: "../wp-admin/admin-ajax.php",
							timeout: 3000,
							data: {id: id, action: 'lnkrob_ajax_action_remove', _ajax_nonce: nonce},
							success: function(data) {
  							if( data == '0') {
  						    console.log( "Error?" );
  					     }
  								else {
  									jQuery('#del'+counter).fadeOut('normal', function() { });
  								}
  							}
						} );
}
function lnkrob_approveLink(id, nonce) {
				var container = '#link-'+id;
					jQuery.ajax({	type: "POST",
							url: "../wp-admin/admin-ajax.php",
							timeout: 3000,
							data: {id: id, action: 'lnkrob_ajax_action_approve', _ajax_nonce: nonce},
							success: function(data) {
  							if( data == '0') {
  						    console.log( "Error?" );
  					     }
  								else {
  									jQuery('#link-'+id).remove(); 
  								}
  							}
						} );
}
function lnkrob_rejectLink(id, nonce) {
				var container = '#link-'+id;
					jQuery.ajax({	type: "POST",
							url: "../wp-admin/admin-ajax.php",
							timeout: 3000,
							data: {id: id, action: 'lnkrob_ajax_action_reject', _ajax_nonce: nonce},
							success: function(data) {
  							if( data == '0') {
  						    console.log( "Error?" );
  					     }
  								else {
  									jQuery('#link-'+id).remove(); 
  								}
  							}
						} );
}
function lnkrob_checkLink (id,nonce){

jQuery( '#checking-'+id ).before( '<span id="checking-'+id+'">Checking</span>' ).remove(); 

jQuery.ajax({	type: "POST",
				url: "../wp-admin/admin-ajax.php",
				timeout: 3000,
				data: {id: id, action: 'lnkrob_ajax_action_check', _ajax_nonce: nonce},
				success: function(data) {
				//console.log(data);
            if(data == 'Failed'){
					//element.innerHTML = 'Error?'; //todo
               jQuery( '#checking-'+id ).before( '<span id="checking-'+id+'">Error</span>' ).remove(); 
				}
				else if( data == '0') {
					//element.innerHTML = 'Error?'; //todo
               jQuery( '#checking-'+id ).before( '<span id="checking-'+id+'">Error</span>' ).remove(); 
				}
				else {
					// TODO replace DOM element
					jQuery( '#link-'+id ).before( data ).remove(); 
				}
			}
		} );
} 
function lnkrob_checkBackLink (id,count, nonce){

jQuery( '#checking-'+id ).before( '<span id="checking-'+id+'">Checking</span>' ).remove(); 

jQuery.ajax({	type: "POST",
				url: "../wp-admin/admin-ajax.php",
				timeout: 3000,
				data: {id: id, count: count, action: 'lnkrob_ajax_action_backcheck', _ajax_nonce: nonce},
				success: function(data) {
				//console.log(data);
				if(data){
               jQuery( '#checking-'+id ).before( '<span id="checking-'+id+'">'+data+'</span>' ).remove(); 
            }
				else{
               jQuery( '#checking-'+id ).before( '<span id="checking-'+id+'">Error</span>' ).remove(); 
            }
			}
		} );
} 


function lnkrob_saveLinkInfo(id, nonce) {
jQuery.ajax({	type: "POST",
				url: "../wp-admin/admin-ajax.php",
				timeout: 3000,  
				data: {
					title: jQuery( "input[name=link-title-"+id+"]" )[0].value,
					url: jQuery( "input[name=link-url-"+id+"]" )[0].value,
					category: jQuery( "select[name=link-category-"+id+"]" ).val(),
					description: jQuery( "input[name=link-description-"+id+"]" )[0].value,
					email: jQuery( "input[name=link-email-"+id+"]" )[0].value,
					rec_url: jQuery( "input[name=link-reciprocalurl-"+id+"]" )[0].value,
					admin_comment: jQuery( "input[name=link-administratorcomment-"+id+"]" )[0].value,
					priority_index: jQuery( "input[name=link-priorityindex-"+id+"]" )[0].value,
					id: id, 
          action: 'lnkrob_ajax_action_save',
					_ajax_nonce: nonce},
				success: function(data) {
					if( data == '0') {
						console.log( "Error?" );
					}
					else {
						jQuery( '#link-'+id ).before( data ).remove(); 
					}
				}
			} );			
	} 
function lnkrob_saveLinkInfoInbox(id, nonce) {
jQuery.ajax({	type: "POST",
				url: "../wp-admin/admin-ajax.php",
				timeout: 3000,  
				data: {
					title: jQuery( "input[name=link-title-"+id+"]" )[0].value,
					url: jQuery( "input[name=link-url-"+id+"]" )[0].value,
					category: jQuery( "select[name=link-category-"+id+"]" ).val(),
					description: jQuery( "textarea[name=link-description-"+id+"]" ).val(),
					email: jQuery( "input[name=link-email-"+id+"]" )[0].value,
					rec_url: jQuery( "input[name=link-reciprocalurl-"+id+"]" )[0].value,
					admin_comment: jQuery( "input[name=link-administratorcomment-"+id+"]" )[0].value,
					priority_index: jQuery( "input[name=link-priorityindex-"+id+"]" )[0].value,
					status: jQuery( "input[name=link-status-"+id+"]" )[0].value,
					id: id, 
          action: 'lnkrob_ajax_action_save_inbox',
					_ajax_nonce: nonce},
				success: function(data) {
					if( data == '0') {
						console.log( "Error?" );
					}
					else {
						jQuery( '#link-'+id ).before( data ).remove(); 
					}
				}
			} );			
	} 
function lnkrob_saveCleanLinkInfo(id, count, nonce) {
jQuery.ajax({	type: "POST",
				url: "../wp-admin/admin-ajax.php",
				timeout: 3000,  
				data: {
					title: jQuery( "input[name=link-title-"+id+"]" )[0].value,
					url: jQuery( "input[name=link-url-"+id+"]" )[0].value,
					category: jQuery( "select[name=link-category-"+id+"]" ).val(),
					description: jQuery( "input[name=link-description-"+id+"]" )[0].value,
					email: jQuery( "input[name=link-email-"+id+"]" )[0].value,
					rec_url: jQuery( "input[name=link-reciprocalurl-"+id+"]" )[0].value,
					admin_comment: jQuery( "input[name=link-administratorcomment-"+id+"]" )[0].value,
					priority_index: jQuery( "input[name=link-priorityindex-"+id+"]" )[0].value,
					status: jQuery( "input[name=link-status-"+id+"]" )[0].value,
					id: id,
          count: count, 
          action: 'lnkrob_ajax_action_clean_save',
					_ajax_nonce: nonce},
				success: function(data) {
					if( data == '0') {
						console.log( "Errror?" );
					}
					else {
						jQuery( '#del'+count ).before( data ).remove(); 
					}
				}
			} );			
	} 
function lnkrob_linkToggleEdit(id, edit, nonce) {
	jQuery( '#link-'+id ).addClass("lnkrob-processing"); 
	jQuery.ajax({	type: "POST",
			url: "../wp-admin/admin-ajax.php",               //todooooooooo zmenit
			timeout: 3000,
			data: { _ajax_nonce: nonce, edit: edit, id: id, action: 'lnkrob_ajax_action_edit'},
			success: function(data) {
			//console.log(data);
				if( data == '0') {
					//element.innerHTML = 'Error?'; //todo
					jQuery( '#link-'+id ).removeClass("lnkrob-processing"); 
				}
				else {
					// TODO replace DOM element
					jQuery( '#link-'+id ).before( data ).remove(); 
				}
			}
		} );
}
function lnkrob_linkToggleEditInbox(id, edit, nonce) {
	jQuery( '#link-'+id ).addClass("lnkrob-processing"); 
	jQuery.ajax({	type: "POST",
			url: "../wp-admin/admin-ajax.php",               //todooooooooo zmenit
			timeout: 3000,
			data: { _ajax_nonce: nonce, edit: edit, id: id, action: 'lnkrob_ajax_action_editinbox'},
			success: function(data) {
			//console.log(data);
				if( data == '0') {
					//element.innerHTML = 'Error?'; //todo
					jQuery( '#link-'+id ).removeClass("lnkrob-processing"); 
				}
				else {
					// TODO replace DOM element
					jQuery( '#link-'+id ).before( data ).remove(); 
				}
			}
		} );
}

function lnkrob_linkCleanUpdateURL(id, url, count, nonce) {
	jQuery.ajax({	type: "POST",
			url: "../wp-admin/admin-ajax.php",               //todooooooooo zmenit
			timeout: 3000,
			data: { _ajax_nonce: nonce, url: url, id: id, count: count, action: 'lnkrob_ajax_action_update_url'},
			success: function(data) {
			//console.log(data);
				if( data == '0') {
					//element.innerHTML = 'Error?'; //todo
				}
				else {
					// TODO replace DOM element
					jQuery( '#del'+count ).before( data ).remove(); 
				}
			}
		} );
}
function lnkrob_linkCleanToggleEdit(id, count, edit, nonce) {

	jQuery( '#del'+count ).addClass("lnkrob-processing"); 
	jQuery.ajax({	type: "POST",
			url: "../wp-admin/admin-ajax.php",               //todooooooooo zmenit
			timeout: 3000,
			data: { _ajax_nonce: nonce, edit: edit, id: id, count: count, action: 'lnkrob_ajax_action_edit_clean'},
			success: function(data) {
			//console.log(data);
				if( data == '0') {
					//element.innerHTML = 'Error?'; //todo
					jQuery( '#del'+count ).removeClass("lnkrob-processing"); 
				}
				else {
					// TODO replace DOM element
					jQuery( '#del'+count ).before( data ).remove(); 
				}
			}
		} );
}
function lnkrob_AddNewLink(id, idCategory, nonce) {
	jQuery.ajax({	type: "POST",
			url: "../wp-admin/admin-ajax.php",               //todooooooooo zmenit
			timeout: 3000,
			data: { _ajax_nonce: nonce, idCategory: idCategory, action: 'lnkrob_ajax_action_addnew'},
			success: function(data) {
				if( data == '0') {
					//element.innerHTML = 'Error?'; //todo
				}
				else {
					// TODO replace DOM element
					jQuery( '#'+id ).before( data ); 
					jQuery( '#AddNewLink').hide(); 
				}
			}
		} );
}

function lnkrob_linkCleanShowFlag(id, nonce){
	
  jQuery.ajax({	type: "POST",
			url: "../wp-admin/admin-ajax.php",               //todooooooooo zmenit
			timeout: 3000,
			data: { _ajax_nonce: nonce, id: id, action: 'lnkrob_ajax_action_show_flags'},
			success: function(data) {
			//console.log(data);
				if( data == '0') {
					//element.innerHTML = 'Error?'; //todo
					//jQuery( '#del'+count ).removeClass("lnkrob-processing"); 
				}
				else {
					// TODO replace DOM element
					jQuery( '#flag-'+id ).before( data ).remove(); 
				}
			}
		} );

}


function lnkrob_linkCleanAddFlag(id, nonce) {
jQuery.ajax({	type: "POST",
				url: "../wp-admin/admin-ajax.php",
				timeout: 3000,  
				data: {
					flags: jQuery( "input[name=newflags-"+id+"]" )[0].value,
					id: id, 
          action: 'lnkrob_ajax_action_add_flags',
					_ajax_nonce: nonce},
				success: function(data) {
					if( data == '0') {					}
					else {
					//element.innerHTML = data;
					jQuery( '#flag-'+id).before(data).remove();					
					}
				}
			} );			
	} 
function lnkrob_addNewLinkInfo(id, nonce) {
jQuery.ajax({	type: "POST",
				url: "../wp-admin/admin-ajax.php",
				timeout: 3000,  
				data: {
					title: jQuery( "input[name=link-title-"+id+"]" )[0].value,
					url: jQuery( "input[name=link-url-"+id+"]" )[0].value,
					category: jQuery( "select[name=link-category-"+id+"]" ).val(),
					description: jQuery( "input[name=link-description-"+id+"]" )[0].value,
					email: jQuery( "input[name=link-email-"+id+"]" )[0].value,
					rec_url: jQuery( "input[name=link-reciprocalurl-"+id+"]" )[0].value,
					admin_comment: jQuery( "input[name=link-administratorcomment-"+id+"]" )[0].value,
					priority_index: jQuery( "input[name=link-priorityindex-"+id+"]" )[0].value,
					status: jQuery( "input[name=link-status-"+id+"]" )[0].value,
					id: id, 
          action: 'lnkrob_ajax_action_new',
					_ajax_nonce: nonce},
				success: function(data) {
					if( data == '0') {
					  jQuery( '#AddErrorMessage').before("<span id='AddErrorMessage'>Something Went Wrong</span>").remove(); 
					}
					else if( data == 'EmailError') {
					  jQuery( '#AddErrorMessage').before("<span id='AddErrorMessage'>Please fill in the email address</span>").remove(); 
					}
					else if( data == 'TitleError') {
					  jQuery( '#AddErrorMessage').before("<span id='AddErrorMessage'>Please fill in the title</span>").remove(); 
					}
					else if( data == 'UrlError') {
					  jQuery( '#AddErrorMessage').before("<span id='AddErrorMessage'>Please fill in the url</span>").remove(); 
					}
					else {
					//element.innerHTML = data;
					jQuery( '#AddNewButton').before(data);					
					jQuery( '#AddNewLink').show(); 
					jQuery( '#link_details-new').remove(); 
					}
				}
			} );			
	} 
	