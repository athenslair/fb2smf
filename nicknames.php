<html>
<head>

    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" href="style.css" >
    <link rel="stylesheet" media="screen" href="m-button.css" >

</head>
<body><table><tr><td>
<form class="contact_form" action="nicknames.php" method="get" name="contact_form">
<ul>
<li>
    <label for="group_id">GROUP_ID</label>
    <input type="text" name="GROUP_ID" placeholder="" required/>
</li>
<li>
    <label for="access_token">ACCESS_TOKEN</label>
    <input type="text" name="ACCESS_TOKEN" placeholder="" required/>
</li>
<li>
    <div align=right><button  class="m-btn black" type="submit">Import Names</button></div>
</li>

</ul><br><div align=right><font size=1 color=gray>for you by solo</font></div>
</form></td><td>
<?

$database_name="fb_athenslair";


function connect_db(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database_name="fb_athenslair";

	// Create connection
	$conn = new mysqli($servername, $username, $password);

	// Check connection
	if ($conn->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
	}

	//select database
	mysqli_select_db($conn,$database_name);
	return $conn;
}


if(isset($_GET['GROUP_ID']) && isset($_GET['ACCESS_TOKEN']) ){
	$GROUP_ID=$_GET['GROUP_ID'];
	$ACCESS_TOKEN=$_GET['ACCESS_TOKEN'];
	$url = 'https://graph.facebook.com/'.$GROUP_ID.'/feed?access_token='.$ACCESS_TOKEN;
	//echo "<a href='".$url."'>link</a>";
	$conn=connect_db();
	$i=-1;
	$kk=-1;
	$array=[];
	while(1){
		$i=$i+1;
		//  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL,$url);
		// Execute
		$result=curl_exec($ch);
		// Closing
		curl_close($ch);

		$obj = json_decode($result,TRUE);
		if (sizeof($obj['data'])==0) break;
		$next_url=$obj['paging']['next'];
        	$url=$next_url;
		$dara_array[$i]=$obj['data'];
 
		$numposts=sizeof($obj['data']);
	

		for ($j=0;$j<$numposts;$j++){
			if (isset($obj['data'][$j]['message'])){
					if (!in_array($obj['data'][$j]['from']['name'],$array))
					array_push($array,$obj['data'][$j]['from']['name']);	
			}
		

                        if (isset($obj['data'][$j]['comments']['data'])){
                                $numcom=sizeof($obj['data'][$j]['comments']['data']);
                                for($c=0;$c<$numcom;$c++){
					 if (isset($obj['data'][$j]['comments']['data'][$c]['message'])){
							if(!in_array($obj['data'][$j]['comments']['data'][$c]['from']['name'],$array))
							array_push($array,$obj['data'][$j]['comments']['data'][$c]['from']['name']);
					}
				}
			}
	
		}
	}

	$k=0;
	$dbnames=[];
	$sql="SELECT realname FROM NICKNAMES";
	$result=$conn->query($sql);
	if ($result->num_rows > 0) {
    		while($row = $result->fetch_assoc()) {
        		$dbnames[$k]= $row["realname"];
			$k++;
    		}
	} 

	
	for($k=0;$k<sizeof($array);$k++){
                $sql="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
                $conn->query($sql);
                $sql="SET NAMES 'utf8'";
                $conn->query($sql);
                $sql="SET CHARACTER SET 'utf8'";
                $conn->query($sql);
		if(!in_array($array[$k],$dbnames)){
			$sql="INSERT INTO NICKNAMES (realname) VALUES ('$array[$k]')";	
			$conn->query($sql);
		}
	}
}
  
?>

</body></html>
