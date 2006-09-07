<?php
/*******************************************************************************
	 Function Listing:
		  setTo($inAddress)
		  setCC($inAddress)
		  setBCC($inAddress)
		  setFrom($inAddress)
		  setSubject($inSubject)
		  setText($inText)
		  setHTML($inHTML)
		  setAttachments($inAttachments)
		  checkEmail($inAddress)
		  loadTemplate($inFileLocation,$inHash,$inFormat)
		  getRandomBoundary($offset)
		  getContentType()
		  formatTextHeader()
		  formatHTMLHeader()
		  formatAttachmentHeader($inFileLocation)
		  send()
		  
		  setPriority($Priority)
		  setDate($gmt)
*******************************************************************************/

class	Email
{
	  //---Global Variables
	var $mailTo				= "";									//	array	of	To	addresses
	var $mailCC				= "";									//	copied recipients
	var $mailBCC			= "";									//	hidden recipients
	 var $mailFrom			= "";									//	from address
	 var $mailSubject		= "";									//	email	subject
	 var $mailText			= "";									//	plain	text message
	 var $mailHTML			= "";									//	html message
	 var $mailAttachments	= "";									//	array	of	attachments
	 var $mailPriority		= "";
	 var $Charset			= "";
	 var $Encoding			= "";									

function setDate($gmt){
	$time = time();
	$time = $time + ($gmt * 60 * 60);
	$flag = "-";
	if($gmt >=0) $flag = "+";
	$gmt = $flag .Num2Str($gmt,2)."00";
	$time = gmdate("D, d M Y G:i:s",$time)." ".$gmt;
	return $time;
}

function setPriority($Priority){
	if ($Priority == "") return false;
	$this->mailPriority	= $Priority;
	return ture;
}

function setEncoding($Encoding){
	if ($Encoding == "") return false;
	$this->Encoding	= $Encoding;
	return ture;
}

function setCharset($Charset){
	if ($Charset == "") return false;
	$this->Charset	= $Charset;
	return ture;
}

/*******************************************************************************
	 Function:			setTo($inAddress)
	 Description:	  sets the email To address
	 Arguments:			 $inAddress	as	string
						  separate multiple values	with comma
	 Returns:		  true if set
*******************************************************************************/
	 function setTo($inAddress){
			//--split addresses at commas
		  $addressArray =	explode(	",",$inAddress);
			//--loop	through each address	and exit	on	error
		  for($i=0;$i<count($addressArray);$i++){
				if($this->checkEmail($addressArray[$i])==false)	return false;
		  }
			//--all values	are OK so implode	array	into string
		  $this->mailTo =	implode($addressArray, ",");
		  return	true;
	 }
/*******************************************************************************
	 Function:			setCC($inAddress)
	 Description:	  sets the email cc address
	 Arguments:			 $inAddress	as	string
						  separate multiple values	with comma
	 Returns:		  true if set
*******************************************************************************/
	 function setCC($inAddress){
			//--split addresses at commas
		  $addressArray =	explode(	",",$inAddress);
			//--loop	through each address	and exit	on	error
		  for($i=0;$i<count($addressArray);$i++){
				if($this->checkEmail($addressArray[$i])==false)	return false;
		  }
			//--all values	are OK so implode	array	into string
		  $this->mailCC =	implode($addressArray, ",");
		  return	true;
	 }
/*******************************************************************************
	 Function:			setBCC($inAddress)
	 Description:	  sets the email bcc	address
	 Arguments:			 $inAddress	as	string
						  separate multiple values	with comma
	 Returns:		  true if set
*******************************************************************************/
	 function setBCC($inAddress){
			//--split addresses at commas
		  $addressArray =	explode(	",",$inAddress);
			//--loop	through each address	and exit	on	error
		  for($i=0;$i<count($addressArray);$i++){
				if($this->checkEmail($addressArray[$i])==false)	return false;
		  }
			//--all values	are OK so implode	array	into string
		  $this->mailBCC = implode($addressArray,	",");
		  return	true;
	 }
/*******************************************************************************
	 Function:			setFrom($inAddress)
	 Description:	  sets the email FROM address
	 Arguments:			 $inAddress	as	string (takes single	email	address)
	 Returns:		  true if set
*******************************************************************************/
	 function setFrom($inAddress){
		  if($this->checkEmail($inAddress)){
				$this->mailFrom =	$inAddress;
				return true;
		  }
		  return	false;
	 }
/*******************************************************************************
	 Function:			setSubject($inSubject)
	 Description:	  sets the email subject
	 Arguments:			 $inSubject	as	string
	 Returns:		  true if set
*******************************************************************************/
	 function setSubject($inSubject){
		  if(strlen(trim($inSubject))	> 0){
				$this->mailSubject =	ereg_replace( "\n", "",$inSubject);
				return true;
		  }
		  return	false;
	 }
/*******************************************************************************
	 Function:			setText($inText)
	 Description:	  sets the email text
	 Arguments:			 $inText	as	string
	 Returns:		  true if set
*******************************************************************************/
	 function setText($inText){
		  if(strlen(trim($inText))	> 0){
				$this->mailText =	$inText;
				return true;
		  }
		  return	false;
	 }
/*******************************************************************************
	 Function:			setHTML($inHTML)
	 Description:	  sets the email HMTL
	 Arguments:			 $inHTML	as	string
	 Returns:		  true if set
*******************************************************************************/
	 function setHTML($inHTML){
		  if(strlen(trim($inHTML))	> 0){
				$this->mailHTML =	$inHTML;
				return true;
		  }
		  return	false;
	 }
/*******************************************************************************
	 Function:			setAttachments($inAttachments)
	 Description:	  stores	the Attachment	string
	 Arguments:			 $inAttachments as string with directory included
						  separate multiple values	with comma
	 Returns:		  true if stored
*******************************************************************************/
	 function setAttachments($inAttachments){
		  if(strlen(trim($inAttachments)) >	0){
				$this->mailAttachments = $inAttachments;
				return true;
		  }
		  return	false;
	 }
/*******************************************************************************
	 Function:			checkEmail($inAddress)
	 Description:	  checks	for valid email
	 Arguments:			 $inAddress	as	string
	 Returns:		  true if valid
*******************************************************************************/
	 function checkEmail($inAddress){
		  return	(ereg(  "^[^@ ]+@([a-zA-Z0-9\-]+\.)+([a-zA-Z0-9\-]{2}|net|com|gov|mil|org|edu|int)\$",$inAddress));
	 }
/*******************************************************************************
	 Function:			loadTemplate($inFileLocation,$inHash,$inFormat)
	 Description:	  reads in a template file	and replaces hash	values
	 Arguments:			 $inFileLocation as string	with relative directory
						  $inHash as Hash	with populated	values
						  $inFormat	as	string either "text"	or	"html"
	 Returns:		  true if loaded
*******************************************************************************/
	 function loadTemplate($inFileLocation,$inHash,$inFormat){
			/*
		  template files have lines such	as:
				Dear ~!UserName~,
				Your address is ~!UserAddress~
		  */
			//--specify	template	delimeters
		  $templateDelim =  "~";
		  $templateNameStart	=	"!";
			//--set out	string
		  $templateLineOut =	 "";
			//--open	template	file
		  if($templateFile =	fopen($inFileLocation, "r")){
				 //--loop through	file,	line by line
				while(!feof($templateFile)){
					  //--get 1000	chars	or	(line	break	internal	to	fgets)
					 $templateLine	= fgets($templateFile,1000);
					  //--split	line into array of hashNames and	regular sentences
					 $templateLineArray = explode($templateDelim,$templateLine);
					  //--loop through array
					 for(	$i=0;	$i<count($templateLineArray);$i++){
							//--look	for $templateNameStart at position 0
						  if(strcspn($templateLineArray[$i],$templateNameStart)==0){
								 //--get	hashName	after	$templateNameStart
								$hashName =	substr($templateLineArray[$i],1);
								 //--replace hashName with	acual	value	in	$inHash
								 //--(string) casts all	values as "strings"
								$templateLineArray[$i] = ereg_replace($hashName,(string)$inHash[$hashName],$hashName);
						  }
					 }
					  //--output array as string and	add to out string
					 $templateLineOut	.=	implode($templateLineArray, "");
				}
				 //--close file
				fclose($templateFile);
				 //--set	Mail body to proper format
				if( strtoupper($inFormat)== "TEXT" ) return($this->setText($templateLineOut));
				else if(	strtoupper($inFormat)==	"HTML" )	return($this->setHTML($templateLineOut));
		  }
		  return	false;
	 }
/*******************************************************************************
	 Function:			getRandomBoundary($offset)
	 Description:	  returns a	random boundary
	 Arguments:			 $offset	as	integer - used	for multiple calls
	 Returns:		  string
*******************************************************************************/
	 function getRandomBoundary($offset	= 0){
			//--seed	random number generator
		  srand(time()+$offset);
			//--return md5	32	bits plus 4	dashes to make	38	chars
		  return	( "----".(md5(rand())));
	 }
/*******************************************************************************
	 Function:			getContentType($inFileName)
	 Description:	  returns content	type for	the file	type
	 Arguments:			 $inFileName as file	name string	(can include path)
	 Returns:		  string
*******************************************************************************/
	 function getContentType($inFileName){
			//--strip path
		  $inFileName = basename($inFileName);
			//--check for no extension
		  if(strrchr($inFileName, ".") == false){
				return  "application/octet-stream";
		  }
			//--get extension	and check cases
		  $extension =	strrchr($inFileName,	".");
		  switch($extension){
				case	".gif":		return "image/gif";
				case	".gz":		return "application/x-gzip";
				case	".htm":		return "text/html";
				case	".html":	return "text/html";
				case	".jpg":		return "image/jpeg";
				case	".tar":		return "application/x-tar";
				case	".txt":		return "text/plain";
				case	".zip":		return "application/zip";
				default:			return "application/octet-stream";
		  }
		  return	 "application/octet-stream";
	 }
/*******************************************************************************
	 Function:			formatTextHeader
	 Description:	  returns a	formated	header for text
	 Arguments:			 none
	 Returns:		  string
*******************************************************************************/
	 function formatTextHeader(){
	 	$Encoding = $this->Encoding;
	 	$Charset = $this->Charset;

		$outTextHeader =  "";
		$outTextHeader .=	"Content-Type: text/plain; charset=$Charset\n";
		$buff = $this->mailText;

		switch ($Encoding){
			case "base64":
				$buff = chunk_split(base64_encode($buff),76,"\n");
//				$buff = preg_replace("/\r\n/","\n",$buff);
				$outTextHeader .=	"Content-Transfer-Encoding: $Encoding\n";
				break;
			case "quoted-printable":
				$buff = imap_8bit($this->mailText);
				$outTextHeader .=	"Content-Transfer-Encoding: $Encoding\n";
				break;
		}
		$outTextHeader .= "\n";		  
		$outTextHeader .= $buff. "\n";
		return	$outTextHeader;
	 }
/*******************************************************************************
	 Function:			formatHTMLHeader
	 Description:	  returns a	formated	header for HTML
	 Arguments:			 none
	 Returns:		  string
*******************************************************************************/
	 function formatHTMLHeader(){
	 	$Encoding = $this->Encoding;
	 	$Charset = $this->Charset;
		$outHTMLHeader =  "";
		$outHTMLHeader .= "Content-Type: text/html; charset=$Charset\n";
		$buff = $this->mailHTML;
		switch ($Encoding){
			case "base64":
				$buff = chunk_split(base64_encode($buff),76,"\n");
//				$buff = preg_replace("/\r\n/","\n",$buff);
				$outHTMLHeader .=	"Content-Transfer-Encoding: $Encoding\n";
				break;
			case "quoted-printable":
				$buff = imap_8bit($this->mailHTML);
				$outHTMLHeader .=	"Content-Transfer-Encoding: $Encoding\n";
				break;
		}
		$outHTMLHeader .= "\n";
		$outHTMLHeader .= $buff. "\n";
		return $outHTMLHeader;
	 }
/*******************************************************************************
	 Function:			formatAttachmentHeader($inFileLocation)
	 Description:	  returns a	formated	header for an attachment
	 Arguments:			 $inFileLocation as string	with relative directory
	 Returns:		  string
*******************************************************************************/
	 function formatAttachmentHeader($inFileLocation){

			$outAttachmentHeader	=	"";
			//--get content type	based	on	file extension
			$contentType =	$this->getContentType($inFileLocation);
			//--format header
			$outAttachmentHeader	.=	 "Content-Type: ".$contentType. "; ";
			$outAttachmentHeader	.=	 'name="'.basename($inFileLocation). '"'.	"\n";
			$outAttachmentHeader	.=	 "Content-Transfer-Encoding: base64\n";
			$outAttachmentHeader	.=	 "Content-Disposition: attachment; ";		//--other: inline
			$outAttachmentHeader	.=	 'filename="'.basename($inFileLocation). '"'. "\n\n";

			$Buff	= "";
			($FD_File =	fopen($inFileLocation, "r"))||die("Error open $inFileLocation");

			while(!feof($FD_File)){
				$Buff	.=	fread($FD_File,2048);
			}
			fclose($FD_File);

			$DEBUG = 0;
			if($DEBUG) $timebegin = gettimeofday();
						
			$Buff = chunk_split(base64_encode($Buff),76,"\n");
//			$Buff = preg_replace("/\r\n/","\n",$Buff);
			$outAttachmentHeader	.=	$Buff."\n\n";

			if ($DEBUG){
				$timeend = gettimeofday();
				$time = $timeend['sec'] - $timebegin['sec'];
				$time3 = $time + ($timeend['usec']-$timebegin['usec'])/1000000;
//				echo $time3;
			}

			return $outAttachmentHeader;
	 }
/*******************************************************************************
	 Function:			send()
	 Description:	  sends the	email
	 Arguments:			 none
	 Returns:		  true if sent
*******************************************************************************/
	 function send(){
	 	  global $CFG_GMT,$REMOTE_ADDR;
	 	  
		  $mailHeader =  "";
		  if($this->mailCC != "")		$mailHeader	.= "CC:	".$this->mailCC. "\n";
		  if($this->mailBCC != "")		$mailHeader .= "BCC: ".$this->mailBCC. "\n";
		  if($this->mailFrom != "")		$mailHeader .= "From: ".$this->mailFrom. "\n";
		  $mailHeader .= "Date: " . $this->setDate($CFG_GMT) ."\n";
		  if($this->mailPriority != "")	$mailHeader .= "X-Priority: ".$this->mailPriority. "\n";
		  $mailHeader .= "X-Mailer: cn-bbs.org Mailer power by everlove\n";
		  $mailHeader .= "X-Originating-IP: [$REMOTE_ADDR]\n";

			//---------------------------MESSAGE TYPE-------------------------------
			//--TEXT	ONLY
		  if($this->mailText	!=	 "" && $this->mailHTML ==	""	&&	$this->mailAttachments ==	""){
				return mail($this->mailTo,$this->mailSubject,$this->mailText,$mailHeader);
		  }
			//--HTML	ONLY
		  else if($this->mailHTML !=	""	&&	$this->mailAttachments ==	""){
				 //--get	random boundary for content types
				$bodyBoundary = $this->getRandomBoundary();
				 //--format	headers
				$textHeader	= $this->formatTextHeader();
				$htmlHeader	= $this->formatHTMLHeader();
				 //--set	MIME-Version
				$mailHeader	.=	 "MIME-Version: 1.0\n";
				 //--set	up	main content header with boundary
				$mailHeader	.=	 "Content-Type: multipart/alternative;\n";
				$mailHeader	.=	 "\tboundary=\"$bodyBoundary\"";
				$mailHeader	.=	 "\n\n";
				 //--add	body and	boundaries
				$mailHeader	.=	 "--".$bodyBoundary.	"\n";
				$mailHeader	.=	$textHeader;
				$mailHeader	.=	 "\n--".$bodyBoundary.	"\n";
				 //--add	html and	ending boundary
				$mailHeader	.=	$htmlHeader;
				$mailHeader	.=	 "\n--".$bodyBoundary. "--";
				 //--send message
				return mail($this->mailTo,$this->mailSubject, "",$mailHeader);
		  }
			//--HTML	AND TEXT	AND ATTACHMENTS
		  else if($this->mailText !=	""	&&	$this->mailHTML !=  "" && $this->mailAttachments !=  ""){

				 //--get	random boundary for attachments
				$attachmentBoundary = $this->getRandomBoundary();
				 //--set	main header	for all parts and	boundary
				$mailHeader	.=	 "Content-Type: multipart/mixed;\n";
				$mailHeader	.=	 '	boundary="'.$attachmentBoundary.	'"'. "\n\n";
				$mailHeader	.=	 "This is a multi-part message in MIME format.\n\n";
				$mailHeader	.=	 "--".$attachmentBoundary.	"\n";

				 //--TEXT AND HTML--
				 //--get	random boundary for content types
				$bodyBoundary = $this->getRandomBoundary(1);
				 //--format	headers
				$textHeader	= $this->formatTextHeader();
				$htmlHeader	= $this->formatHTMLHeader();
				 //--set	MIME-Version
				$mailHeader	.=	 "MIME-Version: 1.0\n";
				 //--set	up	main content header with boundary
				$mailHeader	.=	 "Content-Type: multipart/alternative;\n";
				$mailHeader	.=	 "\tboundary=\"$bodyBoundary\"\n\n";

				 //--add	body and	boundaries
				$mailHeader	.=	 "--".$bodyBoundary.	"\n";
				$mailHeader	.=	$textHeader."\n";
				$mailHeader	.=	 "--".$bodyBoundary.	"\n";
				 //--add	html and	ending boundary
				$mailHeader	.=	$htmlHeader;
				$mailHeader	.=	 "\n--".$bodyBoundary. "--\n";
				 //--send message
				 //--END	TEXT AND	HTML

				 //--get	array	of	attachment filenames
				$attachmentArray = explode( ",",$this->mailAttachments);
				 //--loop through	each attachment
				for($i=0;$i<count($attachmentArray);$i++){
					  //--attachment separator
					 $mailHeader .=  "\n--".$attachmentBoundary.	"\n";
					  //--get attachment	info
					 $mailHeader .= $this->formatAttachmentHeader($attachmentArray[$i]);
				}
				$mailHeader	.=	 "--".$attachmentBoundary.	"--";
//				echo $mailHeader;
				return mail($this->mailTo,$this->mailSubject, "",$mailHeader);
		  }
	 }
}
?>