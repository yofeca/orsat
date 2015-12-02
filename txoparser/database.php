<?php

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASSWORD = '';
    $DATABASE = 'orsat';

    /* Connecting, selecting database */
    $link = mysql_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD) or die("Could not connect : " . mysql_error());
    mysql_select_db($DATABASE) or die("Could not select database");

    function dbQuery( $query )
    {
        global $DATABASE_HOST;
        global $DATABASE_USER; 
        global $DATABASE_PASSWORD;
        global $DATABASE;
        
        $returnArr = array();
        $result = mysql_query($query) or die("Query failed : " . mysql_error() . "<br>Query: <b>$query</b>");
        
        if(@mysql_num_rows($result)) //if query is select
        {
            while ($row = mysql_fetch_assoc($result))
            {
                array_push($returnArr, $row);
            }       
        }
        else if(@mysql_insert_id()) //if query is insert
        {
            $returnArr["mysql_insert_id"] = @mysql_insert_id();
        }
        else //other queries
        {
            return $returnArr;
        }

        //Fee the resultset
        @mysql_free_result($result);

        //return array
        return $returnArr;

    } //end of dbQuery
    
?>

