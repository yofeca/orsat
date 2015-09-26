<?php
$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASSWORD = "";
$DATABASE = "orsat";
function dbQuery($query){
    global $DATABASE_HOST;
    global $DATABASE_USER; 
    global $DATABASE_PASSWORD;
    global $DATABASE;
    $returnArr = array();

    /* Connecting, selecting database */
    $link = mysql_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD)
        or die("Could not connect : " . mysql_error());
    mysql_select_db($DATABASE) or die("Could not select database");

    /* Performing SQL query */
    $result = mysql_query($query) or die("Query failed : " . mysql_error() . "<br>Query: <b>$query</b>");

    
    //if query is select
    if(@mysql_num_rows($result))
    {
        while ($row = mysql_fetch_assoc($result))
        {
            array_push($returnArr, $row);
        }       
    }
    //if query is insert
    else if(@mysql_insert_id())
    {
        $returnArr["mysql_insert_id"] = @mysql_insert_id();
    }
    //other queries
    else
    {
        /* Closing connection */
        mysql_close($link); 
        return $returnArr;
    }
        

    /* Free resultset */
    @mysql_free_result($result);

    /* Closing connection */
    mysql_close($link); 
    
    //return array
    return $returnArr;
}
?>