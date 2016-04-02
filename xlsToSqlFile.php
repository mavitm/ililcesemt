<?php
ini_set('memory_limit','512M');
set_time_limit(0);
header("Content-Type: text/html; charset=UTF-8");
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
include 'PHPExcel/IOFactory.php';

function temizle($tr1) {
    $turkce=array("ş","Ş","ı","ü","Ü","ö","Ö","ç","Ç","ş","Ş","ı","ğ","Ğ","İ","ö","Ö","Ç","ç","ü","Ü");
    $duzgun=array("s","S","i","u","U","o","O","c","C","s","S","i","g","G","I","o","O","C","c","u","U");
    $tr1=str_replace($turkce,$duzgun,$tr1);
    $tr1 = preg_replace("@[^a-z0-9\-_şıüğçİŞĞÜÇ]+@i","-",$tr1);
    return strtolower($tr1);
}

function fileYaz($file, $data){
    $b = fopen($file,"w");
    if($b){
        fwrite($b,$data);
    }
    fclose($b);
}

$ilplaka = [
    'ADANA' => 1,
    'ADIYAMAN' => 2,
    'AFYONKARAHİSAR' => 3,
    'AĞRI' => 4,
    'AMASYA' => 5,
    'ANKARA' => 6,
    'ANTALYA' => 7,
    'ARTVİN' => 8,
    'AYDIN' => 9,
    'BALIKESİR' => 10,
    'BİLECİK' => 11,
    'BİNGÖL' => 12,
    'BİTLİS' => 13,
    'BOLU' => 14,
    'BURDUR' => 15,
    'BURSA' => 16,
    'ÇANAKKALE' => 17,
    'ÇANKIRI' => 18,
    'ÇORUM' => 19,
    'DENİZLİ' => 20,
    'DİYARBAKIR' => 21,
    'EDİRNE' => 22,
    'ELAZIĞ' => 23,
    'ERZİNCAN' => 24,
    'ERZURUM' => 25,
    'ESKİŞEHİR' => 26,
    'GAZİANTEP' => 27,
    'GİRESUN' => 28,
    'GÜMÜŞHANE' => 29,
    'HAKKARİ' => 30,
    'HATAY' => 31,
    'ISPARTA' => 32,
    'MERSİN' => 33,
    'İSTANBUL' => 34,
    'İZMİR' => 35,
    'KARS' => 36,
    'KASTAMONU' => 37,
    'KAYSERİ' => 38,
    'KIRKLARELİ' => 39,
    'KIRŞEHİR' => 40,
    'KOCAELİ' => 41,
    'KONYA' => 42,
    'KÜTAHYA' => 43,
    'MALATYA' => 44,
    'MANİSA' => 45,
    'KAHRAMANMARAŞ' => 46,
    'MARDİN' => 47,
    'MUĞLA' => 48,
    'MUŞ' => 49,
    'NEVŞEHİR' => 50,
    'NİĞDE' => 51,
    'ORDU' => 52,
    'RİZE' => 53,
    'SAKARYA' => 54,
    'SAMSUN' => 55,
    'SİİRT' => 56,
    'SİNOP' => 57,
    'SİVAS' => 58,
    'TEKİRDAĞ' => 59,
    'TOKAT' => 60,
    'TRABZON' => 61,
    'TUNCELİ' => 62,
    'ŞANLIURFA' => 63,
    'UŞAK' => 64,
    'VAN' => 65,
    'YOZGAT' => 66,
    'ZONGULDAK' => 67,
    'AKSARAY' => 68,
    'BAYBURT' => 69,
    'KARAMAN' => 70,
    'KIRIKKALE' => 71,
    'BATMAN' => 72,
    'ŞIRNAK' => 73,
    'BARTIN' => 74,
    'ARDAHAN' => 75,
    'IĞDIR' => 76,
    'YALOVA' => 77,
    'KARABÜK' => 78,
    'KİLİS' => 79,
    'OSMANİYE' => 80,
    'DÜZCE' => 81
];


//http://postakodu.ptt.gov.tr/Dosyalar/pk_list.zip
$xls = "xls.xlsx";
$objPHPExcel = PHPExcel_IOFactory::load($xls);
$rawData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

array_shift($rawData);

$dbData = array();

foreach($rawData as $ilnID=>$arr){
    $il = trim($arr['A']);
    $ilce = trim($arr['B']);
    $semt = trim($arr['C']);
    $mah = trim($arr['D']);
    $pk = trim($arr['E']);

    $dbData[$il][$ilce][] = array($semt, $mah, $pk);
}


