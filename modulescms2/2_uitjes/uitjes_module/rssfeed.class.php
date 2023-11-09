<?
/*
	Dennis Renirie RSS feed creator
	
	
	Used the RSS specification as displayed on http://www.rssboard.org/rss-specification
	
	
	version history
	===============

	1.0
	===============
	-opzet

 *
 *      1.1
 *      ================
 *      -striptags toegevoegd
 *
 *      1.2
 *      ======
 *      -img tag toegevoegd aan uitzondering striptags
	
	
	
	----- HOW TO USE -----
	To explain to you how to use this class, I provide you with an example
	
	$objFeed = new RSSFeed(); //create the class
	$objFeed->setTitle("X POS plus download feed");
	$objFeed->setDescription("feed van de download afdeling van xposplus.nl");
	$objFeed->setLink($www_url);
	
	//create a news item
	$objItem = new RSSItem();
	$objItem->setTitle("X POS plus download feed1");
	$objItem->setDescription("feed van de download afdeling van xposplus.nl1");
	$objItem->setLink("link1");	
	$objFeed->addItem($objItem); //add the item to the feed class

	//create another news item
	$objItem = new RSSItem();
	$objItem->setTitle("X POS plus download feed2");
	$objItem->setDescription("feed van de download afdeling van xposplus.nl2");
	$objItem->setLink("link2");	
	$objFeed->addItem($objItem);

	//choose how to use this feed : show, generate or save it to file
	$objFeed->show("RSS");	
	
*/






class RSSFeed
{
	var $sGenerator = "Dennis Renirie RSSFeed class 1.1";
	var $sTitle;
	var	$sLink;
	var $sDescription;
	var $arrImage; //associative array where the elements of the image tag is stored in
	var $arrItems; //array van item objecten
	var $sCopyright;
	
	/*========================================================================================================================\
	* name				: RSSFeed
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: 
	* output			: 
	* use for			: constructor
	* description		: 
	\========================================================================================================================*/   
	function RSSFeed()
	{
		$arrItems = null;
		$arrImage = null;
		$this->sTitle = "[no title]";
		$this->sLink = "[no link]";		
		$this->sDescription = "[no description]";				
	}
	
	//=========== RSS DATA ITEMS
	function setTitle($sFeedTitle)
	{
		$this->sTitle = strip_tags($sFeedTitle);
	}	

	function setLink($sFeedLink)
	{
		$this->sLink = $sFeedLink;
	}	
	
	function setDescription($sFeedDescription)
	{
		$this->sDescription = strip_tags($sFeedDescription);
	}	
	
	function setImage($sUrl, $sTitle = "", $sLink = "", $iWidth = 88, $iHeight = 31)
	{
		$this->arrImage["url"] = strip_tags($sUrl); //<url> is the URL of a GIF, JPEG or PNG image that represents the channel.
		$this->arrImage["title"] = strip_tags($sTitle); //<title> describes the image, it's used in the ALT attribute of the HTML <img> tag when the channel is rendered in HTML.
		$this->arrImage["link"] = strip_tags($sLink); //<link> is the URL of the site, when the channel is rendered, the image is a link to the site. (Note, in practice the image <title> and <link> should have the same value as the channel's <title> and <link>.
		$this->arrImage["width"] = strip_tags($iWidth); //Maximum value for width is 144, default value is 88.
		$this->arrImage["height"] = strip_tags($iHeight); //Maximum value for height is 400, default value is 31.
	}			
	
	function setCopyright($sCopy)
	{
		$this->sCopyright = strip_tags($sCopy);
	}
	//END=========== RSS DATA ITEMS	


	/*========================================================================================================================\
	* name				: convertHTMLToValidRSSText
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $sHTML : string - text in HTML format
	* output			: string with text that can be used in the xml feed
	* use for			: converting html to plain text
	* description		: 
	\========================================================================================================================*/   
	function addItem($objRSSItem)
	{
		$this->arrItems[] = $objRSSItem;		
	}	
	
	/*========================================================================================================================\
	* name				: convertHTMLToValidRSSText
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $sHTML : string - text in HTML format
	* output			: string with text that can be used in the xml feed
	* use for			: converting html to plain text
	* description		: 
	\========================================================================================================================*/   
	function convertHTMLToValidRSSText($sHTML)
	{
		//we gebruiken gewoon de functie van item
		$objItem = new RSSItem();
		return $objItem->convertHTMLToValidRSSText($sHTML);
	}
	
	/*========================================================================================================================\
	* name				: boolToStr
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $bBooleanValue : boolean
	* output			: string with boolean value in string representation
	* use for			: converting boolean to textual representation
	* description		: 
	\========================================================================================================================*/   
	function boolToStr($bBooleanValue = true)
	{
		$sResult = "true";
		
		if (is_bool($bBooleanValue))
		{
			if ($bBooleanValue)
				$sResult = "true";
			else
				$sResult = "false";			
		}
		else //als geen boolean
		{
			if (($bBooleanValue = null) || ($bBooleanValue = "")) 
				$sResult = "false";
			else
				$sResult = "true";						
		}
		
		return $sResult;
	}	
	
