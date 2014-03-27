<?php

	/*
	*	States that the doctype is html
	*/
	
	function mydoctype() {
		print '<!doctype html>';
	}

	/*
	*	Function that puts into a html header the parameter it receives
	*/
	function myheader($additionalHeaderContent = NULL) {
		print '<html>';
			print '<head>';
				print '<title>';
					if (defined('TITLE')) {
						print TITLE;
					}
					else {
						print 'MiddleClik';
					}
				print '</title>';
				print "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />";
				print $additionalHeaderContent;
				print "<style type=\"text/css\">";
					print '//this is where my css, js goes';
				print "</style>";
			print '</head>';
	}

	/*
	*	Puts into a html body the parameters it receives. Second parameter is for a div into the body.
	*/
	function mybody($bodyContents = '', $asideContent = '') {
		print '<body>';
			print '<div>';
				print $bodyContents;
				myaside($asideContent);
			print '</div>';
		print '</body>';
	}

	/*
	*	Function that creates a div called aside with the content of the parameter it receives
	*/
	function myaside($asideContent) {
		print '<div id="aside">';
			print $asideContent;
		print '</div>';
	}

	/*
	*	Closes the html class
	*/
	function myfooter() {
		print '</html>';
	}
	
	function handle_login(){
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['password'] = $_POST['password'];
		$_SESSION['company'] = '';
	}
	
	function logout() {
		unset ($_SESSION);
		$_SESSION = array();
		session_destroy();
		header("Location: /src/index.php");
	}
	
	function getCSVfromFile($path) {
		$dataFile = file($path);
		$datum = array();		
		foreach($dataFile as $line) {
			$datum[] = str_getcsv($line);
		}		
		return $datum;
	}

?>