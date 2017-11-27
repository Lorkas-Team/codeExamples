<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/API/Users_API_by_george/DB_Functions.php';
	$db = new DB_Functions();

	// json response array
	$response = array("error" => FALSE);
	
	if (isset($_POST['prod_name']) && isset($_POST['prod_descr']) && isset($_POST['price_lower']) &&
		isset($_POST['price_higher']) && isset($_POST['user_email'])) {
	
		$prod_name = $_POST['prod_name'];
		$prod_descr = $_POST['prod_descr'];
		$email = $_POST['user_email'];
		$price_lower = $_POST['price_lower'];
		$price_higher = $_POST['price_higher'];
		
		//TODO check if desire already exists
		
		// create a new desire
		$new_desire = $db->storeNewDesire($email, $prod_name, $prod_descr, 
				$price_lower, $price_higher);
		if ($new_desire) {
			// desire stored successfully
			$response["error"] = FALSE;
			$response["desire"]["name"] = $new_desire["name"];
			$response["desire"]["descr"] = $user["descr"];
			$response["desire"]["price_lower"] = $new_desire["price_lower"];
			$response["desire"]["price_higher"] = $user["price_higher"];
			echo json_encode($response);
		} else {
			// desire failed to store
			$response["error"] = TRUE;
			$response["error_msg"] = "Unknown error occurred in registration!";
			echo json_encode($response);
		}
	} else {
		$response["error"] = TRUE;
		$response["error_msg"] = "Required parameters are missing!";
		echo json_encode($response);
	}
?>