<?php 

class Migration extends MX_Controller
{
    protected $Dump = NULL;
    protected $RealmName = NULL;
    protected $DecodedDump = NULL;
    protected $Realm = NULL;
    protected $AccountId = NULL;
    protected $json = NULL;
    protected $oCharName = NULL;
    protected $oRealmlist = NULL;
    protected $oRealm = NULL;
    protected $CharRaceId = NULL;
    protected $CharRaceName = NULL;
    protected $CharClassId = NULL;
    protected $CharClassname = NULL;
    protected $CharLevel = NULL;
    protected $oAccount = NULL;
    protected $oPassword = NULL;
    protected $oBuild = NULL;
    protected $CharGender = NULL;
    protected $CharSpecCount = NULL;
    protected $cBuild = NULL;
    protected $CharGuid = NULL;
    protected $CharTotalKills = NULL;
    protected $InventarioRow = NULL;
	protected $TestInventory = NULL;
    protected $CharMoney = NULL;
    protected $CharCHonor = 0;
    protected $CharCArena = 0;
    protected $CharCJustice = 0;
    protected $CharCValor = 0;
    protected $cLocale = NULL;
    protected $baseurl = NULL;

    function __construct()
    {
        parent::__construct();
        $this->load->model("functions");
        $this->load->config("master");
        $this->load->config("lang");
    }

    function test()
    {
        echo $this->realms->realmExists(1);
    }

    function index()
    {
        $this->baseurl = $this->config->item("baseurl");
        $Realms_server = array(  );
        foreach( $this->realms->getRealms() as $realmData ) 
        {
            $CompitableRealms[] = array( "id" => $realmData->getId(), "name" => $realmData->getName() );
        }
        unset($realmData);
        $data = array( "isOnline" => $this->user->isOnline(), "token" => $this->security->get_csrf_hash(), "realms" => $CompitableRealms, "getAccTrans" => $this->functions->getAccTrans(), "baseurl" => $this->baseurl, "335alink" => $this->config->item("335alink"), "406alink" => $this->config->item("406alink"), "434link" => $this->config->item("434link"), "utxt100" => $this->config->item("utxt100"), "utxt101" => $this->config->item("utxt101"), "utxt102" => $this->config->item("utxt102"), "utxt103" => $this->config->item("utxt103"), "utxt104" => $this->config->item("utxt104"), "utxt105" => $this->config->item("utxt105"), "utxt106" => $this->config->item("utxt106"), "utxt107" => $this->config->item("utxt107"), "utxt108" => $this->config->item("utxt108"), "utxt109" => $this->config->item("utxt109"), "utxt110" => $this->config->item("utxt110"), "utxt111" => $this->config->item("utxt111"), "utxt112" => $this->config->item("utxt112"), "utxt113" => $this->config->item("utxt113"), "utxt114" => $this->config->item("utxt114"), "utxt115" => $this->config->item("utxt115"), "utxt116" => $this->config->item("utxt116"), "utxt117" => $this->config->item("utxt117"), "utxt118" => $this->config->item("utxt118"), "utxt119" => $this->config->item("utxt119"), "utxt120" => $this->config->item("utxt120"), "utxt121" => $this->config->item("utxt121"), "utxt122" => $this->config->item("utxt122"), "utxt123" => $this->config->item("utxt123"), "utxt124" => $this->config->item("utxt124"), "utxt125" => $this->config->item("utxt125"), "utxt126" => $this->config->item("utxt126"), "utxt127" => $this->config->item("utxt127"), "utxt128" => $this->config->item("utxt128"), "utxt129" => $this->config->item("utxt129"), "utxt130" => $this->config->item("utxt130"), "utxt131" => $this->config->item("utxt131"), "utxt132" => $this->config->item("utxt132"), "utxt133" => $this->config->item("utxt133"), "utxt134" => $this->config->item("utxt134"), "utxt135" => $this->config->item("utxt135"), "utxt136" => $this->config->item("utxt136"), "utxt137" => $this->config->item("utxt137"), "utxt138" => $this->config->item("utxt138"), "utxt139" => $this->config->item("utxt139"), "utxt140" => $this->config->item("utxt140"), "utxt141" => $this->config->item("utxt141"), "utxt142" => $this->config->item("utxt142"), "utxt143" => $this->config->item("utxt143"), "utxt144" => $this->config->item("utxt144"), "utxt145" => $this->config->item("utxt145"), "txt1" => $this->config->item("txt1"), "txt2" => $this->config->item("txt2"), "txt8" => $this->config->item("txt8"), "txt3" => $this->config->item("txt3"), "txt5" => $this->config->item("txt5"), "txt4" => $this->config->item("txt4"), "txt9" => $this->config->item("txt9"), "txt10" => $this->config->item("txt10"), "txt11" => $this->config->item("txt11"), "txt12" => $this->config->item("txt12"), "txt37" => $this->config->item("txt37"), "txt38" => $this->config->item("txt38"), "txt39" => $this->config->item("txt39"), "fname434" => $this->config->item("fname434"), "fname406a" => $this->config->item("fname406a"), "fname335a" => $this->config->item("fname335a") );
        $content = $this->template->loadPage("inicio.tpl", $data);
        $box = $this->template->box($this->config->item("title"), $content);
        $this->template->view($box, "modules/migration/css/migration.css", "modules/migration/js/migration.js");
    }

