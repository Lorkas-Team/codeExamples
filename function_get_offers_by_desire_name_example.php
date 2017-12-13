<?php
public function getOffersByDesireName($name_desire) {
		//unfinished.
		$stmt = $this->conn->prepare("SELECT id , uid, product_name, price, description, image, regDate, expDate FROM offers WHERE product_name LIKE '?'");
        $stmt->bind_param("s", $name_desire);
        if ($stmt->execute()) {
            $resultOffers = $stmt->get_result();
			$stmt->close();
            $offers = array();
            while ($row = $resultOffers->fetch_array(MYSQLI_NUM)) {
                $tmp_arr = array();
                foreach($row as $key=>$value) {
                    $tmp_arr[$key] = $value;
                }
				$uid = $tmp_arr["uid"];
				$stmt = $this->conn->prepare("SELECT name, longitude, latitude FROM business WHERE unique_id= ?");
				$stmt->bind_param("s", $uid);
				if ($stmt->execute()) {
					$resultBusiness = $stmt->get_result();
					$stmt->close();
					$tmp_arr["business_name"] = $resultBusiness["name"];
					$tmp_arr["longitude"] = $resultBusiness["longitude"];
					$tmp_arr["latitude"] = $resultBusiness["latitude"];
				}
                array_push($offers, $tmp_arr);
            }
			return $offers;
        } else {
            return NULL;
        }
    }
?>