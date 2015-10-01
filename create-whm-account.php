<?php
###############################################################
# cPanel WHM Account Creator 1.0
# Dub Dub Design http://www.dubdubdesign.co.nz
# Creates bulk user accounts from CSV
###############################################################

# GLOBAL SETTINGS:

$host 		= "dubdubdesign.co.nz"; 	// host ie dubdubdesign.co.nz
$root_pass 	= "";				// WHM root password
$user_plan 	= "DubDubDesign";		// WHM user plan ie DubDubDesign
$user_email 	= "michael@dubdubdesign.co.nz";	// account owner email
$cpanel_skin 	= "x3"; 			// typically 'x3'
$has_shell	= 'y'; 				// y/n
$csv_path	= 'account.csv';		// path to CSV

###############################################################
# DON'T EDIT BELOW THIS LINE
###############################################################
include_once 'includes/xmlapi.php';
$csv = array_map('str_getcsv', file($csv_path));
$count = 1;

$xmlapi = new xmlapi($host);
$xmlapi->password_auth("root",$root_pass);
$xmlapi->set_debug(1);

while($count<count($csv)){	
	$user_domain 		= $csv[$count][4];
	$user_name 		= $csv[$count][2];
	$user_pass 		= $csv[$count][3];
	$cpuser 		= $csv[$count][2];
	$cppass 		= $csv[$count][3];
	$cpanel_host 		= $host;
	$db_username 		= $csv[$count][7];
	$db_userpass 		= $csv[$count][9];
	$db_name 		= $csv[$count][8];
	$park1			= $csv[$count][5]; 
	$park2			= $csv[$count][6]; 	

	$acct = array( 'username' => $user_name, 'password' => $user_pass, 'domain' => $user_domain , 'plan' => $user_plan, 'contactemail' => $user_email, 'hasshell' => $has_shell );
	$xmlapi->createacct($acct);
	$xmlapi->api1_query($cpuser, "Mysql", "adddb", array($db_name)); 
	$xmlapi->api1_query($cpuser, "Mysql", "adduser", array($db_username, $db_userpass));
	$xmlapi->api1_query($cpuser, "Mysql", "adduserdb", array($db_name, $db_username, 'all'));
	$xmlapi->park($cpuser, $park1, '');
	$xmlapi->park($cpuser, $park2, '');
?>
<table border="1">			
		<tr>
			<td>Customer:</td>
			<td><?php echo $csv[$count][0];?></td>
		</tr>		
		<tr>
			<td>Domain:</td>
			<td><?php echo $csv[$count][4];?></td>
		</tr>
		<tr>
			<td>Dev URL:</td>
			<td><?php echo $park1;?></td>
		</tr>
		<tr>
			<td>Client Test URL:</td>
			<td><?php echo $park2;?></td>
		</tr>		
		<tr>
			<td>FTP/SSH Host</td>
			<td><?php echo $host; ?></td>
		</tr>
		<tr>
			<td>FTP/SSH User:</td>
			<td><?php echo $user_name;?></td>
		</tr>
		<tr>
			<td>FTP/SSH Password:</td>
			<td><?php echo $user_pass;?></td>
		</tr>
		<tr>
			<td>Database:</td>
			<td><?php echo $db_name;?></td>
		</tr>
		<tr>
			<td>Database User:</td>
			<td><?php echo $db_username;?></td>
		</tr>
		<tr>
			<td>Database Password:</td>
			<td><?php echo $db_userpass;?></td>
		</tr>	
	</table>
	<br/>User Account Complete<hr/>		
	<?php
	$count++ ;
}
?>
