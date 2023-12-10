<?php build('content') ?>
	<div class='container-fluid'>
		<h2>Payout</h2>
		<?php Flash::show()?>

		<form action="/payout/multiple">
			<input class='btn btn-primary btn-sm' type="button" id="check_button"  value="Check All" />

			<input class='btn btn-primary btn-sm' type="button" id="uncheck_button"  value="Uncheck All" />

			<div class='table-responsive'>
				<table class="table">

					<thead>

						<th>#</th>

						<th>Fullname</th>

						<th>Wallet</th>

						<th>Pera</th>

						<th>Single Payout</th>

					</thead>



					<tbody>

						<?php $counter = 0?>

						<?php foreach($users as $userKey => $user) :?>

							<?php if($user->wallet <= 0) continue?>

							<tr>

								<td><?php echo ++$counter?></td>

								<td>

									<label for="id_<?php echo $user->id?>">

										<input type="checkbox" class="my_check_box" name="users[]" 

										value="<?php echo $user->id?>"

										id="id_<?php echo $user->id?>"

										checked>

									</label>

									<?php echo $user->firstname . ' ' .$user->lastname?>

								</td>

								<td><?php echo amountHTML($user->wallet)?></td>

								<td>

									<?php if(!$user->pera) :?>

										<label>N/A</label>

									<?php else:?>

										<?php echo $user->pera->account_number?>

									<?php endif?>

								</td>

								<td>

									<a href="/Payout/single/<?php echo $user->id?>">Single payout</a>

								</td>

							</tr>

						<?php endforeach?>

					</tbody>

				</table>
			</div>

			<input type="submit" value="Release" class='btn btn-primary btn-sm'>

		</form>
	</div>

<?php endbuild()?>



<?php build('scripts') ?>



	 <script type="text/javascript" defer>

	

		 $( document ).ready(function(){

	

		      $("#check_button").on('click' , function(e)

		      {	

		      

					$('.my_check_box').prop('checked', true);  

			  });





		      $("#uncheck_button").on('click' , function(e)

		      {	

		      

					$('.my_check_box').prop('checked', false);  

			  });

			   

		 });

	</script>

<?php endbuild()?>



<?php loadTo('tmp/layout')?>