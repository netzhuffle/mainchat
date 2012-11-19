<html>
<head>
<title>Mainchat Installation</title>
<script language="javascript">
function newWindow(url,name) 
{
	hWnd=window.open(url,name,'resizable=yes,scrollbars=yes,width=800,height=400');
}
</script>
</head>
<body>

<?php

require ("functions.php-install.php");
$configdatei="../conf/config.php";


echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"8\" border=\"2\" bordercolor=\"#007ABE\" bgcolor=\"#E5E5E5\" style=\"font-family: Arial;\">\n";
echo "<tr bgcolor=\"#007ABE\"><td><p style=\"font-size:20px;text-align:center;color:White;font-family:Arial;\"><b>Mainchat Installation</b></p></td></tr><tr><td>\n";

switch ($aktion)
{
	case "step_2":
	if (!file_exists ($configdatei))
	{
		$regexemail="|^[_a-z0-9-]+(\.[_a-z0-9]+)*@([a-z0-9-]+\.)[a-z]{2,3}$|mi";

		if ($chat['lobby']==""||$chat['dbase']==""||$chat['host']==""||$chat['user']==""||$chat['pass']!=$chat['pass2']||
		($chat['webmaster']==""||(!preg_match($regexemail,$chat['webmaster'])))||
		($chat['hackmail']==""||(!preg_match($regexemail,$chat['hackmail'])))||$chat['chatname']=="")
		{
             		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">\n".
		             "<tr bgcolor=\"#007ABE\"><td colspan=\"2\" style=\"font-size:15px; text-align:center;color:White;\"><B>FEHLER</b></td></tr>\n";
               		if ($chat['lobby']=="")
                       		echo "<tr style=\"color:red;font-weigth:bold;\"><td>Bitte tragen Sie ein Lobby Defaultraum ein!</td></tr>\n";
			if ($chat['dbase']=="")
				echo "<tr style=\"color:red; font-weigth:bold;\"><td>Bitte tragen Sie den Datenbanknamen ein!</td></tr>\n";
			if ($chat['host']=="")
				echo "<tr style=\"color:red; font-weigth:bold;\"><td>Bitte tragen Sie den Datenbankserver ein!</td></tr>\n";
                	if ($chat['user']=="")
       	               		echo "<tr style=\"color:red; font-weigth:bold;\"><td>Bitte tragen Sie den Datenbankuser ein!</td></tr>\n";
               		if ($chat['pass']!=$chat['pass2'])
                       		echo "<tr style=\"color:red; font-weigth:bold;\"><td>Das Passwort stimmt nicht überein!</td></tr>\n";
                	if ($chat['webmaster']=="")
       	                	echo "<tr style=\"color:red; font-weigth:bold;\"><td>Bitte tragen Sie einen Webmaster ein!</td></tr>\n";
			if ((!preg_match ($regexemail,$chat['webmaster']))&&($chat['webmaster']!=""))
				echo "<tr style=\"color:red; font-weigth:bold;\"><td>Ungültige Email-Adresse Webmaster!</td></tr>\n";
                	if ($chat['hackmail']=="")
       	                	echo "<tr style=\"color:red; font-weigth:bold;\"><td>Bitte tragen Sie eine zutändige Person bei Hackversuchen ein!</td></tr>\n";
			if ((!preg_match ($regexemail,$chat['hackmail']))&&($chat['hackmail']!=""))
                       	        echo "<tr style=\"color:red; font-weigth:bold;\"><td>Ungültige Email-Adresse Hackmail!</td></tr>\n";
                	if ($chat['chatname']=="")
       	                	echo "<tr style=\"color:red; font-weigth:bold;\"><td>Bitte tragen Sie den Chatnamen ein!</td></tr>\n";
			echo "<tr><td colspan=\"2\"><br><br></td></tr></table>\n";
			step_1();
		}	
		else
		{
		        $fp=@fopen ($configdatei,"w+");
	        	if (!$fp)
		        {
	             		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">\n".
			             "<tr bgcolor=\"#007ABE\"><td colspan=\"2\" style=\"font-size:15px; text-align:center;color:White;\"><B>FEHLER</b></td></tr>\n".
        		             "<tr style=\"color:red;font-weigth:bold;\"><td>Die Konfigurationsdatei konnte nicht angelegt werden. Überprüfen Sie die Schreibrechte im Verzeichnis conf!</td></tr>\n".
				     "<tr><td colspan=\"2\"><br><br></td></tr></table>\n";
                		step_1();
		        }
        		else
	        	{
				if (!@$connect=mysql_connect($chat['host'],$chat['user'],$chat['pass']))
				{
	             			echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">\n".
			             	     "<tr bgcolor=\"#007ABE\"><td colspan=\"2\" style=\"font-size:15px; text-align:center;color:White;\"><B>FEHLER</b></td></tr>\n".
					     "<tr style=\"color:red; font-weigth:bold;\"><td>FEHLER: Datenbankverbindung fehlgeschlagen!</td></tr>\n".
					     "<tr><td colspan=\"2\"><br><br></td></tr></table>\n";
					unlink ($configdatei);
					step_1();
				}
				else
				{
					if (!$select=mysql_select_db ($chat['dbase'],$connect))
					{
						if (!$create_db=mysql_query("CREATE DATABASE $chat[dbase]",$connect))
						{
		             				echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">\n".
			             	     		     "<tr bgcolor=\"#007ABE\"><td colspan=\"2\" style=\"font-size:15px; text-align:center;color:White;\"><B>FEHLER</b></td></tr>\n".
							     "<tr style=\"color:red; font-weigth:bold;\"><td>FEHLER: Anlegen der Datenbank misslungen!</td></tr>\n".
							     "<tr><td colspan=\"2\"><br><br></td></tr></table>\n";
							unlink ($configdatei);
							step_1();
						}
						else
						{
							$select=mysql_select_db ($chat['dbase'],$connect);
							step_2($connect,$select,$chat,$fp);
						}
				
					}
					else
						step_2($connect,$select,$chat,$fp);
				}
			}
		}
	}
	else
	{
  		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">\n".
		     "<tr bgcolor=\"#007ABE\"><td colspan=\"2\" style=\"font-size:15px; text-align:center;color:White;\"><B>FEHLER</b></td></tr>\n".
        	     "<tr style=\"color:red; font-weigth:bold;\"><td>FEHLER-Die Datei config.php existiert bereits!</td></tr>\n".
		     "<tr><td colspan=\"2\"><br><br></td></tr></table>\n";
		step_1();
	}
	break;

	default:
		if (file_exists ($configdatei))
		{
	  		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">\n".
			     "<tr bgcolor=\"#007ABE\"><td colspan=\"2\" style=\"font-size:15px; text-align:center;color:White;\"><B>FEHLER</b></td></tr>\n".
        		     "<tr style=\"color:red; font-weigth:bold;\"><td>FEHLER-Die Datei config.php existiert bereits!</td></tr>\n".
        		     "<tr><td colspan=\"2\"><br><br></td></tr></table>\n";
		}
		else
		{
			step_1();
		}
	break;
}


echo "</td></tr></table>\n";
?>
</body>
</html>