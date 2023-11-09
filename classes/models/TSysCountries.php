<?php

namespace dr\classes\models;

use dr\classes\models\TModel;


/**
 * countries and country codes according to ISO 3166 (https://www.iban.com/country-codes)
 * 
 * The countryname is in English by default (just not to overcomplicate things)
 * 
 * THIS CLASS IS USED THROUGHOUT THE WHOLE FRAMEWORK!
 * 
 * created 1 maart 2022
 * 2 mrt 2022: TSysCountries: isDefault field added
 * 17 mrt 2022: TSysCountries: iseuropeanunion field added
 * 19 okt 2023: TSysCountries: loadFromDBByISO2() added
 */

class TSysCountries extends TModel
{
	const FIELD_COUNTRYNAME 	= 'sCountryName'; //countryname in english, i.e. The Netherlands
	const FIELD_ISO2 			= 'sISO2'; //alpha iso 2 digit code, i.e. NL
	const FIELD_ISO3 			= 'sISO3'; //alpha iso 3 code, i.e. NLD
	const FIELD_ISSYSTEMDEFAULT = 'bIsSystemDefault';	//boolean: is this the default country?
	const FIELD_ISEUROPEANUNION = 'bIsEuropeanUnion';	//boolean: is this country in european union?
		
	
	/**
	 * get name of the country
	 * 
	 * @return string
	 */
	public function getCountryName()
	{
		return $this->get(TSysCountries::FIELD_COUNTRYNAME);
	}

	
	/**
	 * set name of the country
	 * 
	 * @param string $sCountry
	 */
	public function setCountryName($sCountry)
	{
		$this->set(TSysCountries::FIELD_COUNTRYNAME, $sCountry);
	}        
	
	/**
	 * get alpha 2 code
	 * 
	 * @return string
	 */
	public function getISO2()
	{
		return $this->get(TSysCountries::FIELD_ISO2);
	}

	/**
	 * set alpha 2 code
	 * 
	 * @param string $sURL
	 */
	public function setISO2($sCode)
	{
		$this->set(TSysCountries::FIELD_ISO2, $sCode);
	}           

	
	/**
	 * get alpha 3 code
	 * 
	 * @return string
	 */
	public function getISO3()
	{
		return $this->get(TSysCountries::FIELD_ISO3);
	}

	/**
	 * set alpha 3 code
	 * 
	 * @param string $sURL
	 */
	public function setISO3($sCode)
	{
		$this->set(TSysCountries::FIELD_ISO3, $sCode);
	}   


	public function getIsDefault()
	{
		return  $this->get(TSysCountries::FIELD_ISSYSTEMDEFAULT);
	}
	
	public function setIsDefault($bDefault)
	{
		$this->set(TSysCountries::FIELD_ISSYSTEMDEFAULT, $bDefault);
	} 	


	/**
	 * 
	 * @return boolean load ok?
	 */
	public function loadFromDBByIsDefault()
	{
		$this->clear();
		$this->find(TSysCountries::FIELD_ISSYSTEMDEFAULT, true);
		return $this->loadFromDB();
	}	


	/**
	 * 
	 * @param string $sISO2CountryCode
	 * @return boolean load ok?
	 */
	public function loadFromDBByISO2($sISO2CountryCode)
	{
		$this->clear();
		$this->find(TSysCountries::FIELD_ISO2, $sISO2CountryCode);
		return $this->loadFromDB();
	}		

