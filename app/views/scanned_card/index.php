<?php build('content') ?>
	<div class="card">
		<div class="card-body">
			<small>Code</small>
			<h1 id="code"></h1>
		</div>
	</div>
<?php endbuild()?>



<?php build('scripts') ?>
	 <script type="text/javascript" defer>
		 $( document ).ready(function()
		 {
		 	let url;
		 	url = getURL('api/ScannedCard/getRecent');

		 	setInterval(function(){
	 			$.ajax({
	 				url : url,
	 				method: 'GET',

	 				success : function(response)
	 				{
	 					if(response.length > 0)
	 					{
	 						$("#code").html(response[0].card_key)
	 					}else{
	 						$("#code").html("Waiting..");
	 					}
	 						
	 				}
	 			});
	 		} , 1000);
		 });

	</script>

<?php endbuild()?>



<?php loadTo('tmp/layout')?>