#############################################
$illerSqlText = "
--
-- Buraya table yapisini ilave edebilirsin
--
CREATE TABLE IF NOT EXISTS `iller` (
  `id` int(11) NOT NULL,
  `adi` varchar(255) NOT NULL,
  `sef` varchar(255) NOT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";
$illerSqlText .= "\n";

#############################################
$ilcelerSqlText = "
--
-- Buraya table yapisini ilave edebilirsin
--
CREATE TABLE IF NOT EXISTS `ilceler` (
`id` int(11) NOT NULL,
  `il_id` int(11) NOT NULL,
  `adi` varchar(255) NOT NULL,
  `sef` varchar(255) NOT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";
$ilcelerSqlText .= "\n";

#############################################
$semtSqlText = "
--
-- Buraya table yapisini ilave edebilirsin
--
CREATE TABLE IF NOT EXISTS `semtler` (
`id` int(11) NOT NULL,
  `il_id` int(11) NOT NULL,
  `ilce_id` int(11) NOT NULL,
  `adi` varchar(255) NOT NULL,
  `sef` varchar(255) NOT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";
$semtSqlText .= "\n";

#############################################
$mahSqlText = "
--
-- Buraya table yapisini ilave edebilirsin
--
CREATE TABLE IF NOT EXISTS `mahalleler` (
`id` int(11) NOT NULL,
  `il_id` int(11) NOT NULL,
  `ilce_id` int(11) NOT NULL,
  `semt_id` int(11) NOT NULL,
  `adi` varchar(255) NOT NULL,
  `sef` varchar(255) NOT NULL,
  `pk` int(11) NOT NULL,
  `modify_date` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";
$mahSqlText .= "\n";


$illerInSql     = array("INSERT INTO iller (id, adi, sef) VALUES ('%s','%s','%s'),","('%s','%s','%s'),");
$ilcelerInSql   = array("INSERT INTO ilceler (id, il_id, adi, sef) VALUES ('%s','%s','%s','%s'),","('%s','%s','%s','%s'),");
$semtInSql      = array("INSERT INTO semtler (id, il_id, ilce_id, adi, sef) VALUES ('%s','%s','%s','%s','%s'),","('%s','%s','%s','%s','%s'),");
$mahInSql       = array("INSERT INTO mahalleler (id, il_id, ilce_id, semt_id, adi, sef, pk) VALUES ('%s','%s','%s','%s','%s','%s','%s'),","('%s','%s','%s','%s','%s','%s','%s'),");

$sqlLinear = 500;


$il_id = 1;
$ilce_id = 1;
$semt_id = 1;
$mah_id = 1;

$ilCount = $ilceCount = $semtCount = $MahCount = 0;

foreach($dbData as $ilAdi=>$arr){

    $il_id = $ilplaka[$ilAdi];

    if($ilCount < 1 || $ilCount >= $sqlLinear){
        $qx = 0;
        $ilCount = 1;
        $illerSqlText = rtrim(rtrim($illerSqlText,"\n"),",").';';
        $illerSqlText .= "\n";
    }else{
        $qx = 1;
    }

    $illerSqlText .= sprintf($illerInSql[$qx],$il_id,$ilAdi,temizle($ilAdi));
    $illerSqlText .= "\n";

    foreach($arr as $ilceAdi=>$smp){

        if($ilceCount < 1 || $ilceCount >= $sqlLinear){
            $qx = 0;
            $ilceCount = 1;
            $ilcelerSqlText = rtrim(rtrim($ilcelerSqlText,"\n"),",").';';
            $ilcelerSqlText .= "\n";
        }else{
            $qx = 1;
        }

        $ilcelerSqlText .= sprintf($illerInSql[$qx],$ilce_id,$il_id,$ilceAdi,temizle($ilceAdi));
        $ilcelerSqlText .= "\n";

        $sx = 0;
        $currentSemt = '';
        foreach($smp as $smpArr){

            $semtAdi = $smpArr[0];
            $mahAdi = $smpArr[1];
            $pkKodu = $smpArr[2];

            if($sx < 1 || $currentSemt != $semtAdi){

                if($semtCount < 1 || $semtCount >= $sqlLinear){
                    $qx = 0;
                    $semtCount = 1;
                    $semtSqlText = rtrim(rtrim($semtSqlText,"\n"),",").';';
                    $semtSqlText .= "\n";
                }else{
                    $qx = 1;
                }

                $currentSemt = $semtAdi;
                $semtSqlText .= sprintf($semtInSql[$qx],$semt_id,$il_id,$ilce_id,$currentSemt,temizle($currentSemt));
                $semtSqlText .= "\n";
                $semt_id += 1;
                $semtCount++;
            }


            if($MahCount < 1 || $MahCount >= $sqlLinear){
                $qx = 0;
                $MahCount = 1;
                $mahSqlText = rtrim(rtrim($mahSqlText,"\n"),",").';';
                $mahSqlText .= "\n";
            }else{
                $qx = 1;
            }

            $mahSqlText .= sprintf($mahInSql[$qx],$mah_id,$il_id,$ilce_id,$semt_id,$mahAdi,temizle($mahAdi),$pkKodu);
            $mahSqlText .= "\n";

            $sx++;
            $mah_id++;
            $MahCount++;
        }

        $ilce_id += 1;
        $ilceCount++;
    }

    $ilCount++;
}

fileYaz("sqlData/iller.txt",rtrim(rtrim($illerSqlText,"\n"),",").';');
fileYaz("sqlData/ilceler.txt",rtrim(rtrim($ilcelerSqlText,"\n"),",").';');
fileYaz("sqlData/semtler.txt",rtrim(rtrim($semtSqlText,"\n"),",").';');
fileYaz("sqlData/mahalle.txt",rtrim(rtrim($mahSqlText,"\n"),",").';');
?>