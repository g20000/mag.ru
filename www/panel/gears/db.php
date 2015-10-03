<?php
/* !=== DB Class === */
class dbClass {

	private $link;
	private $callsCount=0;
	private $callsDebug=Array();
	private $rawDebug = false; // put all queryes to log

	// функция соединения с БД
	function connect($dbdata) {
		$driver = $dbdata[ "dbdriver" ];
		$dsn = "${driver}:" ;
		$user = $dbdata[ "dbuser" ] ;
		$password = $dbdata[ "dbpassword" ] ;
		$options = $dbdata[ "dboptions" ] ;
		$attributes = $dbdata[ "dbattributes" ] ;
		
		// перечитываем аттрибуты
		foreach ( $dbdata[ "dsn" ] as $k => $v ) { $dsn .= "${k}=${v};"; }
		
		try {
			// стараемся создать подключение
			$this->link = new PDO ( $dsn, $user, $password, $options ) ;
			// устанавливаем аттрибуты
			foreach ( $attributes as $k => $v ) {
				$this->link -> setAttribute ( constant ( "PDO::{$k}" ), constant ( "PDO::{$v}" ) ) ;
			}
			
		} catch(PDOException $e) {
			// если что-то не так, то вываливаем ошибку	
			myErrorHandler(0,$e -> getMessage(),__FILE__,__LINE__);
			
		}
	}

	function __construct($cfg) {
		$this->connect($cfg);
	}

	function uncommentSQL($sql) {
		$sqlComments = '@(([\'"]).*?[^\\\]\2)|((?:\#|--).*?$|/\*(?:[^/*]|/(?!\*)|\*(?!/)|(?R))*\*\/)\s*|(?<=;)\s+@ms';
		/* Commented version
		$sqlComments = '@
		    (([\'"]).*?[^\\\]\2) # $1 : Skip single & double quoted expressions
		    |(                   # $3 : Match comments
		        (?:\#|--).*?$    # - Single line comments
		        |                # - Multi line (nested) comments
		         /\*             #   . comment open marker
		            (?: [^/*]    #   . non comment-marker characters
		                |/(?!\*) #   . ! not a comment open
		                |\*(?!/) #   . ! not a comment close
		                |(?R)    #   . recursive case
		            )*           #   . repeat eventually
		        \*\/             #   . comment close marker
		    )\s*                 # Trim after comments
		    |(?<=;)\s+           # Trim after semi-colon
		    @msx';
		*/
		$uncommentedSQL = trim( preg_replace( $sqlComments, '$1', $sql ) );
		preg_match_all( $sqlComments, $sql, $comments );
		$extractedComments = array_filter( $comments[ 3 ] );
		//var_dump( $uncommentedSQL, $extractedComments );
		return $uncommentedSQL;
	}
	
	function parseQuery($q) {
		$q = $this->uncommentSQL($q);
		$q = str_replace("\n", " ", $q);
		$q = str_replace("\r", " ", $q);
		$q = str_replace("\t", " ", $q);
		$q = preg_replace("/\/\*.*\*\//Uis",'',$q);
		$q = preg_replace("/\s+/is",' ',$q);
		$q = trim($q);
		$type = explode(" ",$q);
		$type = trim(mb_strtoupper($type[0],"UTF-8"));
		return $type;

	}
	
	// простой запрос к базе
	function query($query) {
		global $cfg;
		// разбираем запрос
		$type = $this->parseQuery($query);
		// выполняем запрос
		try {
			$result=$this->link->query($query);
			
			// получаем результаты 
			if (in_array($type,array('SELECT', 'SHOW'))) {
				$result->setFetchMode(PDO::FETCH_OBJ);
				while($row = $result->fetch()) {
					$res[]=$row;
				}
			} elseif(in_array($type,array('INSERT'))) {
				$res[]=$this->link->lastInsertId(); 
			}
			
			// увеличиваем счетчик запросов
			$this->callsCount++;
			// если дебаг включен то добавляем запрос в лог
			if ($cfg['debug']==true) { $this->callsDebug[]=$query; }
			if ($this->rawDebug == true) { logError($query); }
			
		} catch(PDOException $e) {
			myErrorHandler(0, $e -> getMessage()."\n".$query, __FILE__,__LINE__);
		}
		return (isset($res)) ? $res : NULL;
	}
	
	function queryInsertBinary($query, $binarray) {
		$pdoLink = $this->link;
		$stmt = $pdoLink->prepare($query);
		foreach($binarray as $key=>$value) {
			$stmt->bindParam(":".$key, $value['data'], $value['param'][0], $value['param'][1]); //PDO::PARAM_STR || PDO::PARAM_LOB, sizeof($binary)
		}
		$stmt->execute();
		return $pdoLink->lastInsertId();
	}
	
	
	function getVar($var) {
		return $this->$var;
	}
	
}
?>