    function cancelar($id)
    {
        $RealmID = $this->functions->getTransfer($id)->cRealm;
        $ACCOUNT_ID = $this->functions->getTransfer($id)->cAccount;
        $ID = $id;
        if( $this->user->getId() != $ACCOUNT_ID ) 
        {
            exit( $this->config->item("txt1") );
        }

        if( $this->functions->getTransfer($id)->cStatus != 0 ) 
        {
            exit( $this->config->item("txt2") );
        }

        $this->functions->UpdateDumpStatus($ID, 3);
        exit( $this->config->item("txt3") );
    }

    function rechazar($id)
    {
        if( !hasPermission("manage") ) 
        {
            exit( $this->config->item("txt4") );
        }

        $RealmID = $this->functions->getTransfer($id)->cRealm;
        $ACCOUNT_ID = $this->functions->getTransfer($id)->cAccount;
        $ID = $id;
        if( $this->functions->getTransfer($id)->cStatus != 0 ) 
        {
            exit( $this->config->item("txt5") );
        }

        $this->functions->UpdateDumpStatus($ID, 2);
        exit( $this->config->item("txt6") );
    }
	
    function aceptar($id)
    {
        if( !hasPermission("manage") ) 
        {
            exit( $this->config->item("txt4") );
        }
		
		/* custom */
		$EMO_DUMP = $this->functions->getTransfer($id)->cDump;
		$EMO_DDUMP = strrev(base64_decode(strrev(strrev(base64_decode(strrev($EMO_DUMP))))));
		$EMO_DECODED_DUMP = json_decode(stripslashes($EMO_DDUMP), true);
		$EMO_RASA_DECODED = strtoupper($EMO_DECODED_DUMP["uinf"]["race"]); // glavni bukvi
		$EMO_KLAS_DECODED = strtoupper($EMO_DECODED_DUMP["uinf"]["class"]); // glavni bukvi

        $Realm = $this->functions->getTransfer($id)->cRealm;
        $AccountId = $this->functions->getTransfer($id)->cAccount;
        $Dump = $this->functions->getTransfer($id)->cDump;
		$Guid = $this->functions->getTransfer($id)->GUID;
		$Name = $this->functions->getCharacterName($Realm, $Guid, $this->functions->getTransfer($id)->cAccount);
		$HysteriaCharacterRaceId = $this->functions->getCharacterRaceId($Realm, $Guid, $this->functions->getTransfer($id)->cAccount);  /* izvadi rasite  */
		$HysteriaCharacterClassId = $this->functions->getCharacterClassId($Realm, $Guid, $this->functions->getTransfer($id)->cAccount); /* izvadi klasite */
		
        if( !$this->realms->getRealm($Realm)->isOnline() ) 
            exit( $this->config->item("txt7") );
		
        if( $this->functions->getTransfer($id)->cStatus != 0 ) 
            exit( $this->config->item("txt5") );

		if ($this->functions->_GetRaceID($EMO_RASA_DECODED) != $HysteriaCharacterRaceId) // ako ne e sushtata rasa
			exit ("The character you have created has to be the same race with the one you have copied from the other server!");
		
		if ($this->functions->_GetClassID($EMO_KLAS_DECODED) != $HysteriaCharacterClassId) // ako ne e sushtiq klas
			exit ("The character you have created has to be the same class with the one you have copied from the other server!");

        $this->getEntregar($Realm, $AccountId, $id, $Dump);
        $this->basicData();
        $this->reputaciones();
        $this->getinventoryf();
        $this->skillsLearn();
        $this->functions->sendMails($this->InventarioRow, $Name, $this->Realm);
        $this->functions->teleport($this->Realm, $this->oCharName, $HysteriaCharacterRaceId);
        $this->functions->_TalentsReset($this->Realm, $this->Guid);
        foreach( $this->json["spells"] as $SpellID => $value ) 
        {
            if( $this->functions->isSpellValid($SpellID, $HysteriaCharacterClassId) ) 
            {
                $this->functions->LearnSeparateSpell((int) $SpellID, $this->Guid, $this->Realm);
            }

        }
        //$this->functions->UpdateDumpStatus($id, 1);
        $data = array( "GUID" => $this->Guid, "cItemRow" => $this->InventarioRow, "date_checked" => date("Y-m-d H.i:s"), "gmAccount" => $this->user->getId() );
        $this->db->where("id", $id);
        $this->db->update("migration_transfer", $data);
        exit( $this->config->item("txt8") );
    }

