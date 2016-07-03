<?php
	require_once('shared.php');	

	$bookISBN = $_REQUEST['isbn'];	

	$suggestedBooks;

	$action = $_REQUEST['action'];
	$buy_success = false;
	$err;
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$critiqueId = $_REQUEST['critique_id'];

		if(empty($action) || empty($_USER_)) {
			Utils::redirect(__APPURL__ . '/login.php');
		}
		else if(!empty($_USER_['Manager'])) {			
			if($action == 'update_num_copies') {
				$numCopies = $_REQUEST['num_copies'];
				if($numCopies < 0) {
					$err = 'number of copies cannot be less than 0';
				}
				else if(!DatabaseHelper::updateNumCopies($bookISBN,$numCopies)) {
					$err = "something went wrong.";
				}
			}
		}
		else if($action == 'up')
		{
			if(empty($critiqueId)) {
				// 0.o
			}
			else if(!DatabaseHelper::addCritiqueRating($critiqueId,$_USER_['Id'],true)) {
				$err = 'cannot rate on your own critique or rate more than once.';
			}
		}
		else if($action == 'down') {
			if(empty($critiqueId)) {
				// 0.o
			}
			else if(!DatabaseHelper::addCritiqueRating($critiqueId,$_USER_['Id'],false)){
				$err = 'cannot rate on your own critique or rate a critique more than once.';
			}
		}
		else if($action == 'buy') {
			if(empty($bookISBN)) {
				// 0.o
			}
			else if(!DatabaseHelper::addOrder($bookISBN,$_USER_['Id'])) {
				$err = 'sorry, your order could not be processed.';
			}
			else {
				$buy_success = true;
				$suggestedBooks = DatabaseHelper::getSuggestedBooks($bookISBN,$_USER_['Id']);				
			}
		}
		else if($action == 'critique') {
			$rating = $_REQUEST['rating'];
			$comments = $_REQUEST['comments'];
			if(empty($bookISBN) || empty($rating) || empty($rating)) {
				$err = 'all fields are required.';
			} 
			else if(!DatabaseHelper::addCritique($bookISBN,$_USER_['Id'],$rating,$comments)){
				$err = 'you can only critique once per book and rating should be between 0 and 10 inclusive.';
			}
		}
	}

	$book = DatabaseHelper::getBook($bookISBN);	
	$critiques = DatabaseHelper::getCritiques($bookISBN);	
	
	// var_dump($book);
	// var_dump($critiques);
?>

<?php include('layouts/header.php'); ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div>	
				<p class="text-center text-danger"><?=(isset($err) ? $err : "")?></p>
				<p class="text-center text-success"><?=(isset($buy_success) && $buy_success ? "you just ordered a copy of " . $book['Book_Title'] : "")?></p>
			</div>					
			<div class="book-container">
				<div class="lg book <?php echo (intval($book['Num_Copies']) <= 0 ? 'bg-warning' : ''); ?>">
					<div class="col-md-3">
						<div class="cover">
							<img src="http://lorempixel.com/300/600/abstract" alt="title">
						</div>						
					</div>
					<div class="col-md-9">
						<div>
							<h2><a href="book.php?isbn=<?=$book['ISBN']?>"><?=$book['Book_Title']?></a></h2>													
							<p><span class="label label-info">Written by</span> <?=($book['Authors'] ? $book['Authors'] : 'unknown')?>
							<br>
							<span class="label label-info">Published by</span> <?=$book['Publisher']?> <span class="label label-info">in</span> <?=$book['Publication_Year']?>
							<br>
							<span class="label label-info">Format</span> <?=$book['Cover']?>
							<br>
							<span class="label label-info">Price</span> <?= (intval($book['Num_Copies']) <= 0 ? 'out of stock' : 'Rs. ' . $book['Price']) ?>
							</p>
							<p class="text-left"><span class="badge lg"><?= $book['Average_Rating'] ?></span></p>							
						</div>						
						<?php if(empty($_USER_['Manager'])) {?>
							<form action="book.php" method="POST">
								<div class="input-group">
									<input name="isbn" type="hidden" value="<?=$book['ISBN']?>">
								</div>							
								<div class="btn-group pull-right" role="group">								
								  <button type="submit" name="action" value="buy" class="btn btn-success lg">Buy</button>
								</div>								
							</form>
						<?php } ?>
						<?php if(!empty($_USER_['Manager'])) {?>
							<form action="book.php" method="POST">
								<div class="input-group">
							      <span class="input-group-btn">
							        <button name="action" value="update_num_copies" class="btn btn-default" type="submit">Update Number of Copies</button>
							      </span>
							      <input name="isbn" type="hidden" class="form-control" value="<?= $book['ISBN'] ?>">
							      <input name="num_copies" min="0" type="number" class="form-control" value="<?= $book['Num_Copies'] ?>">
							    </div><!-- /input-group -->									
							</form>								
						<?php } ?>							
					</div>						
				</div>
			</div>											
		</div>
	</div>
