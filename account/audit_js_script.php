<script>
$(document).ready(function(){
		$(document).on('click', '#approve_record', function(e){
			
			var recordId = $(this).data('id');
			SwalApprove(recordId);
			e.preventDefault();
		});
		
	});
	
	function SwalApprove(recordId){
		
		swal({
			title: 'Are you sure?',
			text: "This record will be verified!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, verify!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'audit_approve_func<?php echo $suffix; ?>.php',
			    	type: 'POST',
			       	data: 'approve='+recordId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Verified!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, record not verified!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});	
		
		function reloadPage(){
			window.location.reload();
		}
	}	
</script>

<script>
$(document).ready(function(){
		$(document).on('click', '#decline_record', function(e){
			
			var declineId = $(this).data('id');
			SwalDecline(declineId);
			e.preventDefault();
		});
		
	});
	
	function SwalDecline(declineId){
		
		swal({
			title: 'Are you sure?',
			text: "This record will NOT be verified!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, decline!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'audit_decline_func<?php echo $suffix; ?>.php',
			    	type: 'POST',
			       	data: 'decline='+declineId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Declined!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, unable to decline record!', 'error');
			     });
			  });
		    },
			allowOutsideClick: false			  
		});
		
		function reloadPage(){
			window.location.reload();
		}
	}		
</script>