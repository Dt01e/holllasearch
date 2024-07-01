<?php
include("config.php"); // Includes the configuration file that sets up the database connection
include("classes/SiteResultsProvider.php"); // Includes the SiteResultsProvider class for handling search results

if(isset($_GET["term"])) { // Checks if the "term" parameter is present in the URL
	$term = $_GET["term"]; // Retrieves the search term from the URL
}
else {
	exit("You must enter a search term"); // Exits the script if no search term is provided
}

$type = isset($_GET["type"]) ? $_GET["type"] : "sites"; // Retrieves the type parameter from the URL, defaults to "sites" if not present
$page = isset($_GET["page"]) ? $_GET["page"] : 1; // Retrieves the page parameter from the URL, defaults to 1 if not present
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Holla</title> <!-- Sets the title of the web page -->

	<link rel="stylesheet" type="text/css" href="assets/css/style.css"> <!-- Links to the external CSS file for styling -->

</head>
<body>

	<div class="wrapper">
	
		<div class="header">

			<div class="headerContent">

				<div class="logoContainer">
					<a href="index.php"> <!-- Link to the homepage -->
						<img src="assets/images/hollaLogo.png"> <!-- Displays the logo -->
					</a>
				</div>

				<div class="searchContainer">

					<form action="search.php" method="GET"> <!-- Form for submitting search queries -->

						<div class="searchBarContainer">

							<input class="searchBox" type="text" name="term" value="<?php echo $term; ?>"> <!-- Input field pre-filled with the search term -->
							<button class="searchButton"> <!-- Submit button for the form -->
								<img src="assets/images/icons/search.png"> <!-- Search icon on the button -->
							</button>
						</div>

					</form>

				</div>

			</div>

			<div class="tabsContainer">

				<ul class="tabList">

					<li class="<?php echo $type == 'sites' ? 'active' : '' ?>"> <!-- Checks if the current type is "sites" to set the active class -->
						<a href='<?php echo "search.php?term=$term&type=sites"; ?>'> <!-- Link to the sites search results -->
							Sites
						</a>
					</li>

				</ul>

			</div>
		</div>

		<div class="mainResultsSection">

			<?php
			$resultsProvider = new SiteResultsProvider($con); // Creates a new SiteResultsProvider object
			$pageSize = 20; // Sets the number of results to display per page

			$numResults = $resultsProvider->getNumResults($term); // Gets the total number of results for the search term

			echo "<p class='resultsCount'>$numResults results found</p>"; // Displays the total number of results

			echo $resultsProvider->getResultsHtml($page, $pageSize, $term); // Displays the search results for the current page
			?>

		</div>

		<div class="paginationContainer">

			<div class="pageButtons">

				<div class="pageNumberContainer">
					<img src="assets/images/start.png"> <!-- Image for the start page button (not functional in this snippet) -->
				</div>

				<?php
				$pagesToShow = 10; // Sets the maximum number of pagination buttons to display
				$numPages = ceil($numResults / $pageSize); // Calculates the total number of pages
				$pagesLeft = min($pagesToShow, $numPages); // Sets the number of pages left to show

				$currentPage = $page - floor($pagesToShow / 2); // Calculates the starting page number for pagination

				if($currentPage < 1) { // Ensures the current page is not less than 1
					$currentPage = 1;
				}

				if($currentPage + $pagesLeft > $numPages + 1) { // Ensures the pagination does not exceed the total number of pages
					$currentPage = $numPages + 1 - $pagesLeft;
				}

				while($pagesLeft != 0 && $currentPage <= $numPages) { // Loops through the pages to display pagination buttons

					if($currentPage == $page) { // Highlights the current page
						echo "<div class='pageNumberContainer'>
								<img src='assets/images/pageSelected.png'>
								<span class='pageNumber'>$currentPage</span>
							</div>";
					}
					else { // Displays links for other pages
						echo "<div class='pageNumberContainer'>
								<a href='search.php?term=$term&type=$type&page=$currentPage'>
									<img src='assets/images/page.png'>
									<span class='pageNumber'>$currentPage</span>
								</a>
						</div>";
					}

					$currentPage++; // Increments the current page
					$pagesLeft--; // Decrements the pages left to show
				}
				?>

				<div class="pageNumberContainer">
					<img src='assets/images/end.png'> <!-- Image for the end page button (not functional in this snippet) -->
				</div>

			</div>

		</div>

	</div>

</body>
</html>