</div>

<?php if(!empty($suggestedBooks)) { ?>
	<div class="container-fluid">
		<div class="row crop">		
			<div class="col-md-12">			
				<div class="books-container">
				<h4 class="text-center">You might also Like</h4>
				<hr>
				<?php 
					$cols = 6;
					$bookCount = count($suggestedBooks);
					for ($j=1; $j <= $cols; $j++) { 
				?>
					<div class="col-md-2">
						<?php 							
							for ($i = $j; $i < $bookCount; $i += $cols) { 
							$suggestedBook = $suggestedBooks[$i];
						?>
							<div class="book-container">
								<div class="book small">
									<div class="cover">
										<img src="http://lorempixel.com/300/600/abstract" alt="title">
										<!-- <img src="images/sample.jpg" alt="title"> -->
									</div>
									<div>
										<h4><a href="book.php?isbn=<?=$suggestedBook['ISBN']?>"><?=$suggestedBook['Book_Title']?></a></h4>									
									</div>
								</div>
							</div>
						<?php } ?>	
					</div>	
				<?php } ?>
				</div>
			</div>	
		</div>
	</div>
<?php } ?>

<div class="container-960">
	<?php if (!empty($critiques)) { ?>	
	<div class="critiques">
		<h4 class="text-center">Critiques</h4>
		<hr>
		<ul class="media-list">
			<?php foreach ($critiques as $critique) { ?>		
			  <li class="media">
			    <a class="media-left" href="#">			    
			      <h4><span class="label label-primary"><?=$critique['Login']?></span></h4>
			    </a>
			    <div class="media-body">
			      <p><span class="badge lg"><?=$critique['Rating']?></span>&nbsp;&nbsp; <?=$critique['Comment']?></p>
			    </div>
			    <?php if(!empty($_USER_) && empty($_USER_['Manager'])) { ?>
				    <div class="pull-right rating-btn-group">				    
				    	<form action="book.php" method="POST">
				    		<input name="isbn" type="hidden" value="<?=$bookISBN?>">
				    		<input name="critique_id" type="hidden" value="<?=$critique['Id']?>">
				    		<div class="btn-group-vertical" role="group">
							    <p>this critique was <button name="action" type="submit" value="up" class="btn btn-link btn-success"><u class="text-success">useful</u></button> | 
							    <button name="action" type="submit" value="down" class="btn btn-link btn-danger"><u class="text-danger">useless</u></button></p>
							</div>			    				    		
				    	</form>			   

				    </div>
			    <?php } ?>			    
			  </li>
			  <li><hr></li>
		  	<?php } ?>
		</ul>
	</div>
	<?php } ?>
	
	<?php if(empty($_USER_['Manager'])) {?>
		<div class="critique-form">
			<div class="form-container">
				<h4 class="text-center">Leave a Critique</h4>
				<form action="book.php" method="POST">			
					<input name="isbn" type="hidden" value="<?=$bookISBN?>">
					<div class="col-md-2">
						<div class="input-group">
						  <span class="input-group-addon">Rating</span>
						  <input type="number" min="0" max="10" name="rating" class="form-control" placeholder="0" required>
						</div>				
					</div>
					<div class="col-md-8">
						<div class="input-group">
						  <span class="input-group-addon">Comments</span>
						  <input type="text" name="comments" class="form-control" placeholder="meh..." required>
						</div>				
					</div>
					<div class="col-md-2">
						<div class="input-group">
						  <button name="action" value="critique" class="btn btn-primary" type="submit">Submit</button>
						</div>				
					</div>
				</form>
			</div>				
		</div>
	<?php } ?>
</div>


<?php include('layouts/footer.php'); ?>
