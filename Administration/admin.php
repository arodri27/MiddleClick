<?php
	
	ob_start();
	define ('TITLE', 'Administration page');	
	include ('../Include/common.php');
	
	mydoctype();
	
	myheader("<h1>MiddleClik</h1>");
	
	session_start();
	$html = "";	
	
	
	/* Code for saving the HTML snippet */
	if (!empty($_POST['snippet'])){	//	If the snippet has been sent
		if ((!empty($_POST['company'])) AND (!empty($_POST['campaign']))) {	//	And campaign and company fields have been filled
			$company = $_POST['company'];
			$campaign = $_POST['campaign'];
			if (!file_exists("../Content/$company")){	// 
				mkdir ("../Content/$company");
			}
			if (!file_exists("../Content/$company/$campaign.html")){
				file_put_contents ("../Content/$company/$campaign.html", $_POST['snippet']);
				$html .= "Your snippet has been successfully sent <br />";
			}
			else {
				$html .= "The snippet for this campaign already exists. Please remove the existing file  first to replace it. <br />";
			}
		}
		else {
			$html .= "Please enter a valid company and campaign name <br />";
		}
		$_POST['snippet']=NULL;
		$_POST['company']=NULL;
		$_POST['campaign']=NULL;
	}
	
	/* Code for saving the image */
	if (isset($_FILES['image']['name'])){	//	If the image has been uploaded
		if ((!empty($_POST['company'])) AND (!empty($_POST['campaign']))) {	// And campaign and company fields have been filled
			$company = $_POST['company'];
			$campaign = $_POST['campaign'];
			if (!file_exists("../Content/$company")){	// And the folder for the company doesn't exist
				mkdir ("../Content/$company");
			}
			$extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // I check the file extension
			if ($extension != 'csv'){
				if (!file_exists("../Content/$company/$campaign.$extension")){
					move_uploaded_file($_FILES['image']['tmp_name'], "../Content/$company/$campaign.$extension");
					$html .= "Your image has been successfully sent <br />";
				}
				else {
					$html .= "The image for this campaign already exists. Please remove the existing file  first to replace it. <br />";
				}
			}
			else {
				$html .= "Please upload a valid image file <br />";
			}
		}
		else {
			$html .= "Please enter a valid company and campaign name <br />";
		}
		$_FILES['image']=NULL;
		$_POST['company']=NULL;
		$_POST['campaign']=NULL;
	}
	
	/* Code for saving the CSV file */
	if (isset($_FILES['csv']['name'])){	//	If the image has been uploaded
		if ((isset($_POST['company'])) AND (isset($_POST['campaign']))) {	// And campaign and company fields have been filled
			$company = $_POST['company'];
			$campaign = $_POST['campaign'];
			if (!file_exists("../Content/$company")){	// And the folder for the company doesn't exist
				mkdir ("../Content/$company");
			}
			$extension = pathinfo($_FILES['csv']['name'], PATHINFO_EXTENSION); // I check the file extension
			if ($extension == 'csv'){	//	If it's a CSV file
				if (!file_exists("../Content/$company/marketing.csv")){
					move_uploaded_file($_FILES['image']['tmp_name'], "../Content/$company/marketing.csv");
					$html .= "Your CSV file has been successfully uploaded <br />";
				}
				else {
					$html .= "The CSV file for this company already exists. Please remove the existing file  first to replace it. <br />";
				}
			}
			else {
				$html .= "Please upload a valid CSV file <br />";
			}
		}
		else {
			$html .= "Please enter a valid company and campaign name <br />";
		}
		$_FILES['csv']=NULL;
		$_POST['company']=NULL;
		$_POST['campaign']=NULL;
	}
	
	if ($_SESSION['company'] == 'middleclik') {
		$html .=	
			"You are successfully logged in. Please upload either a html snippet, an image or a CSV file for your company <br />
		<form action=\"admin.php\" method=\"post\" enctype=\"multipart/form-data\">
			<p>Company name: <input type=\"text\" name=\"company\" size=\"20\" /></p>
			<p>Campaign name: <input type=\"text\" name=\"campaign\" size=\"20\" /></p>
			<p>Enter the HTML snippet: <textarea name=\"snippet\" rows=\"8\" cols=\"45\" placeholder=\"Enter your snippet here\"></textarea></p>
			<p>Choose an image: <input type=\"file\" name=\"image\" size=\"chars\"> </p>
			<p>Choose a CSV file: <input type=\"file\" name=\"csv\" size=\"chars\"> </p>
			<input type=\"submit\" name=\"submit\" value=\"Submit\" />
		</form> <br />";
		
		$html .= "<form action=\"../index.php\" method=\"post\">
			<input type=\"submit\" name=\"logout\" value=\"Logout\" />
		</form> <br />";
	}
	else {
		$html .= "You don't have permission to view this page";
		print_r($_SESSION);
	}
	
	

	mybody($html);
	myfooter();
	
	ob_end_flush();
?>