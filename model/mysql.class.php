<?PHP

if (!defined ("_CLASS_DB_")) {
	define ("_CLASS_DB_",	1);

	define("OBJECT", "OBJECT", 1);
	define("ARRAY_A", "ARRAY_A", 1);
	define("ARRAY_N", "ARRAY_N", 1);
	define("ERROR", "ERROR", 1);
	/**
	 * Conexion a Base de Datos MySQL 
	 * @package Common
	 */
	class db {

		var $dbLine;
		var $dbLastQuery;
        
		/*
		 * Set database connection 
		 */
		function db($param = "servidor_monystar") {
			$this->dbConnect = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
			if (!$this->dbConnect)
				$this->printError("Error establing connection.");

			switch($param){
				case "servidor_monystar": $this->select(DB_DATABASE); break;
			}
		}

		/*
		 * Set database 
		 */
		function select($db) {
			if (!@mysql_select_db($db, $this->dbConnect))
				$this->printError("Error selecting database <u>".$db."</u>");
			else
				mysql_query ("SET NAMES 'utf8'");
		}


		/**
		 * Ejecuto query 
		 * @return Void. Get query 
		 */
		function query($query, $line = "") {
			$this->dbLastResult = "";
			$this->dbColInfo = "";
			$this->dbLastQuery = $query;
			$this->dbLine = ($line) ? $line : $this->dbLine;
			$this->result = mysql_query($query, $this->dbConnect);
			if (mysql_error())
				$this->printError();

			if (preg_match("/^\\s*(insert) /i", $query)) {
				$this->insert_id = mysql_insert_id($this->dbConnect);
				$return_val = $this->insert_id;

			} else {
				if ($this->result) {
					for ($i = 0; $i < @mysql_num_fields($this->result); $i++)
						$this->dbColInfo[$i] = @mysql_fetch_field($this->result);
					$i = 0;
					while ($row = @mysql_fetch_object($this->result)) {
						$this->dbLastResult[$i] = $row;
						$i++;
					}
					//@mysql_free_result($this->result);
					if ($i) return true; else return false;
				}
			}
			return $return_val;
		}


		/*
		 * @return String. Get one variable 
		 */
		function getVar($query = null, $line = "NaN") {
			$this->dbLine = $line;
			if ($query)
				$this->query($query);
			if ($this->dbLastResult[0])
				$values = array_values(get_object_vars($this->dbLastResult[0]));
			return $values[0] ? $values[0] : null;
		}


		/*
		 * @return Output. Get one row 
		 */
		function getRow($query = null, $line = "NaN", $output = OBJECT) {
			$this->dbLine = $line;
			if ($query) 
				$this->query($query);
			if ($output == OBJECT)
				return $this->dbLastResult[0] ? $this->dbLastResult[0] : null;
			elseif ($output == ARRAY_A)
				return $this->dbLastResult[0] ? get_object_vars($this->dbLastResult[0]) : null;
			elseif ( $output == ARRAY_N)
				return $this->dbLastResult[0] ? array_values(get_object_vars($this->dbLastResult[0])) : null;
		}


		/**
		* @desc Recupero el id autogenerado en al ultima consulta 
		* @return string Get last make Id 
		*/
		function getNewId() {
			return mysql_insert_id();
		}


		/**
		 * Recupero el numero de filas de la ultima consulta 
		 * @return String. Get last make Id 
		 */
		function getCount() {
			return mysql_numrows($this->result);
		}

		function AffectedRows() {
			return mysql_affected_rows();
		}
		

		/*
		 * @return Output. Get one column from the cached result set based in X index 
		 */
		function getCol($query = null, $x = 0, $line = "NaN") {
			$this->dbLine = $line;
			if ($query)
				$this->query($query);
			for ($i = 0; $i < count($this->dbLastResult); $i++)
				$new_array[$i] = $this->get_var(null, $x, $i);
			return $new_array;
		}


		/*
		 * @Return Output. Get the query as a result set 
		 */
		function getResults($query = null, $output = OBJECT, $line = "NaN") {
			$this->dbLine = $line;
			if ($query) $this->query($query);
			if ($output == OBJECT) return $this->dbLastResult;
			elseif ( $output == ARRAY_A || $output == ARRAY_N ) {
				if ($this->dbLastResult) {
					$i = 0;
					foreach($this->dbLastResult as $row) {
						$new_array[$i] = get_object_vars($row);
						if ($output == ARRAY_N) $new_array[$i] = array_values($new_array[$i]);
						$i++;
					}
					return $new_array;
				} else
					return null;
			}
		}


		/*
		 * @Return String[]. Get column meta data info pertaining to the last query 
		 */
		function getColInfo($infoType = "name", $colOffset = -1, $line = "NaN") {
			$this->dbLine = $line;
			if ($this->dbColInfo) {
				if ($colOffset == -1) {
					$i = 0;
					foreach($this->dbColInfo as $col) {
						$new_array[$i] = $col->{$infoType};
						$i++;
					}
					return $new_array;
				} else
					return $this->dbColInfo[$colOffset]->{$infoType};
			}
		}


		/*
		 * @return String. Get string correctly for safe insert under all PHP conditions 
		 */
		function escape($str) {
			return mysql_escape_string(stripslashes($str));
		}


		/*
		 * Print SQL/DB error 
		 */
		function printError($str = "") {
		if (ERROR == 1){
			if (!$str) $str = mysql_error();
			if (!$this->dbLine) $this->dbLine = "NaN";
			
			echo '
				<fieldset title="MySQL Error" style="width:100%">
					<legend><font color="FF0000" size="2" face="Verdana"><b>MySQL Error&nbsp;</b></font></legend>
					<table border="0" cellspacing="0" cellpadding="2" style="font-size:11px;font-family:Verdana;">
						<tr><td width="100" align="right" valign="top"><b>File:</b>&nbsp;</td><td width="400" valign="top">[&nbsp;'.$_SERVER["PHP_SELF"].'&nbsp;]</td></tr>
						<tr><td align="right" valign="top"><b>Query:</b>&nbsp;</td><td valign="top">[&nbsp;'.$this->dbLastQuery.'&nbsp;]</td></tr>
						<tr><td align="right" valign="top"><b>Line:</b>&nbsp;</td><td valign="top">[&nbsp;'.$this->dbLine.'&nbsp;]</td></tr>
						<tr><td align="right" valign="top"><b>Detail:</b>&nbsp;</td><td valign="top">[&nbsp;'.$str.'&nbsp;]</td></tr>
					</table>
				</fieldset>';
				
		} else {
		    /*echo "<script>alert('Su acceso no fue autenticado'); window.location='../../home.php' ;</script>"*/;
		}
	}
		


		/*
		 * Print the last query string that was sent to the database & a table listing results 
		 */
		function debug() {
			if ($this->dbColInfo) {
				$tmp = '<table border="0" cellpadding="2" cellspacing="1" bgcolor="555555" style="font-size:11px;font-family:Verdana;color:5555599">
							<tr bgcolor="eeeeee">
								<td width="20" nowrap>&nbsp;</td>';
				
				for ($i = 0; $i < count($this->dbColInfo); $i++)
					$tmp .= '	<td width="100" nowrap><b>'.$this->dbColInfo[$i]->name.'</b><br>
									<font size="1">&nbsp;&nbsp;'.$this->dbColInfo[$i]->type.' ('.$this->dbColInfo[$i]->max_length.')</td>';
				$tmp .= "	</tr>";
				
				if ($this->dbLastResult) {
					$i = 0;
					foreach ($this->getResults(null, ARRAY_N) as $one_row) {
						$i++;
						$tmp .= '<tr bgcolor="ffffff">
									<td bgcolor="eeeeee" nowrap align="middle">'.$i.'</td>';
						foreach ($one_row as $item)
							$tmp .= '<td nowrap>'.$item.'</td>';
						$tmp .= '</tr>';
					}
				} else
					$tmp .= '<tr bgcolor="ffffff">
								<td colspan='.(count($this->dbColInfo)+1).'>No Results</td>
							</tr>';
				$tmp .= '</table>';
			} else
				$tmp = '<font face="Verdana" size="2">No Results</font>';
			
			echo "<br>dbLastResult:" . $this->dbLastResult;
			echo "<br>result:" . $tmp;
			return;
			

			$t = new Template(TMPL_."common/t_debug.html");
			$t->replace("{debugFile}", 		$_SERVER["PHP_SELF"]);
			$t->replace("{debugLine}",		$this->dbLine);
			$t->replace("{debugQuery}", 	$this->dbLastQuery);
			$t->replace("{debugResult}", 	$tmp);
			echo $t->parse();
			return true;
		}


		/**
		 * Cierro conexion 
		 * @return void 
		 */
		function close() {
			@mysql_free_result($this->result);
			@mysql_close($this->dbConnect);
		}
	
	}
}

?>