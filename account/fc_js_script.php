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
			text: "This record will hit the Financials!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, approve!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'fc_approve_func.php',
			    	type: 'POST',
			       	data: 'approve='+recordId,
			       	dataType: 'json'
			     })
			     .done(function(response){
			     	swal('Approved!', response.message, response.status);
					reloadPage();
			     })
				 
			     .fail(function(){
			     	swal('Oops...', 'Something went wrong, record not approved!', 'error');
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
			text: "This record will NOT hit the Financials!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, decline!',
			showLoaderOnConfirm: true,
			  
			preConfirm: function() {
			  return new Promise(function(resolve) {
			       
			     $.ajax({
			   		url: 'fc_decline_func.php',
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