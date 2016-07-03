<?php
class DatabaseHelper {
	private static $_conn;

	private static function connect() {
		if(isset(static::$_conn) && static::$_conn != null) {
		}
		else
		{
			static::$_conn = new mysqli('localhost','root','rootpass','DB_Project_BookStore');
			if (static::$_conn->connect_error) {
			    die("Connection failed: " . static::$_conn->connect_error);
			}
		}
		return static::$_conn;
	}

	public static function test() {
		$conn = self::connect();
		echo 'got connected';
	}

	public static function buildQuery($table, $attrs, $limit, $offset) {
		// leaves db open to sql injections but short on time
		// and this is not a production app
		$sql = 'SELECT * FROM ' . $table . ' ';
		if(!empty($attrs)) {
			$sql .= 'WHERE ';
			$first = true;
			foreach ($attrs as $key => $value) {
				if(!$first) {
					$sql .= 'AND ';
				}
				$sql .= $key . ' = \'' . $value . '\' ';
				$first = false;
			}
		}
		if(!empty($limit)) {
			$sql .= 'LIMIT ' . $limit . ' ';
		}
		if(!empty($offset)) {
			$sql .= 'OFFSET ' . $offset . ' ';
		}
		return $sql;
	}

	public static function addCritique($isbn,$criticId,$rating,$comments) {
		$conn = static::connect();

		$sql = "INSERT INTO Book_Critiques(ISBN,Rating,Comment,Critic_Id) ";
		$sql .= "VALUES ('" . $isbn . "'," . $rating . ",'" . $comments . "','" . $criticId . "')";

		// echo $sql;

		$res = $conn->query($sql);

		return !empty($res);		
	}

	public static function addCritiqueRating($critiqueId,$metaCriticId,$isUseful) {
		$conn = static::connect();		

		$sql = "INSERT INTO Critique_Ratings VALUES(" . $critiqueId . ", " . $metaCriticId . ", " . ($isUseful ? "'Useful'" : "'Useless'") . ")";

		$res = $conn->query($sql);

		return !empty($res);
	}

	public static function addCustomer($username, $password, $firstName, $lastName, $address, $phoneNumber, $creditCardNumber) {
		$conn = static::connect();

		$sql = "INSERT INTO Customers (Login,Password,First_Name,Last_Name,Address,Phone,Credit_Card_Number) "; 
		$sql .= "VALUES ('$username','$password','$firstName','$lastName','$address','$phoneNumber','$creditCardNumber')";

		$res = $conn->query($sql);

		return !empty($res);
	}

	public static function getSuggestedBooks($bookISBN, $customerId) {
		$conn = static::connect();

		$sql = "SELECT b.ISBN, ";
		$sql .= "b.Book_Title, ";
		$sql .= "COUNT(*) AS Sale_Count ";
		$sql .= "FROM Orders o1  ";
		$sql .= "JOIN Orders o2 ON o1.Customer_Id = o2.Customer_Id AND o1.ISBN != o2.ISBN ";
		$sql .= "JOIN Books b ON b.ISBN = o2.ISBN ";
		$sql .= "WHERE o1.ISBN = '" . $bookISBN . "' AND o1.Customer_Id != '" . $customerId . "'";
		$sql .= "GROUP BY o1.ISBN, o2.ISBN ";
		$sql .= "ORDER BY Sale_Count DESC ";

		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}	

	public static function getCritiqueRatingHistory($customerId) {
		$conn = static::connect();

		$sql = "SELECT bc.ISBN, ";
		$sql .= "c.Login, ";
		$sql .= "bc.Rating, ";
		$sql .= "bc.Comment, ";
		$sql .= "cr.Usefulness, ";
		$sql .= "(SELECT COUNT(*) FROM Critique_Ratings WHERE Critique_Id = bc.Id AND Usefulness = 'Useful') / (SELECT COUNT(*) FROM Critique_Ratings WHERE Critique_Id = bc.Id) AS Average_Usefulness ";
		$sql .= "FROM Book_Critiques bc  ";
		$sql .= "JOIN Customers c ON bc.Critic_Id = c.Id ";
		$sql .= "JOIN Critique_Ratings cr ON bc.Id = cr.Critique_Id ";
		$sql .= "WHERE cr.Meta_Critic_Id = '" . $customerId . "' ";
		$sql .= "ORDER By Usefulness DESC; ";

		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}

