<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */
 define (TABLE_STATS,'userstats');
class plugin_userloging extends baseplugin {

	public $plugin_info		  = 'User Logging';
	/**
	 * description - A full description of your plugin.
	 */
	public $plugin_description	= 'This plugin Will log user access';
	/**
	 * version - Your plugin's version string. Required value.
	 */
	public $plugin_myversion		= '0.0.1';
	/**
	 * requires - An array of key/value pairs of basename/version plugin dependencies.
	 * Prefixing a version with '<' will allow your plugin to specify a maximum version (non-inclusive) for a dependency.
	 */
	public $plugin_requires	= null;
	/**
	 * author - Your name, or an array of names.
	 */
	public $plugin_author		= 'The FusionTicket team';
	/**
	 * contact - An email address where you can be contacted.
	 */
	public $plugin_email		= 'info@fusionticket.com';
	/**
	 * url - A web address for your plugin.
	 */
	public $plugin_url			= 'http://www.fusionticket.org';

  public $plugin_actions  = array ('config','install','uninstall','priority','enable');

	public $totaalVisits;

		function GetTotalVisits ()
			{
			$result=$this->db->Query("select userstats_id from ".TABLE_STATS);
			$count=$this->db->CountResult($result);
			$this->db->FreeResult($result);
			return $count;
			}

		function GetTotalUniqueVisits ()
			{
			$result=$this->db->Query("select distinct(userstats_ip) from ".TABLE_STATS);
			$count=$this->db->CountResult($result);
			$this->db->FreeResult($result);
			return $count;
			}

		function GetTotalUniqueBrowsers ()
			{
			$result=$this->db->Query("select distinct(userstats_browser) from ".TABLE_STATS);
			$count=$this->db->CountResult($result);
			$this->db->FreeResult($result);
			return $count;
			}

		function GetTopVisitors ()
			{

			$Visitors=Array();
			$result=$this->db->Query("select userstats_ip, count(*) as count from ".TABLE_STATS." group by userstats_ip order by userstats_ip");
			for($i=0;$i<$this->db->CountResult($result);$i++)
				{
				$Visitors[$this->db->Result($result,$i,"ip")] = $this->db->Result($result,$i,"count");
				}
			$this->db->FreeResult($result);
			array_multisort($Visitors,SORT_NUMERIC,SORT_DESC);
			$VisitorCounts=Array();
			$top= 0;
			foreach ($Visitors as $k => $v)
				{
				$VisitorCounts[$k]=$v."/".$this->totaalVisits;
				$top++;
				if ($top==10) {break;}
				}
			return $VisitorCounts;
			}

		function GetTopBrowsers ()
			{
			$BrowserTypes=Array();
			$result=$this->db->Query("select userstats_browser, count(*) as count from ".TABLE_STATS." group by userstats_browser order by userstats_browser");
			for($i=0;$i<$this->db->CountResult($result);$i++)
				{
				$BrowserTypes[$this->db->Result($result,$i,"userstats_browser")] = $this->db->Result($result,$i,"count");
				}
			$this->db->FreeResult($result);
			array_multisort($BrowserTypes,SORT_NUMERIC,SORT_DESC);
			$top=0;
			$BrowserCounts=Array();
			foreach ($BrowserTypes as $k => $v)
				{
				$BrowserCounts[$k]=$v."/".$this->totaalVisits;
				$top++ ;
				if ($top==10) {break;}
				}
			return $BrowserCounts;
			}

		function GetTopRequests ()
			{
			$Referrers=Array();
			$result=$this->db->Query("select userstats_request_uri, count(*) as count from ".TABLE_STATS." group by userstats_REQUEST_URI order by userstats_REQUEST_URI");
			for($i=0;$i<$this->db->CountResult($result);$i++)
				{
				$Referrers[$this->db->Result($result,$i,"userstats_request_uri")] = $this->db->Result($result,$i,"count");
				}
			$this->db->FreeResult($result);
			array_multisort($Referrers,SORT_NUMERIC,SORT_DESC);
			$ReferrerCounts=Array();
			$top=0;
			foreach ($Referrers as $k => $v)
				{
				$ReferrerCounts[$k]=$v."/".$this->totaalVisits;
				$top++ ;
				if ($top==10) {break;}
				}
			return $ReferrerCounts;
			}