	/**
	 * this function creates table in database and calls all foreign key classes to do the same
	 *
	 * the $arrPreviousDependenciesModelClasses prevents a endless loop by storing all the classnames that are already installed
	 *
	 * @param array $arrPreviousDependenciesModelClasses with classnames.
	 * @return bool success?
	 */
	public function install($arrPreviousDependenciesModelClasses = null)
	{
		$bSuccess = parent::install($arrPreviousDependenciesModelClasses);
		
		$sCSV = "Afghanistan,AF,AFG,0
		Albania,AL,ALB,0
		Algeria,DZ,DZA,0
		American Samoa,AS,ASM,0
		Andorra,AD,AND,0
		Angola,AO,AGO,0
		Anguilla,AI,AIA,0
		Antarctica,AQ,ATA,0
		Antigua and Barbuda,AG,ATG,0
		Argentina,AR,ARG,0
		Armenia,AM,ARM,0
		Aruba,AW,ABW,0
		Australia,AU,AUS,0
		Austria,AT,AUT,1
		Azerbaijan,AZ,AZE,0
		Bahamas (the),BS,BHS,0
		Bahrain,BH,BHR,0
		Bangladesh,BD,BGD,0
		Barbados,BB,BRB,0
		Belarus,BY,BLR,0
		Belgium,BE,BEL,1
		Belize,BZ,BLZ,0
		Benin,BJ,BEN,0
		Bermuda,BM,BMU,0
		Bhutan,BT,BTN,0
		Bolivia (Plurinational State of),BO,BOL,0
		Bonaire and Sint Eustatius and Saba,BQ,BES,0
		Bosnia and Herzegovina,BA,BIH,0
		Botswana,BW,BWA,0
		Bouvet Island,BV,BVT,0
		Brazil,BR,BRA,0
		British Indian Ocean Territory (the),IO,IOT,0
		Brunei Darussalam,BN,BRN,0
		Bulgaria,BG,BGR,1
		Burkina Faso,BF,BFA,0
		Burundi,BI,BDI,0
		Cabo Verde,CV,CPV,0
		Cambodia,KH,KHM,0
		Cameroon,CM,CMR,0
		Canada,CA,CAN,0
		Cayman Islands (the),KY,CYM,0
		Central African Republic (the),CF,CAF,0
		Chad,TD,TCD,0
		Chile,CL,CHL,0
		China,CN,CHN,0
		Christmas Island,CX,CXR,0
		Cocos (Keeling) Islands (the),CC,CCK,0
		Colombia,CO,COL,0
		Comoros (the),KM,COM,0
		Congo (the Democratic Republic of the),CD,COD,0
		Congo (the),CG,COG,0
		Cook Islands (the),CK,COK,0
		Costa Rica,CR,CRI,0
		Croatia,HR,HRV,1
		Cuba,CU,CUB,0
		Curaçao,CW,CUW,0
		Cyprus,CY,CYP,1
		Czechia,CZ,CZE,1
		Côte d'Ivoire,CI,CIV,0
		Denmark,DK,DNK,1
		Djibouti,DJ,DJI,0
		Dominica,DM,DMA,0
		Dominican Republic (the),DO,DOM,0
		Ecuador,EC,ECU,0
		Egypt,EG,EGY,0
		El Salvador,SV,SLV,0
		Equatorial Guinea,GQ,GNQ,0
		Eritrea,ER,ERI,0
		Estonia,EE,EST,1
		Eswatini,SZ,SWZ,0
		Ethiopia,ET,ETH,0
		Falkland Islands (the) [Malvinas],FK,FLK,0
		Faroe Islands (the),FO,FRO,0
		Fiji,FJ,FJI,0
		Finland,FI,FIN,1
		France,FR,FRA,1
		French Guiana,GF,GUF,0
		French Polynesia,PF,PYF,0
		French Southern Territories (the),TF,ATF,0
		Gabon,GA,GAB,0
		Gambia (the),GM,GMB,0
		Georgia,GE,GEO,0
		Germany,DE,DEU,1
		Ghana,GH,GHA,0
		Gibraltar,GI,GIB,0
		Greece,GR,GRC,1
		Greenland,GL,GRL,0
		Grenada,GD,GRD,0
		Guadeloupe,GP,GLP,0
		Guam,GU,GUM,0
		Guatemala,GT,GTM,0
		Guernsey,GG,GGY,0
		Guinea,GN,GIN,0
		Guinea-Bissau,GW,GNB,0
		Guyana,GY,GUY,0
		Haiti,HT,HTI,0
		Heard Island and McDonald Islands,HM,HMD,0
		Holy See (the),VA,VAT,0
		Honduras,HN,HND,0
		Hong Kong,HK,HKG,0
		Hungary,HU,HUN,1
		Iceland,IS,ISL,0
		India,IN,IND,0
		Indonesia,ID,IDN,0
		Iran (Islamic Republic of),IR,IRN,0
		Iraq,IQ,IRQ,0
		Ireland,IE,IRL,1
		Isle of Man,IM,IMN,0
		Israel,IL,ISR,0
		Italy,IT,ITA,1
		Jamaica,JM,JAM,0
		Japan,JP,JPN,0
		Jersey,JE,JEY,0
		Jordan,JO,JOR,0
		Kazakhstan,KZ,KAZ,0
		Kenya,KE,KEN,0
		Kiribati,KI,KIR,0
		Korea (the Democratic People's Republic of),KP,PRK,0
		Korea (the Republic of),KR,KOR,0
		Kuwait,KW,KWT,0
		Kyrgyzstan,KG,KGZ,0
		Lao People's Democratic Republic (the),LA,LAO,0
		Latvia,LV,LVA,1
		Lebanon,LB,LBN,0
		Lesotho,LS,LSO,0
		Liberia,LR,LBR,0
		Libya,LY,LBY,0
		Liechtenstein,LI,LIE,0
		Lithuania,LT,LTU,1
		Luxembourg,LU,LUX,1
		Macao,MO,MAC,0
		Madagascar,MG,MDG,0
		Malawi,MW,MWI,0
		Malaysia,MY,MYS,0
		Maldives,MV,MDV,0
		Mali,ML,MLI,0
		Malta,MT,MLT,1
		Marshall Islands (the),MH,MHL,0
		Martinique,MQ,MTQ,0
		Mauritania,MR,MRT,0
		Mauritius,MU,MUS,0
		Mayotte,YT,MYT,0
		Mexico,MX,MEX,0
		Micronesia (Federated States of),FM,FSM,0
		Moldova (the Republic of),MD,MDA,0
		Monaco,MC,MCO,0
		Mongolia,MN,MNG,0
		Montenegro,ME,MNE,0
		Montserrat,MS,MSR,0
		Morocco,MA,MAR,0
		Mozambique,MZ,MOZ,0
		Myanmar,MM,MMR,0
		Namibia,NA,NAM,0
		Nauru,NR,NRU,0
		Nepal,NP,NPL,0
		Netherlands (the),NL,NLD,1
		New Caledonia,NC,NCL,0
		New Zealand,NZ,NZL,0
		Nicaragua,NI,NIC,0
		Niger (the),NE,NER,0
		Nigeria,NG,NGA,0
		Niue,NU,NIU,0
		Norfolk Island,NF,NFK,0
		Northern Mariana Islands (the),MP,MNP,0
		Norway,NO,NOR,0
		Oman,OM,OMN,0
		Pakistan,PK,PAK,0
		Palau,PW,PLW,0
		Palestine (State of),PS,PSE,0
		Panama,PA,PAN,0
		Papua New Guinea,PG,PNG,0
		Paraguay,PY,PRY,0
		Peru,PE,PER,0
		Philippines (the),PH,PHL,0
		Pitcairn,PN,PCN,0
		Poland,PL,POL,1
		Portugal,PT,PRT,1
		Puerto Rico,PR,PRI,0
		Qatar,QA,QAT,0
		Republic of North Macedonia,MK,MKD,0
		Romania,RO,ROU,1
		Russian Federation (the),RU,RUS,0
		Rwanda,RW,RWA,0
		Réunion,RE,REU,0
		Saint Barthélemy,BL,BLM,0
		Saint Helena and Ascension and Tristan da Cunha,SH,SHN,0
		Saint Kitts and Nevis,KN,KNA,0
		Saint Lucia,LC,LCA,0
		Saint Martin (French part),MF,MAF,0
		Saint Pierre and Miquelon,PM,SPM,0
		Saint Vincent and the Grenadines,VC,VCT,0
		Samoa,WS,WSM,0
		San Marino,SM,SMR,0
		Sao Tome and Principe,ST,STP,0
		Saudi Arabia,SA,SAU,0
		Senegal,SN,SEN,0
		Serbia,RS,SRB,0
		Seychelles,SC,SYC,0
		Sierra Leone,SL,SLE,0
		Singapore,SG,SGP,0
		Sint Maarten (Dutch part),SX,SXM,0
		Slovakia,SK,SVK,0
		Slovenia,SI,SVN,0
		Solomon Islands,SB,SLB,0
		Somalia,SO,SOM,0
		South Africa,ZA,ZAF,0
		South Georgia and the South Sandwich Islands,GS,SGS,0
		South Sudan,SS,SSD,0
		Spain,ES,ESP,1
		Sri Lanka,LK,LKA,0
		Sudan (the),SD,SDN,0
		Suriname,SR,SUR,0
		Svalbard and Jan Mayen,SJ,SJM,0
		Sweden,SE,SWE,1
		Switzerland,CH,CHE,0
		Syrian Arab Republic,SY,SYR,0
		Taiwan (Province of China),TW,TWN,0
		Tajikistan,TJ,TJK,0
		Tanzania (United Republic of),TZ,TZA,0
		Thailand,TH,THA,0
		Timor-Leste,TL,TLS,0
		Togo,TG,TGO,0
		Tokelau,TK,TKL,0
		Tonga,TO,TON,0
		Trinidad and Tobago,TT,TTO,0
		Tunisia,TN,TUN,0
		Turkey,TR,TUR,0
		Turkmenistan,TM,TKM,0
		Turks and Caicos Islands (the),TC,TCA,0
		Tuvalu,TV,TUV,0
		Uganda,UG,UGA,0
		Ukraine,UA,UKR,0
		United Arab Emirates (the),AE,ARE,0
		United Kingdom of Great Britain and Northern Ireland (the),GB,GBR,0
		United States Minor Outlying Islands (the),UM,UMI,0
		United States of America (the),US,USA,0
		Uruguay,UY,URY,0
		Uzbekistan,UZ,UZB,0
		Vanuatu,VU,VUT,0
		Venezuela (Bolivarian Republic of),VE,VEN,0
		Viet Nam,VN,VNM,0
		Virgin Islands (British),VG,VGB,0
		Virgin Islands (U.S.),VI,VIR,0
		Wallis and Futuna,WF,WLF,0
		Western Sahara,EH,ESH,0
		Yemen,YE,YEM,0
		Zambia,ZM,ZMB,0
		Zimbabwe,ZW,ZWE,0
		Åland Islands,AX,ALA,0";

		
		$sCSV = "Netherlands (the),NL,NLD,1"; //-===> when you want a quick install, we can uncomment this
	
	
		if ($bSuccess)
		{
			$this->limitOne();
			$this->loadFromDB(false);
			if ($this->count() == 0) //only add when table is empty
			{
				$this->clear();
				
				$arrLines = explode("\n", $sCSV);
				
				foreach ($arrLines as $sLine)
				{
					$arrColumns = explode(',', $sLine);
					$this->newRecord();
					$this->set(TSysCountries::FIELD_COUNTRYNAME, trim($arrColumns[0])); //the csv above has tab-chars in it, trim them
					$this->set(TSysCountries::FIELD_ISO2, $arrColumns[1]);
					$this->set(TSysCountries::FIELD_ISO3, $arrColumns[2]);
					if ($arrColumns[3] == '1')
						$this->set(TSysCountries::FIELD_ISEUROPEANUNION, true);
					
					if ($arrColumns[1] == GLOBAL_LOCATION_DEFAULT)
					{
						$this->set(TSysCountries::FIELD_ISSYSTEMDEFAULT, true);
					}

					if (!$this->saveToDB())
						error('error saving language on install: '. $arrColumns[1]);
				}
			}
		}
		
		return $bSuccess;
	}




	/**
	 * This function is called in the constructor and the clear() function
	 * this is used to define default values for fields
         * 
	 * initialize values
	 */
	public function initRecord()
	{}
		
	
	
	/**
	 * defines the fields in the tables
	 * i.e. types, default values, enum values, referenced tables etc
	*/
	public function defineTable()
	{
		//country name
		$this->setFieldDefaultValue(TSysCountries::FIELD_COUNTRYNAME, '');
		$this->setFieldType(TSysCountries::FIELD_COUNTRYNAME, CT_VARCHAR);
		$this->setFieldLength(TSysCountries::FIELD_COUNTRYNAME, 100);
		$this->setFieldDecimalPrecision(TSysCountries::FIELD_COUNTRYNAME, 0);
		$this->setFieldPrimaryKey(TSysCountries::FIELD_COUNTRYNAME, false);
		$this->setFieldNullable(TSysCountries::FIELD_COUNTRYNAME, false);
		$this->setFieldEnumValues(TSysCountries::FIELD_COUNTRYNAME, null);
		$this->setFieldUnique(TSysCountries::FIELD_COUNTRYNAME, true);
		$this->setFieldIndexed(TSysCountries::FIELD_COUNTRYNAME, false);
		$this->setFieldForeignKeyClass(TSysCountries::FIELD_COUNTRYNAME, null);
		$this->setFieldForeignKeyTable(TSysCountries::FIELD_COUNTRYNAME, null);
		$this->setFieldForeignKeyField(TSysCountries::FIELD_COUNTRYNAME, null);
		$this->setFieldForeignKeyJoin(TSysCountries::FIELD_COUNTRYNAME, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCountries::FIELD_COUNTRYNAME, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCountries::FIELD_COUNTRYNAME, null);
		$this->setFieldAutoIncrement(TSysCountries::FIELD_COUNTRYNAME, false);
		$this->setFieldUnsigned(TSysCountries::FIELD_COUNTRYNAME, false);
		$this->setFieldEncryptionDisabled(TSysCountries::FIELD_COUNTRYNAME);									

		
		//alpha 2 code
		$this->setFieldDefaultValue(TSysCountries::FIELD_ISO2, '');
		$this->setFieldType(TSysCountries::FIELD_ISO2, CT_VARCHAR);
		$this->setFieldLength(TSysCountries::FIELD_ISO2, 2);
		$this->setFieldDecimalPrecision(TSysCountries::FIELD_ISO2, 0);
		$this->setFieldPrimaryKey(TSysCountries::FIELD_ISO2, false);
		$this->setFieldNullable(TSysCountries::FIELD_ISO2, false);
		$this->setFieldEnumValues(TSysCountries::FIELD_ISO2, null);
		$this->setFieldUnique(TSysCountries::FIELD_ISO2, true);
		$this->setFieldIndexed(TSysCountries::FIELD_ISO2, false);//it is already unique
		$this->setFieldForeignKeyClass(TSysCountries::FIELD_ISO2, null);
		$this->setFieldForeignKeyTable(TSysCountries::FIELD_ISO2, null);
		$this->setFieldForeignKeyField(TSysCountries::FIELD_ISO2, null);
		$this->setFieldForeignKeyJoin(TSysCountries::FIELD_ISO2, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCountries::FIELD_ISO2, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCountries::FIELD_ISO2, null);
		$this->setFieldAutoIncrement(TSysCountries::FIELD_ISO2, false);
		$this->setFieldUnsigned(TSysCountries::FIELD_ISO2, false);
		$this->setFieldEncryptionDisabled(TSysCountries::FIELD_ISO2);									


		//alpha 3 code
		$this->setFieldDefaultValue(TSysCountries::FIELD_ISO3, '');
		$this->setFieldType(TSysCountries::FIELD_ISO3, CT_VARCHAR);
		$this->setFieldLength(TSysCountries::FIELD_ISO3, 3);
		$this->setFieldDecimalPrecision(TSysCountries::FIELD_ISO3, 0);
		$this->setFieldPrimaryKey(TSysCountries::FIELD_ISO3, false);
		$this->setFieldNullable(TSysCountries::FIELD_ISO3, false);
		$this->setFieldEnumValues(TSysCountries::FIELD_ISO3, null);
		$this->setFieldUnique(TSysCountries::FIELD_ISO3, true);
		$this->setFieldIndexed(TSysCountries::FIELD_ISO3, false); //it is already unique
		$this->setFieldForeignKeyClass(TSysCountries::FIELD_ISO3, null);
		$this->setFieldForeignKeyTable(TSysCountries::FIELD_ISO3, null);
		$this->setFieldForeignKeyField(TSysCountries::FIELD_ISO3, null);
		$this->setFieldForeignKeyJoin(TSysCountries::FIELD_ISO3, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCountries::FIELD_ISO3, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCountries::FIELD_ISO3, null);
		$this->setFieldAutoIncrement(TSysCountries::FIELD_ISO3, false);
		$this->setFieldUnsigned(TSysCountries::FIELD_ISO3, false);
		$this->setFieldEncryptionDisabled(TSysCountries::FIELD_ISO3);	


		//default country
		$this->setFieldDefaultValue(TSysCountries::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldType(TSysCountries::FIELD_ISSYSTEMDEFAULT, CT_BOOL);
		$this->setFieldLength(TSysCountries::FIELD_ISSYSTEMDEFAULT, 0);
		$this->setFieldDecimalPrecision(TSysCountries::FIELD_ISSYSTEMDEFAULT, 0);
		$this->setFieldPrimaryKey(TSysCountries::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldNullable(TSysCountries::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldEnumValues(TSysCountries::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldUnique(TSysCountries::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldIndexed(TSysCountries::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldForeignKeyClass(TSysCountries::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyTable(TSysCountries::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyField(TSysCountries::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyJoin(TSysCountries::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCountries::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCountries::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldAutoIncrement(TSysCountries::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldUnsigned(TSysCountries::FIELD_ISSYSTEMDEFAULT, false);	
        $this->setFieldEncryptionDisabled(TSysCountries::FIELD_ISSYSTEMDEFAULT);						

		//european union?
		$this->setFieldCopyProps(TSysCountries::FIELD_ISEUROPEANUNION, TSysCountries::FIELD_ISSYSTEMDEFAULT);
	}
	
	
	/**
	 * returns an array with fields that are publicly viewable
	 * sometimes (for security reasons the password-field for example) you dont want to display all table fields to the user
	 *
	 * i.e. it can be used for searchqueries, sorting, filters or exports
	 *
	 * @return array function returns array WITHOUT tablename
	*/
	public function getFieldsPublic()
	{
		return array(TSysCountries::FIELD_COUNTRYNAME, TSysCountries::FIELD_ISO2, TSysCountries::FIELD_ISO3, TSysCountries::FIELD_ISSYSTEMDEFAULT, TSysCountries::FIELD_ISEUROPEANUNION);
	}
	
	/**
	 * use the auto-added id-field ?
	 * @return bool
	*/
	public function getTableUseIDField()
	{
		return true;
	}
	
	
	/**
	 * use the auto-added date-changed & date-created field ?
	 * @return bool
	*/
	public function getTableUseDateCreatedChangedField()
	{
		return false;
	}
	
	
	/**
	 * use the checksum field ?
	 * @return bool
	*/
	public function getTableUseChecksumField()
	{
		return true;
	}
	
	/**
	 * order field to switch order between records
	*/
	public function getTableUseOrderField()
	{
		return false;
	}
	
	/**
	 * use checkout for locking file for editing
	*/
	public function getTableUseCheckout()
	{
		return false;
	}
		
	/**
	 * use record locking to prevent record editing
	*/
	public function getTableUseLock()
	{
		return false;
	}            
	
        
	/**
	 * use image in your record?
    * if you don't want a small and large version, use this one
	*/
	public function getTableUseImageFileLarge()
	{
		return false;
	}
        
	/**
	 * use image in your record?
    * this is the small version
	*/
	public function getTableUseImageFileThumbnail()
	{
		return false;
	}

    /**
	 * use image in your record?
     * this is the large version
	*/
	public function getTableUseImageFileMedium()
	{
		return false;
	}
        
	/**
	 * opvragen of records fysiek uit de databasetabel verwijderd moeten worden
	 *
	 * returnwaarde interpretatie:
	 * true = fysiek verwijderen uit tabel
	 * false = record-hidden-veld gebruiken om bij te houden of je het record kan zien in overzichten
	 *
	 * @return bool moeten records fysiek verwijderd worden ?
	*/
	public function getTablePhysicalDeleteRecord()
	{
		return true;
	}
	
	
	
	
	/**
	 * type of primary key field
	 *
	 * @return integer with constant CT_AUTOINCREMENT or CT_INTEGER or something else that is not recommendable
	*/
	public function getTableIDFieldType()
	{
		return CT_AUTOINCREMENT;
	}
	
	
	/**
	 * de child moet deze overerven
	 *
	 * @return string naam van de databasetabel
	*/
	public static function getTable()
	{
		return GLOBAL_DB_TABLEPREFIX.'SysCountries';
	}
	
	
	
	/**
	 * OVERSCHRIJF DOOR CHILD KLASSE ALS NODIG
	 *
	 * Voor de gui functies (zoals het maken van comboboxen) vraagt deze functie op
	 * welke waarde er in het gui-element geplaatst moet worden, zoals de naam bijvoorbeeld
	 *
	 *
	 * return '??? - functie niet overschreven door child klasse';
	*/
	public function getGUIItemName()
	{
		return $this->get(TSysCountries::FIELD_COUNTRYNAME);
	}
	
	
	/**
	 * erf deze functie over om je eigen checksum te maken voor je tabel.
	 * je berekent deze de belangrijkste velden te pakken, wat strings toe te
	 * voegen en alles vervolgens de coderen met een hash algoritme
	 * zoals met sha1 (geen md5, gezien deze makkelijk te breken is)
	 * de checksum mag maar maximaal 50 karakters lang zijn
	 *
	 * BELANGRIJK: je mag NOOIT het getID() en getChecksum()-field meenemen in
	 * je checksum berekening (id wordt pas toegekend na de save in de database,
	 * dus is nog niet bekend ten tijde van het checksum berekenen)
	 *
	 * @return string
	*/
	public function getChecksumUncrypted()
	{
		return 'winkelbediende'.$this->get(TSysCountries::FIELD_COUNTRYNAME).'proletarisch'.$this->get(TSysCountries::FIELD_ISO2).''.$this->get(TSysCountries::FIELD_ISO3).'winkelen'.$this->get(TSysCountries::FIELD_COUNTRYNAME).'dat-is-dus-stelen'.boolToStr($this->get(TSysCountries::FIELD_ISSYSTEMDEFAULT));
	}
	
	
	/**
	 * DEZE FUNCTIE MOET OVERGEERFD WORDEN DOOR DE CHILD KLASSE
	 *
	 * checken of alle benodigde waardes om op te slaan wel aanwezig zijn
	 *
	 * @return bool true=ok, false=not ok
	*/
	public function areValuesValid()
	{     
		return true;
	}
	
	/**
	 * for the automatic database table upgrade system to work this function
	 * returns the version number of this class
	 * The update system can compare the version of the database with the Business Logic
	 *
	 * default with no updates = 0
	 * first update = 1, second 2 etc
	 *
	 * @return int
	*/
	public function getVersion()
	{
		return 0;
	}
	
	/**
	 * update the table in the database
	 * (may have been changes to fieldnames, fields added or removed etc)
	 *
	 * @param int $iFromVersion upgrade vanaf welke versie ?
	 * @return bool is alles goed gegaan ? true = ok (of er is geen upgrade gedaan)
	*/
	public function updateDBTable($iFromVersion)
	{
		return true;
	}	
        
	/**
	 * use a second id that has no follow-up numbers?
	 */
	public function getTableUseRandomID()
	{
		return false;
	}        
	
	/**
	 * is randomid field a primary key?
	 */        
	public function getTableUseRandomIDAsPrimaryKey()
	{
		return false;
	}       
        
	/**
	 * use a third character-based id that has no logically follow-up numbers?
	 * 
	 * a tertiary unique key (uniqueid) can be useful for security reasons like login sessions: you don't want to _POST the follow up numbers in url
	 */
	public function getTableUseUniqueID()
	{
		return false;
	}

	/**
	 * is this model a translation model?
	 *
	 * @return bool is this model a translation model?
	 */
	public function getTableUseTranslationLanguageID()
	{
		return false;
	}        

} 
?>