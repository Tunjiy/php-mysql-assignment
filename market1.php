<html>
<body>
    <form action="market1.php" method ="POST" >
       Enter first name: <input type="text" name="f_name"><br>
       Enter last name: <input type="text" name="l_name"><br>
       Enter items bought: <input type="text" name="i_bought"><br>
       <input type="submit" name="submit_btn"><br><br>
      <input type= "submit" name="display_db" value="display_database"><br><br>
      <input type="submit" name="prev" value="previous">
       <input type="submit" name="nxt" value="next">
      
      <center>
        <input type = "text" name="search"/>
        <input type="submit" name="submit" value="search db">
        </center>
        <hr/>
        <u>Result:</u>&nbsp
      </form>



    <?php

        $conn= mysqli_connect("localhost", "root", "", "market");
        if(!$conn)
        {
            die("failed to connect to db".mysqli_connect_errno());
        }
        $firstname=$_POST['f_name'];
        $lastname=$_POST['l_name'];
        $items_bought=$_POST['i_bought'];
        if(isset($_POST['submit_btn']))
        {

            $query=mysqli_query($conn, "INSERT INTO store1 VALUES('','$firstname', '$lastname', '$items_bought')");
            echo "items successfully stored in db <br>";
        }
        
        //var to divide db into 6 rows
        $per_page= 6;
        // alternative if else stmt
        $page= (Isset($_GET['page'])) ? (int) $_GET['page'] : 1;
        //set the pageto start from 0   
        $start= ($page -1) * $per_page;
        //var used to get the next page
        $next_page= $per_page +1;
        //if stmt to get the first 6 rows
        if (isset($_POST['display_db'])||isset($_POST['prev']))
        {   echo "<table border=1>
            <th>id</th> <th>Name</th> <th>Items Bought</th>
            </table>";
            //select all element in db from first to 6th row
            $Pagination_query3=mysqli_query($conn, "SELECT * FROM store1 LIMIT $start, $per_page");
            while($row= mysqli_fetch_array($Pagination_query3))
            {
                echo 
                "<table border=1>
                    <tr> <td>$row[id]</td>
                    <td>$row[firstname] 
                    $row[lastname]</td>
                    <td>$row[items_bought]</td>
                    </tr>
                </table>";
            }        
        }
        //if stmt to get the next 6 rows
        else if(isset($_POST['nxt']))
        {   echo "<table border=1>
            <th>id</th> <th>Name</th> <th>Items Bought</th>
            </table>";
            //select all element in the db from 7th to 12th row
            $Pagination_query3=mysqli_query($conn, "SELECT * FROM store1 LIMIT $next_page, $per_page");
            while($row= mysqli_fetch_array($Pagination_query3))
            {
            
                echo 
                "<table border=1>
                    <tr> <td>$row[id]</td>
                    <td>$row[firstname] 
                    $row[lastname]</td>
                    <td>$row[items_bought]</td>
                    </tr>
                </table>";
            }

        }
        //searching
        // if isset means when the button is clicked, the ffg action should be performed
        if(isset($_REQUEST['submit']))
        {
            $search=$_POST['search'];
            $terms= explode(" ",$search);/* explode function helps to turn all data entered
            into an array form*/
            $sql="SELECT * FROM store1 WHERE ";
            $i=0;
            foreach($terms as $each)
            {
                $i++;
                if($i==1)// if we search exactly one word do
                {//like allows one to search for a specified pattern in db
                    $sql .= "lastname LIKE '%$each%' "; 
                }
                else{ // do if word is more than one
                    $sql .= "lastname LIKE '%$each%' ";
                }
            }
            //$checkdb= mysqli_select_db($connect,"store1");
            $searchbox= mysqli_query($conn,$sql);
            $num= mysqli_num_rows($searchbox);
            if($num > 0 && $search !="")
            {
                echo"$num result(s) found for $search";
                while($row=mysqli_fetch_assoc($searchbox))
                {
                    $id=$row['id'];
                    $firstname=$row['firstname'];
                    $lastname=$row['lastname'];
                    $items_bought=$row['items_bought'];
                    echo "<br/><h3>firstname: $firstname(id: $id)</h3><br/>lastname: $lastname<br/>items bought: $items_bought<br/>";
                }
            }
            else{
                echo "no result found";
            }
        }else{
            echo "please enter any word to search (lastname only).....";
        }
            
        
     /*questions
     1. If I want to be able to search using both firstname,lastname and items_bought,pls how do I write the sql
     2. I'm not sure how to sort alphabetically or ascending/descending.what I did was to divide the num of rows
     displayed by 6 since the table has bn arranged in the db via the index key.pls help me out with this
     */   
    ?>
   


</body>
</html>


