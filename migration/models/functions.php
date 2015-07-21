<?php 

class Functions extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function _DECRYPT($STRING)
    {
        return strrev(base64_decode(strrev(strrev(base64_decode(strrev($STRING))))));
    }

    function _GetRaceID($race)
    {
        switch( $race ) 
        {
            case "HUMAN":
                return 1;
            case "ORC":
                return 2;
            case "DWARF":
                return 3;
            case "NIGHTELF":
                return 4;
            case "SCOURGE":
                return 5;
            case "TAUREN":
                return 6;
            case "GNOME":
                return 7;
            case "TROLL":
                return 8;
            case "BLOODELF":
                return 10;
            case "DRAENEI":
                return 11;
            default:
                exit( "error" );
        }
    }

    function _GetClassID($class)
    {
        switch( $class ) 
        {
            case "WARRIOR":
                return 1;
            case "PALADIN":
                return 2;
            case "HUNTER":
                return 3;
            case "ROGUE":
                return 4;
            case "PRIEST":
                return 5;
            case "DEATHKNIGHT":
                return 6;
            case "SHAMAN":
                return 7;
            case "MAGE":
                return 8;
            case "WARLOCK":
                return 9;
            case "DRUID":
                return 11;
            default:
                exit( "<br>YOUR CHARACTER CLASS IS NOT BLIZZLIKE FOR 3.3.5a<br>" );
        }
    }

    function cRealmInfo($realm)
    {
        $db = $this->external_account_model->getConnection();
        $query = $db->get_where("realmlist", array( "id" => $realm ), 1);
        $row = $query->row();
        return $row;
    }

    function _MaxValue($VALUE1, $VALUE2)
    {
        return $VALUE2 < $VALUE1 ? $VALUE2 : $VALUE1;
    }

    function MaxclBybuild($build = false)
    {
        if( in_array($build, array( 18414, 17898, 17688, 17658, 17538, 17359, 17128, 16981, 16826, 16769, 16760, 16733, 16716, 16709, 16701, 16685, 16683, 16669, 16650, 16357, 16309, 16135 )) ) 
        {
            return "90";
        }

        if( in_array($build, array( 15595, 13623, 14545 )) ) 
        {
            return "85";
        }

        if( in_array($build, array( 12340 )) ) 
        {
            return "80";
        }

        return "60";
    }

    function CHECKDAY($TIME1, $TIME2)
    {
        $DIFF = floor(($TIME1 - $TIME2) / 86400);
        return $DIFF;
    }

    function checkblackList($value, $value2)
    {
        $this->db->like("server", $value);
        $this->db->like("server", $value2);
        $query = $this->db->get("migration_blacklist");
        $this->db->close();
        if( 0 < $query->num_rows() ) 
        {
            return true;
        }

        return false;
    }

    function maxDk($realm, $account_id)
    {
        $db = $this->realms->getRealm($realm)->getCharacters()->getConnection();
        $q = $db->query("SELECT * FROM characters WHERE class = '6' AND account = ?", array( $account_id ));
        return $q->num_rows();
    }

    public function _GetChangedItem($REALMID, $ID)
    {
        if( $this->_CheckWrongOrNoItem($REALMID, $ID) ) 
        {
            return -1;
        }
		return $ID;
        /*foreach( $this->config->item("replace_items") as $key => $value ) 
        {
            if( $REALMID == $key ) 
            {
                foreach( {$this->config->item("replace_items")}[$key] as $i => $value ) 
                {
                    if( $ID == $i ) 
                    {
                        return $value["replace"];
                    }

                    return $ID;
                }
            }
            else
            {
                return $ID;
            }

        }*/
    }

    function _CheckWrongOrNoItem($REALMID, $ID)
    {
        foreach( $this->config->item("ignore_items") as $key => $value ) 
        {
            if( $key == $REALMID ) 
            {
                if( in_array($ID, $value) ) 
                {
                    return true;
                }

                return false;
            }

            return false;
        }
    }

    function CheckItemCount($count)
    {
        $count = $count < 1 ? 1 : $count;
        $count = 1000 < $count ? 1000 : $count;
        return $count;
    }

    function getIconName($item, $realm)
    {
        $cache = $this->cache->get("items/item_migration_display_" . $realm . "_" . $item);
        if( $cache ) 
        {
            return $cache;
        }

        $context = stream_context_create(array( "http" => array( "user_agent" => "Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.114 Safari/537.36" ) ));
        $xml = file_get_contents("http://es.wowhead.com/item=" . $item . "&xml", false, $context);
        if( empty($xml) ) 
        {
            $icon = "inv_misc_questionmark";
        }
        else
        {
            $itemData = $this->xmlToArray($xml);
            $icon = isset($itemData["item"]["icon"]) ? strtolower($itemData["item"]["icon"]) : "inv_misc_questionmark";
        }

        $this->cache->save("items/item_migration_display_" . $realm . "_" . $item, $icon);
        return $icon;
    }

    private function xmlToArray($xml)
    {
        $xml = simplexml_load_string($xml);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        return $array;
    }

    function getCurrencyId($cnameid, $locale)
    {
        switch( $locale ) 
        {
            case "FRFR":
                switch( $cnameid ) 
                {
                    case "RUNEFORGER":
                        return 776;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "DEDE":
                switch( $cnameid ) 
                {
                    case "RUNEN SCHMIEDEN":
                        return 776;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "RURU":
                switch( $cnameid ) 
                {
                    case "НАЧЕРТАНИЕ":
                        return 773;
                    case "КОВКА РУН":
                        return 776;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "ESES":
                switch( $cnameid ) 
                {
                    case "PUNTOS DE CONQUISTA":
                        return "390";
                    case "PUNTOS DE ARENA":
                        return "390";
                    case "PUNTOS DE HONOR":
                        return "392";
                    case "PUNTOS DE VALOR":
                        return "396";
                    case "EMBLEMA DE ESCARCHA":
                        return "396";
                    case "PUNTOS DE JUSTICIA":
                        return "395";
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "ENUS":
            case "ENGB":
                switch( $cnameid ) 
                {
                    case "ARENA POINTS":
                        return "390";
                    case "CONQUEST POINTS":
                        return "390";
                    case "HONOR POINTS":
                        return "392";
                    case "VALOR POINTS":
                        return "396";
                    case "EMBLEM OF FROST":
                        return "396";
                    case "JUSTICE POINTS":
                        return "395";
                    default:
                        return -1;
                }
        }
    }

    function getAccTrans()
    {
        if( hasPermission("manage") ) 
        {
            $query = $this->db->query("SELECT * FROM `migration_transfer` WHERE `cStatus` != 4 ORDER BY `id` DESC LIMIT 25;");
        }
        else
        {
            $query = $this->db->query("SELECT * FROM `migration_transfer` WHERE `cAccount` = ? ORDER BY `id` DESC LIMIT 10;", array( $this->user->getId() ));
        }

        $this->db->close();
        if( 0 < $query->num_rows() ) 
        {
            $row = $query->result_array();
            return $row;
        }

        return false;
    }

    function checkDump($dump)
    {
        $this->db->where("cDump", $dump);
        $q = $this->db->get("migration_transfer");
        if( 0 < $q->num_rows ) 
        {
            if( $q->row()->cStatus == 0 || $q->row()->cStatus == 2 || $q->row()->cStatus == 1 ) 
            {
                return true;
            }

        }
        else
        {
            return false;
        }

    }

    function UpdateDumpStatus($ID, $STATUS)
    {
        $Reason = $this->input->post("reason") ? $this->input->post("reason") : false;
        if( $Reason ) 
        {
            $this->db->query("UPDATE `migration_transfer` SET `cStatus` = ?, `gmAccount` = ?, `Reason` = ? WHERE `id` = ?", array( $STATUS, $this->user->getId(), ucfirst(strtolower($Reason)), $ID ));
        }
        else
        {
            $this->db->query("UPDATE `migration_transfer` SET `cStatus` = ?, `gmAccount` = ? WHERE `id` = ?", array( $STATUS, $this->user->getId(), $ID ));
        }

        $this->db->close();
    }

    function getTransfer($id)
    {
        $r = $this->db->query("Select * from migration_transfer where id = ?", array( $id ));
        $this->db->close();
        return $r->row();
    }

    function GetCharacterGuid($Realm, $AccountId)
    {
        $db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
        $query = $db->query("select max(`guid`) as max FROM `characters`");
        return $query->row()->max;
        //$db->close();
		//return 0;
    }

    function LearnSeparateSpell($SpellID, $Guid, $Realm)
    {
        $db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
        $db->query("INSERT IGNORE INTO `character_spell` VALUES (?, ?, 1, 0 )", array( $Guid, (int) $SpellID ));
        $db->close();
    }

    function DeathKnightTransfer($GUID, $CHAR_REALM)
    {
        $db = $this->realms->getRealm($CHAR_REALM)->getCharacters()->getConnection();
        return $db->query("INSERT INTO `character_queststatus_rewarded`(`guid`,`quest`) VALUES\r\n            (" . $GUID . ", 12593),   (" . $GUID . ", 12619),   (" . $GUID . ", 12641),   (" . $GUID . ", 12657),\r\n            (" . $GUID . ", 12670),   (" . $GUID . ", 12678),   (" . $GUID . ", 12679),   (" . $GUID . ", 12680),\r\n            (" . $GUID . ", 12687),   (" . $GUID . ", 12697),   (" . $GUID . ", 12698),   (" . $GUID . ", 12700),\r\n            (" . $GUID . ", 12701),   (" . $GUID . ", 12706),   (" . $GUID . ", 12711),   (" . $GUID . ", 12714),\r\n            (" . $GUID . ", 12715),   (" . $GUID . ", 12716),   (" . $GUID . ", 12717),   (" . $GUID . ", 12719),\r\n            (" . $GUID . ", 12720),   (" . $GUID . ", 12722),   (" . $GUID . ", 12723),   (" . $GUID . ", 12724),\r\n            (" . $GUID . ", 12725),   (" . $GUID . ", 12727),   (" . $GUID . ", 12733),   (" . $GUID . ", 12738),\r\n            (" . $GUID . ", 12747), /* RACE       */\r\n            (" . $GUID . ", 13189), /* HORDE      */\r\n            (" . $GUID . ", 13188), /* ALLIANCE   */\r\n            (" . $GUID . ", 12751),   (" . $GUID . ", 12754),   (" . $GUID . ", 12755),   (" . $GUID . ", 12756),\r\n            (" . $GUID . ", 12757),   (" . $GUID . ", 12778),   (" . $GUID . ", 12779),   (" . $GUID . ", 12800),\r\n            (" . $GUID . ", 12801),   (" . $GUID . ", 12842),   (" . $GUID . ", 12848),   (" . $GUID . ", 12849),\r\n            (" . $GUID . ", 12850),   (" . $GUID . ", 13165),   (" . $GUID . ", 13166);");
    }

    function checkhaveDK($Realm, $AccountId)
    {
        $chardb = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
        $query = $chardb->query("SELECT * FROM characters WHERE class = 6 AND account = ?;", array( $AccountId ));
        if( 0 < $query->num_rows() ) 
        {
            return true;
        }

        return false;
    }

    function GetFactionID($faction, $locale)
    {
        switch( $locale ) 
        {
            case "FRFR":
                switch( $faction ) 
                {
                    case "BAIE-DU-BUTIN":
                        return 21;
                    case "FORGEFER":
                        return 47;
                    case "EXILÉS DE GNOMEREGAN":
                        return 54;
                    case "CONFRÉRIE DU THORIUM":
                        return 59;
                    case "FOSSOYEUSE":
                        return 68;
                    case "DARNASSUS":
                        return 69;
                    case "SYNDICAT":
                        return 70;
                    case "HURLEVENT":
                        return 72;
                    case "ORGRIMMAR":
                        return 76;
                    case "LES PITONS DU TONNERRE":
                        return 81;
                    case "LA VOILE SANGLANTE":
                        return 87;
                    case "CENTAURES (GELKIS)":
                        return 92;
                    case "CENTAURES (MAGRAM)":
                        return 93;
                    case "TRIBU ZANDALAR":
                        return 270;
                    case "RAVENHOLDT":
                        return 349;
                    case "GADGETZAN":
                        return 369;
                    case "CABESTAN":
                        return 470;
                    case "LA LIGUE D'ARATHOR":
                        return 509;
                    case "LES PROFANATEURS":
                        return 510;
                    case "AUBE D'ARGENT":
                        return 529;
                    case "LES GRUMEGUEULES":
                        return 576;
                    case "LONG-GUET":
                        return 577;
                    case "ÉLEVEURS DE SABRES-D'HIVER":
                        return 589;
                    case "CERCLE CÉNARIEN":
                        return 609;
                    case "CLAN LOUP-DE-GIVRE":
                        return 729;
                    case "GARDE FOUDREPIQUE":
                        return 730;
                    case "LES HYDRAXIENS":
                        return 749;
                    case "SHEN'DRALAR":
                        return 809;
                    case "SENTINELLES D'AILE-ARGENT":
                        return 890;
                    case "FOIRE DE SOMBRELUNE":
                        return 909;
                    case "PROGÉNITURE DE NOZDORMU":
                        return 910;
                    case "LUNE-D'ARGENT":
                        return 911;
                    case "TRANQUILLIEN":
                        return 922;
                    case "EXODAR":
                        return 930;
                    case "L'ALDOR":
                        return 932;
                    case "LE CONSORTIUM":
                        return 933;
                    case "LES CLAIRVOYANTS":
                        return 934;
                    case "LES SHA'TAR":
                        return 935;
                    case "SHATTRATH":
                        return 936;
                    case "MAG'HAR":
                        return 941;
                    case "EXPÉDITION CÉNARIENNE":
                        return 942;
                    case "BASTION DE L'HONNEUR":
                        return 946;
                    case "THRALLMAR":
                        return 947;
                    case "L'ŒIL POURPRE":
                        return 967;
                    case "SPOREGGAR":
                        return 970;
                    case "KURENAÏ":
                        return 978;
                    case "GARDIENS DU TEMPS":
                        return 989;
                    case "LA BALANCE DES SABLES":
                        return 990;
                    case "VILLE BASSE":
                        return 1011;
                    case "LIGEMORT CENDRELANGUE":
                        return 1012;
                    case "AILE-DU-NÉANT":
                        return 1015;
                    case "GARDE-CIEL SHA'TARI":
                        return 1031;
                    case "AVANT-GARDE DE L'ALLIANCE":
                        return 1037;
                    case "OGRI'LA":
                        return 1038;
                    case "EXPÉDITION DE LA BRAVOURE":
                        return 1050;
                    case "EXPÉDITION DE LA HORDE":
                        return 1052;
                    case "LES TAUNKAS":
                        return 1064;
                    case "LA MAIN DE LA VENGEANCE":
                        return 1067;
                    case "LIGUE DES EXPLORATEURS":
                        return 1068;
                    case "LES KALU'AKS":
                        return 1073;
                    case "OPÉRATION SOLEIL BRISÉ":
                        return 1077;
                    case "OFFENSIVE CHANTEGUERRE":
                        return 1085;
                    case "KIRIN TOR":
                        return 1090;
                    case "L'ACCORD DU REPOS DU VER":
                        return 1091;
                    case "LE CONCORDAT ARGENTÉ":
                        return 1094;
                    case "CHEVALIERS DE LA LAME D'ÉBÈNE":
                        return 1098;
                    case "TRIBU FRÉNÉCŒUR":
                        return 1104;
                    case "LES ORACLES":
                        return 1105;
                    case "LA CROISADE D'ARGENT":
                        return 1106;
                    case "LES FILS DE HODIR":
                        return 1119;
                    case "LES SACCAGE-SOLEIL":
                        return 1124;
                    case "LES GIVRE-NÉS":
                        return 1126;
                    case "HAIT TOUT ET TOUT LE MONDE":
                        return 1145;
                    case "LE VERDICT DES CENDRES":
                        return 1156;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "DEDE":
                switch( $faction ) 
                {
                    case "BEUTEBUCHT":
                        return 21;
                    case "EISENSCHMIEDE":
                        return 47;
                    case "GNOMEREGANGNOME":
                        return 54;
                    case "THORIUMBRUDERSCHAFT":
                        return 59;
                    case "UNTERSTADT":
                        return 68;
                    case "DARNASSUS":
                        return 69;
                    case "SYNDIKAT":
                        return 70;
                    case "STURMWIND":
                        return 72;
                    case "ORGRIMMAR":
                        return 76;
                    case "DONNERFELS":
                        return 81;
                    case "BLUTSEGELBUKANIERE":
                        return 87;
                    case "GELKISKLAN":
                        return 92;
                    case "MAGRAMKLAN":
                        return 93;
                    case "STAMM DER ZANDALARI":
                        return 270;
                    case "RABENHOLDT":
                        return 349;
                    case "GADGETZAN":
                        return 369;
                    case "RATSCHET":
                        return 470;
                    case "DER BUND VON ARATHOR":
                        return 509;
                    case "DIE ENTWEIHTEN":
                        return 510;
                    case "ARGENTUMDÄMMERUNG":
                        return 529;
                    case "HOLZSCHLUNDFESTE":
                        return 576;
                    case "EWIGE WARTE":
                        return 577;
                    case "WINTERSÄBLERAUSBILDER":
                        return 589;
                    case "ZIRKEL DES CENARIUS":
                        return 609;
                    case "FROSTWOLFKLAN":
                        return 729;
                    case "STURMLANZENGARDE":
                        return 730;
                    case "HYDRAXIANER":
                        return 749;
                    case "SHEN'DRALAR":
                        return 809;
                    case "SILBERSCHWINGEN":
                        return 890;
                    case "DUNKELMOND-JAHRMARKT":
                        return 909;
                    case "BRUT NOZDORMUS":
                        return 910;
                    case "SILBERMOND":
                        return 911;
                    case "TRISTESSA":
                        return 922;
                    case "DIE EXODAR":
                        return 930;
                    case "DIE ALDOR":
                        return 932;
                    case "DAS KONSORTIUM":
                        return 933;
                    case "DIE SEHER":
                        return 934;
                    case "DIE SHA'TAR":
                        return 935;
                    case "SHATTRATH":
                        return 936;
                    case "DIE MAG'HAR":
                        return 941;
                    case "EXPEDITION DES CENARIUS":
                        return 942;
                    case "EHRENFESTE":
                        return 946;
                    case "THRALLMAR":
                        return 947;
                    case "DAS VIOLETTE AUGE":
                        return 967;
                    case "SPOREGGAR":
                        return 970;
                    case "KURENAI":
                        return 978;
                    case "HÜTER DER ZEIT":
                        return 989;
                    case "DIE WÄCHTER DER SANDE":
                        return 990;
                    case "UNTERES VIERTEL":
                        return 1011;
                    case "DIE TODESHÖRIGEN":
                        return 1012;
                    case "NETHERSCHWINGEN":
                        return 1015;
                    case "HIMMELSWACHE DER SHA'TARI":
                        return 1031;
                    case "VORPOSTEN DER ALLIANZ":
                        return 1037;
                    case "OGRI'LA":
                        return 1038;
                    case "EXPEDITION VALIANZ":
                        return 1050;
                    case "EXPEDITION DER HORDE":
                        return 1052;
                    case "DIE TAUNKA":
                        return 1064;
                    case "DIE HAND DER RACHE":
                        return 1067;
                    case "FORSCHERLIGA":
                        return 1068;
                    case "DIE KALU'AK":
                        return 1073;
                    case "OFFENSIVE DER ZERSCHMETTERTEN SONNE":
                        return 1077;
                    case "KRIEGSHYMNENOFFENSIVE":
                        return 1085;
                    case "KIRIN TOR":
                        return 1090;
                    case "DER WYRMRUHPAKT":
                        return 1091;
                    case "DER SILBERBUND":
                        return 1094;
                    case "RITTER DER SCHWARZEN KLINGE":
                        return 1098;
                    case "STAMM DER WILDHERZEN":
                        return 1104;
                    case "DIE ORAKEL":
                        return 1105;
                    case "ARGENTUMKREUZZUG":
                        return 1106;
                    case "DIE SÖHNE HODIRS":
                        return 1119;
                    case "DIE SONNENHÄSCHER":
                        return 1124;
                    case "DIE FROSTERBEN":
                        return 1126;
                    case "HASST ALLES":
                        return 1145;
                    case "DAS ÄSCHERNE VERDIKT":
                        return 1156;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "ESMX":
            case "ESES":
                switch( $faction ) 
                {
                    case "BAHÍA DEL BOTÍN":
                        return 21;
                    case "FORJAZ":
                        return 47;
                    case "EXILIADOS DE GNOMEREGAN":
                        return 54;
                    case "HERMANDAD DEL TORIO":
                        return 59;
                    case "ENTRAÑAS":
                        return 68;
                    case "DARNASSUS":
                        return 69;
                    case "LA HERMANDAD":
                        return 70;
                    case "VENTORMENTA":
                        return 72;
                    case "ORGRIMMAR":
                        return 76;
                    case "CIMA DEL TRUENO":
                        return 81;
                    case "BUCANEROS VELASANGRE":
                        return 87;
                    case "CENTAUROS DEL CLAN GELKIS":
                        return 92;
                    case "CENTAUROS DEL CLAN MAGRAM":
                        return 93;
                    case "TRIBU ZANDALAR":
                        return 270;
                    case "RAVENHOLDT":
                        return 349;
                    case "TRINQUETE":
                        return 470;
                    case "LIGA DE ARATHOR":
                        return 509;
                    case "LOS RAPIÑADORES":
                        return 510;
                    case "EL ALBA ARGENTA":
                        return 529;
                    case "TROLS LANZA NEGRA":
                        return 530;
                    case "BASTIÓN FAUCES DE MADERA":
                        return 576;
                    case "VISTA ETERNA":
                        return 577;
                    case "INSTRUCTORES DE SABLEINVERNALES":
                        return 589;
                    case "CÍRCULO CENARION":
                        return 609;
                    case "CLAN LOBO GÉLIDO":
                        return 729;
                    case "GUARDIA PICO TORMENTA":
                        return 730;
                    case "SRS. DEL AGUA DE HYDRAXIS":
                        return 749;
                    case "SEÑORES DEL FUEGO DE SULFURON":
                        return 750;
                    case "SHEN'DRALAR":
                        return 809;
                    case "CENTINELAS ALA DE PLATA":
                        return 890;
                    case "FERIA DE LA LUNA NEGRA":
                        return 909;
                    case "LINAJE DE NOZDORMU":
                        return 910;
                    case "CIUDAD DE LUNARGENTA":
                        return 911;
                    case "TRANQUILLIEN":
                        return 922;
                    case "EL EXODAR":
                        return 930;
                    case "LOS ALDOR":
                        return 932;
                    case "EL CONSORCIO":
                        return 933;
                    case "LOS ARÚSPICES":
                        return 934;
                    case "LOS SHA'TAR":
                        return 935;
                    case "CIUDAD DE SHATTRATH":
                        return 936;
                    case "LOS MAG'HAR":
                        return 941;
                    case "EXPEDICIÓN CENARION":
                        return 942;
                    case "BASTIÓN DEL HONOR":
                        return 946;
                    case "THRALLMAR":
                        return 947;
                    case "EL OJO VIOLETA":
                        return 967;
                    case "ESPORAGGAR":
                        return 970;
                    case "KURENAI":
                        return 978;
                    case "VIGILANTES DEL TIEMPO":
                        return 989;
                    case "LA ESCAMA DE LAS ARENAS":
                        return 990;
                    case "BAJO ARRABAL":
                        return 1011;
                    case "JURAMORTE LENGUA DE CENIZA":
                        return 1012;
                    case "ALA ABISAL":
                        return 1015;
                    case "GUARDIA DEL CIELO SHA'TARI":
                        return 1031;
                    case "VANGUARDIA DE LA ALIANZA":
                        return 1037;
                    case "OGRI'LA":
                        return 1038;
                    case "EXPEDICIÓN DE DENUEDO":
                        return 1050;
                    case "EXPEDICIÓN DE LA HORDA":
                        return 1052;
                    case "LOS TAUNKA":
                        return 1064;
                    case "LA MANO DE LA VENGANZA":
                        return 1067;
                    case "LIGA DE EXPEDICIONARIOS":
                        return 1068;
                    case "LOS KALU'AK":
                        return 1073;
                    case "OFENSIVA SOL DEVASTADO":
                        return 1077;
                    case "OFENSIVA GRITO DE GUERRA":
                        return 1085;
                    case "KIRIN TOR":
                        return 1090;
                    case "EL ACUERDO DEL REPOSO DEL DRAGÓN":
                        return 1091;
                    case "EL PACTO DE PLATA":
                        return 1094;
                    case "CABALLEROS DE LA ESPADA DE ÉBANO":
                        return 1098;
                    case "TRIBU CORAZÓN FRENÉTICO":
                        return 1104;
                    case "LOS ORÁCULOS":
                        return 1105;
                    case "CRUZADA ARGENTA":
                        return 1106;
                    case "LOS HIJOS DE HODIR":
                        return 1119;
                    case "LOS ATRACASOL":
                        return 1124;
                    case "LOS NATOESCARCHA":
                        return 1126;
                    case "ODIA TODO":
                        return 1145;
                    case "EL VEREDICTO CINÉREO":
                        return 1156;
                    case "CELADORES DE BARADIN":
                        return 1177;
                    case "CLAN FAUCEDRACO":
                        return 1172;
                    case "CLAN MARTILLO SALVAJE":
                        return 1174;
                    case "EL ANILLO DE LA TIERRA":
                        return 1135;
                    case "GUARDIANES DE HYJAL":
                        return 1158;
                    case "MANDO GRITO INFERNAL":
                        return 1178;
                    case "RAMKAHEN":
                        return 1173;
                    case "THERAZANE":
                        return 1171;
                    case "VENGADORES DE HYJAL":
                        return 1204;
                    case "CHEE CHEE":
                        return 1277;
                    case "ELLA":
                        return 1275;
                    case "GINA ZARPA FANGOSA":
                        return 1281;
                    case "GRANJERO FUNG":
                        return 1283;
                    case "HAOHAN ZARPA FANGOSA":
                        return 1279;
                    case "JOGU EL EBRIO":
                        return 1273;
                    case "PEZ JUNCO TALADO":
                        return 1282;
                    case "SHO":
                        return 1278;
                    case "TINA ZARPA FANGOSA":
                        return 1280;
                    case "VIEJO ZARPA COLLADO":
                        return 1276;
                    case "LOS LABRADORES":
                        return 1272;
                    case "NAT PAGLE":
                        return 1358;
                    case "LOS PESCADORES":
                        return 1302;
                    case "ACADEMIA DE SHANG XI":
                        return 1216;
                    case "ASALTO DEL SHADOPAN":
                        return 1435;
                    case "EL PRÍNCIPE NEGRO":
                        return 1359;
                    case "EMBATE DE LOS ATRACASOL":
                        return 1388;
                    case "EMPERADOR SHAOHAO":
                        return 1492;
                    case "HOZEN DEL BOSQUE":
                        return 1228;
                    case "JINJU ALETA DE NÁCAR":
                        return 1242;
                    case "LOS AUGUSTOS CELESTIALES":
                        return 1341;
                    case "LOS EREMITAS":
                        return 1345;
                    case "LOS KLAXXI":
                        return 1337;
                    case "LOS MAESTROS CERVECEROS":
                        return 1351;
                    case "LOTO DORADO":
                        return 1269;
                    case "MURO DE ESCUDOS":
                        return 1376;
                    case "OFENSIVA DE DOMINANCIA":
                        return 1375;
                    case "OFENSIVA DEL KIRIN TOR":
                        return 1387;
                    case "ORDEN DEL DRAGÓN NIMBO":
                        return 1271;
                    case "REBELIÓN LANZA NEGRA":
                        return 1440;
                    case "SHADOPAN":
                        return 1270;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "RURU":
                switch( $faction ) 
                {
                    case "ПИРАТСКАЯ БУХТА":
                        return 21;
                    case "СТАЛЬГОРН":
                        return 47;
                    case "ИЗГНАННИКИ ГНОМРЕГАНА":
                        return 54;
                    case "БРАТСТВО ТОРИЯ":
                        return 59;
                    case "ПОДГОРОД":
                        return 68;
                    case "ДАРНАС":
                        return 69;
                    case "СИНДИКАТ":
                        return 70;
                    case "ШТОРМГРАД":
                        return 72;
                    case "ОРГРИММАР":
                        return 76;
                    case "ГРОМОВОЙ УТЕС":
                        return 81;
                    case "ПИРАТЫ КРОВАВОГО ПАРУСА":
                        return 87;
                    case "КЕНТАВРЫ ИЗ ПЛЕМЕНИ ГЕЛКИС":
                        return 92;
                    case "КЕНТАВРЫ ИЗ ПЛЕМЕНИ МАГРАМ":
                        return 93;
                    case "ПЛЕМЯ ЗАНДАЛАР":
                        return 270;
                    case "ЧЕРНЫЙ ВОРОН":
                        return 349;
                    case "ПРИБАМБАССК":
                        return 369;
                    case "КАБЕСТАН":
                        return 470;
                    case "ЛИГА АРАТОРА":
                        return 509;
                    case "ОСКВЕРНИТЕЛИ":
                        return 510;
                    case "СЕРЕБРЯНЫЙ РАССВЕТ":
                        return 529;
                    case "ТРОЛЛИ ЧЕРНОГО КОПЬЯ":
                        return 530;
                    case "ДРЕВОБРЮХИ":
                        return 576;
                    case "КРУГОВЗОР":
                        return 577;
                    case "УКРОТИТЕЛИ ЛЕДОПАРДОВ":
                        return 589;
                    case "КРУГ КЕНАРИЯ":
                        return 609;
                    case "КЛАН СЕВЕРНОГО ВОЛКА":
                        return 729;
                    case "СТРАЖА ГРОЗОВОЙ ВЕРШИНЫ":
                        return 730;
                    case "ГИДРАКСИАНСКИЕ ПОВЕЛИТЕЛИ ВОД":
                        return 749;
                    case "ШЕН'ДРАЛАР":
                        return 809;
                    case "СРЕБРОКРЫЛЫЕ ЧАСОВЫЕ":
                        return 890;
                    case "ЯРМАРКА НОВОЛУНИЯ":
                        return 909;
                    case "РОД НОЗДОРМУ":
                        return 910;
                    case "ЛУНОСВЕТ":
                        return 911;
                    case "ТРАНКВИЛЛИОН":
                        return 922;
                    case "ЭКЗОДАР":
                        return 930;
                    case "АЛДОРЫ":
                        return 932;
                    case "КОНСОРЦИУМ":
                        return 933;
                    case "ПРОВИДЦЫ":
                        return 934;
                    case "ША'ТАР":
                        return 935;
                    case "ГОРОД ШАТТРАТ":
                        return 936;
                    case "МАГ'ХАРЫ":
                        return 941;
                    case "КЕНАРИЙСКАЯ ЭКСПЕДИЦИЯ":
                        return 942;
                    case "ОПЛОТ ЧЕСТИ":
                        return 946;
                    case "ТРАЛЛМАР":
                        return 947;
                    case "АМЕТИСТОВОЕ ОКО":
                        return 967;
                    case "СПОРЕГГАР":
                        return 970;
                    case "КУРЕНАЙ":
                        return 978;
                    case "ХРАНИТЕЛИ ВРЕМЕНИ":
                        return 989;
                    case "ПЕСЧАНАЯ ЧЕШУЯ":
                        return 990;
                    case "НИЖНИЙ ГОРОД":
                        return 1011;
                    case "ПЕПЛОУСТЫ-СЛУЖИТЕЛИ":
                        return 1012;
                    case "КРЫЛЬЯ ПУСТОТЫ":
                        return 1015;
                    case "СТРАЖИ НЕБЕС ША'ТАР":
                        return 1031;
                    case "АВАНГАРД АЛЬЯНСА":
                        return 1037;
                    case "ОГРИ'ЛА":
                        return 1038;
                    case "ЭКСПЕДИЦИЯ ОТВАЖНЫХ":
                        return 1050;
                    case "ЭКСПЕДИЦИЯ ОРДЫ":
                        return 1052;
                    case "ТАУНКА":
                        return 1064;
                    case "КАРАЮЩАЯ ДЛАНЬ":
                        return 1067;
                    case "ЛИГА ИССЛЕДОВАТЕЛЕЙ":
                        return 1068;
                    case "КАЛУ'АК":
                        return 1073;
                    case "АРМИЯ РАСКОЛОТОГО СОЛНЦА":
                        return 1077;
                    case "АРМИЯ ПЕСНИ ВОЙНЫ":
                        return 1085;
                    case "КИРИН-ТОР":
                        return 1090;
                    case "ДРАКОНИЙ СОЮЗ":
                        return 1091;
                    case "СЕРЕБРЯНЫЙ СОЮЗ":
                        return 1094;
                    case "РЫЦАРИ ЧЕРНОГО КЛИНКА":
                        return 1098;
                    case "ПЛЕМЯ БЕШЕНОГО СЕРДЦА":
                        return 1104;
                    case "ОРАКУЛЫ":
                        return 1105;
                    case "СЕРЕБРЯНЫЙ АВАНГАРД":
                        return 1106;
                    case "СЫНЫ ХОДИРА":
                        return 1119;
                    case "ПОХИТИТЕЛИ СОЛНЦА":
                        return 1124;
                    case "ЗИМОРОЖДЕННЫЕ":
                        return 1126;
                    case "НЕНАВИДИТ ВСЕ":
                        return 1145;
                    case "ПЕПЕЛЬНЫЙ СОЮЗ":
                        return 1156;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "ENUS":
            case "ENGB":
                switch( $faction ) 
                {
                    case "BOOTY BAY":
                        return 21;
                    case "IRONFORGE":
                        return 47;
                    case "GNOMEREGAN EXILES":
                        return 54;
                    case "THORIUM BROTHERHOOD":
                        return 59;
                    case "UNDERCITY":
                        return 68;
                    case "DARNASSUS":
                        return 69;
                    case "SYNDICATE":
                        return 70;
                    case "STORMWIND":
                        return 72;
                    case "ORGRIMMAR":
                        return 76;
                    case "THUNDER BLUFF":
                        return 81;
                    case "BLOODSAIL BUCCANEERS":
                        return 87;
                    case "GELKIS CLAN CENTAUR":
                        return 92;
                    case "MAGRAM CLAN CENTAUR":
                        return 93;
                    case "ZANDALAR TRIBE":
                        return 270;
                    case "RAVENHOLDT":
                        return 349;
                    case "GADGETZAN":
                        return 369;
                    case "RATCHET":
                        return 470;
                    case "THE LEAGUE OF ARATHOR":
                        return 509;
                    case "THE DEFILERS":
                        return 510;
                    case "ARGENT DAWN":
                        return 529;
                    case "DARKSPEAR TROLLS":
                        return 530;
                    case "TIMBERMAW HOLD":
                        return 576;
                    case "WINTERSABER TRAINERS":
                        return 589;
                    case "CENARION CIRCLE":
                        return 609;
                    case "FROSTWOLF CLAN":
                        return 729;
                    case "STORMPIKE GUARD":
                        return 730;
                    case "HYDRAXIAN WATERLORDS":
                        return 749;
                    case "SHEN'DRALAR":
                        return 809;
                    case "SILVERWING SENTINELS":
                        return 890;
                    case "DARKMOON FAIRE":
                        return 909;
                    case "BROOD OF NOZDORMU":
                        return 910;
                    case "SILVERMOON CITY":
                        return 911;
                    case "TRANQUILLIEN":
                        return 922;
                    case "EXODAR":
                        return 930;
                    case "THE ALDOR":
                        return 932;
                    case "THE CONSORTIUM":
                        return 933;
                    case "THE SCRYERS":
                        return 934;
                    case "THE SHA'TAR":
                        return 935;
                    case "SHATTRATH CITY":
                        return 936;
                    case "THE MAG'HAR":
                        return 941;
                    case "CENARION EXPEDITION":
                        return 942;
                    case "THE VIOLET EYE":
                        return 967;
                    case "SPOREGGAR":
                        return 970;
                    case "KURENAI":
                        return 978;
                    case "KEEPERS OF TIME":
                        return 989;
                    case "THE SCALE OF THE SANDS":
                        return 990;
                    case "LOWER CITY":
                        return 1011;
                    case "ASHTONGUE DEATHSWORN":
                        return 1012;
                    case "NETHERWING":
                        return 1015;
                    case "SHA'TARI SKYGUARD":
                        return 1031;
                    case "ALLIANCE VANGUARD":
                        return 1037;
                    case "OGRI'LA":
                        return 1038;
                    case "VALIANCE EXPEDITION":
                        return 1050;
                    case "HORDE EXPEDITION":
                        return 1052;
                    case "THE TAUNKA":
                        return 1064;
                    case "THE HAND OF VENGEANCE":
                        return 1067;
                    case "EXPLORERS' LEAGUE":
                        return 1068;
                    case "THE KALU'AK":
                        return 1073;
                    case "SHATTERED SUN OFFENSIVE":
                        return 1077;
                    case "WARSONG OFFENSIVE":
                        return 1085;
                    case "KIRIN TOR":
                        return 1090;
                    case "THE WYRMREST ACCORD":
                        return 1091;
                    case "THE SILVER COVENANT":
                        return 1094;
                    case "KNIGHTS OF THE EBON BLADE":
                        return 1098;
                    case "FRENZYHEART TRIBE":
                        return 1104;
                    case "THE ORACLES":
                        return 1105;
                    case "ARGENT CRUSADE":
                        return 1106;
                    case "THE SONS OF HODIR":
                        return 1119;
                    case "THE SUNREAVERS":
                        return 1124;
                    case "THE FROSTBORN":
                        return 1126;
                    case "HATES EVERYTHING":
                        return 1145;
                    case "THE ASHEN VERDICT":
                        return 1156;
                    case "AVENGERS OF HYJAL":
                        return 1204;
                    case "BARADIN'S WARDENS":
                        return 1177;
                    case "DRAGONMAW CLAN":
                        return 1172;
                    case "GUARDIANS OF HYJAL":
                        return 1158;
                    case "HELLSCREAM'S REACH":
                        return 1178;
                    case "RAMKAHEN":
                        return 1173;
                    case "THE EARTHEN RING":
                        return 1135;
                    case "THERAZANE":
                        return 1171;
                    case "WILDHAMMER CLAN":
                        return 1174;
                    case "CHEE CHEE":
                        return 1277;
                    case "DARKSPEAR REBELLION":
                        return 1440;
                    case "DOMINANCE OFFENSIVE":
                        return 1375;
                    case "ELLA":
                        return 1275;
                    case "EMPEROR SHAOHAO":
                        return 1492;
                    case "FARMER FUNG":
                        return 1283;
                    case "FISH FELLREED":
                        return 1282;
                    case "FOREST HOZEN":
                        return 1228;
                    case "GINA MUDCLAW":
                        return 1281;
                    case "GOLDEN LOTUS":
                        return 1269;
                    case "HAOHAN MUDCLAW":
                        return 1279;
                    case "JOGU THE DRUNK":
                        return 1273;
                    case "KIRIN TOR OFFENSIVE":
                        return 1387;
                    case "NAT PAGLE":
                        return 1358;
                    case "OLD HILLPAW":
                        return 1276;
                    case "OPERATION: SHIELDWALL":
                        return 1376;
                    case "ORDER OF THE CLOUD SERPENT":
                        return 1271;
                    case "PEARLFIN JINYU":
                        return 1242;
                    case "SHADO-PAN":
                        return 1270;
                    case "SHADO-PAN ASSAULT":
                        return 1435;
                    case "SHANG XI'S ACADEMY":
                        return 1216;
                    case "SHO":
                        return 1278;
                    case "SUNREAVER ONSLAUGHT":
                        return 1388;
                    case "THE ANGLERS":
                        return 1302;
                    case "THE AUGUST CELESTIALS":
                        return 1341;
                    case "THE BLACK PRINCE":
                        return 1359;
                    case "THE BREWMASTERS":
                        return 1351;
                    case "THE KAXXI":
                        return 1337;
                    case "THE LOREWALKERS":
                        return 1345;
                    case "THE TILLERS":
                        return 1272;
                    case "TINA MUDCLAW":
                        return 1280;
                    default:
                        return -1;
                }
        }
    }

    function SonsOfHordirTransfer($GUID, $CHAR_REALM)
    {
        $db = $this->realms->getRealm($CHAR_REALM)->getCharacters()->getConnection();
        return $db->query("INSERT INTO `character_queststatus_rewarded`(`guid`,`quest`) VALUES\r\n            (" . $GUID . ", 12841),   (" . $GUID . ", 12843),   (" . $GUID . ", 12846),   (" . $GUID . ", 12851),\r\n            (" . $GUID . ", 12856),   (" . $GUID . ", 12886),   (" . $GUID . ", 12900),   (" . $GUID . ", 12905),\r\n            (" . $GUID . ", 12906),   (" . $GUID . ", 12907),   (" . $GUID . ", 12908),   (" . $GUID . ", 12915),\r\n            (" . $GUID . ", 12921),   (" . $GUID . ", 12924),   (" . $GUID . ", 12969),   (" . $GUID . ", 12970),\r\n            (" . $GUID . ", 12971),   (" . $GUID . ", 12972),   (" . $GUID . ", 12983),   (" . $GUID . ", 12996),\r\n            (" . $GUID . ", 12997),   (" . $GUID . ", 13061),   (" . $GUID . ", 13062),   (" . $GUID . ", 13063),\r\n            (" . $GUID . ", 13064);");
    }
	
    function LevelUpTo80($CHAR_REALM, $CHAR_NAME)
    {
		$this->realms->getRealm($CHAR_REALM)->getEmulator()->send("char level " . $CHAR_NAME . " 80");
    }
	

    function sendMails($row, $CHAR_NAME, $CHAR_REALM)
    {
		$this->realms->getRealm($CHAR_REALM)->getEmulator()->send("char level " . $CHAR_NAME . " 80");
        $msj = $this->config->item("mail_content");
        $titulo = $this->config->item("mail_title");
        $item_array = explode(" ", trim($row));
        $by10 = 10;
        $toSend = "";
        $needSend = count($item_array);
        for( $i = 0; $i < count($item_array); $i++ ) 
        {
            $toSend .= $item_array[$i];
            $toSend .= " ";
            if( $by10 == 10 ) 
            {
                $this->realms->getRealm($CHAR_REALM)->getEmulator()->send("send items " . $CHAR_NAME . " \"" . $titulo . "\" \" " . $msj . " \" " . $toSend . "");
                $needSend = $needSend - $by10;
                $by10 = 1;
                $toSend = "";
            }
            else
            {
                if( $needSend - $by10 == 0 ) 
                {
                    $this->realms->getRealm($CHAR_REALM)->getEmulator()->send("send items " . $CHAR_NAME . " \"" . $titulo . "\" \" " . $msj . " \" " . $toSend . "");
                    $toSend = "";
                }
                else
                { 
                    $by10++;
                }

            }

        }
    }
	
    function getCharacterRaceId($Realm, $Guid, $AccountId)
    {
        $db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
        $q = $db->query("SELECT race FROM characters WHERE guid = $Guid and account = $AccountId");
		return $q->row()->race;
    }

    function getCharacterClassId($Realm, $Guid, $AccountId)
    {
        $db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
        $q = $db->query("SELECT class FROM characters WHERE guid = $Guid and account = $AccountId");
		return $q->row()->class;
    }


    function getCharacterName($Realm, $Guid, $AccountId)
    {
        $db = $this->realms->getRealm($Realm)->getCharacters()->getConnection();
        $q = $db->query("SELECT name FROM characters WHERE guid = $Guid and account = $AccountId");
		return $q->row()->name;
    }

    function _CheckMail($CharactersDB, $GUID)
    {
        $db = $this->realms->getRealm($CharactersDB)->getCharacters()->getConnection();
        $query = $db->query("SELECT * FROM mail WHERE subject = ? AND receiver = ?;", array( $this->config->item("mail_title"), $GUID ));
        $db->close();
        return $query->num_rows();
    }

    function teleport($realm, $name, $race)
    {
        $zoneA = "stormwind";
        $zoneH = "orgrimmar";
        if( in_array($race, array( 25, 1, 22, 7, 3, 4, 11 )) ) 
        {
            $this->realms->getRealm($realm)->getEmulator()->send("tele name " . $name . " " . $zoneA);
        }
        else
        {
            if( in_array($race, array( 10, 8, 6, 26, 2, 9, 5 )) ) 
            {
                $this->realms->getRealm($realm)->getEmulator()->send("tele name " . $name . " " . $zoneH);
            }

        }

    }

    function _TalentsReset($CHAR_REALM, $GUID)
    {
        $db = $this->realms->getRealm($CHAR_REALM)->getCharacters()->getConnection();
        $query = $db->query("UPDATE `characters` SET `at_login` = '13' WHERE `guid` = ?", array( $GUID ));
        $db->close();
    }

    function isSpellValid($SpellID, $ClassID)
    {
        if( $this->_isClassSpellValid($SpellID, $ClassID) ) 
        {
            return true;
        }

        if( $this->_isProfessionSpell($SpellID) ) 
        {
            return true;
        }

        return false;
    }

    function _isClassSpellValid($SpellID, $ClassID)
    {
        switch( $ClassID ) 
        {
            case 1:
                switch( $SpellID ) 
                {
                    case 768:
                    case 770:
                    case 783:
                    case 1066:
                    case 2782:
                    case 2893:
                    case 5209:
                    case 5215:
                    case 5225:
                    case 5229:
                    case 6795:
                    case 8983:
                    case 16081:
                    case 16835:
                    case 16857:
                    case 16860:
                    case 16864:
                    case 16931:
                    case 16935:
                    case 16941:
                    case 16944:
                    case 16949:
                    case 16975:
                    case 16979:
                    case 17007:
                    case 17051:
                    case 17061:
                    case 17070:
                    case 18658:
                    case 18990:
                    case 20719:
                    case 22812:
                    case 22842:
                    case 24242:
                    case 24866:
                    case 24894:
                    case 25953:
                    case 26054:
                    case 26055:
                    case 26056:
                    case 26995:
                    case 27004:
                    case 27006:
                    case 29166:
                    case 33357:
                    case 33786:
                    case 33867:
                    case 33873:
                    case 33878:
                    case 33917:
                    case 33943:
                    case 33957:
                    case 33982:
                    case 34300:
                    case 48378:
                    case 48412:
                    case 48441:
                    case 48443:
                    case 48447:
                    case 48451:
                    case 48461:
                    case 48463:
                    case 48465:
                    case 48467:
                    case 48469:
                    case 48470:
                    case 48477:
                    case 48479:
                    case 48491:
                    case 48495:
                    case 48559:
                    case 48561:
                    case 48567:
                    case 48570:
                    case 48571:
                    case 48573:
                    case 48576:
                    case 48578:
                    case 49376:
                    case 49377:
                    case 49799:
                    case 49802:
                    case 50212:
                    case 50334:
                    case 50464:
                    case 50763:
                    case 51269:
                    case 51412:
                    case 52610:
                    case 53307:
                    case 53308:
                    case 53312:
                    case 54753:
                    case 57881:
                    case 60119:
                    case 61336:
                    case 61467:
                    case 62078:
                    case 62600:
                    case 63503:
                    case 71:
                    case 72:
                    case 197:
                    case 200:
                    case 201:
                    case 202:
                    case 227:
                    case 264:
                    case 266:
                    case 355:
                    case 674:
                    case 676:
                    case 694:
                    case 750:
                    case 871:
                    case 1161:
                    case 1180:
                    case 1680:
                    case 1715:
                    case 1719:
                    case 2458:
                    case 2565:
                    case 2567:
                    case 2687:
                    case 3127:
                    case 3411:
                    case 5011:
                    case 5246:
                    case 6552:
                    case 7384:
                    case 7386:
                    case 7420:
                    case 7426:
                    case 7454:
                    case 7457:
                    case 7745:
                    case 7748:
                    case 7771:
                    case 7779:
                    case 7788:
                    case 7795:
                    case 7857:
                    case 7861:
                    case 7863:
                    case 11578:
                    case 12292:
                    case 12658:
                    case 12664:
                    case 12666:
                    case 12678:
                    case 12727:
                    case 12753:
                    case 12764:
                    case 12803:
                    case 12809:
                    case 12818:
                    case 12835:
                    case 12856:
                    case 12861:
                    case 12958:
                    case 12960:
                    case 12974:
                    case 12975:
                    case 13002:
                    case 13048:
                    case 13378:
                    case 13421:
                    case 13485:
                    case 13501:
                    case 13503:
                    case 13529:
                    case 13538:
                    case 13607:
                    case 13622:
                    case 13626:
                    case 13628:
                    case 13631:
                    case 13635:
                    case 13637:
                    case 13640:
                    case 13642:
                    case 13644:
                    case 13648:
                    case 13657:
                    case 13659:
                    case 13661:
                    case 13663:
                    case 13693:
                    case 13695:
                    case 13700:
                    case 13702:
                    case 13746:
                    case 13794:
                    case 13815:
                    case 13822:
                    case 13836:
                    case 13858:
                    case 13887:
                    case 13890:
                    case 13905:
                    case 13917:
                    case 13935:
                    case 13937:
                    case 13939:
                    case 13941:
                    case 13943:
                    case 13948:
                    case 14293:
                    case 14807:
                    case 14809:
                    case 14810:
                    case 15590:
                    case 16463:
                    case 16492:
                    case 16542:
                    case 17180:
                    case 17181:
                    case 18499:
                    case 20008:
                    case 20012:
                    case 20013:
                    case 20014:
                    case 20016:
                    case 20023:
                    case 20028:
                    case 20230:
                    case 20252:
                    case 23588:
                    case 23881:
                    case 23920:
                    case 27899:
                    case 27905:
                    case 27944:
                    case 27947:
                    case 27957:
                    case 27958:
                    case 27961:
                    case 28027:
                    case 28028:
                    case 29144:
                    case 29592:
                    case 29594:
                    case 29599:
                    case 29763:
                    case 29792:
                    case 29801:
                    case 29889:
                    case 32664:
                    case 32667:
                    case 33990:
                    case 33991:
                    case 33993:
                    case 33995:
                    case 33996:
                    case 34001:
                    case 34002:
                    case 34004:
                    case 34090:
                    case 34428:
                    case 42613:
                    case 42615:
                    case 44383:
                    case 44484:
                    case 44488:
                    case 44489:
                    case 44492:
                    case 44500:
                    case 44506:
                    case 44508:
                    case 44509:
                    case 44510:
                    case 44513:
                    case 44528:
                    case 44529:
                    case 44555:
                    case 44582:
                    case 44584:
                    case 44589:
                    case 44592:
                    case 44593:
                    case 44598:
                    case 44616:
                    case 44623:
                    case 44629:
                    case 44630:
                    case 44633:
                    case 44635:
                    case 44636:
                    case 44645:
                    case 46917:
                    case 46949:
                    case 46953:
                    case 46968:
                    case 47296:
                    case 47436:
                    case 47437:
                    case 47440:
                    case 47450:
                    case 47465:
                    case 47471:
                    case 47475:
                    case 47488:
                    case 47498:
                    case 47502:
                    case 47520:
                    case 47766:
                    case 47900:
                    case 50720:
                    case 51313:
                    case 54197:
                    case 55531:
                    case 55694:
                    case 56924:
                    case 56932:
                    case 57499:
                    case 57755:
                    case 57823:
                    case 58874:
                    case 59089:
                    case 59636:
                    case 60606:
                    case 60609:
                    case 60616:
                    case 60619:
                    case 60621:
                    case 60623:
                    case 60653:
                    case 60663:
                    case 60668:
                    case 60767:
                    case 60970:
                    case 61222:
                    case 62959:
                    case 63644:
                    case 63645:
                    case 63746:
                    case 64382:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 2:
                switch( $SpellID ) 
                {
                    case 196:
                    case 197:
                    case 200:
                    case 201:
                    case 202:
                    case 498:
                    case 642:
                    case 750:
                    case 1038:
                    case 1044:
                    case 1152:
                    case 3127:
                    case 4987:
                    case 5502:
                    case 6940:
                    case 10278:
                    case 10308:
                    case 10326:
                    case 13819:
                    case 19746:
                    case 19752:
                    case 20045:
                    case 20057:
                    case 20100:
                    case 20105:
                    case 20113:
                    case 20121:
                    case 20146:
                    case 20164:
                    case 20165:
                    case 20166:
                    case 20217:
                    case 20266:
                    case 20271:
                    case 20332:
                    case 20337:
                    case 20375:
                    case 23214:
                    case 25780:
                    case 25898:
                    case 25957:
                    case 26016:
                    case 31789:
                    case 31801:
                    case 31868:
                    case 31872:
                    case 31878:
                    case 31884:
                    case 32223:
                    case 34091:
                    case 35395:
                    case 35397:
                    case 48782:
                    case 48785:
                    case 48788:
                    case 48801:
                    case 48806:
                    case 48817:
                    case 48819:
                    case 48932:
                    case 48934:
                    case 48936:
                    case 48938:
                    case 48942:
                    case 48943:
                    case 48945:
                    case 48947:
                    case 48950:
                    case 53376:
                    case 53382:
                    case 53385:
                    case 53407:
                    case 53408:
                    case 53488:
                    case 53503:
                    case 53601:
                    case 53648:
                    case 54043:
                    case 54197:
                    case 54428:
                    case 59571:
                    case 61411:
                    case 62124:
                    case 34767:
                    case 34769:
                    case 53736:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 3:
                switch( $SpellID ) 
                {
                    case 674:
                    case 781:
                    case 883:
                    case 982:
                    case 1002:
                    case 1462:
                    case 1494:
                    case 1515:
                    case 1543:
                    case 2641:
                    case 2974:
                    case 3034:
                    case 3043:
                    case 3045:
                    case 3127:
                    case 5116:
                    case 5118:
                    case 5149:
                    case 5384:
                    case 6197:
                    case 6991:
                    case 8737:
                    case 13159:
                    case 13161:
                    case 13163:
                    case 13809:
                    case 14311:
                    case 14327:
                    case 19263:
                    case 19801:
                    case 19878:
                    case 19879:
                    case 19880:
                    case 19882:
                    case 19883:
                    case 19884:
                    case 19885:
                    case 20736:
                    case 27044:
                    case 34026:
                    case 34074:
                    case 34477:
                    case 34600:
                    case 48990:
                    case 48996:
                    case 49001:
                    case 49045:
                    case 49048:
                    case 49052:
                    case 49056:
                    case 49067:
                    case 49071:
                    case 53271:
                    case 53338:
                    case 53339:
                    case 58434:
                    case 60192:
                    case 61006:
                    case 61847:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 4:
                switch( $SpellID ) 
                {
                    case 921:
                    case 1725:
                    case 1766:
                    case 1776:
                    case 1784:
                    case 1804:
                    case 1833:
                    case 1842:
                    case 1860:
                    case 2094:
                    case 2836:
                    case 2842:
                    case 3127:
                    case 5938:
                    case 6774:
                    case 8643:
                    case 8647:
                    case 11305:
                    case 26669:
                    case 26889:
                    case 31224:
                    case 48638:
                    case 48657:
                    case 48659:
                    case 48668:
                    case 48672:
                    case 48674:
                    case 48676:
                    case 48691:
                    case 51722:
                    case 51723:
                    case 51724:
                    case 57934:
                    case 57993:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 5:
                switch( $SpellID ) 
                {
                    case 453:
                    case 528:
                    case 552:
                    case 586:
                    case 605:
                    case 988:
                    case 1180:
                    case 1706:
                    case 2053:
                    case 6064:
                    case 6346:
                    case 8129:
                    case 10890:
                    case 10909:
                    case 10955:
                    case 14767:
                    case 14791:
                    case 15012:
                    case 15286:
                    case 15310:
                    case 15316:
                    case 15317:
                    case 15328:
                    case 15332:
                    case 15448:
                    case 15473:
                    case 15487:
                    case 17191:
                    case 17323:
                    case 27840:
                    case 27901:
                    case 32375:
                    case 33193:
                    case 33215:
                    case 33225:
                    case 33371:
                    case 34433:
                    case 47570:
                    case 47582:
                    case 47585:
                    case 48063:
                    case 48066:
                    case 48068:
                    case 48071:
                    case 48072:
                    case 48073:
                    case 48074:
                    case 48078:
                    case 48113:
                    case 48120:
                    case 48123:
                    case 48125:
                    case 48127:
                    case 48135:
                    case 48156:
                    case 48158:
                    case 48160:
                    case 48161:
                    case 48162:
                    case 48168:
                    case 48169:
                    case 48170:
                    case 48171:
                    case 48300:
                    case 51167:
                    case 53023:
                    case 63627:
                    case 64044:
                    case 64843:
                    case 64901:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 6:
                switch( $SpellID ) 
                {
                    case 198:
                    case 199:
                    case 3714:
                    case 34091:
                    case 42650:
                    case 45524:
                    case 45529:
                    case 46584:
                    case 46628:
                    case 47476:
                    case 47528:
                    case 47568:
                    case 48263:
                    case 48265:
                    case 48707:
                    case 48743:
                    case 48778:
                    case 48792:
                    case 48982:
                    case 49005:
                    case 49016:
                    case 49028:
                    case 49393:
                    case 49395:
                    case 49480:
                    case 49489:
                    case 49491:
                    case 49501:
                    case 49504:
                    case 49509:
                    case 49530:
                    case 49534:
                    case 49543:
                    case 49562:
                    case 49568:
                    case 49589:
                    case 49895:
                    case 49909:
                    case 49921:
                    case 49924:
                    case 49930:
                    case 49938:
                    case 49941:
                    case 50029:
                    case 50034:
                    case 50111:
                    case 50150:
                    case 50371:
                    case 50842:
                    case 51425:
                    case 51456:
                    case 51746:
                    case 52286:
                    case 53138:
                    case 53323:
                    case 53331:
                    case 53341:
                    case 53342:
                    case 53343:
                    case 53344:
                    case 54197:
                    case 54446:
                    case 54447:
                    case 55108:
                    case 55133:
                    case 55233:
                    case 55262:
                    case 56222:
                    case 56815:
                    case 57623:
                    case 59568:
                    case 61158:
                    case 61278:
                    case 61999:
                    case 62158:
                    case 62908:
                    case 63644:
                    case 63645:
                    case 66:
                    case 130:
                    case 201:
                    case 475:
                    case 1180:
                    case 1953:
                    case 2139:
                    case 3563:
                    case 3566:
                    case 3567:
                    case 7301:
                    case 11417:
                    case 11418:
                    case 11420:
                    case 12051:
                    case 12826:
                    case 26054:
                    case 27090:
                    case 28272:
                    case 30449:
                    case 32267:
                    case 32272:
                    case 33717:
                    case 35715:
                    case 35717:
                    case 41513:
                    case 42833:
                    case 42842:
                    case 42846:
                    case 42859:
                    case 42873:
                    case 42897:
                    case 42914:
                    case 42917:
                    case 42921:
                    case 42926:
                    case 42931:
                    case 42940:
                    case 42956:
                    case 42985:
                    case 42995:
                    case 43002:
                    case 43008:
                    case 43010:
                    case 43012:
                    case 43015:
                    case 43017:
                    case 43020:
                    case 43024:
                    case 43046:
                    case 45438:
                    case 47610:
                    case 49358:
                    case 49361:
                    case 51412:
                    case 53140:
                    case 53142:
                    case 55342:
                    case 58659:
                    case 50977:
                    case 53428:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 7:
                switch( $SpellID ) 
                {
                    case 131:
                    case 196:
                    case 197:
                    case 199:
                    case 526:
                    case 546:
                    case 556:
                    case 1180:
                    case 2062:
                    case 2484:
                    case 2645:
                    case 2825:
                    case 2894:
                    case 3738:
                    case 6196:
                    case 6495:
                    case 8012:
                    case 8143:
                    case 8170:
                    case 8177:
                    case 8512:
                    case 8737:
                    case 10399:
                    case 15590:
                    case 16041:
                    case 16108:
                    case 16109:
                    case 16116:
                    case 16161:
                    case 16164:
                    case 16166:
                    case 16305:
                    case 16582:
                    case 17489:
                    case 20608:
                    case 24242:
                    case 29065:
                    case 30666:
                    case 30674:
                    case 30679:
                    case 34091:
                    case 36936:
                    case 43338:
                    case 49231:
                    case 49233:
                    case 49236:
                    case 49238:
                    case 49271:
                    case 49273:
                    case 49276:
                    case 49277:
                    case 49281:
                    case 51470:
                    case 51482:
                    case 51486:
                    case 51514:
                    case 51881:
                    case 51994:
                    case 54197:
                    case 55459:
                    case 57722:
                    case 57960:
                    case 57994:
                    case 58582:
                    case 58643:
                    case 58656:
                    case 58704:
                    case 58734:
                    case 58739:
                    case 58745:
                    case 58749:
                    case 58753:
                    case 58757:
                    case 58774:
                    case 58790:
                    case 58796:
                    case 58804:
                    case 59159:
                    case 59568:
                    case 60043:
                    case 60188:
                    case 61657:
                    case 62101:
                    case 63372:
                    case 63644:
                    case 63645:
                    case 66842:
                    case 66843:
                    case 66844:
                    case 32182:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 8:
                switch( $SpellID ) 
                {
                    case 66:
                    case 130:
                    case 201:
                    case 475:
                    case 1180:
                    case 1953:
                    case 2139:
                    case 3563:
                    case 3566:
                    case 3567:
                    case 7301:
                    case 11080:
                    case 11417:
                    case 11418:
                    case 11420:
                    case 11958:
                    case 12042:
                    case 12043:
                    case 12051:
                    case 12469:
                    case 12472:
                    case 12490:
                    case 12497:
                    case 12503:
                    case 12519:
                    case 12571:
                    case 12577:
                    case 12592:
                    case 12598:
                    case 12605:
                    case 12606:
                    case 12826:
                    case 12840:
                    case 12847:
                    case 12953:
                    case 12983:
                    case 15047:
                    case 15060:
                    case 16758:
                    case 16766:
                    case 16770:
                    case 18464:
                    case 27090:
                    case 28272:
                    case 29440:
                    case 29444:
                    case 30449:
                    case 31570:
                    case 31571:
                    case 31583:
                    case 31588:
                    case 31589:
                    case 31678:
                    case 31683:
                    case 31687:
                    case 32267:
                    case 32272:
                    case 33717:
                    case 34091:
                    case 35581:
                    case 35715:
                    case 35717:
                    case 42833:
                    case 42842:
                    case 42846:
                    case 42859:
                    case 42873:
                    case 42897:
                    case 42914:
                    case 42917:
                    case 42921:
                    case 42926:
                    case 42931:
                    case 42940:
                    case 42956:
                    case 42985:
                    case 42995:
                    case 43002:
                    case 43008:
                    case 43010:
                    case 43012:
                    case 43015:
                    case 43017:
                    case 43020:
                    case 43024:
                    case 43039:
                    case 43046:
                    case 44379:
                    case 44396:
                    case 44403:
                    case 44545:
                    case 44549:
                    case 44561:
                    case 44571:
                    case 44572:
                    case 44781:
                    case 45438:
                    case 47610:
                    case 49358:
                    case 49361:
                    case 51412:
                    case 53140:
                    case 53142:
                    case 54197:
                    case 54354:
                    case 54490:
                    case 54646:
                    case 54659:
                    case 54734:
                    case 54749:
                    case 54787:
                    case 55092:
                    case 55094:
                    case 55340:
                    case 55342:
                    case 58659:
                    case 59568:
                    case 63644:
                    case 63645:
                    case 3561:
                    case 3562:
                    case 3565:
                    case 10059:
                    case 11416:
                    case 11419:
                    case 12952:
                    case 16765:
                    case 16769:
                    case 24242:
                    case 28332:
                    case 28593:
                    case 32266:
                    case 32271:
                    case 33690:
                    case 33691:
                    case 44557:
                    case 49359:
                    case 49360:
                    case 59571:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 9:
                switch( $SpellID ) 
                {
                    case 126:
                    case 132:
                    case 201:
                    case 688:
                    case 691:
                    case 696:
                    case 697:
                    case 698:
                    case 712:
                    case 1122:
                    case 2331:
                    case 2332:
                    case 2334:
                    case 2337:
                    case 3170:
                    case 3171:
                    case 3173:
                    case 3176:
                    case 3177:
                    case 3447:
                    case 3448:
                    case 3450:
                    case 3452:
                    case 5138:
                    case 5500:
                    case 5697:
                    case 5784:
                    case 6215:
                    case 7179:
                    case 7181:
                    case 7836:
                    case 7837:
                    case 7841:
                    case 7845:
                    case 11448:
                    case 11449:
                    case 11450:
                    case 11451:
                    case 11457:
                    case 11460:
                    case 11461:
                    case 11465:
                    case 11467:
                    case 11478:
                    case 11719:
                    case 12609:
                    case 15833:
                    case 17551:
                    case 17552:
                    case 17553:
                    case 17555:
                    case 17556:
                    case 17557:
                    case 17572:
                    case 17573:
                    case 17780:
                    case 17785:
                    case 17792:
                    case 17805:
                    case 17814:
                    case 17834:
                    case 17918:
                    case 17928:
                    case 17958:
                    case 17962:
                    case 18095:
                    case 18120:
                    case 18130:
                    case 18136:
                    case 18174:
                    case 18183:
                    case 18219:
                    case 18275:
                    case 18540:
                    case 18647:
                    case 18693:
                    case 18695:
                    case 18699:
                    case 18704:
                    case 18708:
                    case 18710:
                    case 18744:
                    case 18768:
                    case 18773:
                    case 18829:
                    case 19028:
                    case 22808:
                    case 23161:
                    case 23825:
                    case 25953:
                    case 26055:
                    case 26056:
                    case 28581:
                    case 28582:
                    case 28584:
                    case 28585:
                    case 28586:
                    case 28587:
                    case 28588:
                    case 28589:
                    case 28590:
                    case 28591:
                    case 29858:
                    case 30064:
                    case 30145:
                    case 30146:
                    case 30248:
                    case 30292:
                    case 30296:
                    case 30302:
                    case 32295:
                    case 32383:
                    case 32394:
                    case 32484:
                    case 33732:
                    case 33738:
                    case 33740:
                    case 34091:
                    case 34939:
                    case 35693:
                    case 44744:
                    case 46628:
                    case 47193:
                    case 47195:
                    case 47200:
                    case 47202:
                    case 47231:
                    case 47240:
                    case 47241:
                    case 47247:
                    case 47260:
                    case 47270:
                    case 47809:
                    case 47811:
                    case 47813:
                    case 47815:
                    case 47820:
                    case 47823:
                    case 47825:
                    case 47827:
                    case 47836:
                    case 47838:
                    case 47843:
                    case 47847:
                    case 47855:
                    case 47856:
                    case 47857:
                    case 47860:
                    case 47864:
                    case 47865:
                    case 47867:
                    case 47878:
                    case 47884:
                    case 47888:
                    case 47889:
                    case 47891:
                    case 47893:
                    case 48018:
                    case 48020:
                    case 50511:
                    case 50581:
                    case 51304:
                    case 51309:
                    case 53042:
                    case 53812:
                    case 53836:
                    case 53837:
                    case 53838:
                    case 53839:
                    case 53841:
                    case 53842:
                    case 53848:
                    case 53899:
                    case 53900:
                    case 53901:
                    case 53902:
                    case 53903:
                    case 54197:
                    case 54213:
                    case 55642:
                    case 56007:
                    case 57946:
                    case 58435:
                    case 58887:
                    case 59164:
                    case 59172:
                    case 59571:
                    case 59672:
                    case 59673:
                    case 59741:
                    case 60220:
                    case 60893:
                    case 60990:
                    case 60993:
                    case 60994:
                    case 61191:
                    case 61290:
                    case 61294:
                    case 62409:
                    case 63108:
                    case 63123:
                    case 63158:
                    case 63245:
                    case 63351:
                    case 63644:
                    case 63645:
                    case 63732:
                    case 64731:
                    case 6213:
                    case 11672:
                    case 11675:
                    case 11688:
                    case 11700:
                    case 11708:
                    case 11712:
                    case 11721:
                    case 17925:
                    case 18709:
                    case 50589:
                    case 54785:
                    case 59671:
                    case 17803:
                    case 18697:
                    case 54118:
                    case 196:
                    case 200:
                    case 202:
                    case 227:
                    case 266:
                    case 674:
                    case 781:
                    case 883:
                    case 982:
                    case 1002:
                    case 1462:
                    case 1494:
                    case 1515:
                    case 1543:
                    case 2567:
                    case 2641:
                    case 2974:
                    case 3034:
                    case 3043:
                    case 3045:
                    case 3127:
                    case 5011:
                    case 5116:
                    case 5118:
                    case 5384:
                    case 6197:
                    case 6991:
                    case 8737:
                    case 13159:
                    case 13161:
                    case 13163:
                    case 13809:
                    case 14311:
                    case 14327:
                    case 15590:
                    case 19184:
                    case 19259:
                    case 19263:
                    case 19373:
                    case 19431:
                    case 19490:
                    case 19500:
                    case 19503:
                    case 19801:
                    case 19878:
                    case 19879:
                    case 19880:
                    case 19882:
                    case 19883:
                    case 19884:
                    case 19885:
                    case 20736:
                    case 24283:
                    case 24297:
                    case 27044:
                    case 34026:
                    case 34074:
                    case 34477:
                    case 34484:
                    case 34493:
                    case 34496:
                    case 34499:
                    case 34503:
                    case 34600:
                    case 34839:
                    case 48990:
                    case 48996:
                    case 49001:
                    case 49012:
                    case 49045:
                    case 49048:
                    case 49050:
                    case 49052:
                    case 49056:
                    case 49067:
                    case 49071:
                    case 52785:
                    case 53271:
                    case 53292:
                    case 53338:
                    case 53339:
                    case 56337:
                    case 56341:
                    case 56344:
                    case 58434:
                    case 60053:
                    case 60118:
                    case 60192:
                    case 61006:
                    case 61847:
                    case 63458:
                    case 63672:
                    case 18176:
                    case 18223:
                    case 18288:
                    case 30057:
                    case 47197:
                    case 47199:
                    case 47205:
                    case 53754:
                    case 34090:
                    case 54753:
                    case 693:
                    case 17877:
                    case 17930:
                    case 18127:
                    case 18135:
                    case 30283:
                    case 30291:
                    case 30295:
                    case 47223:
                    case 50796:
                    case 59739:
                    case 63350:
                        return true;
                    default:
                        return false;
                }
        }
        switch( $ClassID ) 
        {
            case 11:
                switch( $SpellID ) 
                {
                    case 768:
                    case 770:
                    case 783:
                    case 1066:
                    case 2782:
                    case 2893:
                    case 5209:
                    case 5215:
                    case 5225:
                    case 5229:
                    case 5420:
                    case 6795:
                    case 8983:
                    case 16081:
                    case 16835:
                    case 16857:
                    case 16864:
                    case 17051:
                    case 17061:
                    case 17066:
                    case 17073:
                    case 17078:
                    case 17108:
                    case 17113:
                    case 17116:
                    case 17120:
                    case 17124:
                    case 18562:
                    case 18658:
                    case 20719:
                    case 22812:
                    case 22842:
                    case 24242:
                    case 24946:
                    case 24972:
                    case 26995:
                    case 29166:
                    case 33357:
                    case 33786:
                    case 33880:
                    case 33883:
                    case 33890:
                    case 33891:
                    case 34091:
                    case 34153:
                    case 40120:
                    case 48378:
                    case 48412:
                    case 48441:
                    case 48443:
                    case 48447:
                    case 48451:
                    case 48461:
                    case 48463:
                    case 48465:
                    case 48467:
                    case 48469:
                    case 48470:
                    case 48477:
                    case 48480:
                    case 48500:
                    case 48545:
                    case 48560:
                    case 48562:
                    case 48568:
                    case 48570:
                    case 48572:
                    case 48574:
                    case 48575:
                    case 48577:
                    case 48579:
                    case 49800:
                    case 49802:
                    case 49803:
                    case 50213:
                    case 50464:
                    case 50763:
                    case 52610:
                    case 53251:
                    case 53307:
                    case 53308:
                    case 53312:
                    case 54197:
                    case 59571:
                    case 59793:
                    case 61467:
                    case 62078:
                    case 62600:
                    case 63410:
                    case 63680:
                    case 65139:
                    case 9634:
                    case 24858:
                    case 33876:
                    case 33878:
                        return true;
                    default:
                        return false;
                }
        }
    }

    function _isProfessionSpell($SpellID)
    {
        switch( $SpellID ) 
        {
            case 2153:
            case 2158:
            case 2159:
            case 2160:
            case 2161:
            case 2162:
            case 2163:
            case 2164:
            case 2165:
            case 2166:
            case 2167:
            case 2168:
            case 2169:
            case 2331:
            case 2332:
            case 2333:
            case 2334:
            case 2335:
            case 2336:
            case 2337:
            case 2385:
            case 2386:
            case 2389:
            case 2392:
            case 2393:
            case 2394:
            case 2395:
            case 2396:
            case 2397:
            case 2399:
            case 2401:
            case 2402:
            case 2403:
            case 2406:
            case 2539:
            case 2541:
            case 2542:
            case 2543:
            case 2544:
            case 2545:
            case 2546:
            case 2547:
            case 2548:
            case 2549:
            case 2661:
            case 2662:
            case 2664:
            case 2665:
            case 2666:
            case 2667:
            case 2668:
            case 2670:
            case 2671:
            case 2672:
            case 2673:
            case 2674:
            case 2675:
            case 2737:
            case 2738:
            case 2739:
            case 2740:
            case 2741:
            case 2742:
            case 2795:
            case 2964:
            case 3116:
            case 3117:
            case 3170:
            case 3171:
            case 3172:
            case 3173:
            case 3174:
            case 3175:
            case 3176:
            case 3177:
            case 3188:
            case 3230:
            case 3276:
            case 3277:
            case 3278:
            case 3292:
            case 3293:
            case 3294:
            case 3295:
            case 3296:
            case 3297:
            case 3319:
            case 3320:
            case 3321:
            case 3323:
            case 3324:
            case 3325:
            case 3326:
            case 3328:
            case 3330:
            case 3331:
            case 3333:
            case 3334:
            case 3336:
            case 3337:
            case 3370:
            case 3371:
            case 3372:
            case 3373:
            case 3376:
            case 3377:
            case 3397:
            case 3398:
            case 3399:
            case 3400:
            case 3447:
            case 3448:
            case 3449:
            case 3450:
            case 3451:
            case 3452:
            case 3453:
            case 3454:
            case 3491:
            case 3492:
            case 3493:
            case 3494:
            case 3495:
            case 3496:
            case 3497:
            case 3498:
            case 3500:
            case 3501:
            case 3502:
            case 3503:
            case 3504:
            case 3505:
            case 3506:
            case 3507:
            case 3508:
            case 3511:
            case 3513:
            case 3515:
            case 3753:
            case 3755:
            case 3756:
            case 3757:
            case 3758:
            case 3759:
            case 3760:
            case 3761:
            case 3762:
            case 3763:
            case 3764:
            case 3765:
            case 3766:
            case 3767:
            case 3768:
            case 3769:
            case 3770:
            case 3771:
            case 3772:
            case 3773:
            case 3774:
            case 3775:
            case 3776:
            case 3777:
            case 3778:
            case 3779:
            case 3780:
            case 3813:
            case 3816:
            case 3817:
            case 3818:
            case 3839:
            case 3840:
            case 3841:
            case 3842:
            case 3843:
            case 3844:
            case 3845:
            case 3847:
            case 3848:
            case 3849:
            case 3850:
            case 3851:
            case 3852:
            case 3854:
            case 3855:
            case 3856:
            case 3857:
            case 3858:
            case 3859:
            case 3860:
            case 3861:
            case 3862:
            case 3863:
            case 3864:
            case 3865:
            case 3866:
            case 3868:
            case 3869:
            case 3870:
            case 3871:
            case 3872:
            case 3873:
            case 3914:
            case 3922:
            case 3923:
            case 3924:
            case 3925:
            case 3926:
            case 3928:
            case 3929:
            case 3930:
            case 3931:
            case 3932:
            case 3933:
            case 3934:
            case 3936:
            case 3937:
            case 3938:
            case 3939:
            case 3940:
            case 3941:
            case 3942:
            case 3944:
            case 3945:
            case 3946:
            case 3947:
            case 3949:
            case 3950:
            case 3952:
            case 3953:
            case 3954:
            case 3955:
            case 3956:
            case 3957:
            case 3958:
            case 3959:
            case 3960:
            case 3961:
            case 3962:
            case 3963:
            case 3965:
            case 3966:
            case 3967:
            case 3968:
            case 3969:
            case 3971:
            case 3972:
            case 3973:
            case 3977:
            case 3978:
            case 3979:
            case 4094:
            case 4096:
            case 4097:
            case 4508:
            case 4942:
            case 5244:
            case 6412:
            case 6413:
            case 6414:
            case 6415:
            case 6416:
            case 6417:
            case 6418:
            case 6419:
            case 6458:
            case 6499:
            case 6500:
            case 6501:
            case 6517:
            case 6518:
            case 6521:
            case 6617:
            case 6618:
            case 6624:
            case 6661:
            case 6686:
            case 6688:
            case 6690:
            case 6692:
            case 6693:
            case 6695:
            case 6702:
            case 6703:
            case 6704:
            case 6705:
            case 7133:
            case 7135:
            case 7147:
            case 7149:
            case 7151:
            case 7153:
            case 7156:
            case 7179:
            case 7181:
            case 7213:
            case 7221:
            case 7222:
            case 7223:
            case 7224:
            case 7255:
            case 7256:
            case 7257:
            case 7258:
            case 7259:
            case 7408:
            case 7420:
            case 7426:
            case 7430:
            case 7443:
            case 7454:
            case 7457:
            case 7623:
            case 7624:
            case 7629:
            case 7630:
            case 7633:
            case 7636:
            case 7639:
            case 7643:
            case 7745:
            case 7748:
            case 7751:
            case 7752:
            case 7753:
            case 7754:
            case 7755:
            case 7766:
            case 7771:
            case 7776:
            case 7779:
            case 7782:
            case 7786:
            case 7788:
            case 7793:
            case 7795:
            case 7817:
            case 7818:
            case 7827:
            case 7828:
            case 7836:
            case 7837:
            case 7841:
            case 7845:
            case 7857:
            case 7859:
            case 7861:
            case 7863:
            case 7867:
            case 7892:
            case 7893:
            case 7928:
            case 7929:
            case 7934:
            case 7935:
            case 7953:
            case 7954:
            case 7955:
            case 8238:
            case 8240:
            case 8243:
            case 8322:
            case 8334:
            case 8339:
            case 8366:
            case 8367:
            case 8368:
            case 8465:
            case 8467:
            case 8483:
            case 8489:
            case 8607:
            case 8758:
            case 8760:
            case 8762:
            case 8764:
            case 8766:
            case 8768:
            case 8770:
            case 8772:
            case 8774:
            case 8776:
            case 8778:
            case 8780:
            case 8782:
            case 8784:
            case 8786:
            case 8789:
            case 8791:
            case 8793:
            case 8795:
            case 8797:
            case 8799:
            case 8802:
            case 8804:
            case 8880:
            case 8895:
            case 9060:
            case 9062:
            case 9064:
            case 9065:
            case 9068:
            case 9070:
            case 9072:
            case 9074:
            case 9145:
            case 9146:
            case 9147:
            case 9148:
            case 9149:
            case 9193:
            case 9194:
            case 9195:
            case 9196:
            case 9197:
            case 9198:
            case 9201:
            case 9202:
            case 9206:
            case 9207:
            case 9208:
            case 9269:
            case 9271:
            case 9273:
            case 9513:
            case 9787:
            case 9788:
            case 9811:
            case 9813:
            case 9814:
            case 9818:
            case 9820:
            case 9916:
            case 9918:
            case 9920:
            case 9921:
            case 9926:
            case 9928:
            case 9931:
            case 9933:
            case 9935:
            case 9937:
            case 9939:
            case 9942:
            case 9945:
            case 9950:
            case 9952:
            case 9954:
            case 9957:
            case 9959:
            case 9961:
            case 9964:
            case 9966:
            case 9968:
            case 9970:
            case 9972:
            case 9974:
            case 9979:
            case 9980:
            case 9983:
            case 9985:
            case 9986:
            case 9987:
            case 9993:
            case 9995:
            case 9997:
            case 10001:
            case 10003:
            case 10005:
            case 10007:
            case 10009:
            case 10011:
            case 10013:
            case 10015:
            case 10482:
            case 10487:
            case 10490:
            case 10499:
            case 10507:
            case 10509:
            case 10511:
            case 10516:
            case 10518:
            case 10520:
            case 10525:
            case 10529:
            case 10531:
            case 10533:
            case 10542:
            case 10544:
            case 10546:
            case 10548:
            case 10550:
            case 10552:
            case 10554:
            case 10556:
            case 10558:
            case 10560:
            case 10562:
            case 10564:
            case 10566:
            case 10568:
            case 10570:
            case 10572:
            case 10574:
            case 10619:
            case 10621:
            case 10630:
            case 10632:
            case 10647:
            case 10650:
            case 10656:
            case 10658:
            case 10660:
            case 10840:
            case 10841:
            case 11448:
            case 11449:
            case 11450:
            case 11451:
            case 11452:
            case 11453:
            case 11454:
            case 11456:
            case 11457:
            case 11458:
            case 11459:
            case 11460:
            case 11461:
            case 11464:
            case 11465:
            case 11466:
            case 11467:
            case 11468:
            case 11472:
            case 11473:
            case 11476:
            case 11477:
            case 11478:
            case 11479:
            case 11480:
            case 11643:
            case 12045:
            case 12046:
            case 12047:
            case 12048:
            case 12049:
            case 12050:
            case 12052:
            case 12053:
            case 12055:
            case 12056:
            case 12059:
            case 12060:
            case 12061:
            case 12062:
            case 12063:
            case 12064:
            case 12065:
            case 12066:
            case 12067:
            case 12068:
            case 12069:
            case 12070:
            case 12071:
            case 12072:
            case 12073:
            case 12074:
            case 12075:
            case 12076:
            case 12077:
            case 12078:
            case 12079:
            case 12080:
            case 12081:
            case 12082:
            case 12083:
            case 12084:
            case 12085:
            case 12086:
            case 12087:
            case 12088:
            case 12089:
            case 12090:
            case 12091:
            case 12092:
            case 12093:
            case 12259:
            case 12584:
            case 12585:
            case 12586:
            case 12587:
            case 12589:
            case 12590:
            case 12591:
            case 12594:
            case 12595:
            case 12596:
            case 12597:
            case 12599:
            case 12603:
            case 12607:
            case 12609:
            case 12614:
            case 12615:
            case 12616:
            case 12617:
            case 12618:
            case 12619:
            case 12620:
            case 12621:
            case 12622:
            case 12624:
            case 12715:
            case 12716:
            case 12717:
            case 12718:
            case 12720:
            case 12722:
            case 12754:
            case 12755:
            case 12758:
            case 12759:
            case 12760:
            case 12895:
            case 12897:
            case 12899:
            case 12900:
            case 12902:
            case 12903:
            case 12904:
            case 12905:
            case 12906:
            case 12907:
            case 12908:
            case 13028:
            case 13240:
            case 13378:
            case 13380:
            case 13419:
            case 13421:
            case 13464:
            case 13485:
            case 13501:
            case 13503:
            case 13522:
            case 13529:
            case 13536:
            case 13538:
            case 13607:
            case 13612:
            case 13617:
            case 13620:
            case 13622:
            case 13626:
            case 13628:
            case 13631:
            case 13635:
            case 13637:
            case 13640:
            case 13642:
            case 13644:
            case 13646:
            case 13648:
            case 13653:
            case 13655:
            case 13657:
            case 13659:
            case 13661:
            case 13663:
            case 13687:
            case 13689:
            case 13693:
            case 13695:
            case 13698:
            case 13700:
            case 13702:
            case 13746:
            case 13794:
            case 13815:
            case 13817:
            case 13822:
            case 13836:
            case 13841:
            case 13846:
            case 13858:
            case 13868:
            case 13882:
            case 13887:
            case 13890:
            case 13898:
            case 13905:
            case 13915:
            case 13917:
            case 13931:
            case 13933:
            case 13935:
            case 13937:
            case 13939:
            case 13941:
            case 13943:
            case 13945:
            case 13947:
            case 13948:
            case 14293:
            case 14379:
            case 14380:
            case 14807:
            case 14809:
            case 14810:
            case 14930:
            case 14932:
            case 15255:
            case 15292:
            case 15293:
            case 15294:
            case 15295:
            case 15296:
            case 15596:
            case 15628:
            case 15633:
            case 15833:
            case 15853:
            case 15855:
            case 15856:
            case 15861:
            case 15863:
            case 15865:
            case 15906:
            case 15910:
            case 15915:
            case 15933:
            case 15935:
            case 15972:
            case 15973:
            case 16639:
            case 16640:
            case 16641:
            case 16642:
            case 16643:
            case 16644:
            case 16645:
            case 16646:
            case 16647:
            case 16648:
            case 16649:
            case 16650:
            case 16651:
            case 16652:
            case 16653:
            case 16654:
            case 16655:
            case 16656:
            case 16657:
            case 16658:
            case 16659:
            case 16660:
            case 16661:
            case 16662:
            case 16663:
            case 16664:
            case 16665:
            case 16667:
            case 16724:
            case 16725:
            case 16726:
            case 16728:
            case 16729:
            case 16730:
            case 16731:
            case 16732:
            case 16741:
            case 16742:
            case 16744:
            case 16745:
            case 16746:
            case 16960:
            case 16965:
            case 16967:
            case 16969:
            case 16970:
            case 16971:
            case 16973:
            case 16978:
            case 16980:
            case 16983:
            case 16984:
            case 16985:
            case 16986:
            case 16987:
            case 16988:
            case 16990:
            case 16991:
            case 16992:
            case 16993:
            case 16994:
            case 16995:
            case 17039:
            case 17040:
            case 17041:
            case 17180:
            case 17181:
            case 17187:
            case 17551:
            case 17552:
            case 17553:
            case 17554:
            case 17555:
            case 17556:
            case 17557:
            case 17559:
            case 17560:
            case 17561:
            case 17562:
            case 17563:
            case 17564:
            case 17565:
            case 17566:
            case 17570:
            case 17571:
            case 17572:
            case 17573:
            case 17574:
            case 17575:
            case 17576:
            case 17577:
            case 17578:
            case 17579:
            case 17580:
            case 17632:
            case 17634:
            case 17635:
            case 17636:
            case 17637:
            case 17638:
            case 18238:
            case 18239:
            case 18240:
            case 18241:
            case 18242:
            case 18243:
            case 18244:
            case 18245:
            case 18246:
            case 18247:
            case 18401:
            case 18402:
            case 18403:
            case 18404:
            case 18405:
            case 18406:
            case 18407:
            case 18408:
            case 18409:
            case 18410:
            case 18411:
            case 18412:
            case 18413:
            case 18414:
            case 18415:
            case 18416:
            case 18417:
            case 18418:
            case 18419:
            case 18420:
            case 18421:
            case 18422:
            case 18423:
            case 18424:
            case 18434:
            case 18436:
            case 18437:
            case 18438:
            case 18439:
            case 18440:
            case 18441:
            case 18442:
            case 18444:
            case 18445:
            case 18446:
            case 18447:
            case 18448:
            case 18449:
            case 18450:
            case 18451:
            case 18452:
            case 18453:
            case 18454:
            case 18455:
            case 18456:
            case 18457:
            case 18458:
            case 18560:
            case 18629:
            case 18630:
            case 19047:
            case 19048:
            case 19049:
            case 19050:
            case 19051:
            case 19052:
            case 19053:
            case 19054:
            case 19055:
            case 19058:
            case 19059:
            case 19060:
            case 19061:
            case 19062:
            case 19063:
            case 19064:
            case 19065:
            case 19066:
            case 19067:
            case 19068:
            case 19070:
            case 19071:
            case 19072:
            case 19073:
            case 19074:
            case 19075:
            case 19076:
            case 19077:
            case 19078:
            case 19079:
            case 19080:
            case 19081:
            case 19082:
            case 19083:
            case 19084:
            case 19085:
            case 19086:
            case 19087:
            case 19088:
            case 19089:
            case 19090:
            case 19091:
            case 19092:
            case 19093:
            case 19094:
            case 19095:
            case 19097:
            case 19098:
            case 19100:
            case 19101:
            case 19102:
            case 19103:
            case 19104:
            case 19106:
            case 19107:
            case 19435:
            case 19567:
            case 19666:
            case 19667:
            case 19668:
            case 19669:
            case 19788:
            case 19790:
            case 19791:
            case 19792:
            case 19793:
            case 19794:
            case 19795:
            case 19796:
            case 19799:
            case 19800:
            case 19814:
            case 19815:
            case 19819:
            case 19825:
            case 19830:
            case 19831:
            case 19833:
            case 20008:
            case 20009:
            case 20010:
            case 20011:
            case 20012:
            case 20013:
            case 20014:
            case 20015:
            case 20016:
            case 20017:
            case 20020:
            case 20023:
            case 20024:
            case 20025:
            case 20026:
            case 20028:
            case 20029:
            case 20030:
            case 20031:
            case 20032:
            case 20033:
            case 20034:
            case 20035:
            case 20036:
            case 20051:
            case 20201:
            case 20219:
            case 20222:
            case 20626:
            case 20648:
            case 20649:
            case 20650:
            case 20848:
            case 20849:
            case 20853:
            case 20854:
            case 20855:
            case 20872:
            case 20873:
            case 20874:
            case 20876:
            case 20890:
            case 20897:
            case 20916:
            case 21143:
            case 21144:
            case 21161:
            case 21175:
            case 21913:
            case 21923:
            case 21931:
            case 21940:
            case 21943:
            case 21945:
            case 22331:
            case 22480:
            case 22704:
            case 22711:
            case 22727:
            case 22732:
            case 22749:
            case 22750:
            case 22757:
            case 22759:
            case 22761:
            case 22793:
            case 22795:
            case 22797:
            case 22808:
            case 22813:
            case 22815:
            case 22866:
            case 22867:
            case 22868:
            case 22869:
            case 22870:
            case 22902:
            case 22921:
            case 22922:
            case 22923:
            case 22926:
            case 22927:
            case 22928:
            case 23066:
            case 23067:
            case 23068:
            case 23069:
            case 23070:
            case 23071:
            case 23077:
            case 23078:
            case 23079:
            case 23080:
            case 23081:
            case 23082:
            case 23096:
            case 23129:
            case 23190:
            case 23399:
            case 23486:
            case 23489:
            case 23507:
            case 23628:
            case 23629:
            case 23632:
            case 23633:
            case 23636:
            case 23637:
            case 23638:
            case 23639:
            case 23650:
            case 23652:
            case 23653:
            case 23662:
            case 23663:
            case 23664:
            case 23665:
            case 23666:
            case 23667:
            case 23703:
            case 23704:
            case 23705:
            case 23706:
            case 23707:
            case 23708:
            case 23709:
            case 23710:
            case 23787:
            case 23799:
            case 23800:
            case 23801:
            case 23802:
            case 23803:
            case 23804:
            case 24091:
            case 24092:
            case 24093:
            case 24121:
            case 24122:
            case 24123:
            case 24124:
            case 24125:
            case 24136:
            case 24137:
            case 24138:
            case 24139:
            case 24140:
            case 24141:
            case 24266:
            case 24356:
            case 24357:
            case 24365:
            case 24366:
            case 24367:
            case 24368:
            case 24399:
            case 24418:
            case 24654:
            case 24655:
            case 24703:
            case 24801:
            case 24846:
            case 24847:
            case 24848:
            case 24849:
            case 24850:
            case 24851:
            case 24901:
            case 24902:
            case 24903:
            case 24912:
            case 24913:
            case 24914:
            case 24940:
            case 25072:
            case 25073:
            case 25074:
            case 25078:
            case 25079:
            case 25080:
            case 25081:
            case 25082:
            case 25083:
            case 25084:
            case 25086:
            case 25124:
            case 25125:
            case 25126:
            case 25127:
            case 25128:
            case 25129:
            case 25130:
            case 25146:
            case 25278:
            case 25280:
            case 25283:
            case 25284:
            case 25287:
            case 25305:
            case 25317:
            case 25318:
            case 25320:
            case 25321:
            case 25323:
            case 25339:
            case 25490:
            case 25498:
            case 25610:
            case 25612:
            case 25613:
            case 25614:
            case 25615:
            case 25617:
            case 25618:
            case 25619:
            case 25620:
            case 25621:
            case 25622:
            case 25659:
            case 25704:
            case 25954:
            case 26011:
            case 26085:
            case 26086:
            case 26087:
            case 26277:
            case 26279:
            case 26403:
            case 26407:
            case 26416:
            case 26417:
            case 26418:
            case 26420:
            case 26421:
            case 26422:
            case 26423:
            case 26424:
            case 26425:
            case 26426:
            case 26427:
            case 26428:
            case 26442:
            case 26443:
            case 26745:
            case 26746:
            case 26747:
            case 26749:
            case 26750:
            case 26751:
            case 26752:
            case 26753:
            case 26754:
            case 26755:
            case 26756:
            case 26757:
            case 26758:
            case 26759:
            case 26760:
            case 26761:
            case 26762:
            case 26763:
            case 26764:
            case 26765:
            case 26770:
            case 26771:
            case 26772:
            case 26773:
            case 26774:
            case 26775:
            case 26776:
            case 26777:
            case 26778:
            case 26779:
            case 26780:
            case 26781:
            case 26782:
            case 26783:
            case 26784:
            case 26797:
            case 26798:
            case 26801:
            case 26872:
            case 26873:
            case 26874:
            case 26875:
            case 26876:
            case 26878:
            case 26880:
            case 26881:
            case 26882:
            case 26883:
            case 26885:
            case 26887:
            case 26896:
            case 26897:
            case 26900:
            case 26902:
            case 26903:
            case 26906:
            case 26907:
            case 26908:
            case 26909:
            case 26910:
            case 26911:
            case 26912:
            case 26914:
            case 26915:
            case 26916:
            case 26918:
            case 26920:
            case 26926:
            case 26927:
            case 26928:
            case 27032:
            case 27033:
            case 27585:
            case 27586:
            case 27587:
            case 27588:
            case 27589:
            case 27590:
            case 27658:
            case 27659:
            case 27660:
            case 27724:
            case 27725:
            case 27829:
            case 27830:
            case 27832:
            case 27837:
            case 27899:
            case 27905:
            case 27906:
            case 27911:
            case 27913:
            case 27914:
            case 27917:
            case 27920:
            case 27924:
            case 27926:
            case 27927:
            case 27944:
            case 27945:
            case 27946:
            case 27947:
            case 27948:
            case 27950:
            case 27951:
            case 27954:
            case 27957:
            case 27958:
            case 27960:
            case 27961:
            case 27962:
            case 27967:
            case 27968:
            case 27971:
            case 27972:
            case 27975:
            case 27977:
            case 27981:
            case 27982:
            case 27984:
            case 28003:
            case 28004:
            case 28016:
            case 28019:
            case 28021:
            case 28022:
            case 28027:
            case 28028:
            case 28205:
            case 28207:
            case 28208:
            case 28209:
            case 28210:
            case 28219:
            case 28220:
            case 28221:
            case 28222:
            case 28223:
            case 28224:
            case 28242:
            case 28243:
            case 28244:
            case 28267:
            case 28327:
            case 28461:
            case 28462:
            case 28463:
            case 28472:
            case 28473:
            case 28474:
            case 28480:
            case 28481:
            case 28482:
            case 28543:
            case 28544:
            case 28545:
            case 28546:
            case 28549:
            case 28550:
            case 28551:
            case 28552:
            case 28553:
            case 28554:
            case 28555:
            case 28556:
            case 28557:
            case 28558:
            case 28562:
            case 28563:
            case 28564:
            case 28565:
            case 28566:
            case 28567:
            case 28568:
            case 28569:
            case 28570:
            case 28571:
            case 28572:
            case 28573:
            case 28575:
            case 28576:
            case 28577:
            case 28578:
            case 28579:
            case 28580:
            case 28581:
            case 28582:
            case 28583:
            case 28584:
            case 28585:
            case 28586:
            case 28587:
            case 28588:
            case 28589:
            case 28590:
            case 28591:
            case 28672:
            case 28675:
            case 28677:
            case 28903:
            case 28905:
            case 28906:
            case 28907:
            case 28910:
            case 28912:
            case 28914:
            case 28915:
            case 28916:
            case 28917:
            case 28918:
            case 28924:
            case 28925:
            case 28927:
            case 28933:
            case 28936:
            case 28938:
            case 28944:
            case 28947:
            case 28948:
            case 28950:
            case 28953:
            case 28955:
            case 28957:
            case 29545:
            case 29547:
            case 29548:
            case 29549:
            case 29550:
            case 29551:
            case 29552:
            case 29553:
            case 29556:
            case 29557:
            case 29558:
            case 29565:
            case 29566:
            case 29568:
            case 29569:
            case 29571:
            case 29603:
            case 29605:
            case 29606:
            case 29608:
            case 29610:
            case 29611:
            case 29613:
            case 29614:
            case 29615:
            case 29616:
            case 29617:
            case 29619:
            case 29620:
            case 29621:
            case 29622:
            case 29628:
            case 29629:
            case 29630:
            case 29642:
            case 29643:
            case 29645:
            case 29648:
            case 29649:
            case 29654:
            case 29656:
            case 29657:
            case 29658:
            case 29662:
            case 29663:
            case 29664:
            case 29668:
            case 29669:
            case 29671:
            case 29672:
            case 29688:
            case 29692:
            case 29693:
            case 29694:
            case 29695:
            case 29696:
            case 29697:
            case 29698:
            case 29699:
            case 29700:
            case 29728:
            case 29729:
            case 30303:
            case 30304:
            case 30305:
            case 30306:
            case 30307:
            case 30308:
            case 30309:
            case 30310:
            case 30311:
            case 30312:
            case 30313:
            case 30314:
            case 30315:
            case 30316:
            case 30317:
            case 30318:
            case 30325:
            case 30329:
            case 30332:
            case 30334:
            case 30337:
            case 30341:
            case 30342:
            case 30343:
            case 30344:
            case 30346:
            case 30347:
            case 30348:
            case 30349:
            case 30547:
            case 30548:
            case 30549:
            case 30551:
            case 30552:
            case 30556:
            case 30558:
            case 30560:
            case 30561:
            case 30563:
            case 30565:
            case 30566:
            case 30568:
            case 30569:
            case 30570:
            case 30573:
            case 30574:
            case 30575:
            case 31048:
            case 31049:
            case 31050:
            case 31051:
            case 31052:
            case 31053:
            case 31054:
            case 31055:
            case 31056:
            case 31057:
            case 31058:
            case 31060:
            case 31061:
            case 31062:
            case 31063:
            case 31064:
            case 31065:
            case 31066:
            case 31067:
            case 31068:
            case 31070:
            case 31071:
            case 31072:
            case 31076:
            case 31077:
            case 31078:
            case 31079:
            case 31080:
            case 31081:
            case 31082:
            case 31083:
            case 31084:
            case 31085:
            case 31087:
            case 31088:
            case 31089:
            case 31090:
            case 31091:
            case 31092:
            case 31094:
            case 31095:
            case 31096:
            case 31097:
            case 31098:
            case 31099:
            case 31100:
            case 31101:
            case 31102:
            case 31103:
            case 31104:
            case 31105:
            case 31106:
            case 31107:
            case 31108:
            case 31109:
            case 31110:
            case 31111:
            case 31112:
            case 31113:
            case 31149:
            case 31252:
            case 31373:
            case 31430:
            case 31431:
            case 31432:
            case 31433:
            case 31434:
            case 31435:
            case 31437:
            case 31438:
            case 31440:
            case 31441:
            case 31442:
            case 31443:
            case 31444:
            case 31448:
            case 31449:
            case 31450:
            case 31451:
            case 31452:
            case 31453:
            case 31454:
            case 31455:
            case 31456:
            case 31459:
            case 31460:
            case 31461:
            case 32178:
            case 32179:
            case 32284:
            case 32285:
            case 32454:
            case 32455:
            case 32456:
            case 32457:
            case 32458:
            case 32461:
            case 32462:
            case 32463:
            case 32464:
            case 32465:
            case 32466:
            case 32467:
            case 32468:
            case 32469:
            case 32470:
            case 32471:
            case 32472:
            case 32473:
            case 32478:
            case 32479:
            case 32480:
            case 32481:
            case 32482:
            case 32485:
            case 32487:
            case 32488:
            case 32489:
            case 32490:
            case 32493:
            case 32494:
            case 32495:
            case 32496:
            case 32497:
            case 32498:
            case 32499:
            case 32500:
            case 32501:
            case 32502:
            case 32503:
            case 32655:
            case 32656:
            case 32657:
            case 32664:
            case 32665:
            case 32667:
            case 32765:
            case 32766:
            case 32801:
            case 32807:
            case 32808:
            case 32809:
            case 32810:
            case 32814:
            case 32866:
            case 32867:
            case 32868:
            case 32869:
            case 32870:
            case 32871:
            case 32872:
            case 32873:
            case 32874:
            case 33276:
            case 33277:
            case 33278:
            case 33279:
            case 33284:
            case 33285:
            case 33286:
            case 33287:
            case 33288:
            case 33289:
            case 33290:
            case 33291:
            case 33292:
            case 33293:
            case 33294:
            case 33295:
            case 33296:
            case 33732:
            case 33733:
            case 33738:
            case 33740:
            case 33741:
            case 33990:
            case 33991:
            case 33992:
            case 33993:
            case 33994:
            case 33995:
            case 33996:
            case 33997:
            case 33999:
            case 34001:
            case 34002:
            case 34003:
            case 34004:
            case 34005:
            case 34006:
            case 34007:
            case 34008:
            case 34009:
            case 34010:
            case 34069:
            case 34529:
            case 34530:
            case 34533:
            case 34534:
            case 34535:
            case 34537:
            case 34538:
            case 34540:
            case 34541:
            case 34542:
            case 34543:
            case 34544:
            case 34545:
            case 34546:
            case 34547:
            case 34548:
            case 34590:
            case 34607:
            case 34608:
            case 34955:
            case 34959:
            case 34960:
            case 34961:
            case 34979:
            case 34981:
            case 34982:
            case 34983:
            case 35520:
            case 35521:
            case 35522:
            case 35523:
            case 35524:
            case 35525:
            case 35526:
            case 35527:
            case 35528:
            case 35529:
            case 35530:
            case 35531:
            case 35532:
            case 35533:
            case 35534:
            case 35535:
            case 35536:
            case 35537:
            case 35538:
            case 35539:
            case 35540:
            case 35543:
            case 35544:
            case 35549:
            case 35554:
            case 35555:
            case 35557:
            case 35558:
            case 35559:
            case 35560:
            case 35561:
            case 35562:
            case 35563:
            case 35564:
            case 35567:
            case 35568:
            case 35572:
            case 35573:
            case 35574:
            case 35575:
            case 35576:
            case 35577:
            case 35580:
            case 35582:
            case 35584:
            case 35585:
            case 35587:
            case 35588:
            case 35589:
            case 35590:
            case 35591:
            case 36074:
            case 36075:
            case 36076:
            case 36077:
            case 36078:
            case 36079:
            case 36122:
            case 36124:
            case 36125:
            case 36126:
            case 36128:
            case 36129:
            case 36130:
            case 36131:
            case 36133:
            case 36134:
            case 36135:
            case 36136:
            case 36137:
            case 36210:
            case 36256:
            case 36257:
            case 36258:
            case 36259:
            case 36260:
            case 36261:
            case 36262:
            case 36263:
            case 36315:
            case 36316:
            case 36317:
            case 36318:
            case 36349:
            case 36351:
            case 36352:
            case 36353:
            case 36355:
            case 36357:
            case 36358:
            case 36359:
            case 36389:
            case 36390:
            case 36391:
            case 36392:
            case 36523:
            case 36524:
            case 36525:
            case 36526:
            case 36665:
            case 36667:
            case 36668:
            case 36669:
            case 36670:
            case 36672:
            case 36686:
            case 36954:
            case 36955:
            case 37818:
            case 37836:
            case 37855:
            case 37873:
            case 37882:
            case 37883:
            case 37884:
            case 38068:
            case 38070:
            case 38175:
            case 38473:
            case 38475:
            case 38476:
            case 38477:
            case 38478:
            case 38479:
            case 38503:
            case 38504:
            case 38867:
            case 38868:
            case 38960:
            case 38961:
            case 38962:
            case 39451:
            case 39452:
            case 39455:
            case 39458:
            case 39462:
            case 39463:
            case 39466:
            case 39467:
            case 39470:
            case 39471:
            case 39636:
            case 39637:
            case 39638:
            case 39639:
            case 39705:
            case 39706:
            case 39710:
            case 39711:
            case 39712:
            case 39713:
            case 39714:
            case 39715:
            case 39716:
            case 39717:
            case 39718:
            case 39719:
            case 39720:
            case 39721:
            case 39722:
            case 39723:
            case 39724:
            case 39725:
            case 39727:
            case 39728:
            case 39729:
            case 39730:
            case 39731:
            case 39732:
            case 39733:
            case 39734:
            case 39735:
            case 39736:
            case 39737:
            case 39738:
            case 39739:
            case 39740:
            case 39741:
            case 39742:
            case 39895:
            case 39961:
            case 39963:
            case 39971:
            case 39973:
            case 39997:
            case 40001:
            case 40002:
            case 40003:
            case 40004:
            case 40005:
            case 40006:
            case 40020:
            case 40021:
            case 40023:
            case 40024:
            case 40033:
            case 40034:
            case 40035:
            case 40036:
            case 40060:
            case 40514:
            case 41132:
            case 41133:
            case 41134:
            case 41135:
            case 41156:
            case 41157:
            case 41158:
            case 41160:
            case 41161:
            case 41162:
            case 41163:
            case 41164:
            case 41205:
            case 41206:
            case 41207:
            case 41208:
            case 41307:
            case 41314:
            case 41414:
            case 41415:
            case 41418:
            case 41420:
            case 41429:
            case 41458:
            case 41500:
            case 41501:
            case 41502:
            case 41503:
            case 42296:
            case 42302:
            case 42305:
            case 42546:
            case 42558:
            case 42588:
            case 42589:
            case 42590:
            case 42591:
            case 42592:
            case 42593:
            case 42613:
            case 42615:
            case 42620:
            case 42662:
            case 42688:
            case 42731:
            case 42736:
            case 42974:
            case 43493:
            case 43549:
            case 43676:
            case 43707:
            case 43758:
            case 43761:
            case 43765:
            case 43772:
            case 43779:
            case 43846:
            case 44155:
            case 44157:
            case 44343:
            case 44344:
            case 44359:
            case 44383:
            case 44391:
            case 44483:
            case 44484:
            case 44488:
            case 44489:
            case 44492:
            case 44494:
            case 44500:
            case 44506:
            case 44508:
            case 44509:
            case 44510:
            case 44513:
            case 44524:
            case 44528:
            case 44529:
            case 44555:
            case 44556:
            case 44575:
            case 44576:
            case 44582:
            case 44584:
            case 44588:
            case 44589:
            case 44590:
            case 44591:
            case 44592:
            case 44593:
            case 44595:
            case 44596:
            case 44598:
            case 44612:
            case 44616:
            case 44621:
            case 44623:
            case 44625:
            case 44629:
            case 44630:
            case 44631:
            case 44633:
            case 44635:
            case 44636:
            case 44645:
            case 44768:
            case 44770:
            case 44794:
            case 44950:
            case 44953:
            case 44958:
            case 44970:
            case 45022:
            case 45061:
            case 45100:
            case 45117:
            case 45363:
            case 45542:
            case 45545:
            case 45546:
            case 45549:
            case 45550:
            case 45551:
            case 45552:
            case 45553:
            case 45554:
            case 45555:
            case 45556:
            case 45557:
            case 45558:
            case 45559:
            case 45560:
            case 45561:
            case 45562:
            case 45563:
            case 45564:
            case 45565:
            case 45566:
            case 45567:
            case 45568:
            case 45569:
            case 45570:
            case 45571:
            case 45695:
            case 45765:
            case 46113:
            case 46122:
            case 46123:
            case 46124:
            case 46125:
            case 46126:
            case 46127:
            case 46128:
            case 46129:
            case 46130:
            case 46131:
            case 46132:
            case 46133:
            case 46134:
            case 46135:
            case 46136:
            case 46137:
            case 46138:
            case 46139:
            case 46140:
            case 46141:
            case 46142:
            case 46144:
            case 46403:
            case 46404:
            case 46405:
            case 46578:
            case 46594:
            case 46597:
            case 46601:
            case 46684:
            case 46688:
            case 46697:
            case 46775:
            case 46776:
            case 46777:
            case 46778:
            case 46779:
            case 46803:
            case 47046:
            case 47048:
            case 47049:
            case 47050:
            case 47051:
            case 47053:
            case 47054:
            case 47055:
            case 47056:
            case 47280:
            case 47672:
            case 47766:
            case 47898:
            case 47899:
            case 47900:
            case 47901:
            case 48121:
            case 48247:
            case 48248:
            case 48789:
            case 49677:
            case 50194:
            case 50598:
            case 50599:
            case 50600:
            case 50601:
            case 50602:
            case 50603:
            case 50604:
            case 50605:
            case 50606:
            case 50607:
            case 50608:
            case 50609:
            case 50610:
            case 50611:
            case 50612:
            case 50614:
            case 50616:
            case 50617:
            case 50618:
            case 50619:
            case 50620:
            case 50644:
            case 50647:
            case 50936:
            case 50938:
            case 50939:
            case 50940:
            case 50941:
            case 50942:
            case 50943:
            case 50944:
            case 50945:
            case 50946:
            case 50947:
            case 50948:
            case 50949:
            case 50950:
            case 50951:
            case 50952:
            case 50953:
            case 50954:
            case 50955:
            case 50956:
            case 50957:
            case 50958:
            case 50959:
            case 50960:
            case 50961:
            case 50962:
            case 50963:
            case 50964:
            case 50965:
            case 50966:
            case 50967:
            case 50970:
            case 50971:
            case 51296:
            case 51300:
            case 51302:
            case 51304:
            case 51306:
            case 51309:
            case 51311:
            case 51313:
            case 51568:
            case 51569:
            case 51570:
            case 51571:
            case 51572:
            case 52175:
            case 52567:
            case 52568:
            case 52569:
            case 52570:
            case 52571:
            case 52572:
            case 52733:
            case 52739:
            case 52840:
            case 52843:
            case 53042:
            case 53056:
            case 53281:
            case 53462:
            case 53771:
            case 53773:
            case 53774:
            case 53775:
            case 53776:
            case 53777:
            case 53779:
            case 53780:
            case 53781:
            case 53782:
            case 53783:
            case 53784:
            case 53812:
            case 53830:
            case 53831:
            case 53832:
            case 53834:
            case 53835:
            case 53836:
            case 53837:
            case 53838:
            case 53839:
            case 53840:
            case 53841:
            case 53842:
            case 53843:
            case 53844:
            case 53845:
            case 53847:
            case 53848:
            case 53852:
            case 53853:
            case 53854:
            case 53855:
            case 53856:
            case 53857:
            case 53859:
            case 53860:
            case 53861:
            case 53862:
            case 53863:
            case 53864:
            case 53865:
            case 53866:
            case 53867:
            case 53868:
            case 53869:
            case 53870:
            case 53871:
            case 53872:
            case 53873:
            case 53874:
            case 53875:
            case 53876:
            case 53877:
            case 53878:
            case 53879:
            case 53880:
            case 53881:
            case 53882:
            case 53883:
            case 53884:
            case 53885:
            case 53886:
            case 53887:
            case 53888:
            case 53889:
            case 53890:
            case 53891:
            case 53892:
            case 53893:
            case 53894:
            case 53895:
            case 53898:
            case 53899:
            case 53900:
            case 53901:
            case 53902:
            case 53903:
            case 53904:
            case 53905:
            case 53916:
            case 53917:
            case 53918:
            case 53919:
            case 53920:
            case 53921:
            case 53922:
            case 53923:
            case 53924:
            case 53925:
            case 53926:
            case 53927:
            case 53928:
            case 53929:
            case 53930:
            case 53931:
            case 53932:
            case 53933:
            case 53934:
            case 53936:
            case 53937:
            case 53938:
            case 53939:
            case 53940:
            case 53941:
            case 53942:
            case 53943:
            case 53945:
            case 53946:
            case 53947:
            case 53948:
            case 53949:
            case 53950:
            case 53951:
            case 53952:
            case 53953:
            case 53954:
            case 53955:
            case 53956:
            case 53957:
            case 53958:
            case 53959:
            case 53960:
            case 53961:
            case 53962:
            case 53963:
            case 53964:
            case 53965:
            case 53966:
            case 53967:
            case 53968:
            case 53969:
            case 53970:
            case 53971:
            case 53972:
            case 53973:
            case 53974:
            case 53975:
            case 53976:
            case 53977:
            case 53978:
            case 53979:
            case 53980:
            case 53981:
            case 53982:
            case 53983:
            case 53984:
            case 53985:
            case 53986:
            case 53987:
            case 53988:
            case 53989:
            case 53990:
            case 53991:
            case 53992:
            case 53993:
            case 53994:
            case 53995:
            case 53996:
            case 53997:
            case 53998:
            case 54000:
            case 54001:
            case 54002:
            case 54003:
            case 54004:
            case 54005:
            case 54006:
            case 54007:
            case 54008:
            case 54009:
            case 54010:
            case 54011:
            case 54012:
            case 54013:
            case 54014:
            case 54017:
            case 54019:
            case 54020:
            case 54023:
            case 54213:
            case 54218:
            case 54220:
            case 54221:
            case 54222:
            case 54353:
            case 54550:
            case 54551:
            case 54552:
            case 54553:
            case 54554:
            case 54555:
            case 54556:
            case 54557:
            case 54736:
            case 54793:
            case 54917:
            case 54918:
            case 54941:
            case 54944:
            case 54945:
            case 54946:
            case 54947:
            case 54948:
            case 54949:
            case 54978:
            case 54979:
            case 54980:
            case 54981:
            case 54998:
            case 54999:
            case 55002:
            case 55013:
            case 55014:
            case 55015:
            case 55016:
            case 55017:
            case 55055:
            case 55056:
            case 55057:
            case 55058:
            case 55174:
            case 55177:
            case 55179:
            case 55181:
            case 55182:
            case 55183:
            case 55184:
            case 55185:
            case 55186:
            case 55187:
            case 55199:
            case 55200:
            case 55201:
            case 55202:
            case 55203:
            case 55204:
            case 55206:
            case 55243:
            case 55252:
            case 55298:
            case 55300:
            case 55301:
            case 55302:
            case 55303:
            case 55304:
            case 55305:
            case 55306:
            case 55307:
            case 55308:
            case 55309:
            case 55310:
            case 55311:
            case 55312:
            case 55369:
            case 55370:
            case 55371:
            case 55372:
            case 55373:
            case 55374:
            case 55375:
            case 55376:
            case 55377:
            case 55384:
            case 55386:
            case 55387:
            case 55388:
            case 55389:
            case 55390:
            case 55392:
            case 55393:
            case 55394:
            case 55395:
            case 55396:
            case 55397:
            case 55398:
            case 55399:
            case 55400:
            case 55401:
            case 55402:
            case 55403:
            case 55404:
            case 55405:
            case 55407:
            case 55534:
            case 55628:
            case 55641:
            case 55642:
            case 55656:
            case 55732:
            case 55769:
            case 55777:
            case 55834:
            case 55835:
            case 55839:
            case 55898:
            case 55899:
            case 55900:
            case 55901:
            case 55902:
            case 55903:
            case 55904:
            case 55906:
            case 55907:
            case 55908:
            case 55910:
            case 55911:
            case 55913:
            case 55914:
            case 55919:
            case 55920:
            case 55921:
            case 55922:
            case 55923:
            case 55924:
            case 55925:
            case 55941:
            case 55943:
            case 55993:
            case 55994:
            case 55995:
            case 55996:
            case 55997:
            case 55998:
            case 55999:
            case 56000:
            case 56001:
            case 56002:
            case 56003:
            case 56004:
            case 56005:
            case 56006:
            case 56007:
            case 56008:
            case 56009:
            case 56010:
            case 56011:
            case 56014:
            case 56015:
            case 56016:
            case 56017:
            case 56018:
            case 56019:
            case 56020:
            case 56021:
            case 56022:
            case 56023:
            case 56024:
            case 56025:
            case 56026:
            case 56027:
            case 56028:
            case 56029:
            case 56030:
            case 56031:
            case 56034:
            case 56039:
            case 56048:
            case 56049:
            case 56052:
            case 56053:
            case 56054:
            case 56055:
            case 56056:
            case 56074:
            case 56076:
            case 56077:
            case 56079:
            case 56081:
            case 56083:
            case 56084:
            case 56085:
            case 56086:
            case 56087:
            case 56088:
            case 56089:
            case 56193:
            case 56194:
            case 56195:
            case 56196:
            case 56197:
            case 56199:
            case 56201:
            case 56202:
            case 56203:
            case 56205:
            case 56206:
            case 56208:
            case 56234:
            case 56273:
            case 56280:
            case 56349:
            case 56357:
            case 56400:
            case 56459:
            case 56460:
            case 56461:
            case 56462:
            case 56463:
            case 56464:
            case 56466:
            case 56467:
            case 56468:
            case 56469:
            case 56470:
            case 56471:
            case 56472:
            case 56473:
            case 56474:
            case 56475:
            case 56476:
            case 56477:
            case 56478:
            case 56479:
            case 56496:
            case 56497:
            case 56498:
            case 56499:
            case 56500:
            case 56501:
            case 56514:
            case 56519:
            case 56530:
            case 56531:
            case 56549:
            case 56550:
            case 56551:
            case 56552:
            case 56553:
            case 56554:
            case 56555:
            case 56556:
            case 56574:
            case 56943:
            case 56944:
            case 56945:
            case 56946:
            case 56947:
            case 56948:
            case 56949:
            case 56950:
            case 56951:
            case 56952:
            case 56953:
            case 56954:
            case 56955:
            case 56956:
            case 56957:
            case 56958:
            case 56959:
            case 56960:
            case 56961:
            case 56963:
            case 56965:
            case 56968:
            case 56971:
            case 56972:
            case 56973:
            case 56974:
            case 56975:
            case 56976:
            case 56977:
            case 56978:
            case 56979:
            case 56980:
            case 56981:
            case 56982:
            case 56983:
            case 56984:
            case 56985:
            case 56986:
            case 56987:
            case 56988:
            case 56989:
            case 56990:
            case 56991:
            case 56994:
            case 56995:
            case 56996:
            case 56997:
            case 56998:
            case 56999:
            case 57000:
            case 57001:
            case 57002:
            case 57003:
            case 57004:
            case 57005:
            case 57006:
            case 57007:
            case 57008:
            case 57009:
            case 57010:
            case 57011:
            case 57012:
            case 57013:
            case 57014:
            case 57019:
            case 57020:
            case 57021:
            case 57022:
            case 57023:
            case 57024:
            case 57025:
            case 57026:
            case 57027:
            case 57028:
            case 57029:
            case 57030:
            case 57031:
            case 57032:
            case 57033:
            case 57034:
            case 57035:
            case 57036:
            case 57112:
            case 57113:
            case 57114:
            case 57115:
            case 57116:
            case 57117:
            case 57119:
            case 57120:
            case 57121:
            case 57122:
            case 57123:
            case 57124:
            case 57125:
            case 57126:
            case 57127:
            case 57128:
            case 57129:
            case 57130:
            case 57131:
            case 57132:
            case 57133:
            case 57151:
            case 57152:
            case 57153:
            case 57154:
            case 57155:
            case 57156:
            case 57157:
            case 57158:
            case 57159:
            case 57160:
            case 57161:
            case 57162:
            case 57163:
            case 57164:
            case 57165:
            case 57166:
            case 57167:
            case 57168:
            case 57169:
            case 57170:
            case 57172:
            case 57181:
            case 57183:
            case 57184:
            case 57185:
            case 57186:
            case 57187:
            case 57188:
            case 57189:
            case 57190:
            case 57191:
            case 57192:
            case 57193:
            case 57194:
            case 57195:
            case 57196:
            case 57197:
            case 57198:
            case 57199:
            case 57200:
            case 57201:
            case 57202:
            case 57207:
            case 57208:
            case 57209:
            case 57210:
            case 57211:
            case 57212:
            case 57213:
            case 57214:
            case 57215:
            case 57216:
            case 57217:
            case 57218:
            case 57219:
            case 57220:
            case 57221:
            case 57222:
            case 57223:
            case 57224:
            case 57225:
            case 57226:
            case 57227:
            case 57228:
            case 57229:
            case 57230:
            case 57231:
            case 57232:
            case 57233:
            case 57234:
            case 57235:
            case 57236:
            case 57237:
            case 57238:
            case 57239:
            case 57240:
            case 57241:
            case 57242:
            case 57243:
            case 57244:
            case 57245:
            case 57246:
            case 57247:
            case 57248:
            case 57249:
            case 57250:
            case 57251:
            case 57252:
            case 57253:
            case 57257:
            case 57258:
            case 57259:
            case 57260:
            case 57261:
            case 57262:
            case 57263:
            case 57264:
            case 57265:
            case 57266:
            case 57267:
            case 57268:
            case 57269:
            case 57270:
            case 57271:
            case 57272:
            case 57273:
            case 57274:
            case 57275:
            case 57276:
            case 57277:
            case 57421:
            case 57423:
            case 57425:
            case 57427:
            case 57433:
            case 57434:
            case 57435:
            case 57436:
            case 57437:
            case 57438:
            case 57439:
            case 57440:
            case 57441:
            case 57442:
            case 57443:
            case 57690:
            case 57691:
            case 57692:
            case 57694:
            case 57696:
            case 57699:
            case 57701:
            case 57703:
            case 57704:
            case 57706:
            case 57707:
            case 57708:
            case 57709:
            case 57710:
            case 57711:
            case 57712:
            case 57713:
            case 57714:
            case 57715:
            case 57716:
            case 57719:
            case 58065:
            case 58141:
            case 58142:
            case 58143:
            case 58144:
            case 58145:
            case 58146:
            case 58147:
            case 58148:
            case 58149:
            case 58150:
            case 58286:
            case 58287:
            case 58288:
            case 58289:
            case 58296:
            case 58297:
            case 58298:
            case 58299:
            case 58300:
            case 58301:
            case 58302:
            case 58303:
            case 58305:
            case 58306:
            case 58307:
            case 58308:
            case 58310:
            case 58311:
            case 58312:
            case 58313:
            case 58314:
            case 58315:
            case 58316:
            case 58317:
            case 58318:
            case 58319:
            case 58320:
            case 58321:
            case 58322:
            case 58323:
            case 58324:
            case 58325:
            case 58326:
            case 58327:
            case 58328:
            case 58329:
            case 58330:
            case 58331:
            case 58332:
            case 58333:
            case 58336:
            case 58337:
            case 58338:
            case 58339:
            case 58340:
            case 58341:
            case 58342:
            case 58343:
            case 58344:
            case 58345:
            case 58346:
            case 58347:
            case 58472:
            case 58473:
            case 58476:
            case 58478:
            case 58480:
            case 58481:
            case 58482:
            case 58483:
            case 58484:
            case 58485:
            case 58486:
            case 58487:
            case 58488:
            case 58489:
            case 58490:
            case 58491:
            case 58492:
            case 58507:
            case 58512:
            case 58521:
            case 58523:
            case 58525:
            case 58527:
            case 58528:
            case 58565:
            case 58868:
            case 58871:
            case 58954:
            case 59315:
            case 59326:
            case 59338:
            case 59339:
            case 59340:
            case 59387:
            case 59390:
            case 59405:
            case 59406:
            case 59436:
            case 59438:
            case 59440:
            case 59441:
            case 59442:
            case 59475:
            case 59478:
            case 59480:
            case 59484:
            case 59486:
            case 59487:
            case 59488:
            case 59489:
            case 59490:
            case 59491:
            case 59493:
            case 59494:
            case 59495:
            case 59496:
            case 59497:
            case 59498:
            case 59499:
            case 59500:
            case 59501:
            case 59502:
            case 59503:
            case 59504:
            case 59559:
            case 59560:
            case 59561:
            case 59582:
            case 59583:
            case 59584:
            case 59585:
            case 59586:
            case 59587:
            case 59588:
            case 59589:
            case 59619:
            case 59621:
            case 59625:
            case 59636:
            case 59759:
            case 60336:
            case 60337:
            case 60350:
            case 60354:
            case 60355:
            case 60356:
            case 60357:
            case 60365:
            case 60366:
            case 60367:
            case 60396:
            case 60403:
            case 60405:
            case 60583:
            case 60584:
            case 60599:
            case 60600:
            case 60601:
            case 60604:
            case 60605:
            case 60606:
            case 60607:
            case 60608:
            case 60609:
            case 60611:
            case 60613:
            case 60616:
            case 60619:
            case 60620:
            case 60621:
            case 60622:
            case 60623:
            case 60624:
            case 60627:
            case 60629:
            case 60630:
            case 60631:
            case 60637:
            case 60640:
            case 60643:
            case 60645:
            case 60647:
            case 60649:
            case 60651:
            case 60652:
            case 60653:
            case 60655:
            case 60658:
            case 60660:
            case 60663:
            case 60665:
            case 60666:
            case 60668:
            case 60669:
            case 60671:
            case 60691:
            case 60692:
            case 60697:
            case 60702:
            case 60703:
            case 60704:
            case 60705:
            case 60706:
            case 60707:
            case 60711:
            case 60712:
            case 60714:
            case 60715:
            case 60716:
            case 60718:
            case 60720:
            case 60721:
            case 60723:
            case 60725:
            case 60727:
            case 60728:
            case 60729:
            case 60730:
            case 60731:
            case 60732:
            case 60734:
            case 60735:
            case 60737:
            case 60743:
            case 60746:
            case 60747:
            case 60748:
            case 60749:
            case 60750:
            case 60751:
            case 60752:
            case 60754:
            case 60755:
            case 60756:
            case 60757:
            case 60758:
            case 60759:
            case 60760:
            case 60761:
            case 60763:
            case 60767:
            case 60874:
            case 60893:
            case 60969:
            case 60971:
            case 60990:
            case 60993:
            case 60994:
            case 60996:
            case 60997:
            case 60998:
            case 60999:
            case 61000:
            case 61002:
            case 61008:
            case 61009:
            case 61010:
            case 61117:
            case 61118:
            case 61119:
            case 61120:
            case 61177:
            case 61288:
            case 61471:
            case 61482:
            case 61677:
            case 62044:
            case 62045:
            case 62049:
            case 62050:
            case 62051:
            case 62162:
            case 62176:
            case 62177:
            case 62202:
            case 62213:
            case 62242:
            case 62256:
            case 62257:
            case 62350:
            case 62409:
            case 62410:
            case 62448:
            case 62941:
            case 62948:
            case 62959:
            case 63182:
            case 63187:
            case 63188:
            case 63189:
            case 63190:
            case 63191:
            case 63192:
            case 63194:
            case 63195:
            case 63196:
            case 63197:
            case 63198:
            case 63199:
            case 63200:
            case 63201:
            case 63203:
            case 63204:
            case 63205:
            case 63206:
            case 63732:
            case 63742:
            case 63743:
            case 63746:
            case 63750:
            case 63765:
            case 63770:
            case 63924:
            case 64051:
            case 64053:
            case 64054:
            case 64246:
            case 64247:
            case 64248:
            case 64249:
            case 64250:
            case 64251:
            case 64252:
            case 64253:
            case 64254:
            case 64255:
            case 64256:
            case 64257:
            case 64258:
            case 64259:
            case 64260:
            case 64261:
            case 64262:
            case 64266:
            case 64267:
            case 64268:
            case 64270:
            case 64271:
            case 64273:
            case 64274:
            case 64275:
            case 64276:
            case 64277:
            case 64278:
            case 64279:
            case 64280:
            case 64281:
            case 64282:
            case 64283:
            case 64284:
            case 64285:
            case 64286:
            case 64287:
            case 64288:
            case 64289:
            case 64291:
            case 64294:
            case 64295:
            case 64296:
            case 64297:
            case 64298:
            case 64299:
            case 64300:
            case 64302:
            case 64303:
            case 64304:
            case 64305:
            case 64307:
            case 64308:
            case 64309:
            case 64310:
            case 64311:
            case 64312:
            case 64313:
            case 64314:
            case 64315:
            case 64316:
            case 64317:
            case 64318:
            case 64358:
            case 64441:
            case 64579:
            case 64661:
            case 64725:
            case 64726:
            case 64727:
            case 64728:
            case 64729:
            case 64730:
            case 65245:
            case 65454:
            case 66034:
            case 66035:
            case 66036:
            case 66037:
            case 66038:
            case 66338:
            case 66428:
            case 66429:
            case 66430:
            case 66431:
            case 66432:
            case 66433:
            case 66434:
            case 66435:
            case 66436:
            case 66437:
            case 66438:
            case 66439:
            case 66440:
            case 66441:
            case 66442:
            case 66443:
            case 66444:
            case 66445:
            case 66446:
            case 66447:
            case 66448:
            case 66449:
            case 66450:
            case 66451:
            case 66452:
            case 66453:
            case 66497:
            case 66498:
            case 66499:
            case 66500:
            case 66501:
            case 66502:
            case 66503:
            case 66504:
            case 66505:
            case 66506:
            case 66553:
            case 66554:
            case 66555:
            case 66556:
            case 66557:
            case 66558:
            case 66559:
            case 66560:
            case 66561:
            case 66562:
            case 66563:
            case 66564:
            case 66565:
            case 66566:
            case 66567:
            case 66568:
            case 66569:
            case 66570:
            case 66571:
            case 66572:
            case 66573:
            case 66574:
            case 66575:
            case 66576:
            case 66577:
            case 66578:
            case 66579:
            case 66580:
            case 66581:
            case 66582:
            case 66583:
            case 66584:
            case 66585:
            case 66586:
            case 66587:
            case 66658:
            case 66659:
            case 66660:
            case 66662:
            case 66663:
            case 66664:
            case 67025:
            case 67064:
            case 67065:
            case 67066:
            case 67079:
            case 67080:
            case 67081:
            case 67082:
            case 67083:
            case 67084:
            case 67085:
            case 67086:
            case 67087:
            case 67091:
            case 67092:
            case 67093:
            case 67094:
            case 67095:
            case 67096:
            case 67130:
            case 67131:
            case 67132:
            case 67133:
            case 67134:
            case 67135:
            case 67136:
            case 67137:
            case 67138:
            case 67139:
            case 67140:
            case 67141:
            case 67142:
            case 67143:
            case 67144:
            case 67145:
            case 67146:
            case 67147:
            case 67326:
            case 67600:
            case 67790:
            case 67839:
            case 67920:
            case 68067:
            case 68166:
            case 68253:
            case 69385:
            case 69386:
            case 69388:
            case 69412:
            case 70550:
            case 70551:
            case 70552:
            case 70553:
            case 70554:
            case 70555:
            case 70556:
            case 70557:
            case 70558:
            case 70559:
            case 70560:
            case 70561:
            case 70562:
            case 70563:
            case 70565:
            case 70566:
            case 70567:
            case 70568:
            case 71015:
            case 71101:
            case 71102:
            case 71692:
            case 72952:
            case 72953:
            case 75597:
                return true;
        }
        return false;
    }

    function _CheckRiding($SKILL, $CUR, $CHAR_REALM, $GUID, $LEVEL)
    {
        $SpellID = -1;
        switch( $SKILL ) 
        {
            case "RIDING":
            case "MONTE":
            case "REITEN":
            case "EQUITACIÓN":
            case "ВЕРХОВАЯ ЕЗДА":
                switch( $CUR ) 
                {
                    case 75:
                        $SpellID = 33388;
                        break;
                    case 150:
                        $SpellID = 33391;
                        break;
                    case 225:
                        $SpellID = 34090;
                        break;
                    case 300:
                        $SpellID = 34091;
                        if( $LEVEL == 80 ) 
                        {
                            $this->LearnSeparateSpell(54197, $GUID, $CHAR_REALM);
                        }

                        break;
                    default:
                        return false;
                }
            default:
                return false;
        }
        $this->LearnSeparateSpell($SpellID, $GUID, $CHAR_REALM);
        return true;
    }

    function GetSkillID($skill, $locale)
    {
        switch( $locale ) 
        {
            case "FRFR":
                switch( $skill ) 
                {
                    case "EPÉES":
                        return 43;
                    case "HACHES":
                        return 44;
                    case "ARCS":
                        return 45;
                    case "ARMES À FEU":
                        return 46;
                    case "MASSE":
                        return 54;
                    case "EPÉES À DEUX MAINS":
                        return 55;
                    case "DÉFENSE":
                        return 95;
                    case "SECOURISME":
                        return 129;
                    case "BÂTONS":
                        return 136;
                    case "MASSES À DEUX MAINS":
                        return 160;
                    case "MAINS NUES":
                        return 162;
                    case "FORGE":
                        return 164;
                    case "TRAVAIL DU CUIR":
                        return 165;
                    case "ALCHIMIE":
                        return 171;
                    case "HACHES À DEUX MAINS":
                        return 172;
                    case "DAGUES":
                        return 173;
                    case "ARMES DE JET":
                        return 176;
                    case "HERBORISTERIE":
                        return 182;
                    case "CUISINE":
                        return 185;
                    case "MINAGE":
                        return 186;
                    case "COUTURE":
                        return 197;
                    case "INGÉNIERIE":
                        return 202;
                    case "ARBALÈTES":
                        return 226;
                    case "BAGUETTES":
                        return 228;
                    case "ARMES D'HAST":
                        return 229;
                    case "ARMURE EN PLAQUES":
                        return 293;
                    case "ENCHANTEMENT":
                        return 333;
                    case "PÊCHE":
                        return 356;
                    case "DÉPEÇAGE":
                        return 393;
                    case "MAILLES":
                        return 413;
                    case "CUIR":
                        return 414;
                    case "TISSU":
                        return 415;
                    case "BOUCLIER":
                        return 433;
                    case "ARMES DE PUGILAT":
                        return 473;
                    case "CROCHETAGE":
                        return 633;
                    case "JOAILLERIE":
                        return 755;
                    case "CALLIGRAPHIE":
                        return 773;
                    case "RUNEFORGER":
                        return 776;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "DEDE":
                switch( $skill ) 
                {
                    case "SCHWERTER":
                        return 43;
                    case "AXTE":
                        return 44;
                    case "BOGEN":
                        return 45;
                    case "SCHUSSWAFFEN":
                        return 46;
                    case "STREITKOLBEN":
                        return 54;
                    case "ZWEIHANDSCHWERTER":
                        return 55;
                    case "VERTEIDIGUNG":
                        return 95;
                    case "ERSTE HILFE":
                        return 129;
                    case "STABE":
                        return 136;
                    case "ZWEIHANDSTREITKOLBEN":
                        return 160;
                    case "UNBEWAFFNET":
                        return 162;
                    case "SCHMIEDEKUNST":
                        return 164;
                    case "LEDERVERARBEITUNG":
                        return 165;
                    case "ALCHEMIE":
                        return 171;
                    case "ZWEIHANDAXTE":
                        return 172;
                    case "DOLCHE":
                        return 173;
                    case "WURFWAFFEN":
                        return 176;
                    case "KRAUTERKUNDE":
                        return 182;
                    case "KOCHKUNST":
                        return 185;
                    case "BERGBAU":
                        return 186;
                    case "SCHNEIDEREI":
                        return 197;
                    case "INGENIEURSKUNST":
                        return 202;
                    case "ARMBRUSTE":
                        return 226;
                    case "ZAUBERSTABE":
                        return 228;
                    case "STANGENWAFFEN":
                        return 229;
                    case "PLATTENPANZER":
                        return 293;
                    case "VERZAUBERKUNST":
                        return 333;
                    case "ANGELN":
                        return 356;
                    case "KURSCHNEREI":
                        return 393;
                    case "SCHWERE RUSTUNG":
                        return 413;
                    case "LEDER":
                        return 414;
                    case "STOFF":
                        return 415;
                    case "SCHILD":
                        return 433;
                    case "FAUSTWAFFEN":
                        return 473;
                    case "SCHLOSSKNACKEN":
                        return 633;
                    case "JUWELENSCHLEIFEN":
                        return 755;
                    case "INSCHRIFTENKUNDE":
                        return 773;
                    case "RUNEN SCHMIEDEN":
                        return 776;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "RURU":
                switch( $skill ) 
                {
                    case "МЕЧИ":
                        return 43;
                    case "ТОПОРЫ":
                        return 44;
                    case "ЛУКИ":
                        return 45;
                    case "ОГНЕСТРЕЛЬНОЕ ОРУЖИЕ":
                        return 46;
                    case "ДРОБЯЩЕЕ ОРУЖИЕ":
                        return 54;
                    case "ДВУРУЧНЫЕ МЕЧИ":
                        return 55;
                    case "ЗАЩИТА":
                        return 95;
                    case "ПЕРВАЯ ПОМОЩЬ":
                        return 129;
                    case "ПОСОХИ":
                        return 136;
                    case "ДВУРУЧНОЕ ДРОБЯЩЕЕ ОРУЖИЕ":
                        return 160;
                    case "РУКОПАШНЫЙ БОЙ":
                        return 162;
                    case "КУЗНЕЧНОЕ ДЕЛО":
                        return 164;
                    case "КОЖЕВНИЧЕСТВО":
                        return 165;
                    case "АЛХИМИЯ":
                        return 171;
                    case "ДВУРУЧНЫЕ ТОПОРЫ":
                        return 172;
                    case "КИНЖАЛЫ":
                        return 173;
                    case "МЕТАТЕЛЬНОЕ ОРУЖИЕ":
                        return 176;
                    case "ТРАВНИЧЕСТВО":
                        return 182;
                    case "КУЛИНАРИЯ":
                        return 185;
                    case "ГОРНОЕ ДЕЛО":
                        return 186;
                    case "ПОРТНЯЖНОЕ ДЕЛО":
                        return 197;
                    case "ИНЖЕНЕРНОЕ ДЕЛО":
                        return 202;
                    case "АРБАЛЕТЫ":
                        return 226;
                    case "ЖЕЗЛЫ":
                        return 228;
                    case "ДРЕВКОВОЕ ОРУЖИЕ":
                        return 229;
                    case "ЛАТЫ":
                        return 293;
                    case "НАЛОЖЕНИЕ ЧАР":
                        return 333;
                    case "РЫБНАЯ ЛОВЛЯ":
                        return 356;
                    case "СНЯТИЕ ШКУР":
                        return 393;
                    case "КОЛЬЧУЖНЫЕ ДОСПЕХИ":
                        return 413;
                    case "КОЖАНЫЕ ДОСПЕХИ":
                        return 414;
                    case "ТКАНЕВЫЕ ДОСПЕХИ":
                        return 415;
                    case "ЩИТ":
                        return 433;
                    case "КИСТЕВОЕ ОРУЖИЕ":
                        return 473;
                    case "ВЗЛОМ":
                        return 633;
                    case "ЮВЕЛИРНОЕ ДЕЛО":
                        return 755;
                    case "НАЧЕРТАНИЕ":
                        return 773;
                    case "КОВКА РУН":
                        return 776;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "ESES":
                switch( $skill ) 
                {
                    case "ESPADAS":
                        return 43;
                    case "HACHAS":
                        return 44;
                    case "ARCOS":
                        return 45;
                    case "ARMAS DE FUEGO":
                        return 46;
                    case "MAZAS":
                        return 54;
                    case "ESPADAS DE DOS MANOS":
                        return 55;
                    case "DEFENSA":
                        return 95;
                    case "PRIMEROS AUXILIOS":
                        return 129;
                    case "BASTONES":
                        return 136;
                    case "MAZAS DE DOS MANOS":
                        return 160;
                    case "SIN ARMAS":
                        return 162;
                    case "HERRERÍA":
                        return 164;
                    case "PELETERÍA":
                        return 165;
                    case "ALQUIMIA":
                        return 171;
                    case "HACHAS DE DOS MANOS":
                        return 172;
                    case "DAGAS":
                        return 173;
                    case "ARMAS ARROJADIZAS":
                        return 176;
                    case "HERBORISTERÍA":
                        return 182;
                    case "COCINA":
                        return 185;
                    case "MINERÍA":
                        return 186;
                    case "SASTRERÍA":
                        return 197;
                    case "INGENIERÍA":
                        return 202;
                    case "BALLESTAS":
                        return 226;
                    case "VARITAS":
                        return 228;
                    case "ARMAS DE ASTA":
                        return 229;
                    case "MALLA DE PLACAS":
                        return 293;
                    case "ENCANTAMIENTO":
                        return 333;
                    case "PESCA":
                        return 356;
                    case "DESOLLAR":
                        return 393;
                    case "MALLA":
                        return 413;
                    case "CUERO":
                        return 414;
                    case "TELA":
                        return 415;
                    case "ESCUDO":
                        return 433;
                    case "ARMAS DE PUÑO":
                        return 473;
                    case "GANZÚA":
                        return 633;
                    case "JOYERÍA":
                        return 755;
                    case "INSCRIPCIÓN":
                        return 773;
                    case "FORJA DE RUNAS":
                        return 776;
                    default:
                        return -1;
                }
        }
        switch( $locale ) 
        {
            case "ENUS":
            case "ENGB":
                switch( $skill ) 
                {
                    case "SWORDS":
                        return 43;
                    case "AXES":
                        return 44;
                    case "BOWS":
                        return 45;
                    case "GUNS":
                        return 46;
                    case "MACES":
                        return 54;
                    case "TWO-HANDED SWORDS":
                        return 55;
                    case "DEFENSE":
                        return 95;
                    case "FIRST AID":
                        return 129;
                    case "STAVES":
                        return 136;
                    case "TWO-HANDED MACES":
                        return 160;
                    case "UNARMED":
                        return 162;
                    case "BLACKSMITHING":
                        return 164;
                    case "LEATHERWORKING":
                        return 165;
                    case "ALCHEMY":
                        return 171;
                    case "TWO-HANDED AXES":
                        return 172;
                    case "DAGGERS":
                        return 173;
                    case "THROWN":
                        return 176;
                    case "HERBALISM":
                        return 182;
                    case "COOKING":
                        return 185;
                    case "MINING":
                        return 186;
                    case "TAILORING":
                        return 197;
                    case "ENGINEERING":
                        return 202;
                    case "CROSSBOWS":
                        return 226;
                    case "WANDS":
                        return 228;
                    case "POLEARMS":
                        return 229;
                    case "PLATE MAIL":
                        return 293;
                    case "ENCHANTING":
                        return 333;
                    case "FISHING":
                        return 356;
                    case "SKINNING":
                        return 393;
                    case "MAIL":
                        return 413;
                    case "LEATHER":
                        return 414;
                    case "CLOTH":
                        return 415;
                    case "SHIELD":
                        return 433;
                    case "FIST WEAPONS":
                        return 473;
                    case "LOCKPICKING":
                        return 633;
                    case "JEWELCRAFTING":
                        return 755;
                    case "INSCRIPTION":
                        return 773;
                    case "RUNEFORGING":
                        return 776;
                    default:
                        return -1;
                }
        }
    }

    function CheckExtraSpell($skill)
    {
        switch( $skill ) 
        {
            case 393:
            case 182:
            case 185:
            case 186:
            case 333:
            case 755:
            case 773:
                return true;
        }
        return false;
    }

    function _CheckSkillLevel($cur)
    {
        if( 1 <= $cur && $cur <= 74 ) 
        {
            return 0;
        }

        if( 75 <= $cur && $cur <= 149 ) 
        {
            return 75;
        }

        if( 150 <= $cur && $cur <= 224 ) 
        {
            return 150;
        }

        if( 225 <= $cur && $cur <= 299 ) 
        {
            return 225;
        }

        if( 300 <= $cur && $cur <= 374 ) 
        {
            return 300;
        }

        if( 375 <= $cur && $cur <= 449 ) 
        {
            return 375;
        }

        if( $cur == 450 ) 
        {
            return 450;
        }

    }

    function GetExtraSpellForSkill($skill, $cur, $char_guid, $connection)
    {
        switch( $skill ) 
        {
            case 393:
                switch( $this->_CheckSkillLevel($cur) ) 
                {
                    case 75:
                        return 53125;
                    case 150:
                        return 53662;
                    case 225:
                        return 53663;
                    case 300:
                        return 53664;
                    case 375:
                        return 53665;
                    case 450:
                        return 53666;
                    case 525:
                        return 74495;
                    case 600:
                        return 102219;
                    default:
                        return -1;
                }
        }
        switch( $skill ) 
        {
            case 182:
                $this->LearnSeparateSpell(2383, $char_guid, $connection);
                switch( $this->_CheckSkillLevel($cur) ) 
                {
                    case 75:
                        return 55428;
                    case 150:
                        return 55480;
                    case 225:
                        return 55500;
                    case 300:
                        return 55501;
                    case 375:
                        return 55502;
                    case 450:
                        return 55503;
                    case 525:
                        return 74519;
                    case 600:
                        return 110413;
                    default:
                        return -1;
                }
        }
        switch( $skill ) 
        {
            case 186:
                $this->LearnSeparateSpell(2656, $char_guid, $connection);
                $this->LearnSeparateSpell(2580, $char_guid, $connection);
                switch( $this->_CheckSkillLevel($cur) ) 
                {
                    case 75:
                        return 53120;
                    case 150:
                        return 53121;
                    case 225:
                        return 53122;
                    case 300:
                        return 53123;
                    case 375:
                        return 53124;
                    case 450:
                        return 53040;
                    default:
                        return -1;
                }
        }
        switch( $skill ) 
        {
            case 185:
                return 818;
            case 333:
                return 13262;
            case 755:
                return 31252;
            case 773:
                return 51005;
        }
    }

    function LearnPandariaCreatures($spell, $account)
    {
        $db = $this->external_account_model->getConnection();
        $db->query("INSERT IGNORE INTO account_spell VALUES (?,?,1,0);", array( $account, $spell ));
    }

    function RemoveRaceBonus($RaceID, $SkillID, $value)
    {
        switch( $RaceID ) 
        {
            case 6:
                switch( $SkillID ) 
                {
                    case 182:
                        $value = $value - 5;
                        return $value;
                    default:
                        return $value;
                }
            case 7:
                switch( $SkillID ) 
                {
                    case 202:
                        $value = $value - 15;
                        return $value;
                    default:
                        return $value;
                }
            case 10:
                switch( $SkillID ) 
                {
                    case 333:
                        $value = $value - 10;
                        return $value;
                    default:
                        return $value;
                }
            case 11:
                switch( $SkillID ) 
                {
                    case 755:
                        $value = $value - 5;
                        return $value;
                    default:
                        return $value;
                }
            default:
                return $value;
        }
    }

    function GetSpellIDForSkill($SkillID, $max)
    {
        switch( $SkillID ) 
        {
            case 43:
                return 201;
            case 44:
                return 196;
            case 45:
                return 264;
            case 46:
                return 266;
            case 54:
                return 198;
            case 55:
                return 202;
            case 118:
                return 674;
            case 95:
                return 204;
            case 226:
                return 5011;
            case 228:
                return 5009;
            case 229:
                return 200;
            case 293:
                return 750;
            case 413:
                return 8737;
            case 414:
                return 9077;
            case 415:
                return 9078;
            case 433:
                return 9116;
            case 473:
                return 15590;
            case 633:
                return 1804;
            case 172:
                return 197;
            case 173:
                return 1180;
            case 176:
                return 2567;
            case 136:
                return 227;
            case 160:
                return 199;
            case 162:
                return 203;
            case 776:
                return 53428;
            case 129:
                switch( $max ) 
                {
                    case 75:
                        return 3273;
                    case 150:
                        return 3274;
                    case 225:
                        return 7924;
                    case 300:
                        return 10846;
                    case 375:
                        return 27028;
                    case 450:
                        return 45542;
                    case 525:
                        return 74559;
                    case 600:
                        return 110406;
                }
                break;
            case 164:
                switch( $max ) 
                {
                    case 75:
                        return 2018;
                    case 150:
                        return 3100;
                    case 225:
                        return 3538;
                    case 300:
                        return 9785;
                    case 375:
                        return 29844;
                    case 450:
                        return 51300;
                    case 525:
                        return 76666;
                    case 600:
                        return 110396;
                }
                break;
            case 165:
                switch( $max ) 
                {
                    case 75:
                        return 2108;
                    case 150:
                        return 3104;
                    case 225:
                        return 3811;
                    case 300:
                        return 10662;
                    case 375:
                        return 32549;
                    case 450:
                        return 51302;
                    case 525:
                        return 81199;
                    case 600:
                        return 110423;
                }
                break;
            case 171:
                switch( $max ) 
                {
                    case 75:
                        return 2259;
                    case 150:
                        return 3101;
                    case 225:
                        return 3464;
                    case 300:
                        return 11611;
                    case 375:
                        return 28596;
                    case 450:
                        return 51304;
                    case 525:
                        return 80731;
                    case 600:
                        return 105206;
                }
                break;
            case 182:
                switch( $max ) 
                {
                    case 75:
                        return 2366;
                    case 150:
                        return 2368;
                    case 225:
                        return 3570;
                    case 300:
                        return 11993;
                    case 375:
                        return 28695;
                    case 450:
                        return 50300;
                    case 525:
                        return 74519;
                    case 600:
                        return 110413;
                }
                break;
            case 185:
                switch( $max ) 
                {
                    case 75:
                        return 2550;
                    case 150:
                        return 3102;
                    case 225:
                        return 3413;
                    case 300:
                        return 18260;
                    case 375:
                        return 33359;
                    case 450:
                        return 51296;
                    case 525:
                        return 88053;
                    case 600:
                        return 104381;
                }
                break;
            case 186:
                switch( $max ) 
                {
                    case 75:
                        return 2575;
                    case 150:
                        return 2576;
                    case 225:
                        return 3564;
                    case 300:
                        return 10248;
                    case 375:
                        return 29354;
                    case 450:
                        return 50310;
                    case 525:
                        return 74517;
                    case 600:
                        return 102161;
                }
                break;
            case 197:
                switch( $max ) 
                {
                    case 75:
                        return 3908;
                    case 150:
                        return 3909;
                    case 225:
                        return 3910;
                    case 300:
                        return 12180;
                    case 375:
                        return 26790;
                    case 450:
                        return 51309;
                    case 525:
                        return 75156;
                    case 600:
                        return 110426;
                }
                break;
            case 202:
                switch( $max ) 
                {
                    case 75:
                        return 4036;
                    case 150:
                        return 4037;
                    case 225:
                        return 4038;
                    case 300:
                        return 12656;
                    case 375:
                        return 30350;
                    case 450:
                        return 51306;
                    case 525:
                        return 82774;
                    case 600:
                        return 110403;
                }
                break;
            case 333:
                switch( $max ) 
                {
                    case 75:
                        return 7411;
                    case 150:
                        return 7412;
                    case 225:
                        return 7413;
                    case 300:
                        return 13920;
                    case 375:
                        return 28029;
                    case 450:
                        return 51313;
                    case 525:
                        return 74258;
                    case 600:
                        return 110400;
                }
                break;
            case 356:
                switch( $max ) 
                {
                    case 75:
                        return 7620;
                    case 150:
                        return 7731;
                    case 225:
                        return 7732;
                    case 300:
                        return 18248;
                    case 375:
                        return 33095;
                    case 450:
                        return 51294;
                    case 525:
                        return 88868;
                    case 600:
                        return 110410;
                }
                break;
            case 393:
                switch( $max ) 
                {
                    case 75:
                        return 8613;
                    case 150:
                        return 8617;
                    case 225:
                        return 8618;
                    case 300:
                        return 10768;
                    case 375:
                        return 32678;
                    case 450:
                        return 50305;
                    case 525:
                        return 74522;
                    case 600:
                        return 102216;
                }
                break;
            case 755:
                switch( $max ) 
                {
                    case 75:
                        return 25229;
                    case 150:
                        return 25230;
                    case 225:
                        return 28894;
                    case 300:
                        return 28895;
                    case 375:
                        return 28897;
                    case 450:
                        return 51311;
                    case 525:
                        return 73318;
                    case 600:
                        return 110420;
                }
                break;
            case 794:
                switch( $max ) 
                {
                    case 75:
                        return 78670;
                    case 150:
                        return 88961;
                    case 225:
                        return 89718;
                    case 300:
                        return 89719;
                    case 375:
                        return 89720;
                    case 450:
                        return 89721;
                    case 525:
                        return 89722;
                    case 600:
                        return 110393;
                }
                break;
            case 773:
                switch( $max ) 
                {
                    case 75:
                        return 45357;
                    case 150:
                        return 45358;
                    case 225:
                        return 45359;
                    case 300:
                        return 45360;
                    case 375:
                        return 45361;
                    case 450:
                        return 45363;
                    case 525:
                        return 86008;
                    case 600:
                        return 110417;
                }
                break;
            default:
                return -1;
        }
    }

    function totalCharacters($realm, $account_id)
    {
        $db = $this->realms->getRealm($realm)->getCharacters()->getConnection();
        $q = $db->query("SELECT * FROM characters WHERE account = ?", array( $account_id ));
        return $q->num_rows();
    }

}