		function GetTopReferrers ()
			{
			$Referrers=Array();
			$result=$this->db->Query("select userstats_referrer, count(*) as count from ".TABLE_STATS." group by userstats_referrer order by userstats_referrer");
			for($i=0;$i<$this->db->CountResult($result);$i++)
				{
	                        $Referrers[$this->db->Result($result,$i,"userstats_referrer")] = $this->db->Result($result,$i,"count");
				}
			$this->db->FreeResult($result);
			$ReferrerCounts=Array();
			$top=0;
			array_multisort($Referrers,SORT_NUMERIC,SORT_DESC);
			foreach ($Referrers as $k => $v)
				{
				$ReferrerCounts[$k]=$v."/".$this->totaalVisits;
				$top++ ;
				if ($top==10) {break;}
				}
			return $ReferrerCounts;
			}

		function configxx ()
			{
      $output = "<br>";
			/* top visitors display*/
			$this->totaalVisits = $this->GetTotalVisits ();

			$TopVisitors =  $this->GetTopVisitors (); $row=true;
			$output .= $this->uw->UI_OpenTable("100%");
			$output .= "<tr><td class=\"TableHeader\" colspan=2>".$this->uw->UI_Label(MODA_1)." (".$this->GetTotalUniqueVisits()."  ".MODA_2.")</td></tr>";
			foreach ($TopVisitors as $k => $v)
				{
				$class=($row= !$row)?"TableRow1":"TableRow2";
        $output .= "<tr><td width='100%' class=\"".$class."\">".(($k=="")?'{empty}':$k)."</td>";
        $output .= "<td class=\"".$class."\" valign='right' width=40>".$v."</td></tr>\n";
				}
			$output .= $this->uw->UI_CloseTable()."<br>";

			/* top browsers display*/
			$TopBrowsers =  $this->GetTopBrowsers (); $row=true;
			$output .= $this->uw->UI_OpenTable("100%");
			$output .= "<tr><td class=\"TableHeader\" colspan=2>".$this->uw->UI_Label(MODA_3)." (".$this->GetTotalUniqueBrowsers()." ".MODA_4.")</td></tr>";
			foreach ($TopBrowsers as $k => $v)
				{
				$class=($row= !$row)?"TableRow1":"TableRow2";
        $output .= "<tr><td width='100%' class=\"".$class."\">".(($k=="")?'{empty}':$k)."</td>";
        $output .= "<td class=\"".$class."\" valign='right' width=40>".$v."</td></tr>\n";
				}
			$output .= $this->uw->UI_CloseTable()."<br>";

			/* top referrers display */
			$TopReferrers =  $this->GetTopReferrers (); $row=true;
			$output .= $this->uw->UI_OpenTable("100%");
			$output .= "<tr><td class=\"TableHeader\" colspan=2>".$this->uw->UI_Label(MODA_5)."</td></tr>";
			foreach ($TopReferrers as $k => $v)
				{
				$class=($row= !$row)?"TableRow1":"TableRow2";
        $output .= "<tr><td width='100%' class=\"".$class."\">".(($k=="")?'{empty}':$k)."</td>";
        $output .= "<td class=\"".$class."\" valign='right' width=40>".$v."</td></tr>\n";
				}
			$output .= $this->uw->UI_CloseTable()."<br>";

			/* top REQUEST_URI display */
			$TopRequests =  $this->GetTopRequests (); $row=true;
			$output .= $this->uw->UI_OpenTable("100%");
			$output .= "<tr><td class=\"TableHeader\" colspan=2>".$this->uw->UI_Label(MODA_12)."</td></tr>\n";
			foreach ($TopRequests as $k => $v)
				{
				$class=($row= !$row)?"TableRow1":"TableRow2";
        $output .= "<tr><td width='100%' class=\"".$class."\">".(($k=="")?'{empty}':$k)."</td>";
        $output .= "<td class=\"".$class."\" valign='right' width=40>".$v."</td></tr>\n";
				}
			$output .= $this->uw->UI_CloseTable()."<br>";

			/* raw logfile display */
			$sql="select * from ".TABLE_STATS." order by userstatse_timestamp desc";
			$result=$this->db->Query($sql);
			$matches=$this->db->CountResult($result);
			$this->db->FreeResult($result);
			if(!isset($_REQUEST['prevoffset'])){$_REQUEST['prevoffset']=0;}
			if(!isset($_REQUEST['offset'])){$_REQUEST['offset']=0;}

			$sql .=" limit ".$_REQUEST['offset'].",10";
			$result=$this->db->Query($sql);
			$output .= $this->uw->UI_OpenTable();
			$output .= "<tr>";
			$output .= "<td class=\"TableHeader\" colspan=2>".$this->uw->UI_Label(MODA_6)."</td>";
			$output .= $this->uw->UI_OpenForm("",$_SERVER['PHP_SELF']."?cmd=".MODS_USE."&file=".MODULE_NAME."&purge=1","");
			$output .= "<td class=\"TableHeader\" align=\"right\">".$this->uw->UI_Submit(MODA_7)."</td>";
			$output .= $this->uw->UI_CloseForm();
		        $output .= "</tr>";

			$row=true;
			for($i=0;$i<$this->db->CountResult($result);$i++)
				{
				$class=($row= !$row)?"TableRow1":"TableRow2";
				$output .= "<tr>";
				$output .= "<td width='10%' class=\"".$class."\">".$this->db->Result($result,$i,"ip")."</td>\n";
				$output .= "<td width='10%' class=\"".$class."\">".date(DATE_FORMAT,$this->db->Result($result,$i,"date_logged"))."<br />".
                                                                                   date(TIME_FORMAT,$this->db->Result($result,$i,"time_logged"))."</td>\n";
				$output .= "<td width='80%' class=\"".$class."\">".
                                           "<b>From:</b>&nbsp;<a class=\"Table\" href=\"http://".$this->db->Result($result,$i,"referrer")."\" target=\"_blank\">".$this->db->Result($result,$i,"referrer")."</a><br />".
                                           "<b>To:</b>&nbsp;<a class=\"Table\" href=\"http://".$this->db->Result($result,$i,"request_uri")."\" target=\"_blank\">".$this->db->Result($result,$i,"request_uri")."</a></td></tr>\n";
				}
			$output .=$this->uw->UI_CloseTable();
                        $this->db->FreeResult ($result);
			$PrevPageAction=$_SERVER['PHP_SELF']."?cmd=".MODS_USE."&file=".MODULE_NAME;
			$PrevPageAction.="&offset=" ;
                        $output .= $this->uw->UI_PageBar($_REQUEST['offset'], $matches, $PrevPageAction);
                        Return $output;
			}


