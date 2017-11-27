<?php

	// EXAMPLE OF TWO METHODS ( storeNewDesire() & getUserIDByEmail() )
	// FROM DB_FUNCTIONS CLASS IN USERS API.
	
	/**
     * Storing and returns new users desire
     */
    public function storeNewDesire($email, $prod_name, $prod_descr, $price_lower, $price_higher) {
 
		$user_id = getUserIDByEmail($email);
		
        $stmt = $this->conn->prepare("INSERT INTO desires(users_id, name, descr, price_lower, price_higher) VALUES(?, ?, ?, ?, ?)");
       
        $stmt->bind_param("sssss",$user_id, $prod_name, $prod_descr, $price_lower, $price_higher);
        $result = $stmt->execute();
        $stmt->close();
      
        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM desires WHERE users_id = ?");
            $stmt->bind_param("s", $user_id);
            $stmt->execute();
            $new_desire = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $new_desire;
        } else {
            return false;
        }
    }
	
	/** Retuns users id by email */
	public function getUserIDByEmail($email) {
		
		$stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
		
		if ($stmt->execute()) {
            $user_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
                return $user_id;
            }
        } else {
            return NULL;
        }
	}
?>