	public static function getCritiqueHistory($customerId) {
		$conn = static::connect();

		$sql = "SELECT b.ISBN, ";
		$sql .= "b.Book_Title, ";
		$sql .= "bc.Rating, ";
		$sql .= "bc.Comment ";
		$sql .= "FROM Book_Critiques bc  ";
		$sql .= "NATURAL JOIN Books b ";
		$sql .= "WHERE Critic_Id = '" . $customerId . "'; ";

		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}

	public static function updateNumCopies($bookISBN, $numCopies) {
		$conn = static::connect();

		$sql = "UPDATE Books ";
		$sql .= "SET Num_Copies = ". $numCopies . " ";
		$sql .= "WHERE ISBN = '" . $bookISBN . "' ";

		// echo $sql;

		$res = $conn->query($sql);

		return !empty($res);
	}

	public static function getTopCritics($limit = 10) {
		$conn = static::connect();

		$sql = "SELECT Critic_Id, ";
		$sql .= "c.Login, ";
		$sql .= "c.First_Name, ";
		$sql .=	"c.Last_Name, ";
		$sql .= "SUM(Usefulness = 'Useful') / COUNT(*) AS Average_Usefulness ";
		$sql .= "FROM Customers c  ";
		$sql .= "JOIN Book_Critiques bc ON c.Id = bc.Critic_Id ";
		$sql .= "JOIN Critique_Ratings cr ON bc.Id = cr.Critique_Id ";
		$sql .= "GROUP BY Critic_Id ";
		$sql .= "ORDER BY Average_Usefulness DESC ";
		$sql .= "LIMIT " . $limit . " ";

		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}

	public static function getTopAuthors($limit = 10) {
		$conn = static::connect();

		$sql = "SELECT a.Name AS Author, ";
		$sql .= "COUNT(*) AS Num_Copies ";
		$sql .= "FROM Orders o ";
		$sql .= "NATURAL JOIN Books b ";
		$sql .= "NATURAL JOIN Book_Authors ba ";
		$sql .= "JOIN Authors a ON ba.Author_Id = a.Id ";
		$sql .= "GROUP BY a.Id ";
		$sql .= "ORDER BY Num_Copies DESC ";
		$sql .= "LIMIT " . $limit . " ";


		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}

	public static function getTopSales($limit = 10) {
		$conn = static::connect();

		$sql = "SELECT b.ISBN, ";
		$sql .= "b.Book_Title, ";
		$sql .= "COUNT(*) AS Num_Copies ";
		$sql .= "FROM Orders o  ";
		$sql .= "JOIN Books b ON o.ISBN = b.ISBN ";
		$sql .= "GROUP BY b.ISBN ";
		$sql .= "LIMIT " . $limit . " ";

		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}

	public static function getOrderHistory($customerId) {
		$conn = static::connect();

		$sql = "SELECT b.ISBN, ";
		$sql .= "b.Book_Title, ";
		$sql .= "b.Price, ";
		$sql .= "o.DateTime, ";
		$sql .= "COUNT(*) AS Num_Copies ";
		$sql .= "FROM Orders o NATURAL JOIN Books b ";
		$sql .= "WHERE Customer_Id = '" . $customerId . "' ";
		$sql .= "GROUP BY b.ISBN ";
		$sql .= "ORDER BY o.DateTime ";

		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}