    function resend($id)
    {
        if( !hasPermission("manage") ) 
        {
            exit( $this->config->item("txt4") );
        }

        $Realm = $this->functions->getTransfer($id)->cRealm;
        $AccountId = $this->functions->getTransfer($id)->cAccount;
        $Dump = $this->functions->getTransfer($id)->cDump;
        $Guid = $this->functions->getTransfer($id)->GUID;
        $Name = $this->functions->getCharacterName($Realm, $Guid, $AccountId);

		
        $this->getEntregar($Realm, $AccountId, $id, $Dump);
        $this->basicData();
        $this->reputaciones();
        $this->getinventoryf();

        $itemsrow = $this->InventarioRow;
        if( !$Name ) 
        {
            exit( $this->config->item("txt9") );
        }

        if( !$this->realms->getRealm($Realm)->isOnline() ) 
        {
            exit( $this->config->item("txt7") );
        }

        if( 1 <= $this->functions->_CheckMail($Realm, $Guid) ) 
        {
            exit( $this->config->item("txt10") );
        }

        if( $this->functions->getTransfer($id)->cStatus != 1 ) 
        {
            exit( $this->config->item("txt11") );
        }
		
		if (!$itemsrow)
		{
			exit ("[error]itemsrow missing");
		}

        $this->functions->sendMails($itemsrow, $Name, $Realm);
        exit( $this->config->item("txt12") );
    }