		function PurgeStats ()
			{
			$result=$this->db->Query("delete from ".TABLE_STATS);
			$Content = $this->uw->UI_Message(MODA_10);
			$Content .= $this->uw->UI_Navigate($_SERVER['PHP_SELF']."?cmd=".MODS_USE."&file=".MODULE_NAME);
			return $Content;
                        }

                function GetContent(& $Data)
                        {
	                if(!isset($_REQUEST['purge'])){$_REQUEST['purge']=0;}
	                $Data['SkinContent'] .=$this->uw->UI_OpenWidget(MODA_11);
                        if($_REQUEST['purge']==1)
                             {$Data['SkinContent'] .=$this->PurgeStats ();}
	                else
                             {$Data['SkinContent'] .= $this->ViewStats ();}
	                $Data['SkinContent'] .=$this->uw->UI_CloseWidget();

                        }


  function config() {

  }
  function doPageload() {
		$date_logged=date('c');
		$ip=$_SERVER['REMOTE_ADDR'];

		$browser  = $_SERVER['HTTP_USER_AGENT'];
    $REQUEST_URI = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

		$referrer = getenv("HTTP_REFERER");
		if (isset( $_SERVER["HTTP_COOKIE"]))
            {
		    $referrer = str_replace("&".$_SERVER["HTTP_COOKIE"],'',$referrer);
		    $referrer = str_replace("?".$_SERVER["HTTP_COOKIE"],'',$referrer);

		    $REQUEST_URI = str_replace("&".$_SERVER["HTTP_COOKIE"],'',$REQUEST_URI);
		    $REQUEST_URI = str_replace("?".$_SERVER["HTTP_COOKIE"],'',$REQUEST_URI);
		    }

		$sql="insert into userstats (userstatse_timestamp, userstats_ip, userstats_browser, userstats_referrer, userstats_server, userstats_request_uri) values (";
		$sql.=_esc($date_logged).", ";
		$sql.=_esc($ip).",";
		$sql.=_esc($browser).",";
		$sql.=_esc($referrer).",";
		$sql.=_esc(print_r($_SERVER,true)).",";
		$sql.=_esc($REQUEST_URI);
		$sql.=")";
		ShopDB::Query($sql);
  }


}

?>