	public static function getCritiques($bookISBN) {
		$conn = static::connect();

		$sql = "SELECT bc.Id, ";
		$sql .= "bc.ISBN, ";
		$sql .= "c.Login, ";
		$sql .= "bc.Rating, ";
		$sql .= "bc.Comment, ";
		$sql .= "(SELECT COUNT(*) FROM Critique_Ratings WHERE Critique_Id = bc.Id AND Usefulness = 'Useful') / (SELECT COUNT(*) FROM Critique_Ratings WHERE Critique_Id = bc.Id) AS Usefulness ";
		$sql .= "FROM Book_Critiques bc  ";
		$sql .= "JOIN Customers c ON bc.Critic_Id = c.Id ";
		$sql .= "WHERE ISBN = '" . $bookISBN . "' ";
		$sql .= "ORDER By Usefulness DESC ";

		// echo $sql;

		$res = $conn->query($sql);

		$items = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($items, $row);
			}
		}

		return $items;
	}

	public static function getBook($bookISBN) {
		$conn = static::connect();

		$sql = "SELECT b.ISBN, ";
		$sql .= "Book_Title, ";
		$sql .= "Publication_Year, ";
		$sql .= "Publisher, ";
		$sql .= "Price, ";
		$sql .= "Subject, ";
		$sql .= "Cover, ";
		$sql .= "Num_Copies, ";
		$sql .= "AVG(Rating) AS Average_Rating, ";
		$sql .= "bas.Authors ";
		$sql .= "FROM Books b ";
		$sql .= "LEFT JOIN (SELECT ba.ISBN, ";
		$sql .= "GROUP_CONCAT(a.Name SEPARATOR ',') AS Authors ";
		$sql .= "FROM Book_Authors ba ";
		$sql .= "LEFT JOIN Authors a ON ba.Author_Id = a.Id ";
		$sql .= "GROUP BY ba.ISBN) bas ON bas.ISBN = b.ISBN ";
		$sql .= "LEFT JOIN Book_Critiques bc ON bc.ISBN = bas.ISBN ";
		$sql .= "WHERE b.ISBN = '" . $bookISBN . "' ";
		$sql .= "GROUP BY b.ISBN ";
		$sql .= "ORDER BY b.ISBN ";
		
		// echo $sql;

		$res = $conn->query($sql);

		$books = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($books, $row);
			}
		}

		return $books[0];
	}

	public static function getBooks($bookTitle, $bookSubject, $bookAuthor, $bookPublisher, $booksOrder) {
		$conn = static::connect();

		$sql = "SELECT b.ISBN, ";
		$sql .= "Book_Title, ";
		$sql .= "Publication_Year, ";
		$sql .= "Publisher, ";
		$sql .= "Price, ";
		$sql .= "Subject, ";
		$sql .= "Cover, ";
		$sql .= "Num_Copies, ";
		$sql .= "AVG(Rating) AS Average_Rating, ";
		$sql .= "bas.Authors ";
		$sql .= "FROM Books b ";
		$sql .= "LEFT JOIN (SELECT ba.ISBN, ";
		$sql .= "GROUP_CONCAT(a.Name SEPARATOR ',') AS Authors ";
		$sql .= "FROM Book_Authors ba ";
		$sql .= "LEFT JOIN Authors a ON ba.Author_Id = a.Id ";
		$sql .= "GROUP BY ba.ISBN) bas ON bas.ISBN = b.ISBN ";
		$sql .= "LEFT JOIN Book_Critiques bc ON bc.ISBN = bas.ISBN ";
		$sql .=	"WHERE Book_Title LIKE '%" . $bookTitle . "%' AND ";
		$sql .=	"Subject LIKE '%" . $bookSubject . "%' AND ";
		$sql .=	"Publisher LIKE '%" . $bookPublisher . "%' AND ";
		$sql .=	"(Authors LIKE '%" . $bookAuthor . "%' OR Authors IS NULL) ";
		$sql .=	"GROUP BY b.ISBN ";
		$sql .= "ORDER BY " . $booksOrder . " DESC ";

		// echo $sql;

		$res = $conn->query($sql);

		$books = [];
		if ($res->num_rows > 0) {
		    while($row = $res->fetch_assoc()) {
		    	array_push($books, $row);
			}
		}

		return $books;
	}

	public static function addOrder($bookISBN,$customerId) {
		$conn = static::connect();

		$sql = "INSERT INTO Orders(Customer_Id, ISBN, DateTime, Status) ";
		$sql .= "Values('" . $customerId . "','" . $bookISBN . "',NOW(),1)";

		$res = $conn->query($sql);

		return !empty($res);
	}

	public static function getCustomer($login, $pass) {
		$conn = static::connect();
		$sql = 'SELECT * FROM Customers WHERE Login = \'' . $login . '\' ';
		if(!empty($pass)) {
			$sql .= 'AND Password = \'' . $pass . '\' ';
		}

		// echo $sql;

		$res = $conn->query($sql);
		return $res->fetch_assoc();
	}
}
