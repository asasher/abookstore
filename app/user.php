<?php
	require_once('shared.php');	

	$action = $_REQUEST['action'];
	$err;
	if(empty($_USER_)) {
		Utils::redirect(__APPURL__ . '/login.php');
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POST') {

		if(empty($action)) {
			// do nothing			
		}
	}

	$orders = DatabaseHelper::getOrderHistory($_USER_['Id']);
	$critiques = DatabaseHelper::getCritiqueHistory($_USER_['Id']);
	$critiqueRatings = DatabaseHelper::getCritiqueRatingHistory($_USER_['Id']);
?>

<?php include('layouts/header.php'); ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<p class="text-center text-danger"><?=(isset($err) ? $err : "")?></p>
		</div>
		<div class="col-md-12">
			<div class="user-info">
				<dl class="dl-horizontal">
				  <dt>Username</dt>
				  <dd><?=$_USER_['Login']?></dd>
				  <dt>Name</dt>
				  <dd><?=($_USER_['First_Name'] . ' ' . $_USER_['Last_Name'])?></dd>
				  <dt>Address</dt>
				  <dd><?=$_USER_['Address']?></dd>
				  <dt>Credit Card Number</dt>
				  <dd><?=$_USER_['Credit_Card_Number']?></dd>
				</dl>			
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="table-container">				
				<h4>Orders History</h4>
				<?php if(!empty($orders)) { ?>
					<table class="table table-striped">
						<tr>
							<th>ISBN</th>						
							<th>Book Title</th>
							<th>Price</th>
							<th>Number of Copies</th>
							<th>DateTime</th>
						</tr>		
						<?php foreach ($orders as $order) { ?>					
							<tr>
								<td><a href="book.php?<?=$order['ISBN']?>"><?=$order['ISBN']?></a></td>
								<td><?=$order['Book_Title']?></td>
								<td><?=$order['Price']?></td>
								<td><?=$order['Num_Copies']?></td>
								<td><?=$order['DateTime']?></td>
							</tr>
						<?php } ?>
					</table>			
				<?php } else { ?>
				<div class="no-item-panel bg-warning">
					<p class="text-center">no orders yet</p>					
				</div>				
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="table-container">				
				<h4>Critiques History</h4>
				<?php if(!empty($critiques)) { ?>
					<table class="table table-striped">
						<tr>
							<th>ISBN</th>						
							<th>Book Title</th>
							<th>Rating</th>
							<th>Comment</th>
						</tr>		
						<?php foreach ($critiques as $critique) { ?>					
							<tr>
								<td><a href="book.php?<?=$order['ISBN']?>"><?=$critique['ISBN']?></a></td>
								<td><?=$critique['Book_Title']?></td>
								<td><?=$critique['Rating']?></td>
								<td><?=$critique['Comment']?></td>
							</tr>
						<?php } ?>
					</table>			
				<?php } else { ?>
				<div class="no-item-panel bg-warning">
					<p class="text-center">no critiques yet</p>					
				</div>				
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="table-container">				
				<h4>Critiques Rating History</h4>
				<?php if(!empty($critiqueRatings)) { ?>
					<table class="table table-striped">
						<tr>
							<th>ISBN</th>						
							<th>Login</th>
							<th>Rating</th>
							<th>Comment</th>							
							<th>Average_Usefulness</th>
							<th>Usefulness</th>
						</tr>		
						<?php foreach ($critiqueRatings as $critiqueRating) { ?>					
							<tr>
								<td><a href="book.php?<?=$order['ISBN']?>"><?=$critiqueRating['ISBN']?></a></td>
								<td><?=$critiqueRating['Login']?></td>
								<td><?=$critiqueRating['Rating']?></td>
								<td><?=$critiqueRating['Comment']?></td>
								<td><?=$critiqueRating['Average_Usefulness']?></td>
								<td><?=$critiqueRating['Usefulness']?></td>
							</tr>
						<?php } ?>
					</table>			
				<?php } else { ?>
				<div class="no-item-panel bg-warning">
					<p class="text-center">no critiques yet</p>					
				</div>				
				<?php } ?>
			</div>
		</div>
	</div>
</div>


<?php include('layouts/footer.php'); ?>
