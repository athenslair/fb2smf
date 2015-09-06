1) Ανοιγεις με ενα editor το create_database.php και κανεις edit τις ακολουθες μεταβλητες που αφορουν την βαση δεδομενων

$servername = "localhost";
$username = "root";
$password = "";

2) Τρεχεις το create_database.php για να δημιουργηθει η βαση δεδομενων. Θα φτιαχτει βαση δεδομενων με ονονα fb_athenslair και tables
τα POSTS,COMMENTS,NICKNAMES

3) Ανοιγεις με ενα editor το nicknames.php και κανεις edit τις ακολουθες μεταβλητες που αφορουν την βαση δεδομενων (γραμμες 32-34)

$servername = "localhost";
$username = "root";
$password = "";

4) Τρεχεις το nicknames.php οπου σου ζηταει Group_id και Access_token. Tο Group_id ειναι 249565538470710. 

5) Για το Access_token πας https://developers.facebook.com/tools/explorer επιλεγεις version v2.0 και πατας Get Token -> Get Access Token
οπου επιλεγεις για permissions τα user_groups και user_managed_groups

6) Εκτελοντας το βημα 4) στη βαση δεδομενων στο table NICKNAMES εχουν αποθηκευτει ολα τα ονοματα των χρηστων του group (real names).
Πηγαινεις phpmyadmin για να κανεις edit το table ΝICKNAMES για να γραψεις για καθε realname το αντιστoιχο nickname του forum.

7) Ανοιγεις με ενα editor το index_hashtags.php και κανεις edit τις ακολουθες μεταβλητες που αφορουν την βαση δεδομενων  (γραμμες 32-34)

$servername = "localhost";
$username = "root";
$password = "";

7) Ανοιγεις με ενα editor το custom.php και κανεις edit τις ακολουθες μεταβλητες που αφορουν την βαση δεδομενων  (γραμμες 2-4,6)

$servername = "localhost";
$username = "root";
$password = "";
$database_forum="smf";

H μεταβλητη $database_forum παιρνει ως τιμη το ονομα της βαση δεδομενων του forum.(To ονομα της βαση δεδομενων καθοριστηκε κατα το installation του forum)

8) Τρεχεις το index_hashtags.php οπου σου ζηταει Group_id και Access_token. (κοιτα βημα 4-5)

9) Τρεχεις το custom.php

10) done
