<?






$conn=mysql_connect("localhost", "root", "") or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db("smf") or die(mysql_error());
echo "Connected to Database";
//mysql_query("SET NAMES 'greek'", $conn);
//mysql_query("SET CHARACTER SET 'greek'", $conn);

//update_table('smf_topics','id_topic','2','is_sticky','1');
//new_topic('admin','a','a');
//new_topic('solo','post from php','let me see if its work','2');
// new_comment('solo','Re:','comment 2','2','2');

function add($name,$email,$id_member){
mysql_query("INSERT INTO smf_members(id_member, member_name, date_registered, posts, id_group, lngfile, last_login, real_name, instant_messages, unread_messages, new_pm, buddy_list, pm_ignore_list, pm_prefs, mod_prefs, message_labels, passwd, openid_uri, email_address, personal_text, gender, birthdate, website_title, website_url, location, icq, aim, yim, msn, hide_email, show_online, time_format, signature, time_offset, avatar, pm_email_notify, karma_bad, karma_good, usertitle, notify_announcements, notify_regularity, notify_send_body, notify_types, member_ip, member_ip2, secret_question, secret_answer, id_theme, is_activated, validation_code, id_msg_last_visit, additional_groups, smiley_set, id_post_group, total_time_logged_in, password_salt, ignore_boards, warning, passwd_flood, pm_receive_from) VALUES ('$id_member','$name','123', '0', '5','', '', '$name', '0', '0', '0', '', '','0', '', '', '', '', '$email','', '0', '', '', '', '','', '', '', '', '0', '1', '', '', '0', '', '0', '0', '0','', '1', '1','0','2', '', '', '', '', '0', '1','', '1', '', '', '0','75', '8184', '', '0','', '1')")or die(mysql_error());

}

$arraya[0]='Gregorio Sing';
$arraya[1]='Thomas Papaspiros';
$arraya[2]='Ilias Tsagk';
$arraya[3]='Nasos Psarrakos';
$arraya[4]='Manos Petrinolis';
$arraya[5]='Roberto Zanon';
$arraya[6]='Agamemnon Christofidis';
$arraya[7]='Iraklis Kiriakakis';
$arraya[8]='Alexandros Davellis';
$arraya[9]='Giannis Yee Georgopoulos';
$arraya[10]='Greg H. Grammenos';
$arraya[11]='Fotis Parisos';
$arraya[12]='Paris Chatzipares';
$arraya[13]='Anthony Del';
$arraya[14]='Konstantinos Dekavallas';
$arraya[15]='Mark Pitsilos';
$arraya[16]='Marios Tourlos';
$arraya[17]='Spyros Georgopoulos';
$arraya[18]='Nicholas Chopper';
$arraya[19]='Konstantinos Kalivas';
$arraya[20]='Teo Kark';
$arraya[21]='Don Mario Jose';
$arraya[22]='Apostolis Bakos';
$arraya[23]='Giannis Mitsogiannis';
$arraya[24]='Photius D Mata';
$arraya[25]='Karim Von Romahorn';
$arraya[26]='Giannis Koniaris';
$arraya[27]='Panagiotis Kouts';
$arraya[28]='Alex Vatan';
$arraya[29]='Nikos Martini';
$arraya[30]='Vasilis Tsamis';
$arraya[31]='Ioannis Anastassakis';
$arraya[32]='Stavros Karaxwmateros';
$arraya[33]='Panagiotis Geo';
$arraya[34]='Mike Kovy';
$arraya[35]='Thanassis Gkatzaras';
$arraya[36]='Dimitris Lymperis';
$arraya[37]='Alexandros Fatsis';
$arraya[38]='George Papadopoulos';
$arraya[39]='Petros Leandros';
$arraya[40]='Elias Marineas';
$arraya[41]='Nikolas Korkolis';
$arraya[42]='Huggy Sammy';
$arraya[43]='Angelo Karageorgos';
$arraya[44]='Thodoras Krevvatas';
$arraya[45]='Χούντας Καρατζάς Σωτήριος';
$arraya[46]='George Kourogiorgas';
$arraya[47]='Alex Chaidaroglou';
$arraya[48]='Antonis Kaliniktakis';
$arraya[49]='Thanos Katsigiannis';
$arraya[50]='Λάζαρος Σιδηρόπουλος';
$arraya[51]='Pan Buzz';
$arraya[52]='Kiriakos Mihalos';
$arraya[53]='Kostas Theoulakis';
$arraya[54]='Dimitris Mitropoulos';
$arraya[55]='Vagelis Kotsonis';
$arraya[56]='Alexandros Papadakis';
$arraya[57]='Dimitris Tsamitros';




for($i=0;$i<=57;$i++)
add($arraya[$i],'alex'.$i.'@gmail.com',$i+5);
?>