	/*========================================================================================================================\
	* name				: saveAsFile
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $sFilePath : string - path of filename to save
						  $sRSSType : type of RSS feed
	* output			: 
	* use for			: het opslaan van het resultaat (XML) in  een bestand
	* description		: 
	\========================================================================================================================*/   
	function saveToFile($sFilePath, $sRSSType)
	{

		if (!is_writable)
		{
			echo "ERROR : Cannot write XML file '$sFilePath'";
		}
		else
		{
			$sXML = $this->generate($sRSSType);
		
	   		$objHandle = fopen($sFilePath, "wb");
			fwrite($objHandle, $sXML);
			fclose($objHandle);
		}
  
	}

	/*========================================================================================================================\
	* name				: show
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $sRSSType : type of RSS feed
	* output			: 
	* use for			: het weergeven van het xml bestand
	* description		: 
	\========================================================================================================================*/   
	function show($sRSSType)
	{
		echo $this->generate($sRSSType);
	}
	
	/*========================================================================================================================\
	* name				: generate
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $sRSSType : type of RSS feed (possible input "RSS", "RSS2.0")
	* output			: string with the xml contents of the feed
	* use for			: het werkelijk genereren van de XML feed
	* description		: 
	\========================================================================================================================*/   
	function generate($sRSSType)
	{
		$sRSSType = strtoupper($sRSSType); // make it case insensitive
		
		switch ($sRSSType)
		{
			case "RSS" : //fallthrough
			case "RSS2" : //fallthrough
			case "RSS2.0" :
				$sResult = $this->generateRSS20();
				break;
			default : 
				$sResult = $this->generateRSS20();
		}	
		
		return $sResult;		
	}
	
	/*========================================================================================================================\
	* name				: generateRSS20
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $sRSSType : type of RSS feed (possible input "RSS", "RSS2.0"
	* output			: string with the xml contents of the feed
	* use for			: het werkelijk genereren van de XML feed
	* description		: 
	\========================================================================================================================*/   
	function generateRSS20()
	{
		$sResult = "";
		
		//begin
		$sResult.= "<?xml version=\"1.0\"?>\n";
		$sResult.= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'."\n";
		$sResult.= "\t<channel>\n";
		$sResult.= "\t\t<title>".$this->convertHTMLToValidRSSText($this->sTitle)."</title>\n";
		$sResult.= "\t\t<link>".$this->convertHTMLToValidRSSText($this->sLink)."</link>\n";
		$sResult.= "\t\t<description>".$this->convertHTMLToValidRSSText($this->sDescription)."</description>\n";
		$sResult.= "\t\t<generator>".$this->convertHTMLToValidRSSText($this->sGenerator)."</generator>\n";
		$sResult.= "\t\t<copyright>".$this->convertHTMLToValidRSSText($this->sCopyright)."</copyright>\n";
		$sResult.= "\t\t<docs>http://blogs.law.harvard.edu/tech/rss</docs>\n";
		$sResult.= "\t\t<lastBuildDate>".date("r")."</lastBuildDate>\n";//Sat, 07 Sep 2002 09:42:31 GMT
		if (is_array($this->arrImage)) //alleen image weergeven als deze bestaat
		{
			$sResult.= "\t\t<image>\n";

			if ($this->arrImage["url"] != "")
				$sResult.= "\t\t\t<url>".$this->arrImage["url"]."</url>\n";
			if ($this->arrImage["link"] != "")
				$sResult.= "\t\t\t<link>".$this->arrImage["link"]."</link>\n";
			else
				$sResult.= "\t\t\t<link>".$this->convertHTMLToValidRSSText($this->sLink)."</link>\n";			
			if ($this->convertHTMLToValidRSSText($this->arrImage["title"]) != "")
				$sResult.= "\t\t\t<title>".$this->convertHTMLToValidRSSText($this->arrImage["title"])."</title>\n";
			else
				$sResult.= "\t\t\t<title>".$this->convertHTMLToValidRSSText($this->sTitle)."</title>\n";
			if ($this->arrImage["width"] != "")
				$sResult.= "\t\t\t<width>".$this->convertHTMLToValidRSSText($this->arrImage["width"])."</width>\n";
			if ($this->arrImage["height"] != "")
				$sResult.= "\t\t\t<height>".$this->convertHTMLToValidRSSText($this->arrImage["height"])."</height>\n";

			$sResult.= "\t\t</image>\n";
		}


		//items nalopen
		for ($iTeller = 0; $iTeller < count($this->arrItems); $iTeller++)
		{
			$sResult.= "\t\t<item>\n";
			$sResult.= "\t\t\t<title>".$this->convertHTMLToValidRSSText($this->arrItems[$iTeller]->getTitle())."</title>\n";
			$sResult.= "\t\t\t<description>".$this->convertHTMLToValidRSSText($this->arrItems[$iTeller]->getDescription())."</description>\n";
			$sResult.= "\t\t\t<pubDate>".date("r", $this->arrItems[$iTeller]->getPubDate())."</pubDate>\n";	
			if (count($this->arrItems[$iTeller]->getCategory()) > 0) //alleen weergeven als deze bestaat
				$sResult.= "\t\t\t<category>".$this->convertHTMLToValidRSSText($this->arrItems[$iTeller]->getCategory())."</category>\n";	
			$sResult.= "\t\t\t<link>".$this->convertHTMLToValidRSSText($this->arrItems[$iTeller]->getLink())."</link>\n";
			if (count($this->arrItems[$iTeller]->getAuthor()) > 0)  //alleen weergeven als deze bestaat
				$sResult.= "\t\t\t<author>".$this->convertHTMLToValidRSSText($this->arrItems[$iTeller]->getAuthor())."</author>\n";	
			//--guid
			$arrGuid = $this->arrItems[$iTeller]->getGuid();
			if (is_array($arrGuid))  //alleen weergeven als deze bestaat
			{
				$sResult.= "\t\t\t<guid isPermaLink=\"".$this->boolToStr($arrGuid["isPermaLink"])."\">".$arrGuid["guid"]."</guid>\n";	
			}
			//END--guid

			$sResult.= "\t\t</item>\n";			
		}
		
		//eind
		$sResult.= "\t</channel>\n";
		$sResult.= "</rss>";   
		
		return $sResult;
	}

}

