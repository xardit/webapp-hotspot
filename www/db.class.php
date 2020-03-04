<?php

@date_default_timezone_set('Europe/Rome');

_init_magicQuotes();

$db=new db;
$conn=$db->conn();

function genLike($arr,$key='emri',$seperator='OR'){ // return [sql]<sql statement with ? and [exec]<execute params
	$r=array();
	foreach ($arr as $v) {
		$r[]=$key.' LIKE ?';
	}
	return array(
		'sql' => implode(" $seperator ", $r),
		'exec' => explode(' ', '%'.implode('% %', $arr).'%')
	);
}

class db {
	public function conn(){
		set_exception_handler('db::db_error');
		$c = new PDO('mysql:host=hotspot_db;dbname=data;charset=utf8;', 'root', 'root', array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING //ERRMODE_EXCEPTION or ERRMODE_WARNING
		));
		// $c = new PDO('sqlite:list.sqlite3');
		return $c;
	}
	public static function db_error($log){
			$mylog_encbase64=$_SERVER['REMOTE_ADDR']." - ".$_SERVER['PHP_SELF']." - ".date("d/m/Y h:i:s")." : ".base64_encode($log)."\n";
			$mylog=$_SERVER['REMOTE_ADDR']." - ".$_SERVER['PHP_SELF']." - ".date("d/m/Y h:i:s")." : ".$log."\n";
			$mylog_hidden='<b>'.$_SERVER['REMOTE_ADDR']." - Database server error , please contact admin (diti)</b>";
			// $ff=fopen('db_err_test.log','a');fwrite($ff,$mylog);fclose($ff);
			exit($mylog);
			// exit((((isset($GLOBALS['dev'])&&$GLOBALS['dev']))?$mylog:$mylog_hidden));
	}
	public function fields($t){ //getFields table
		$a=$GLOBALS['conn']->query("DESCRIBE `$t`");
		$r=$a->fetchAll(PDO::FETCH_COLUMN);
		unset($r[0]); //remove id
		return $r;
	}
	public function send($a,$t){ //insert what,table
		if(!is_array($a)||count($a)<1){return false;}
		$keys=array_keys($a);
		foreach($keys as $c=>&$k){$k=':V'.$c;}
		$params=array_combine($keys,array_values($a));
		$sql="INSERT INTO `$t`(`".implode("`,`",array_keys($a))."`) VALUES (".implode(",",$keys).")";
		$st=$GLOBALS['conn']->prepare($sql);
		$st->execute($params);
		$id=$GLOBALS['conn']->lastInsertId();
		if($st->rowCount()){return $id;}
		return false;
	}
	public function remove($a,$t){  //remove where,table
		if(!is_array($a)||count($a)<1||empty($t)){return false;}
		$keys=array_keys($a);
		$keys2=array_keys($a);
		foreach($keys as $c => &$k){$k='`'.$k.'` = :V'.$c;}
		foreach($keys2 as $c=>&$k){$k=':V'.$c;}
		$params=array_combine($keys2,array_values($a));
		$st=$GLOBALS['conn']->prepare("DELETE FROM `$t` WHERE ".implode(" AND ",$keys));
		$st->execute($params);
		if($st->rowCount()){return $st->rowCount();}
		return false;
	}
	public function update($a,$b,$t){ //update set,where,table
		if(!is_array($a)||!is_array($b)||empty($t)){return false;}
		$a_keys=array_keys($a);
		$b_keys=array_keys($b);
		foreach($a_keys as &$k){$k='`'.$k.'`=?';}
		foreach($b_keys as &$k){$k='`'.$k.'`=?';}
		$set_params=array_values($a);		
		$where_params=array_values($b);
		$params=array_merge($set_params,$where_params);
		$st=$GLOBALS['conn']->prepare("UPDATE `$t` SET ".implode(", ",$a_keys)." WHERE ".implode(" AND ",$b_keys));
		$st->execute($params);
		if($st->rowCount()){return true;}
		return false;
	}
	public function receive($a,$t,$o=''){ // receive where,table || where,table,otherSQL
		if(empty($t)){return false;}
		$cn=$GLOBALS['conn'];
		if(is_array($a)){
			$keys=array_keys($a);
			$keys2=array_keys($a);
			foreach($keys as &$k){$k=$k.' = :'.$k;}
			foreach($keys2 as &$k){$k=':'.$k;}
			$params=array_combine($keys2,array_values($a));
			$st=$cn->prepare("SELECT * FROM $t WHERE ".(count($keys)>1?implode(" AND ",$keys):$keys[0]).' '.$o);
			$st->execute($params);
		}elseif($a=='all'){
			$st=$cn->query("SELECT * FROM $t ".$o);
		}else{return false;}
		$result=$st->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	public function check($a,$t,$o=''){ //check existence -> check(where,table,||otherSQL)
		if(empty($t)){return false;}
		$cn=$GLOBALS['conn'];
		if(is_array($a)){
			$keys=array_keys($a);
			$keys2=array_keys($a);
			foreach($keys as &$k){$k=$k.' = :'.$k;}
			foreach($keys2 as &$k){$k=':'.$k;}
			$params=array_combine($keys2,array_values($a));
			$st=$cn->prepare("SELECT * FROM $t WHERE ".(count($keys)>1?implode(" AND ",$keys):$keys[0]).' '.$o);
			$st->execute($params);
		}else{return false;}
		$result=$st->fetch(PDO::FETCH_ASSOC);
		return (!$result?false:true);
	}
	public function count($t,$o=''){// count(table,other)
		if(empty($t)){return false;}
		$cn=$GLOBALS['conn'];
		$st=$cn->query('SELECT COUNT(*) FROM `'.$t.'` '.$o);
		//prepare THEN $st->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); THAN $count=$st->execute();
		$result=$st->fetch(PDO::FETCH_NUM);
		return $result[0];
	}
	public function sql($sql,$params=false,$fetch=true){
		$cn = $GLOBALS['conn'];
		$st=$cn->prepare($sql);
		if((is_array($params)&&empty($params))||!$params){
			$st->execute();
		}else{
			$st->execute($params);
		}
		return ($fetch)?$st->fetchAll(PDO::FETCH_ASSOC):$st->rowCount();
	}
}



// Remove Magic Quotes
function _init_magicQuotes(){
	if (get_magic_quotes_gpc()) {
	    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	    while (list($key, $val) = each($process)) {
	        foreach ($val as $k => $v) {
	            unset($process[$key][$k]);
	            if (is_array($v)) {
	                $process[$key][stripslashes($k)] = $v;
	                $process[] = &$process[$key][stripslashes($k)];
	            } else {
	                $process[$key][stripslashes($k)] = stripslashes($v);
	            }
	        }
	    }
	    unset($process);
	}
}