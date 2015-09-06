<?
$servername = "localhost";
$username = "root";
$password = "";
$database_name="fb_athenslair";
$database_forum="smf";

$conn=mysql_connect($servername, $username, $password) or die(mysql_error());
mysql_select_db($database_forum) or die(mysql_error());

$conn_db = new mysqli($servername, $username, $password);
// Check connection
if ($conn_db->connect_error) die("Connection failed: " . $conn_db->connect_error);
//select database
mysqli_select_db($conn_db,$database_name);
mysqli_set_charset($conn_db, "utf8");

function find_next($table,$col){

	$result = mysql_query("SELECT * FROM $table") or die(mysql_error());

	$i=0;
	while($row = mysql_fetch_array($result)){
        	$idb[$i]=$row[$col];
        	$i=$i+1;
	}

	return max($idb)+1;
}

function update_table($table,$where,$wvalue,$col,$value){
	$result = mysql_query("UPDATE $table SET $col='$value'  WHERE $where='$wvalue'") or die(mysql_error());  
}

function new_comment($username,$title,$message,$id_board,$id_topic){
	$message=mysql_real_escape_string($message);
        $result = mysql_query("SELECT * FROM smf_members WHERE member_name='$username'") or die(mysql_error());
        $member_data = mysql_fetch_array($result) or die(mysql_error());

        $id_member = $member_data['id_member'];
        $posts     = $member_data['posts'];
        $email     = $member_data['email_address'];
	
	$result     = mysql_query("SELECT * FROM smf_topics WHERE id_topic='$id_topic'") or die(mysql_error());
        $topic_data = mysql_fetch_array($result) or die(mysql_error());
	$num_posts  = $topic_data['num_replies'];

	$new_id_message = find_next('smf_messages','id_msg');
	
	//update topics
	update_table('smf_topics','id_topic',$id_topic,'id_last_msg',$new_id_message);
        update_table('smf_topics','id_topic',$id_topic,'id_member_updated',$id_member);
        update_table('smf_topics','id_topic',$id_topic,'num_replies',$num_posts+1);
	$time=time();
        
	//add to messages
	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
        mysql_query("INSERT INTO smf_messages (id_msg, id_topic, id_board, poster_time, id_member, id_msg_modified, subject, poster_name, poster_email, poster_ip, smileys_enabled, modified_time, modified_name, body, icon, approved) VALUES ('$new_id_message','$id_topic','$id_board','$time','$id_member','$new_id_message','$title','$username','$email','','1','0','','$message','xx','1')") or die(mysql_error());

        //post count
        update_table('smf_members','id_member',$id_member,'posts',$posts+1);

        //update log_topics
//        mysql_query("UPDATE smf_log_topics SET id_msg='$new_id_message' WHERE id_member='$id_member' AND id_topic='$id_topic'") or die(mysql_error());

        //insert log_digest
//        mysql_query("INSERT INTO smf_log_digest (id_topic, id_msg, note_type, daily, exclude) VALUES ('$id_topic','$new_id_message','reply','0','0')") or die(mysql_error());

        //update log_boards
        mysql_query("UPDATE smf_log_boards SET id_msg='$new_id_message' WHERE id_member='$id_member' AND id_board='$id_board'") or die(mysql_error());

        //update log_activity
//        $result = mysql_query("SELECT * FROM smf_log_activity") or die(mysql_error());
//        while($row = mysql_fetch_array($result)){
//                $last_date   = $row['date'];
//                $last_posts  = $row['posts'];
//                $last_topics = $row['topics'];
//        }
//        update_table('smf_log_activity','date',$last_date,'posts',$last_posts+1);


        //update boards
        $result = mysql_query("SELECT * FROM smf_boards WHERE id_board='$id_board'") or die(mysql_error());
        $row = mysql_fetch_array($result);
        $ntopics=$row['num_topics'];
        $nposts=$row['num_posts'];
        update_table('smf_boards','id_board',$id_board,'id_last_msg',$new_id_message);
        update_table('smf_boards','id_board',$id_board,'id_msg_updated',$new_id_message);
        update_table('smf_boards','id_board',$id_board,'num_posts',$nposts+1);
      

}

