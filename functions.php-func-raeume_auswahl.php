<?php

// $Id: functions.php-func-raeume_auswahl.php,v 1.5 2012/10/17 06:16:53 student Exp $


function raeume_auswahl($raum,$offen,$alle,$nur_chat=TRUE) {
// Gibt Liste aller Räume aus, wenn $offen=0
// Gibt Liste aller offenen (O,T) Räume aus, wenn $offen=1
// $raum ist Voreinstellung
// "L" sind geLOCKte Räume, werden auch nicht mit angezeigt.
// $nur_chat=TRUE -> Nur Räume zeigen
// $nur_chat=FALSE -> Räume + Community-Bereiche (whotext) zeigen

global $dbase,$u_id,$conn,$o_raum,$timeout,$whotext,$forumfeatures,$communityfeatures;



// alle=TRUE, falls alle Räume (auch leere) gezeigt werden sollen
if ($alle) {
	$subquery1="WHERE ((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(o_aktiv)) <= $timeout OR o_id IS NULL)";
} else {
	$subquery1="WHERE ((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(o_aktiv)) <= $timeout)";
};



// Liste der Räume mit der Anzahl der User aufstellen
$query="SELECT r_id,count(o_id) as anzahl FROM raum ".
	"LEFT JOIN online ON r_id=o_raum ".
	"$subquery1 ".
	"GROUP BY r_id";

// system_msg("",0,$u_id,"#000000","Debug: ".$query);
$result=mysql_query($query, $conn);
while ($row=mysql_fetch_object($result))
	$anzahl_user[$row->r_id]=$row->anzahl;
@mysql_free_result($result);


// Optional Formularzusatz für Community-Module  ergänzen
$zusatz_select="";
if ($forumfeatures && $communityfeatures && !$nur_chat) {
	$query="SELECT o_who,count(o_who) as anzahl FROM online ".
		"WHERE o_who>1 ".
		"GROUP BY o_who ";

	$result=mysql_query($query, $conn);
	while ($row=mysql_fetch_object($result))
		$anzahl_who[$row->o_who]=$row->anzahl;
	@mysql_free_result($result);

	foreach($whotext as $key => $whotxt) {
		if ($key>1 && isset($anzahl_who) && $anzahl_who[$key])
			$zusatz_select.="<OPTION VALUE=\"".($key*(-1))."\">&gt;&gt;".$whotxt."&lt;&lt; (".$anzahl_who[$key].")\n";
	}
}

// offen=FALSE, falls nur offene Räume gezeigt werden sollen
if (! $offen) {

	// Liste der Räume aufstellen und die Anzahl der User ergänzen
	$query="SELECT r_id FROM raum LEFT JOIN invite ON inv_raum=r_id ".
		"WHERE inv_user='$u_id' ";

	# system_msg("",0,$u_id,"#000000","Debug: ".$query);

	$rows=array();
	$result=mysql_query($query, $conn);
	if ($result){
		while ($row=mysql_fetch_row($result)) {
			$rows[]=$row[0];
		}
		mysql_free_result($result);        
	};

	$query="SELECT r_id FROM raum ".
		"WHERE r_status1='O' OR r_status1 like binary 'm' OR r_besitzer=$u_id OR r_id=$o_raum";
	# system_msg("",0,$u_id,"#000000","Debug: ".$query);

	$result=mysql_query($query, $conn);
	if ($result){
		while ($row=mysql_fetch_row($result)) {
			$rows[]=$row[0];
		}
		mysql_free_result($result);
	};
};


// offen=TRUE alle Räume anzeigen
if ($offen) {
	$query="SELECT r_status1,r_name,r_id FROM raum ORDER BY r_name";
} elseif (is_array($rows)) {
	$query="SELECT r_status1,r_name,r_id FROM raum WHERE r_id IN (".implode(",",$rows).") ORDER BY r_name";
} else {
	 return(1);
};
$result=mysql_query($query, $conn);
# system_msg("",0,$u_id,"#000000","Debug: ".$query);

echo $zusatz_select;
while ($row=mysql_fetch_object($result)):
	if ($row->r_status1!="O"):
		// L->t und G->g übersetzen
		if ($row->r_status1=="L"):
			$status="/t";
		elseif ($row->r_status1=="G"):
			$status="/g";
		else:
			$status="/".$row->r_status1;	
		endif;
	else:
		$status="";
	endif;
	if (isset($anzahl_user[$row->r_id]) && $anzahl_user[$row->r_id]>0):
		$anzahl=$anzahl_user[$row->r_id];
	else:
		$anzahl=0;
	endif;

	// Alle Räume oder nur die Räume mit Usern zeigen
	if (($anzahl>0) OR $alle):
		if ($row->r_id==$raum):
			echo "<OPTION SELECTED VALUE=\"$row->r_id\">$row->r_name (".$anzahl.$status.")\n";
		else:
			echo "<OPTION VALUE=\"$row->r_id\">$row->r_name (".$anzahl.$status.")\n";
		endif;
	endif;
endwhile;
echo $zusatz_select;
@mysql_free_result($result);

};


?>