class RSSItem
{
	var $sTitle;
	var $sLink;
	var $sDescription;
	var $iPubDate;//date in unix timestamp format
	var $sCategory;
	var $sComments;
	var $sAuthor;
	var $arrGuid;	
	
	/*========================================================================================================================\
	* name				: RSSItem
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: 
	* output			: 
	* use for			: constructor
	* description		: 
	\========================================================================================================================*/   
	function RSSItem()
	{
		$this->sTitle = "[no title]";
		$this->sLink = "[no link]";		
		$this->sDescription = "[no description]";			
		$this->iPubDate = time();
		$this->arrGuid = null;
	}
	
	//=========== RSS DATA ITEMS
	function setTitle($sTitle)
	{
		$this->sTitle = strip_tags($sTitle);
	}

	function getTitle()
	{
		return $this->sTitle;
	}	
	
	function setLink($sLink)
	{
		$this->sLink = strip_tags($sLink);
	}
	
	function getLink()
	{
		return $this->sLink;
	}

	function setDescription($sDescription)
	{
		$this->sDescription = strip_tags($sDescription, '<br><BR><a><img>');
	}
	
	function getDescription()
	{
		return $this->sDescription;
	}
	
	function setPubDate($iPubDateUnixTimeStamp) //unix timestamp
	{
		$this->iPubDate = strip_tags($iPubDateUnixTimeStamp);
	}
	
	function getPubDate()//unix timestamp
	{
		return $this->iPubDate;
	}
		
	function setCategory($sCategory)
	{
		$this->sCategory = strip_tags($sCategory);
	}
	
	function getCategory()
	{
		return $this->sCategory;
	}
	
	function setComments($sComments) //url where the reader can find comments on the newsitem
	{
		$this->sComments = strip_tags($sComments);
	}		
	
	function getComments()
	{
		return $this->sComments;
	}
	
	function setAuthor($sAuthor) //emailadres
	{
		$this->sAuthor = strip_tags($sAuthor);
	}				
	
	function getAuthor()
	{
		return $this->sAuthor;
	}
	
	function setGuid($sGuid, $bIsPermanentLink = true) 
	{
		$this->arrGuid["guid"] = strip_tags($sGuid); //guid stands for globally unique identifier. It's a string that uniquely identifies the item. When present, an aggregator may choose to use this string to determine if an item is new.
		$this->arrGuid["isPermaLink"] = strip_tags($bIsPermanentLink); //isPermaLink is optional, its default value is true. If its value is false, the guid may not be assumed to be a url, or a url to anything in particular.
	}		
	
	function getGuid()//returns array
	{
		return $this->arrGuid;
	}
	//END=========== RSS DATA ITEMS


	/*========================================================================================================================\
	* name				: convertHTMLToValidRSSText
	* programmer name 	: Dennis Renirie
	* date 				: 21 nov 2006
	* input				: $sHTML : string - text in HTML format
	* output			: string with text that can be used in the xml feed
	* use for			: converting html to plain text
	* description		: 
	\========================================================================================================================*/   
	function convertHTMLToValidRSSText($sHTML)
	{
		$sResult = "";
		
		//allow only the given characters
		$sEscQuote = "\"";
		$sEscTab = "\t";
		$sEscCR = "\r";
		$sEscNewLine = "\n";
		$sEscSlash = "\\";
		
		$sResult = strip_tags($sHTML);
		$sResult = str_replace(chr(160), " ", $sHTML); //om onbekende gooit de inline html editor van Opera er af en toe het ascii teken 160 tussendoor

		//replace special html characters		
		$sResult =  htmlspecialchars($sResult);
		
		return $sResult;
	}

}


?>