function new_topic($username,$title,$message,$id_board){	
        $message=mysql_real_escape_string($message);

	$result = mysql_query("SELECT * FROM smf_members WHERE member_name='$username'") or die(mysql_error());
	$member_data = mysql_fetch_array($result) or die(mysql_error());
	
	$id_member = $member_data['id_member'];
	$posts     = $member_data['posts'];
	$email     = $member_data['email_address'];
	//add to topics
	$new_id_topic   = find_next('smf_topics','id_topic');
	$new_id_message = find_next('smf_messages','id_msg');
	mysql_query("INSERT INTO smf_topics (id_topic, is_sticky, id_board, id_first_msg, id_last_msg, id_member_started, id_member_updated, id_poll, id_previous_board, id_previous_topic, num_replies, num_views, locked, unapproved_posts, approved) VALUES ('$new_id_topic','0','$id_board','$new_id_message','$new_id_message','$id_member','$id_member','0','0','0','0','0','0','0','1')") or die(mysql_error()); 
	$time=time();
	//add to messages
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	mysql_query("INSERT INTO smf_messages (id_msg, id_topic, id_board, poster_time, id_member, id_msg_modified, subject, poster_name, poster_email, poster_ip, smileys_enabled, modified_time, modified_name, body, icon, approved) VALUES ('$new_id_message','$new_id_topic','$id_board','$time','$id_member','$new_id_message','$title','$username','$email','','1','0','','$message','xx','1')") or die(mysql_error());

	//post count
       	update_table('smf_members','id_member',$id_member,'posts',$posts+1);

	//insert log_topics
//	mysql_query("INSERT INTO smf_log_topics (id_member, id_topic, id_msg) VALUES ('$id_member','$new_id_topic','$new_id_message')") or die(mysql_error());

	//insert log_digest
//	mysql_query("INSERT INTO smf_log_digest (id_topic, id_msg, note_type, daily, exclude) VALUES ('$new_id_topic','$new_id_message','topic','0','1')") or die(mysql_error());
	
	//update log_boards
	mysql_query("UPDATE smf_log_boards SET id_msg='$new_id_message' WHERE id_member='$id_member' AND id_board='$id_board'") or die(mysql_error());

	//update log_activity
  //      $result = mysql_query("SELECT * FROM smf_log_activity") or die(mysql_error());
    //    while($row = mysql_fetch_array($result)){
//		$last_date   = $row['date'];
//		$last_posts  = $row['posts'];
//		$last_topics = $row['topics'];
//	}
//	update_table('smf_log_activity','date',$last_date,'posts',$last_posts+1);
//	update_table('smf_log_activity','date',$last_date,'topics',$last_topics+1);

	//update boards
	$result = mysql_query("SELECT * FROM smf_boards WHERE id_board='$id_board'") or die(mysql_error());
	$row = mysql_fetch_array($result);
	$ntopics=$row['num_topics'];
        $nposts=$row['num_posts'];
        update_table('smf_boards','id_board',$id_board,'id_last_msg',$new_id_message);
      	update_table('smf_boards','id_board',$id_board,'id_msg_updated',$new_id_message);
	update_table('smf_boards','id_board',$id_board,'num_posts',$nposts+1);
	update_table('smf_boards','id_board',$id_board,'num_topics',$ntopics+1);
	return $new_id_topic;
}


$sql="SELECT * FROM POSTS WHERE inforum='0'";
$result = $conn_db->query($sql);
$num_posts=0;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $pids[$num_posts]         = $row["pid"];
	$author[$num_posts]       = $row["author"];
	$times[$num_posts]        = $row["time"];
	$messages[$num_posts]     = $row["message"];
	$pictures[$num_posts]     = $row["picture"];
	$links[$num_posts]        = $row["link"];
	$names[$num_posts]        = $row["name"];
	$descriptions[$num_posts] = $row["description"];
	$titles[$num_posts]       = $row["title"];
	$num_posts++;	
    }
} 


for ($i=0;$i<$num_posts;$i++){
	$sql="UPDATE POSTS SET inforum='1' WHERE pid='$pids[$i]'";
	$conn_db->query($sql);

        $post_name=$author[$i];
        $post=$names[$i].'<br><br>'.$descriptions[$i].'<br><br>'.$links[$i].'<br><br>'.$messages[$i];
	$idt=new_topic($post_name,$titles[$i],$post,'2');
        
	$sql="SELECT * FROM COMMENTS WHERE pid='$pids[$i]'";
	$result = $conn_db->query($sql);
	$num_com=0;
	if ($result->num_rows > 0) {
    		// output data of each row
    		while($rowc = $result->fetch_assoc()) {
        		$authorc[$num_com]       = $rowc["author"];
        		$messagesc[$num_com]     = $rowc["message"];
        		$num_com++;
    		}
		$rowc=[];
	}


        for ($j=0;$j<$num_com;$j++){
                $commenter=$authorc[$j];
		$comment=$messagesc[$j];

		new_comment($commenter,'Re:',$comment,'2',$idt);
		
        }
	$authorc=[];
	$messagesc=[];

       
}
echo "press <a href=custom.php>this</a> to complete";
echo "<script type='text/javascript'>location.href = 'index_hashtags.php';</script>";

?>