    function confirm()
    {
        $this->AccountId = $this->user->getId();
        $data = array( "cStatus" => "0", "cDump" => $this->cache->get($this->AccountId . "_Dump"), "cAccount" => $this->AccountId, "cRealm" => $this->cache->get($this->AccountId . "_Realm"), "oAccount" => $this->cache->get($this->AccountId . "_oAccount"), "oPassword" => $this->cache->get($this->AccountId . "_oPassword"), "oRealm" => $this->cache->get($this->AccountId . "_oRealm"), "oRealmlist" => $this->cache->get($this->AccountId . "_oRealmlist"), "cNameOLD" => $this->cache->get($this->AccountId . "_oCharName") );
        $this->db->insert("migration_transfer", $data);
        if( 0 < $this->db->affected_rows() ) 
        {
            $this->cache->delete($this->AccountId . "_DecodedDump");
            $this->cache->delete($this->AccountId . "_Realm");
            $this->cache->delete($this->AccountId . "_oAccount");
            $this->cache->delete($this->AccountId . "_oPassword");
            $this->cache->delete($this->AccountId . "_oRealmlist", $this->oRealmlist, 3600);
            $this->cache->delete($this->AccountId . "_oRealm", $this->oRealm, 3600);
            $this->cache->delete($this->AccountId . "_oCharName", $this->oRealm, 3600);
            echo "success";
        }
        else
        {
            echo $this->config->item("txt13");
        }

        exit();
    }

    function procesar()
    {
        $this->valorarArchivo();
        $this->comprobarLimites();
        $this->basicData();
        $this->getinventoryf();
        $this->reputaciones();
        $this->cache->save($this->AccountId . "_Dump", $this->Dump, 3600);
        $this->cache->save($this->AccountId . "_Realm", $this->Realm, 3600);
        $this->cache->save($this->AccountId . "_oAccount", $this->oAccount, 3600);
        $this->cache->save($this->AccountId . "_oPassword", $this->oPassword, 3600);
        $this->cache->save($this->AccountId . "_oRealmlist", $this->oRealmlist, 3600);
        $this->cache->save($this->AccountId . "_oRealm", $this->oRealm, 3600);
        $this->cache->save($this->AccountId . "_oCharName", $this->oCharName, 3600);
        exit();
    }

    function getEntregar($Realm, $AccountId, $id, $Dump)
    {
        if( !hasPermission("manage") ) 
        {
            echo $this->config->item("txt4");
            exit();
        }

        $this->Realm = $Realm;
        $this->Id = $id;
        $this->AccountId = $AccountId;
        $this->Dump = $Dump;
        $this->DecodedDump = $this->functions->_DECRYPT($this->Dump);
        $this->RealmName = $this->realms->getRealm($this->Realm)->getName();
        $this->cBuild = $this->functions->cRealmInfo($this->Realm)->gamebuild;
        $this->json = json_decode(stripslashes($this->DecodedDump), true);
        $this->oBuild = $this->json["ginf"]["clientbuild"];
        $this->oCharName = "Mi" . $id;
        $this->CharRaceId = $this->functions->_GetRaceID(strtoupper($this->json["uinf"]["race"]));
        $this->CharRaceName = strtoupper($this->json["uinf"]["race"]);
        $this->CharClassId = $this->functions->_GetClassID(strtoupper($this->json["uinf"]["class"]));
        $this->CharClassName = strtoupper($this->json["uinf"]["class"]);
        $this->CharLevel = $this->functions->_MaxValue($this->json["uinf"]["level"], $this->functions->MaxclByBuild($this->oBuild));
        $this->CharMoney = $this->functions->_MaxValue($this->json["uinf"]["money"], $this->config->item("MaxMoney"));
        $this->CharSpecCount = $this->json["uinf"]["specs"];
        $this->CharTotalKills = $this->json["uinf"]["kills"];
        $this->cLocale = trim(strtoupper($this->json["ginf"]["locale"]));
        $this->CharGender = $this->json["uinf"]["gender"] - 2 == 1 ? 1 : 0;
        $this->Guid = $this->functions->GetCharacterGuid($this->Realm, $AccountId);
        if( $this->CharClassId == 6 && $this->functions->checkHaveDK($this->Realm, $this->AccountId) ) 
        {
            echo $this->config->item("txt14");
            exit();
        }

        $dbchar = $this->realms->getRealm($this->Realm)->getCharacters()->getConnection();
        $char_data = array( "guid" => $this->Guid, "race" => $this->CharRaceId, "account" => $this->AccountId, "name" => $this->oCharName, "level" => $this->CharLevel, "gender" => $this->CharGender, "totalKills" => $this->CharTotalKills, "money" => $this->CharMoney, "speccount" => $this->CharSpecCount, "taximask" => "0 0 0 0 0 0 0 0 0 0 0 0 0 0", "online" => "0", "class" => $this->CharClassId );
        $dbchar->insert("characters", $char_data);
        if( $this->CharSpecCount == 2 ) 
        {
            $this->functions->LearnSeparateSpell(63644, $this->Guid, $this->Realm);
            $this->functions->LearnSeparateSpell(63645, $this->Guid, $this->Realm);
        }

        if( $this->CharClassId == 6 ) 
        {
            $this->functions->DeathKnightTransfer($this->Guid, $this->Realm);
        }

    }

