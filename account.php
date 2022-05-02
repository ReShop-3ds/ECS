<?php
require_once "inc/db.php";
$headers = apache_request_headers();
if(!isset($headers['SOAPAction'])){
    echo "So long, gay computer!";
    exit;
}
header("Content-Type: text/xml;charset=utf-8");
$xml = simplexml_load_string(file_get_contents("php://input"));
$nintendo1 = $xml->children('SOAP-ENV', true)->Body->children('ecs', true)->GetAccountStatus;
$nintendo2 = $xml->children('SOAP-ENV', true)->Body->children('ecs', true)->AccountListETicketIds;
$nintendo3 = $xml->children('SOAP-ENV', true)->Body->children('ecs', true)->DeleteSavedCard;
if(!empty($nintendo1)){
    $q = $db->prepare("SELECT * FROM `act` WHERE `device_token`=:device_token");
    $q->execute([
        "device_token" => $nintendo1->DeviceToken
    ]);
    $r = $q->fetch();
    if(!$r){
        $q = $db->prepare("INSERT INTO `act`(device_id, device_token, balance, language, region) VALUES(:did, :dtoken, 0, :country, :region)");
        $q->execute([
            "did" => $nintendo1->DeviceId,
            "dtoken" => $nintendo1->DeviceToken,
            "country" => $nintendo1->Country,
            "region" => $nintendo1->Region
        ]);
    }
    $q = $db->prepare("SELECT * FROM `act` WHERE `device_token`=:device_token");
    $q->execute([
        "device_token" => $nintendo1->DeviceToken
    ]);
    $r = $q->fetch();
    echo '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><GetAccountStatusResponse xmlns="urn:ecs.wsapi.broadon.com"><Version>2.0</Version><DeviceId>'.$nintendo1->DeviceId.'</DeviceId><MessageId>'.$nintendo1->MessageId.'</MessageId><TimeStamp>'.time().'</TimeStamp><ErrorCode>0</ErrorCode><ServiceStandbyMode>false</ServiceStandbyMode><AccountId>'.$r['id'].'</AccountId><AccountStatus>R</AccountStatus><Balance><Amount>'.$r['balance'].'</Amount><Currency>EUR</Currency></Balance><EulaVersion>0</EulaVersion><Country>FR</Country><Region>EUR</Region><AccountAttributes><Name>LOYALTY_LOGIN_NAME</Name><Value></Value></AccountAttributes><TIV>1149809128885587.0</TIV><TIV>1169575131682554.0</TIV><TIV>1314918129672913.0</TIV><TIV>1326222027437328.0</TIV><TIV>1338129668636531.0</TIV><TIV>1186379246602384.0</TIV><TIV>1358509901483789.0</TIV><TIV>1250518513292961.0</TIV><TIV>1233556221450613.0</TIV><TIV>1255164559487202.0</TIV><TIV>1308861876776051.0</TIV><TIV>1286072485522462.0</TIV><TIV>1387092800086442.0</TIV><TIV>1282035307179909.0</TIV><TIV>1203991784779312.0</TIV><TIV>1308681197893237.0</TIV><TIV>1184380370551609.0</TIV><TIV>1196788529703881.0</TIV><TIV>1180251793068540.0</TIV><TIV>1358557812147086.0</TIV><TIV>1266002331520620.0</TIV><TIV>1145695266680248.0</TIV><TIV>1179557585538440.0</TIV><TIV>1339926727609970.0</TIV><TIV>1147624340491422.0</TIV><TIV>1132244943650770.0</TIV><TIV>1193095819017622.0</TIV><TIV>1360780990104007.0</TIV><TIV>1301381848583681.0</TIV><TIV>1398319857900498.0</TIV><TIV>1241500844974252.1</TIV><TIV>1319126278449643.0</TIV><TIV>1401321891205073.0</TIV><TIV>1320413304361997.1</TIV><TIV>1228105690723466.0</TIV><TIV>1173296748471852.0</TIV><TIV>1289107132933952.1</TIV><TIV>1138320722983789.1</TIV><TIV>1160467418015390.2</TIV><TIV>1318649123677401.1</TIV><ServiceURLs><Name>ContentPrefixURL</Name><URI>http://ccs.cdn.c.shop.nintendowifi.net/ccs/download</URI></ServiceURLs><ServiceURLs><Name>UncachedContentPrefixURL</Name><URI>https://ccs.c.shop.nintendowifi.net/ccs/download</URI></ServiceURLs><ServiceURLs><Name>SystemContentPrefixURL</Name><URI>http://nus.cdn.c.shop.nintendowifi.net/ccs/download</URI></ServiceURLs><ServiceURLs><Name>SystemUncachedContentPrefixURL</Name><URI>https://ccs.c.shop.nintendowifi.net/ccs/download</URI></ServiceURLs><ServiceURLs><Name>EcsURL</Name><URI>https://account.samurai-ctr.qck.ar.nf/ecs/services/ECommerceSOAP</URI></ServiceURLs><ServiceURLs><Name>IasURL</Name><URI>https://auth.samurai-ctr.qck.ar.nf/ias/services/IdentityAuthenticationSOAP</URI></ServiceURLs><ServiceURLs><Name>CasURL</Name><URI>https://cas.c.shop.nintendowifi.net/cas/services/CatalogingSOAP</URI></ServiceURLs><ServiceURLs><Name>NusURL</Name><URI>https://nus.c.shop.nintendowifi.net/nus/services/NetUpdateSOAP</URI></ServiceURLs><IVSSyncFlag>false</IVSSyncFlag><CountryAttribits>15</CountryAttribits></GetAccountStatusResponse></soapenv:Body></soapenv:Envelope>';
}elseif(!empty($nintendo2)){
    $q = $db->prepare("SELECT * FROM `act` WHERE `id`=:id");
    $q->execute([
        "id" => $nintendo2->AccountId
    ]);
    $r = $q->fetch();
    echo '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><AccountListETicketIdsResponse xmlns="urn:ecs.wsapi.broadon.com"><Version>2.0</Version><DeviceId>'.$r['device_id'].'</DeviceId><MessageId>'.$nintendo2->MessageId.'</MessageId><TimeStamp>'.time().'</TimeStamp><ErrorCode>0</ErrorCode><ServiceStandbyMode>false</ServiceStandbyMode><TIV>1149809128885587.0</TIV><TIV>1169575131682554.0</TIV><TIV>1314918129672913.0</TIV><TIV>1326222027437328.0</TIV><TIV>1338129668636531.0</TIV><TIV>1186379246602384.0</TIV><TIV>1358509901483789.0</TIV><TIV>1250518513292961.0</TIV><TIV>1233556221450613.0</TIV><TIV>1255164559487202.0</TIV><TIV>1308861876776051.0</TIV><TIV>1286072485522462.0</TIV><TIV>1387092800086442.0</TIV><TIV>1282035307179909.0</TIV><TIV>1203991784779312.0</TIV><TIV>1308681197893237.0</TIV><TIV>1184380370551609.0</TIV><TIV>1196788529703881.0</TIV><TIV>1180251793068540.0</TIV><TIV>1358557812147086.0</TIV><TIV>1266002331520620.0</TIV><TIV>1145695266680248.0</TIV><TIV>1179557585538440.0</TIV><TIV>1339926727609970.0</TIV><TIV>1147624340491422.0</TIV><TIV>1132244943650770.0</TIV><TIV>1193095819017622.0</TIV><TIV>1360780990104007.0</TIV><TIV>1301381848583681.0</TIV><TIV>1398319857900498.0</TIV><TIV>1241500844974252.1</TIV><TIV>1319126278449643.0</TIV><TIV>1401321891205073.0</TIV><TIV>1320413304361997.1</TIV><TIV>1228105690723466.0</TIV><TIV>1173296748471852.0</TIV><TIV>1289107132933952.1</TIV><TIV>1138320722983789.1</TIV><TIV>1160467418015390.2</TIV><TIV>1318649123677401.1</TIV></AccountListETicketIdsResponse></soapenv:Body></soapenv:Envelope>';
}else if(!empty($nintendo3)){
    $q = $db->prepare("SELECT * FROM `act` WHERE `id`=:id");
    $q->execute([
        "id" => $nintendo3->AccountId
    ]);
    $r = $q->fetch();
    echo '<?xml version="1.0" encoding="utf-8"?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><DeleteSavedCardResponse xmlns="urn:ias.wsapi.broadon.com"><Version>2.0</Version><DeviceId>'.$r['device_id'].'</DeviceId><MessageId>'.$nintendo3->MessageId.'</MessageId><TimeStamp>'.time().'</TimeStamp><ErrorCode>0</ErrorCode><ServiceStandbyMode>false</ServiceStandbyMode><AccountId>'.$r['id'].'</AccountId></DeleteSavedCardResponse></soapenv:Body></soapenv:Envelope>';
}