<?php
class shop_dao{
    static $_instance;

    private function __construct(){
    }
    public static function getInstance(){
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function incrementVisits($db,$id_property) {
        $sql = "UPDATE property SET visits = visits + 1 WHERE id_property = $id_property";
    
        $stmt = $db->ejecutar($sql);
        return $db->listar($stmt);
    }
    // public function like_property($db, $id_property, $username) {
    //     $sql = "SELECT id_user FROM users WHERE username = '$username'";
    //     $stmt = $db->ejecutar($sql);
    //     $user = $db->listar($stmt);

    //     if ($user) {
    //         $id_user = $user[0]['id_user'];

    //         $sql = "INSERT INTO likes (id_property, id_user) VALUES ($id_property, $id_user)";
    //         $db->ejecutar($sql);

    //         $sql = "UPDATE property SET likes = likes + 1 WHERE id_property = $id_property";
    //         $db->ejecutar($sql);
    //     }
    // }
    public function like_property($db, $id_property, $username) {
        $sql = "CALL LikeProperty('$username', $id_property)";
        $db->ejecutar($sql);
        // Procedure que se llama:
        // DELIMITER //
        // CREATE PROCEDURE LikeProperty(IN p_username VARCHAR(255), IN p_id_property INT)
        // BEGIN
        //     DECLARE v_id_user INT;
        
        //     SELECT id_user INTO v_id_user FROM users WHERE username = p_username;
        
        //     IF v_id_user IS NOT NULL THEN
        //         INSERT INTO likes (id_property, id_user) VALUES (p_id_property, v_id_user);
        //         UPDATE property SET likes = likes + 1 WHERE id_property = p_id_property;
        //     END IF;
        // END //
        // DELIMITER ;
    }
    public function check_like($db, $id_property, $username){
        $sql = "SELECT id_user FROM users WHERE username = '$username'";
        $stmt = $db->ejecutar($sql);
        $user = $db->listar($stmt);

        if ($user) {
            $id_user = $user[0]['id_user'];

            $sql = "SELECT * FROM likes WHERE id_property = $id_property AND id_user = $id_user";
            $stmt = $db->ejecutar($sql);
            $like = $db->listar($stmt);

            if ($like) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "0";
        }
    }
    public function check_like_social($db, $id_property, $username,$social){
        $sql = "SELECT id FROM users_$social WHERE username = '$username'";
        // error_log($sql,3,"debug.log");
        $stmt = $db->ejecutar($sql);
        $user = $db->listar($stmt);

        if ($user) {
            $id = $user[0]['id'];

            $sql = "SELECT * FROM likes_$social WHERE id_property = $id_property AND id = '$id'";
            $stmt = $db->ejecutar($sql);
            $like = $db->listar($stmt);

            if ($like) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "0";
        }
    }
    public function like_property_social($db, $id_property,$username,$social) {
        $sql = "SELECT id FROM users_$social WHERE username = '$username'";
        $stmt = $db->ejecutar($sql);
        $user = $db->listar($stmt);
        error_log("user".$user[0]['id'],3,"debug.log");
        if ($user) {
            error_log("entro al if del dao",3,"debug.log");
            $id = $user[0]['id'];

            $sql = "INSERT INTO likes_$social (id,id_property) VALUES ('$id',$id_property)";
            error_log($sql,3,"debug.log");

            $db->ejecutar($sql);
            error_log($sql,3,"debug.log");
            $sql = "UPDATE property SET likes = likes + 1 WHERE id_property = $id_property";
            $db->ejecutar($sql);
            error_log($sql,3,"debug.log");
        }
    }
    public function dislike_property_social($db, $id_property, $username, $social) {
        $sql = "SELECT id FROM users_$social WHERE username = '$username'";
        $stmt = $db->ejecutar($sql);
        $user = $db->listar($stmt);

        if ($user) {
            $id = $user[0]['id'];

            $sql = "DELETE FROM likes_$social WHERE id_property = $id_property AND id = '$id'";
            $db->ejecutar($sql);

            $sql = "UPDATE property SET likes = likes - 1 WHERE id_property = $id_property";
            $db->ejecutar($sql);
        }
    }
    public function dislike_property($db, $id_property, $username) {
        $sql = "SELECT id_user FROM users WHERE username = '$username'";
        $stmt = $db->ejecutar($sql);
        $user = $db->listar($stmt);

        if ($user) {
            $id_user = $user[0]['id_user'];

            $sql = "DELETE FROM likes WHERE id_property = $id_property AND id_user = $id_user";
            $db->ejecutar($sql);

            $sql = "UPDATE property SET likes = likes - 1 WHERE id_property = $id_property";
            $db->ejecutar($sql);
        }
    }
    public function insertCurrentDate($db, $id_property) {
        $sql = "UPDATE property SET currently_date = NOW() WHERE id_property = $id_property";
        $db->ejecutar($sql);
    }
    public function select_all_properties($db, $offset, $filter){
        if ($filter == 'price') {
            $order = 'DESC';
            $filter = 'price';
        } else if ($filter == 'name') {
            $order = 'ASC';
            $filter = 'property_name';
        } else if ($filter == 'visits') {
            $order = 'DESC';
            $filter = 'visits';
        } else {
            $order = 'ASC';
            $filter = 'id_property';
        }
        $sql = "SELECT DISTINCT p.*,c.*
        FROM property p
        INNER JOIN city c ON p.id_city = c.id_city
        GROUP BY p.id_property
        ORDER BY p.$filter $order
        LIMIT $offset, 3;";

        $stmt = $db->ejecutar($sql);
        $properties = $db->listar($stmt);

        foreach ($properties as $key => $property) {
            $sql = "SELECT * FROM images WHERE id_property = '{$property['id_property']}'";
            $stmt = $db->ejecutar($sql);
            $images = $db->listar($stmt);
            $properties[$key]['images'] = $images;
        }

        return $properties;
    }
    public function select_order_properties($db, $filters_shop, $offset, $filter){
        $order = 'ASC';
        $filter = 'id_property';

        if ($filter == 'price') {
            $order = 'DESC';
            $filter = 'price';
        }else if ($filter == 'name') {
            $order = 'ASC';
            $filter = 'property_name';
        } else if ($filter == 'visits') {
            $order = 'DESC';
            $filter = 'visits';
        }

        $sql = "SELECT DISTINCT p.*,c.*
        FROM property p, city c
        WHERE p.id_city = c.id_city";

        // Aplicar los otros filtros
        foreach ($filters_shop as $key => $value) {
            if (isset($filters_shop[$key]) && $key != 'order') {
                switch ($key) {
                    case 'id_city':
                        $sql .= " AND c.id_city = " . $filters_shop['id_city'];
                        break;
                    case 'id_large_people':
                        $sql .= " AND p.id_large_people = " . $filters_shop['id_large_people'];
                        break;
                    case 'id_type':
                        $sql .= " AND p.id_property IN (SELECT pt.id_property FROM property_type pt WHERE pt.id_type = " . $filters_shop['id_type'] . ")";
                        break;
                    case 'id_operation':
                        $sql .= " AND p.id_property IN (SELECT po.id_property FROM property_operation po WHERE po.id_operation = " . $filters_shop['id_operation'] . ")";
                        break;
                    case 'id_category':
                        $sql .= " AND p.id_property IN (SELECT pc.id_property FROM property_category pc WHERE pc.id_category = " . $filters_shop['id_category'] . ")";
                        break;
                    case 'id_extras':
                        if (is_array($filters_shop['id_extras'])) {
                            $extras = array_map('intval', $filters_shop['id_extras']);
                            $conditions = [];
                            foreach ($extras as $extra) {
                                $conditions[] = "p.id_property IN (SELECT pe.id_property FROM property_extras pe WHERE pe.id_extras = $extra)";
                            }
                            $sql .= " AND " . implode(' AND ', $conditions);
                        } else {
                            $sql .= " AND p.id_property IN (SELECT pe.id_property FROM property_extras pe WHERE pe.id_extras = " . intval($filters_shop['id_extras']) . ")";
                        }
                        break;
                    case 'minPrice':
                        $sql .= " AND p.price >= " . $filters_shop['minPrice'];
                        break;
                    case 'maxPrice':
                        $sql .= " AND p.price <= " . $filters_shop['maxPrice'];
                        break;
                }
            }
        }

        $sql .= " GROUP BY p.id_property
        ORDER BY p.$filter $order
        LIMIT $offset, 3;";

        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);

        return $retrArray;
    }
    public function search_filter($db,$offset,$order,$filters_search){
            
        $id_category = isset($filters_search['id_category']) ? $filters_search['id_category'] : null;
        $id_city = isset($filters_search['id_city']) ? $filters_search['id_city'] : null;
        $id_type = isset($filters_search['id_type']) ? $filters_search['id_type'] : null;

        $sql = "SELECT DISTINCT p.*,c.*,t.*,cat.*
        FROM property p
        INNER JOIN city c ON p.id_city = c.id_city
        INNER JOIN property_type pt ON pt.id_property = p.id_property
        INNER JOIN type t ON pt.id_type = t.id_type
        INNER JOIN property_category pc ON pc.id_property = p.id_property
        INNER JOIN category cat ON cat.id_category = pc.id_category"
        . ($id_type ? " WHERE t.id_type = $id_type" : "")
        . ($id_city ? " AND p.id_city = $id_city" : "")
        . ($id_category ? " AND cat.id_category = '$id_category'" : "") .
        " GROUP BY p.id_property
        ORDER BY p.$order ASC
        LIMIT $offset, 3;";

        $stmt = $db->ejecutar($sql);
        $properties = $db->listar($stmt);

        foreach ($properties as $key => $property) {
            $sql = "SELECT * FROM images WHERE id_property = '{$property['id_property']}'";
            $stmt = $db->ejecutar($sql);
            $images = $db->listar($stmt);
            $properties[$key]['images'] = $images;
        }

        return $properties;
    }
    public function select_city($db){
        $sql = "SELECT * FROM city";
        $stmt = $db->ejecutar($sql);
        $cityArray = $db->listar($stmt);
        return $cityArray;
    }
    public function select_type($db) {
        $sql= "SELECT * FROM `type` ORDER BY id_type ASC LIMIT 30;";
        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);
        return $retrArray;
    }
    public function select_large_people($db){
        $sql= "SELECT * FROM `large_people` ORDER BY id_large_people ASC LIMIT 30;";
        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);
        return $retrArray;
    }
    public function select_extras($db) {
        $sql= "SELECT * FROM `extras`;";
        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);
        return $retrArray;
    }
    public function select_categories($db) {
        $sql= "SELECT * FROM category";
        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);
        return $retrArray;
    }
    public function select_operations($db){
        $sql= "SELECT * FROM operation";
        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);
        return $retrArray;
    }
    public function select_details_property($db, $id){
        $sql = "SELECT p.*, c.name_city,lp.name_large_people,i.path_images,
                (SELECT GROUP_CONCAT(t.name_type) FROM property_type pt INNER JOIN type t ON pt.id_type = t.id_type WHERE pt.id_property = p.id_property) as type_concat,
                (SELECT GROUP_CONCAT(o.name_operation) FROM property_operation po INNER JOIN operation o ON po.id_operation = o.id_operation WHERE po.id_property = p.id_property) as operation_concat,
                (SELECT GROUP_CONCAT(c.name_category) FROM property_category pc INNER JOIN category c ON pc.id_category = c.id_category WHERE pc.id_property = p.id_property) as category_concat,
                (SELECT GROUP_CONCAT(e.name_extras) FROM property_extras pe INNER JOIN extras e ON pe.id_extras = e.id_extras WHERE pe.id_property = p.id_property) as extras_concat
                FROM property p
                INNER JOIN city c ON p.id_city = c.id_city
                INNER JOIN large_people lp ON p.id_large_people = lp.id_large_people
                INNER JOIN images i ON p.id_property = i.id_property
                WHERE p.id_property = '$id'";
        $stmt = $db->ejecutar($sql);
        $res = $db->listar($stmt);
        foreach ($res as $key => $property) {
            $sql = "SELECT * FROM images WHERE id_property = '{$property['id_property']}'";
            $stmt = $db->ejecutar($sql);
            $images = $db->listar($stmt);
            $properties[$key]['images'] = $images;
        }       
        return $res;
    }
    public function filters_shop($db,$offset,$filter,$filters_shop){
		if (is_string($filters_shop)) {
			$filters_shop = json_decode($filters_shop, true); 
		} elseif (is_object($filters_shop)) {
			$filters_shop = get_object_vars($filters_shop);
		}
		if (!is_array($filters_shop)) {
			// error_log('El array $filters_shop no es un array', 3, "debug.txt");
			return [];
		}
		if ($filter == 'price') {
			$order = 'DESC';
			$filter = 'price';
		}else if ($filter == 'name') {
			$order = 'ASC';
			$filter = 'property_name';
		} else if ($filter == 'visits') {
			$order = 'DESC';
			$filter = 'visits';
		}else{
			$order = 'ASC';
			$filter = 'id_property';
		}
		// error_log($filters_shop, 3, "debug.txt");
		// error_log(print_r($filters_shop, true), 3, "debug.txt");
		$consulta = "SELECT DISTINCT p.*, c.name_city,lp.name_large_people,i.path_images,
			(SELECT GROUP_CONCAT(t.name_type) FROM property_type pt INNER JOIN type t ON pt.id_type = t.id_type WHERE pt.id_property = p.id_property) as type_concat,
			(SELECT GROUP_CONCAT(o.name_operation) FROM property_operation po INNER JOIN operation o ON po.id_operation = o.id_operation WHERE po.id_property = p.id_property) as operation_concat,
			(SELECT GROUP_CONCAT(c.name_category) FROM property_category pc INNER JOIN category c ON pc.id_category = c.id_category WHERE pc.id_property = p.id_property) as category_concat,
			(SELECT GROUP_CONCAT(e.name_extras) FROM property_extras pe INNER JOIN extras e ON pe.id_extras = e.id_extras WHERE pe.id_property = p.id_property) as extras_concat
			FROM property p
			INNER JOIN city c ON p.id_city = c.id_city
			INNER JOIN images i ON p.id_property = i.id_property
			INNER JOIN large_people lp ON p.id_large_people = lp.id_large_people";

		foreach ($filters_shop as $key => $value) {
			// error_log("Dentro del bucle foreach. Clave: $key, Valor: $value", 3, "debug.txt");s
			if (strpos($consulta, 'WHERE') !== false) {
				switch ($key) {
					case 'id_city':
						$consulta .= " AND c.id_city = " . $filters_shop['id_city'];
						break;
					case 'id_large_people':
						$consulta .= " AND lp.id_large_people = " . $filters_shop['id_large_people'];
						break;
					case 'id_type':
						$consulta .= " AND p.id_property IN (SELECT pt.id_property FROM property_type pt WHERE pt.id_type = " . $filters_shop['id_type'] . ")";
						break;
					case 'id_operation':
						$consulta .= " AND p.id_property IN (SELECT po.id_property FROM property_operation po WHERE po.id_operation = " . $filters_shop['id_operation'] . ")";
						break;
					case 'id_category':
						$consulta .= " AND p.id_property IN (SELECT pc.id_property FROM property_category pc WHERE pc.id_category = " . $filters_shop['id_category'] . ")";
						break;
					case 'id_extras':
						if (is_array($filters_shop['id_extras'])) {
							$extras = array_map('intval', $filters_shop['id_extras']);
							$conditions = [];
							foreach ($extras as $extra) {
								$conditions[] = "p.id_property IN (SELECT pe.id_property FROM property_extras pe WHERE pe.id_extras = $extra)";
							}
							$consulta .= " AND " . implode(' AND ', $conditions);
						} else {
							$consulta .= " AND p.id_property IN (SELECT pe.id_property FROM property_extras pe WHERE pe.id_extras = " . intval($filters_shop['id_extras']) . ")";
						}
						break;
					case 'minPrice':
						$consulta .= " AND p.price >= " . $filters_shop['minPrice'];
						break;
					case 'maxPrice':
						$consulta .= " AND p.price <= " . $filters_shop['maxPrice'];
						break;
				}
			} else {
				switch ($key) {
					case 'id_city':
						$consulta .= " WHERE c.id_city = " . $filters_shop['id_city'];
						break;
					case 'id_large_people':
						$consulta .= " WHERE lp.id_large_people = " . $filters_shop['id_large_people'];
						break;
					case 'id_type':
						$consulta .= " WHERE p.id_property IN (SELECT pt.id_property FROM property_type pt WHERE pt.id_type = " . $filters_shop['id_type'] . ")";
						break;
					case 'id_operation':
						$consulta .= " WHERE p.id_property IN (SELECT po.id_property FROM property_operation po WHERE po.id_operation = " . $filters_shop['id_operation'] . ")";
						break;
					case 'id_category':
						$consulta .= " WHERE p.id_property IN (SELECT pc.id_property FROM property_category pc WHERE pc.id_category = " . $filters_shop['id_category'] . ")";
						break;
					case 'id_extras':
						if (is_array($filters_shop['id_extras'])) {
							$extras = array_map('intval', $filters_shop['id_extras']);
							$conditions = [];
							foreach ($extras as $extra) {
								$conditions[] = "p.id_property IN (SELECT pe.id_property FROM property_extras pe WHERE pe.id_extras = $extra)";
							}
							$consulta .= " WHERE " . implode(' AND ', $conditions);
						} else {
							$consulta .= " WHERE p.id_property IN (SELECT pe.id_property FROM property_extras pe WHERE pe.id_extras = " . intval($filters_shop['id_extras']) . ")";
						}
						break;
					case 'minPrice':
						$consulta .= " WHERE p.price >= " . $filters_shop['minPrice'];
						break;
					case 'maxPrice':
						$consulta .= " WHERE p.price <= " . $filters_shop['maxPrice'];
						break;
				}
			}
		}
		$consulta .= " GROUP BY p.id_property 
		ORDER BY p.$filter $order
		LIMIT $offset, 3;";
		
        $stmt = $db->ejecutar($consulta);
        $retrArray = $db->listar($stmt);
        foreach ($retrArray as $key => $property) {
            $sql = "SELECT * FROM images WHERE id_property = '{$property['id_property']}'";
            $stmt = $db->ejecutar($sql);
            $images = $db->listar($stmt);
            $retrArray[$key] = $property; 
            $retrArray[$key]['images'] = $images; 
        }

        return $retrArray;
	}
    public function select_similar_properties($db, $id_large_people){
        $sql= "SELECT * ,i.path_images
            FROM property p, images i
            WHERE p.id_property = i.id_property
            AND i.path_images LIKE '%-1%'
            AND $id_large_people = p.id_large_people
            GROUP BY i.path_images";

        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);

        return $retrArray;
    }
    public function counting_filters($db,$filters_shop){
        $sql = "SELECT COUNT(*)total 
        FROM property p";

        foreach ($filters_shop as $key => $value) {
            if (isset($filters_shop[$key])){
                switch ($key) {
                    case 'id_city':
                        $sql .= " INNER JOIN city c ON p.id_city = c.id_city AND c.id_city = " . $filters_shop['id_city'];
                        break;
                    case 'id_large_people':
                        $sql .= " INNER JOIN large_people lp ON p.id_large_people = lp.id_large_people AND lp.id_large_people = " . $filters_shop['id_large_people'];
                        break;
                    case 'id_type':
                        $sql .= " INNER JOIN property_type pt ON p.id_property = pt.id_property INNER JOIN type t ON pt.id_type = t.id_type AND t.id_type = " . $filters_shop['id_type'];
                        break;
                    case 'id_operation':
                        $sql .= " INNER JOIN property_operation po ON p.id_property = po.id_property INNER JOIN operation o ON po.id_operation = o.id_operation AND o.id_operation = " . $filters_shop['id_operation'];
                        break;
                    case 'id_category':
                        $sql .= " INNER JOIN property_category pc ON p.id_property = pc.id_property INNER JOIN category cat ON pc.id_category = cat.id_category AND cat.id_category = " . $filters_shop['id_category'];
                        break;
                    case 'minPrice':
                        $minPrice = (int)$filters_shop['minPrice'];
                        $sql .= " AND price >= $minPrice";
                        break;
                    case 'maxPrice':
                        $maxPrice = (int)$filters_shop['maxPrice'];
                        $sql .= " AND price <= $maxPrice";
                        break;
                    case 'id_extras':
                        if (is_array($filters_shop['id_extras'])) {
                            $extras = array_map('intval', $filters_shop['id_extras']);
                            foreach ($extras as $extra) {
                                $sql .= " INNER JOIN property_extras pe ON p.id_property = pe.id_property INNER JOIN extras e ON pe.id_extras = e.id_extras AND e.id_extras = $extra";
                            }
                        } else {
                            $sql .= " INNER JOIN property_extras pe ON p.id_property = pe.id_property INNER JOIN extras e ON pe.id_extras = e.id_extras AND e.id_extras = " . intval($filters_shop['id_extras']);
                        }
                        break;
                }
            }
        }
        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);
        return $retrArray;
    }
	public function counting($db){
		$sql = "SELECT COUNT(*)total 
		FROM property p";

        $stmt = $db->ejecutar($sql);
        $retrArray = $db->listar($stmt);
		return $retrArray;
	}
}
?>