    function valorarArchivo($dump = false)
    {
        $this->baseurl = $this->config->item("baseurl");
        if( $this->cache->get($this->user->getId() . "_upload") ) 
        {
            sleep(0);
        }

        $config["upload_path"] = "." . $this->baseurl . "application/modules/migration/filesup";
        $config["allowed_types"] = "*";
        $config["max_size"] = "200";
        $config["max_filename"] = "15";
        $config["file_name"] = $this->user->getId();
        $this->load->library("upload", $config);
        if( $this->upload->do_upload() ) 
        {
            $file_info = $this->upload->data();
            $file = $file_info["full_path"];
            $reqName = array( $this->config->item("fname406a"), $this->config->item("fname434"), $this->config->item("fname335a") );
            if( !in_array($file_info["client_name"], $reqName) ) 
            {
                echo $this->config->item("txt15");
                exit();
            }

            $fileopen = fopen($file, "r");
            $buffer = "";
            while( !feof($fileopen) ) 
            {
                $buffer2 = fgets($fileopen);
                $buffer .= $buffer2;
            }
            fclose($fileopen);
            unlink($file);
            $part = explode("\"", $buffer);
            if( isset($part[1]) ) 
            {
                $this->Dump = $part[1];
                $this->DecodedDump = $this->functions->_DECRYPT($this->Dump);
                $this->Realm = $this->input->post("realm", true);
                $this->RealmName = $this->realms->getRealm($this->Realm)->getName();
                $this->cBuild = $this->functions->cRealmInfo($this->Realm)->gamebuild;
                $this->AccountId = $this->user->getId();
                $this->json = json_decode(stripslashes($this->DecodedDump), true);
                $this->oBuild = $this->json["ginf"]["clientbuild"];
                $this->oCharName = mb_convert_case(mb_strtolower($this->json["uinf"]["name"], "UTF-8"), MB_CASE_TITLE, "UTF-8");
                $this->oRealmlist = $this->json["ginf"]["realmlist"];
                $this->oRealm = $this->json["ginf"]["realm"];
                $this->CharRaceId = $this->functions->_GetRaceID(strtoupper($this->json["uinf"]["race"]));
                $this->CharRaceName = strtoupper($this->json["uinf"]["race"]);
                $this->CharClassId = $this->functions->_GetClassID(strtoupper($this->json["uinf"]["class"]));
                $this->CharClassName = strtoupper($this->json["uinf"]["class"]);
                $this->CharLevel = $this->functions->_MaxValue($this->json["uinf"]["level"], $this->functions->MaxclByBuild($this->oBuild));
                $this->oAccount = $this->input->post("oUsername", true);
                $this->oPassword = base64_encode($this->input->post("oPassword", true));
                $this->CharMoney = $this->functions->_MaxValue($this->json["uinf"]["money"], $this->config->item("MaxMoney"));
                $this->CharSpecCount = $this->json["uinf"]["specs"];
                $this->CharTotalKills = $this->json["uinf"]["kills"];
                $this->cLocale = trim(strtoupper($this->json["ginf"]["locale"]));
                $this->CharGender = $this->json["uinf"]["gender"] - 2 == 1 ? 1 : 0;
                $this->cache->save($this->AccountId . "_upload", "exist", 300);
                if( !$this->realms->realmExists($this->Realm) ) 
                {
                    echo $this->config->item("txt38");
                    exit();
                }

                if( $this->functions->totalCharacters($this->Realm, $this->AccountId) == 10 ) 
                {
                    echo $this->config->item("txt36");
                    exit();
                }

                if( $this->functions->checkDump($this->Dump) ) 
                {
                    echo $this->config->item("txt16");
                    exit();
                }

                if( $this->functions->checkblackList($this->oRealm, $this->oRealmlist) ) 
                {
                    echo $this->config->item("txt17");
                    exit();
                }

                if( $this->cBuild < $this->oBuild ) 
                {
                    echo $this->config->item("txt18");
                    exit();
                }

            }
            else
            {
                echo $this->config->item("txt15");
                exit();
            }

        }
        else
        {
            echo $this->upload->display_errors();
            exit();
        }

    }

