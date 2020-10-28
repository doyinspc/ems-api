<?php
class DbControl{
	
	protected function columnChoice($v){
		$text = ' ';
		if(is_array($v)){
			$text .='';
			if(count($v) == 1){
			 foreach($v  as $k => $v1){
			 		$gg = explode(".",$v1);
					if(count($gg) == 1){
					$text .= "`".$v1."`";
					}
					elseif(count($gg) == 2){
					$text .= $v1;	
					}
				 }
			}
			if(count($v) > 1){
			 foreach($v  as $k => $v1){
			 		$gg = explode(".",$v1);
					if(count($gg) == 1){
					$text .= "`".$v1."`";
					}
					elseif(count($gg) == 2){
					$text .= $v1;	
					}

					$text .= ", ";
				 }
				$text = substr($text, 0 , -2); 
			}
			
			}
		else{
			$text = NULL;
			}
		return $text;
		}
	
	protected function whereClause($v){
		$text = ' ';
		if(is_array($v)){
			$text .=' WHERE ';
			if(count($v) == 1){
			 foreach($v  as $k => $v1){
				 
					$text .= " `". $k .'` = "'. $v1 .'"';
					
				 }
			}
			if(count($v) > 1){
			 foreach($v  as $k => $v1){
					$text .= " `". $k .'` = "'. $v1 .'"';
					$text .= " AND ";
				 }
				$text = substr($text,0, -5); 
			}
			
			}
		else{
			$text = NULL;
			}
		return $text;
		}
	protected function wheresClause($v){
		$text = ' ';
		if(is_array($v)){
			$text .=' WHERE ';
			if(count($v) == 1){
			 foreach($v  as $k => $v1)
			 {
			 	  $h = explode(".", $k);
				  if(count($h) == 2)
				  {
				  	$text .= " `". $h[0] ."`.`". $h[1] .'` = "'. $v1 .'"';
				  }else
				  {
				  	$text .= " `". $k .'` = "'. $v1 .'"';
				  }
					
					
			 }
			}
			if(count($v) > 1){
			 foreach($v  as $k => $v1)
			    {
			    	$h = explode(".", $k);
			    	if(count($h) == 2)
				    {
				    	$text .= " `". $h[0] ."`.`". $h[1] .'` = "'. $v1 .'"';
					    $text .= " AND ";

				    }else
				    {
				    	$text .= " `". $k .'` = "'. $v1 .'"';
					    $text .= " AND ";

				    }
					
				 }
				$text = substr($text,0, -5); 
			}
			
			}
		else{
			$text = NULL;
			}
		return $text;
		}
		
	protected function orderByClause($v){
		$text = ' ';
		if(is_array($v)){
			$text .=' ORDER BY ';
			if(count($v) == 1){
			 foreach($v  as $k => $v1){
					$text .= $v1;
				 }
			}
			if(count($v) > 1){
			 foreach($v  as $k => $v1){
					$text .= $v1;
					$text .= ", ";
				 }
				$text = substr($text, 0 , -2); 
			}
			
			}
		else{
			$text = NULL;
			}
		return $text;
		}
	
	protected function groupByClause($v){
		$text = ' ';
		if(is_array($v)){
			$text .=' GROUP BY ';
			if(count($v) == 1){
			 foreach($v  as $k => $v1){
					$text .= $v1;
				 }
			}
			if(count($v) > 1){
			 foreach($v  as $k => $v1){
					$text .= $v1;
					$text .= ", ";
				 }
				$text = substr($text, 0 , -2); 
			}
			
			}
		else{
			$text = NULL;
			}
		return $text;
		}	
	
	public function stringCA($val, $vd){
		$ca = unserialize($val);
		$text = '';
		$text .= "  (";
		$k = 0;
		if(is_array($ca)){
		foreach($ca as $valuez){
		$k++;
		$text .= "`".$vd."` = '".$valuez."'";
		if($k < count($ca)){ 
		$text .= " OR";
		}
		}
		}
		$text .= ") ";
		
		return $text;
		
		}
	public function stringItem($val, $vd){
		$ca = $val;
		$text = '';
		$text .= "  (";
		$k = 0;
		if(is_array($ca)){
		foreach($ca as $valuez){
		$k++;
		$text .= "`".$vd."` = '".$valuez."'";
		if($k < count($ca)){ 
		$text .= " OR";
		}
		}
		}
		$text .= ") ";
		
		return $text;
		
		}
	
	
	
}




?>