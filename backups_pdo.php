<?php
    include('config.php');
    include('cls_conexio.php');
    include('funcions.php');
    $output ='';
    $db2 = ConectarDB::InstanciaUnica()->cnx();    
    $dbname = DB_NAME;
    $output .= "-- Copia seguretat feta el : " . date("r", time()) . "\n";
    $output .= "-- PHP Versió : " . phpversion() . "\n\n";
    $output .= "-- Base de dades : `$dbname`\n";
    $output .= "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n\n";
    $tables = array();
    $stmt = $db2->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    foreach ($tables as $table) {
        $fields = "";
        $sep2 = "";
        $output .= "\n-- " . str_repeat("-", 60) . "\n\n";
        $output .= "--\n-- Table structure for table `$table`\n--\n\n";
        $stmt = $db2->query("SHOW CREATE TABLE $table");
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $output .= $row[1] . ";\n\n";
        $output .= "--\n-- Dumping data for table `$table`\n--\n\n";
        $stmt = $db2->query("SELECT * FROM $table");
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            if ($fields == "") {
                $fields = "INSERT INTO `$table` (";
                $sep = "";
                foreach ($row as $col => $val) {
                    $fields .= $sep . "`$col`";
                    $sep = ", ";
                }
                $fields .= ") VALUES";
                $output .= $fields . "\n";
            }
            $sep = "";
            $output .= $sep2 . "(";
            foreach ($row as $col => $val) {
                $val = addslashes($val);
                $search = array("\'", "\n", "\r");
                $replace = array("''", "\\n", "\\r");
                $val = str_replace($search, $replace, $val);
                $output .= $sep . "'$val'";
                $sep = ", ";
            }
            $output .= ")";
            $sep2 = ",\n";
        }
        $output .= ";\n";
    }
    $filename2= $dbname . '_' . date('d-m-Y-H-i-s');
    $filename= $filename2 . '.sql';
    $file = fopen($filename, "w");
    fwrite($file, $output . PHP_EOL);
    fclose($file);
    EncriptaArch($filename);
    $zip = new ZipArchive();  
    $zip_name = $filename2 .'.zip' ; 
    if ($zip->open($zip_name, ZipArchive::CREATE) === TRUE) {
      $zip->addFile($filename);
      $zip->close();
      unlink($filename); 
    } 
    
    $db2 = null;
    $zip = null;
?>