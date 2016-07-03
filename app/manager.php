<?php
	require_once('shared.php');	

	$action = $_REQUEST['action'];
	$err;
	if(empty($_USER_) || empty($_USER_['Manager'])) {
		Utils::redirect(__APPURL__ . '/login.php');
	}
	else if($_SERVER['REQUEST_METHOD'] == 'POST') {

		if(empty($action)) {
			// do nothing			
		}
	}

	$topBooks = DatabaseHelper::getTopSales();
	$topAuthors = DatabaseHelper::getTopAuthors();
	$topCritics = DatabaseHelper::getTopCritics();
?>

<?php include('layouts/header.php'); ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="table-container">				
				<h4>Top 10 Books</h4>
				<?php if(!empty($topBooks)) { ?>
					<table class="table table-striped">
						<tr>
							<th>ISBN</th>						
							<th>Book Title</th>
							<th>Number of Copies</th>
						</tr>		
						<?php foreach ($topBooks as $topBook) { ?>					
							<tr>
								<td><a href="book.php?<?=$topBook['ISBN']?>"><?=$topBook['ISBN']?></a></td>
								<td><?=$topBook['Book_Title']?></td>
								<td><?=$topBook['Num_Copies']?></td>
							</tr>
						<?php } ?>
					</table>			
				<?php } else { ?>
				<div class="no-item-panel bg-warning">
					<p class="text-center">no top books yet</p>					
				</div>				
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="table-container">				
				<h4>Top 10 Authors</h4>
				<?php if(!empty($topAuthors)) { ?>
					<table class="table table-striped">
						<tr>
							<th>Author Name</th>						
							<th>Number of Copies</th>
						</tr>		
						<?php foreach ($topAuthors as $topAuthor) { ?>					
							<tr>								
								<td><?=$topAuthor['Author']?></td>
								<td><?=$topAuthor['Num_Copies']?></td>
							</tr>
						<?php } ?>
					</table>			
				<?php } else { ?>
				<div class="no-item-panel bg-warning">
					<p class="text-center">no top authors yet</p>					
				</div>				
				<?php } ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="table-container">				
				<h4>Top 10 Critics</h4>
				<?php if(!empty($topCritics)) { ?>
					<table class="table table-striped">
						<tr>
							<th>Critic Login</th>						
							<th>Critic Name</th>						
						</tr>		
						<?php foreach ($topCritics as $topCritic) { ?>					
							<tr>								
								<td><?=$topCritic['Login']?></td>
								<td><?=$topCritic['First_Name'] . ' ' . $topCritic['Last_Name']?></td>
							</tr>
						<?php } ?>
					</table>			
				<?php } else { ?>
				<div class="no-item-panel bg-warning">
					<p class="text-center">no top critics yet</p>					
				</div>				
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php include('layouts/footer.php'); ?>
