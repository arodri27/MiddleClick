<?php
	ob_start();
	define ('TITLE', 'User page');
	include ('Include/common.php');
	mydoctype();
	myheader("<h1>MiddleClik</h1>");
	
	
	if (isset($_POST['logout'])){
		logout();
	}
	
	$html = "";
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') { // If I have filled the form
	
		session_start();
		handle_login();
		
		$host = 'lake13.rice.iit.edu:3306';
		$username = 'iituser';
		$password = '-8iituser!';
		$database = 'middleclik';
		$tablename = 'users';
		
		$dbc = mysql_connect($host, $username, $password); // Connect to the database

		if (mysql_select_db($database,$dbc)) { // If I am successfully connected to the database
				
			$query = "SELECT password FROM $tablename WHERE username=\"{$_SESSION['username']}\"";
			$result = mysql_query($query, $dbc);
			if ($result) {
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				if (($row['password'] == $_SESSION['password']) AND ($_SESSION['password'] != NULL)){	// If the user has correctly logged in
					$query = "SELECT company FROM $tablename WHERE username=\"{$_SESSION['username']}\""; // Which company?
					$result = mysql_query($query, $dbc);
					if ($result) {
						$row = mysql_fetch_array($result, MYSQL_ASSOC);
						$_SESSION['company'] = "{$row['company']}";
						
						/* User code */
						if ($_SESSION['company'] != 'middleclik') {	// If I am user
							mysql_close($dbc);	// Close the conection with the database
							if (is_dir("Content/{$_SESSION['company']}")){	// If the folder for the company exists
								$staff = scandir("Content/{$_SESSION['company']}");	// Get the contents to show
								foreach($staff as $key => $file){
									$extension = pathinfo($file, PATHINFO_EXTENSION);	// Get the extension of the contents
									$filename = pathinfo($file, PATHINFO_FILENAME);	// Get the name of the campaign
									
									/* CSV */
									if ($extension == 'csv') {	// If it's a CSV flie
										print "Information of {$_SESSION['company']} <br />";
										$datum = getCSVfromFile("Content/{$_SESSION['company']}/marketing.csv");
										print "<table>";
										foreach($datum as $row => $dataInRow) {
											$columType = "td";
											if ($row == 0) {
												$columType = "th";
											}											
											print "<tr>";
											foreach($dataInRow as $columnValue) {
												print "<$columType>".$columnValue."</$columType>";
											}
											print "</tr>";
										}
										print "</table>";
									}
									/* HTML */
									elseif ($extension == 'html') {	// If it's a html snippet
										$html .= "Snippet for the campaign $filename <br />";
										$html .= "<iframe src=\"Content/{$_SESSION['company']}/$file\"></iframe><br />";
										$html .= "<br />";
									}									
									/* Image */
									elseif ($extension != NULL){ // If it's an image
										$html .= "Image for the campaign $filename <br />";
										$html .= "<img src=\"Content/{$_SESSION['company']}/$file\"><br />";
									}
									
								}
							}
							else {
								$html .= "We are sorry, but there is nothing to show for your company <br />";
							}
							$html .= "<form action=\"index.php\" method=\"post\">
											<input type=\"submit\" name=\"logout\" value=\"Logout\" />
										</form> <br />";
						}
						
						
						else {	// If I am admin
							mysql_close($dbc);	// Close the conection with the database
							header("Location: Administration/admin.php");
						}				

						
					}
				}
				else {	// If authentication failed
					$html .= "Please enter a valid username or password <br />
					<form action=\"index.php\" method=\"post\">
						<p>Username: <input type=\"text\" name=\"username\" size=\"20\" /></p>
						<p>Password: <input type=\"password\" name=\"password\" size=\"20\" /></p>
						<input type=\"submit\" name=\"submit\" value=\"Login\" />
					</form>";
				}
			}
			
		}
				
		else {
			$html .="An error ocurred when connecting to database. Please try again later <br />";
		}
		
	}
	
	else { // If I haven't filled the form
		$html .= "<form action=\"index.php\" method=\"post\">
						<p>Username: <input type=\"text\" name=\"username\" size=\"20\" /></p>
						<p>Password: <input type=\"password\" name=\"password\" size=\"20\" /></p>
						<input type=\"submit\" name=\"submit\" value=\"Login\" />
					</form>";
	}
	
	mybody($html);
	myfooter();
	ob_end_flush();
?>