<?php
	require_once('shared.php');	

	$bookTitle = $_REQUEST['search_by_title'];
	$bookSubject = $_REQUEST['search_by_subject'];
	$bookAuthor = $_REQUEST['search_by_author'];
	$bookPublisher = $_REQUEST['search_by_publisher'];

	$booksOrder = $_REQUEST['order_by'];
	$booksOrder = empty($booksOrder) ? 'Publication_Year' : $booksOrder;

	$books = DatabaseHelper::getBooks($bookTitle,$bookSubject,$bookAuthor,$bookPublisher, $booksOrder);		

	// echo $bookCount . " ";
	// echo $booksPerCol
	// var_dump($books);
?>

<?php include('layouts/header.php'); ?>

<!-- <div class="container-fluid">
	<div class="hero">
		<h1>A BIG PHOTO HERE</h1>
	</div>
</div> -->

<div class="container-fluid">
	<div class="row form-container search-container">	
		<div class="search-advanced-container">
			<form action="index.php" method="POST">
				<div class="col-md-6">
					<div class="input-group">		  
					  <input type="text" name="search_by_title" class="form-control" placeholder="A Study in Scarlet" value="<?=$bookTitle?>">
					  <span class="input-group-addon">Title</span>
					</div>						
				</div>
				<div class="col-md-6">
					<div class="input-group">		  
					  <input type="text" name="search_by_subject" class="form-control" placeholder="Mystery" value="<?=$bookSubject?>">
					  <span class="input-group-addon">Subject</span>
					</div>						
				</div>
				<div class="col-md-6">
					<div class="input-group">		  
					  <input type="text" name="search_by_author" class="form-control" placeholder="Arthur Canon Doyle" value="<?=$bookAuthor?>">
					  <span class="input-group-addon">Author</span>
					</div>						
				</div>
				<div class="col-md-6">
					<div class="input-group">		  
					  <input type="text" name="search_by_publisher" class="form-control" placeholder="People Who Pubished Sherlock Holmes" value="<?=$bookPublisher?>">
					  <span class="input-group-addon">Publisher</span>
					</div>						
				</div>
				<div class="col-md-12">						
					<div class="search-btn">
						<button name="action" value="search" class="btn btn-primary" type="submit">Search</button>					
					</div>									
				</div>				
			</form>

			
			<form action="index.php" method="POST">
				<div class="col-md-12">
					<div class="order-btn-group">
						<div class="input-group">						
							<div class="btn-group" role="group">
							  <button type="submit" name="order_by" value="Publication_Year" class="btn <?=($booksOrder == 'Publication_Year' ? 'btn-success' : 'btn-default')?>">Order by Publication Year</button>
							  <button type="submit" name="order_by" value="Average_Rating" class="btn <?=($booksOrder == 'Average_Rating' ? 'btn-success' : 'btn-default')?>">Order by Average Ratings</button>
							</div>					
						</div>												
					</div>					
				</div>
			</form>
			
		</div>			
	</div>
</div>

<div class="container-fluid">
	<div class="row crop">		
		<div class="col-md-12">
			<?php 
				$cols = 4;
				$bookCount = count($books);
				for ($j=1; $j <= $cols; $j++) { 
			?>
				<div class="col-md-3">
					<?php 						
						for ($i = $j; $i < $bookCount; $i += $cols) { 
						$book = $books[$i];
					?>
						<div class="book-container">
							<div class="book <?php echo (intval($book['Num_Copies']) <= 0 ? 'bg-warning' : ''); ?>">
								<div class="cover">
									<img src="http://lorempixel.com/300/600/abstract" alt="title">
									<!-- <img src="images/sample.jpg" alt="title"> -->
								</div>
								<div>
									<h4><a href="book.php?isbn=<?=$book['ISBN']?>"><?=$book['Book_Title']?></a></h4>													
									<p><span class="label label-info">Written by</span> <?=($book['Authors'] ? $book['Authors'] : 'unknown')?>
									<br>
									<span class="label label-info">Published by</span> <?=$book['Publisher']?> <span class="label label-info">in</span> <?=$book['Publication_Year']?>
									<br>
									<span class="label label-info">Format</span> <?=$book['Cover']?>
									<br>
									<span class="label label-info">Price</span> <?= (intval($book['Num_Copies']) <= 0 ? 'out of stock' : 'Rs. ' . $book['Price']) ?>
									</p>
									<p class="text-right"><span class="badge lg"><?= empty($book['Average_Rating']) ? 0 : $book['Average_Rating'] ?></span></p>
								</div>
							</div>
						</div>
					<?php } ?>	
				</div>	
			<?php } ?>			
		</div>	
	</div>
</div>


<?php include('layouts/footer.php'); ?>
