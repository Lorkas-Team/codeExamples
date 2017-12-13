<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/API/Users_API_by_george/DB_Functions.php';
	
	$db = new DB_Functions();
	$response = array("error" => FALSE);
	
	if(isset($_POST["name_desire"])) {
		
		$name_desire = $_POST["name_desire"];
		
		$offers = $db->getOffersByDesireName($name_desire);
		if ($offers) {
			$response["error"] = FALSE;
			$response["offers"] = $offers;
			echo json_encode($response);
		} else {
			$response["error"] = TRUE;
			$response["error_msg"] = "Unknown error occurred on getting offers.";
			echo json_encode($response);
		}
	} else {
		$response["error"] = TRUE;
		$response["error_msg"] = "Required parameters are missing!";
		echo json_encode($response);
	}
?>