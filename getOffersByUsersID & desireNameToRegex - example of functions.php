<?php
public function getOffersByUsersID($users_id){
	$stmt_desires = $this->conn->prepare("SELECT id,price_low,price_high,product_name FROM desires WHERE users_id = ?");
	$stmt_desires->bind_param("i", $users_id);
	if ($stmt_desires->execute()) {
		$result_desires = $stmt_desires->get_result();
		$desires_numb_of_rows = $result_desires->num_rows;
		$stmt_desires->close();
		if ($desires_numb_of_rows > 0) {
			$offers = array();
			while ($row_desire = $result_desires->fetch_assoc()) {
				$tmp_desire = array();
				foreach($row_desire as $key=>$value) {
					$tmp_desire[$key] = $value;
				}
				$regex_desire_name = desireNameToRegex($tmp_desire['product_name']);
				$stmt_offers = $this->conn->prepare("SELECT * FROM offers WHERE product_name REGEXP ?");
				$stmt_offers->bind_param("s", $regex_desire_name);
				if ($stmt_offers->execute()) {
					$result_offers = $stmt_offers->get_result();
					$offers_numb_of_rows = $result_offers->num_rows;
					$stmt_offers->close();
					if($offers_numb_of_rows > 0) {
						while($row_offer = $result_offer->fetch_assoc()){
							$tmp_offer = array();
							$tmp_offer['desire_id'] = $tmp_desire['id'];
							foreach($row_offer as $key=>$value) {
								$tmp_offer[$key] = $value;
								//except image
							} 
							if($tmp_offer['price'] <= $tmp_desire['price_high'] 
									&& $tmp_offer['price'] >= $tmp_desire['price_low']){
								$stmt_business = $this->conn->prepare("SELECT name, longitude, latitude FROM business WHERE unique_id = ?");
								$stmt_business->bind_param("s", $tmp_offer['uid']);
								if ($stmt_business->execute()) {
									$result_business = $stmt_business->get_result();
									$business_num_of_rows = $result_business->num_rows;
									$stmt_business->close();
									if($business_num_of_rows > 0) {
										while($row_business = $business_result->fetch_assoc()){
											$tmp_offer['business_name'] = $row_business['name'];
											$tmp_offer['longitude'] = $row_business['longitude'];
											$tmp_offer['latitude'] = $row_business['latitude'];
										}
										array_push($offers, $temp_offer);
									} //END OF if business rows > 0
								} //END OF if $stmt_business execute
							} //END OF if prices match
						} //END OF while offer fetch					
					} //END OF if offers rows > 0
				} //END OF stmt_offers execute
			} //END OF while $row_desire = $result_desires->fetch_assoc()
			return $offers;	
		} else {
			return NULL;
		}//END OF else (if $desires_numb_of_rows > 0)
	} else {
		return NULL;
	} //END OF else (if $stmt_desires->execute())
} //END OF function - offerFound($users_id)

private function desireNameToRegex($desire_name) {
	$words = preg_split("/[\s,-_.]/", $desire_name);
	$regex_desire_name = "";
	foreach($words as $word) {
		$characters = str_split($word);
		$regex_word = "[";
		foreach($characters as $character) {
			if(!next($characters)) $regex_word .= $character . "]+";
			else $regex_word .= $character . ",";
		}
		$regex_desire_name .= $regex_word . "[\s,-_.]*";
	}
	return $regex_desire_name;
}
?>