    function comprobarLimites()
    {
        $AchievementsCount = 0;
        $ACHMINTime = 0;
        $ACHMAXTime = 0;
        foreach( $this->json["achiev"] as $key => $value ) 
        {
            if( $ACHMINTime == 0 ) 
            {
                $ACHMINTime = $value["D"];
            }

            if( $value["D"] < $ACHMINTime ) 
            {
                $ACHMINTime = $value["D"];
            }

            if( $ACHMAXTime < $value["D"] ) 
            {
                $ACHMAXTime = $value["D"];
            }

            $AchievementsCount++;
        }
        if( $this->config->item("playtime_minlvl") <= $this->CharLevel && $this->functions->CHECKDAY($ACHMAXTime, $ACHMINTime) < $this->config->item("Playtime") ) 
        {
            echo $this->config->item("txt19");
            exit();
        }

        if( $this->CharClassId == 6 && 0 < $this->functions->maxDk($this->Realm, $this->AccountId) ) 
        {
            echo $this->config->item("txt20");
            exit();
        }

    }

    function basicData()
    {
        if( hasPermission("manage") ) 
        {
            $dbchar = $this->realms->getRealm($this->Realm)->getCharacters()->getConnection();
        }

        foreach( $this->json["currency"] as $value ) 
        {
            $currency = $this->functions->getCurrencyId(strtoupper($value["N"]), $this->cLocale);
            $count = $this->functions->_MaxValue($value["C"], "4000");
            if( $currency != -1 ) 
            {
                if( $currency == "390" ) 
                {
                    $this->CharCArena = $count;
                }

                if( $currency == "392" ) 
                {
                    $this->CharCHonor = $count;
                }

                if( $currency == "396" ) 
                {
                    $this->CharCValor = $count;
                }

                if( $currency == "395" ) 
                {
                    $this->CharCJustice = $count;
                }

            }

        }
        if( hasPermission("manage") ) 
        {
            if( in_array($this->cBuild, array( 18414, 17898, 17688, 17658, 17538, 17359, 17128, 16981, 16826, 16769, 16760, 16733, 16716, 16709, 16701, 16685, 16683, 16669, 16650, 16357, 16309, 16135 )) ) 
            {
                $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 390, ?, 0);", array( $this->Guid, $this->CharCArena * 100 ));
                $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 392, ?, 0);", array( $this->Guid, $this->CharCHonor * 100 ));
                $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 396, ?, 0);", array( $this->Guid, $this->CharCValor * 100 ));
                $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 395, ?, 0);", array( $this->Guid, $this->CharCJustice * 100 ));
            }
            else
            {
                if( in_array($this->cBuild, array( 15595, 13623, 14545 )) ) 
                {
                    $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 390, ?, 0);", array( $this->Guid, $this->CharCArena * 100 ));
                    $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 392, ?, 0);", array( $this->Guid, $this->CharCHonor * 100 ));
                    $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 396, ?, 0);", array( $this->Guid, $this->CharCValor * 100 ));
                    $dbchar->query("INSERT INTO character_currency (guid, currency, total_count, week_count) VALUES (?, 395, ?, 0);", array( $this->Guid, $this->CharCJustice * 100 ));
                }
                else
                {
                    if( in_array($this->cBuild, array( 12340 )) ) 
                    {
                        $dbchar->query("UPDATE characters SET arenaPoints = ?, totalHonorPoints = ? WHERE guid = ?;", array( $this->CharCArena, $this->CharCHonor, $this->Guid ));
                        $this->realms->getRealm($this->Realm)->getEmulator()->send("send items " . $this->oCharName . " \"" . $this->config->item("mail_title") . "\" \"" . $this->config->item("mail_content") . "\" 49426:" . $this->CharCValor . "");
                    }

                }

            }

        }

        $total_logros = 0;
        foreach( $this->json["achiev"] as $key => $value ) 
        {
            if( hasPermission("manage") ) 
            {
                $achievement = $value["I"];
                $date = $value["D"];
                $dbchar->query("INSERT IGNORE INTO `character_achievement` VALUES (?,?,?)", array( $this->Guid, (int) $achievement, (int) $date ));
            }

            $total_logros++;
        }
        $total_montus = 0;
        $total_compas = 0;
        foreach( $this->json["creature"] as $key => $SpellID ) 
        {
            if( hasPermission("manage") ) 
            {
                if( in_array($this->cBuild, array( 18414, 17898, 17688, 17658, 17538, 17359, 17128, 16981, 16826, 16769, 16760, 16733, 16716, 16709, 16701, 16685, 16683, 16669, 16650, 16357, 16309, 16135 )) ) 
                {
                    $this->functions->LearnPandariaCreatures((int) $SpellID, $this->AccountId);
                }
                else
                {
                    $this->functions->LearnSeparateSpell((int) $SpellID, $this->Guid, $this->Realm);
                }

            }

            if( substr($key, 0, 1) == "M" ) 
            {
                $total_montus++;
            }

            if( substr($key, 0, 1) == "C" ) 
            {
                $total_compas++;
            }

        }
        if( !hasPermission("manage") ) 
        {
            echo "<h3>" . $this->config->item("txt21") . "</h3><hr><p></p>";
            echo "<div class =\"infouser_g\">";
            echo "<div class=\"infuser\">" . $this->config->item("txt22") . " " . $this->oCharName . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt23") . " " . ucwords(strtolower($this->CharClassName)) . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt24") . " " . ucwords(strtolower($this->CharRaceName)) . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt25") . " " . $this->CharLevel . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt26") . " " . $total_logros . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt27") . " " . $total_montus . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt28") . " " . $total_compas . "</div> ";
            echo "<br><br><div class=\"infuser\">" . $this->config->item("txt29") . " " . floor($this->CharMoney / 10000) . " <img src=\"" . $this->baseurl . "application/modules/migration/images/ui-goldicon.v7275.png\"></div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt30") . " " . $this->CharCValor . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt31") . " " . $this->CharCHonor . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt32") . " " . $this->CharCArena . "</div> ";
            echo "<div class=\"infuser\">" . $this->config->item("txt33") . " " . $this->CharCJustice . "</div> ";
            echo "</div><br><br>";
        }

    }

    function reputaciones()
    {
        if( !hasPermission("manage") ) 
        {
            echo "<h3>" . $this->config->item("txt34") . "</h3><hr><p></p>";
        }

        $dbchar = $this->realms->getRealm($this->Realm)->getCharacters()->getConnection();
        foreach( $this->json["rep"] as $key => $value ) 
        {
            $reputation = $value["V"];
            if( hasPermission("manage") ) 
            {
                $faction = $this->functions->GetFactionID(mb_strtoupper($value["N"], "UTF-8"), $this->cLocale);
                if( $faction < 1 || $reputation < 1 ) 
                {
                    continue;
                }

                $flag = $value["F"] + 1;
                $this->functions->SonsOfHordirTransfer($this->Guid, $this->Realm);
                $dbchar->query("INSERT IGNORE INTO `character_reputation` VALUES (?,?,?,?);", array( $this->Guid, $faction, (int) $reputation, (int) $flag ));
            }
            else
            {
                $faction = $value["N"];
                if( 0 < $reputation ) 
                {
                    echo "<div style=\" text-align: center; padding: 5px 5px 5px 5px; background: rgba(90,90,90,0.1); border-radius: 5px; border: 1px solid #333; margin-bottom: 10px;\">" . $faction . " " . $reputation . " / <b>42999</b></div>";
                }

            }

        }
    }

    function getinventoryf()
    {
        if( !hasPermission("manage") ) 
        {
            echo "<h3>" . $this->config->item("txt35") . "</h3><hr><p></p>";
            echo "<div class=\"items-content-data\">";
        }

        foreach( $this->json["inventory"] as $key => $value ) 
        {
            $item = $this->functions->_GetChangedItem($this->Realm, $value["I"]);
            $count = $this->functions->CheckItemCount($value["C"]);
            if( $item != "-1" ) 
            {
                $this->InventarioRow .= $item . ":" . $count . " ";
                if( !hasPermission("manage") ) 
                {
                    $icon = $this->functions->getIconName($item, $this->Realm);
                    echo "<div class=\"items_img\"><a href=\"http://wotlk.openwow.com/item=" . $item . "\"><img src=\"http://wow.zamimg.com/images/wow/icons/large/" . $icon . ".jpg\"></a><span>x" . $count . "</span></div> ";
                }

            }

        }
        if( !hasPermission("manage") ) 
        {
            echo "</div>";
        }

    }

    function skillsLearn()
    {
        $dbchar = $this->realms->getRealm($this->Realm)->getCharacters()->getConnection();
        foreach( $this->json["skills"] as $key => $value ) 
        {
            $SkillName = mb_strtoupper($value["N"], "UTF-8");
            if( $this->functions->_CheckRiding($SkillName, $value["C"], $this->Realm, $this->Guid, $this->CharLevel) ) 
            {
                continue;
            }

            $SkillID = $this->functions->GetSkillID($SkillName, $this->cLocale);
            if( $SkillID < 1 ) 
            {
                continue;
            }

            $max = $this->functions->_MaxValue($this->functions->RemoveRaceBonus($this->CharRaceId, $SkillID, $value["M"]), 600);
            $cur = $this->functions->_MaxValue($this->functions->RemoveRaceBonus($this->CharRaceId, $SkillID, $value["C"]), 600);
            $SpellID = $this->functions->GetSpellIDForSkill($SkillID, $max);
            if( $this->functions->CheckExtraSpell($SkillID) ) 
            {
                $this->functions->LearnSeparateSpell($this->functions->GetExtraSpellForSkill($SkillID, $cur, $this->Guid, $this->Realm), $this->Guid, $this->Realm);
            }

            $dbchar->query("INSERT IGNORE INTO `character_skills` VALUES (?,?,?,?);", array( $this->Guid, (int) $SkillID, (int) $cur, (int) $max ));
            if( $SpellID < 3 ) 
            {
                continue;
            }

            $dbchar->query("INSERT IGNORE  INTO `character_spell` VALUES (?,?,1,0);", array( $this->Guid, (int) $SpellID ));
        }
    }

}


