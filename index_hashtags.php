<html>
<head>

    <meta charset="utf-8">
    <link rel="stylesheet" media="screen" href="style.css" >
    <link rel="stylesheet" media="screen" href="m-button.css" >

</head>
<body><table><tr><td>
<form class="contact_form" action="index_hashtags.php" method="get" name="contact_form">
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
    <div align=right><button  class="m-btn black" type="submit">Update Forum</button></div>
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

function getnick($name){
	$conn=connect_db();
	$sql="SELECT nickname FROM NICKNAMES WHERE realname='$name'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
    		while($row = $result->fetch_assoc()) {
        		return $row[nickname];
    		}
	}
}



function search_post_id($pid){
	$conn=connect_db();

	$sql = "SELECT pid FROM POSTS WHERE pid = '$pid'";
	$result = $conn->query($sql);

	if ($result -> fetch_assoc()){
		$conn->close();
		return 1;
	}
	else {
		$conn->close();
		return 0;
	}
}

function hashtag_title($hashtag){
        if(strpos($hashtag,'#import_')|| !strcmp(substr($hashtag,0,8),'#import_')){
                $a='';
                $array=explode(" ",$hashtag);
                for($i=0;$i<sizeof($array);$i++){
                        if(strpos($array[$i],'#import_') || !strcmp(substr($array[$i],0,8),'#import_')){
                                $Firstpos=strpos($array[$i], '#');
                                $Secondpos=strlen($array[$i]);
                                $a = substr($array[$i] , $Firstpos, $Secondpos);


                                break;
                        }
                }
                $title=str_replace('#import','',$a);
                return str_replace('_',' ',$title);
        }
        return '';

}

if(isset($_GET['GROUP_ID']) && isset($_GET['ACCESS_TOKEN']) ){
	$GROUP_ID=$_GET['GROUP_ID'];
	$ACCESS_TOKEN=$_GET['ACCESS_TOKEN'];
	$url = 'https://graph.facebook.com/'.$GROUP_ID.'/feed?access_token='.$ACCESS_TOKEN;
	//echo "<a href='".$url."'>link</a>";
	$conn=connect_db();
	$i=-1;

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
			$good_post=0;
			if (isset($obj['data'][$j]['message'])){
				if (strstr($obj['data'][$j]['message'], '#import_') !== FALSE){
					$good_post=1;
				}
			}

                        if (isset($obj['data'][$j]['comments']['data'])){
                                $numcom=sizeof($obj['data'][$j]['comments']['data']);
                                for($c=0;$c<$numcom;$c++){
					 if (isset($obj['data'][$j]['comments']['data'][$c]['message'])){
						if (strstr($obj['data'][$j]['comments']['data'][$c]['message'], '#import_') !== FALSE){
							$good_post=1;
						}
					}
				}
			}
			if ($good_post==1){
				// add only new post in database
			
				if(!search_post_id($obj['data'][$j]['id']) && strcmp($obj['data'][$j]['id'],'249565538470710_845575812203010')){
					$title='';
 					$message='';
					$picture='';
					$link='';
					$name='';
					$description='';
                        		if (isset($obj['data'][$j]['message'])){
						$message= mysql_real_escape_string($obj['data'][$j]['message']);
						$title=hashtag_title($message);
					}
                        		if (isset($obj['data'][$j]['picture']))    $picture=$obj['data'][$j]['picture'];
                        		if (isset($obj['data'][$j]['link']))       $link=$obj['data'][$j]['link'];
                        		if (isset($obj['data'][$j]['name']))       $name= mysql_real_escape_string($obj['data'][$j]['name']);
                        		if (isset($obj['data'][$j]['description']))$description= mysql_real_escape_string($obj['data'][$j]['description']);
					$pid= $obj['data'][$j]['id'];
					$author = $obj['data'][$j]['from']['name'];
					$time = $obj['data'][$j]['created_time'];
					
					$sql="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
					$conn->query($sql);
                                        $sql="SET NAMES 'utf8'";
                                        $conn->query($sql);
                                        $sql="SET CHARACTER SET 'utf8'";
                                        $conn->query($sql);

					$author=getnick($author);

					$sql="INSERT INTO POSTS (pid,author,time,message,picture,link,name,description,title) VALUES ('$pid', '$author', '$time', '$message', '$picture', '$link', '$name', '$description','$title')";
					$conn->query($sql);

					if (isset($obj['data'][$j]['comments']['data'])){
						$numcom=sizeof($obj['data'][$j]['comments']['data']);
						for($c=0;$c<$numcom;$c++){
		                                        $message='';
                		                        $picture='';
                                		        $link='';
                                		        $name='';
							$title='';
                                        		$cid=$obj['data'][$j]['comments']['data'][$c]['id']; 
                                        		$author=$obj['data'][$j]['comments']['data'][$c]['from']['name'];
                                        		$time=$obj['data'][$j]['comments']['data'][$c]['created_time'];
    							if (isset($obj['data'][$j]['comments']['data'][$c]['message'])){
								$message= mysql_real_escape_string($obj['data'][$j]['comments']['data'][$c]['message']);
								if (!strcmp($title,'')){
									$title=hashtag_title($message);
								}
							}
                        				if(isset($obj['data'][$j]['comments']['data'][$c]['picture'])) $picture=$obj['data'][$j]['comments']['data'][$c]['picture'];
                        				if (isset($obj['data'][$j]['comments']['data'][$c]['link'])) $link=$obj['data'][$j]['comments']['data'][$c]['link'];
                        				if (isset($obj['data'][$j]['comments']['data'][$c]['name'])) $name= mysql_real_escape_string($obj['data'][$j]['comments']['data'][$c]['name']);
                        				if (isset($obj['data'][$j]['comments']['data'][$c]['description'])) $description= mysql_real_escape_string($obj['data'][$j]['comments']['data'][$c]['description']);

							$sql="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
                                        		$conn->query($sql);
							$sql="SET NAMES 'utf8'";
							$conn->query($sql);
							$sql="SET CHARACTER SET 'utf8'";
							$conn->query($sql);
	
							$author=getnick($author);

							$sql="INSERT INTO COMMENTS (id,pid,author,time,message,picture,link,name,description) VALUES ('$cid','$pid', '$author', '$time', '$message', '$picture', '$link', '$name', '$description')";
                                        		$conn->query($sql);
							
							
							if (strcmp($title,'')){
								$sql="SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'";
                                                        	$conn->query($sql);
                                                        	$sql="SET NAMES 'utf8'";
                                                        	$conn->query($sql);
                                                        	$sql="SET CHARACTER SET 'utf8'";
                                                        	$conn->query($sql);
								$sql="UPDATE POSTS SET title='$title' WHERE pid='$pid'";
								$conn->query($sql);
							}
						}
					}
				}
			}
	
		}
	}
	$conn->close();

	echo "<script type='text/javascript'>location.href = 'custom.php';</script>";
}
  
?>

</body></html>
