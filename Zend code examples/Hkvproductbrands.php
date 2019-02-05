<?php

class Custom_Webshop_Model_DbTable_Hkvproductbrands
{

    private $_dbh;

    public function __construct()
    {
        if (Zend_Registry::isRegistered('hakvoortDbh')) {
            $this->_dbh = Zend_Registry::get('hakvoortDbh');
        }
    }

    public function getDbPrefixByAdminCode($adminCode, $db)
    {

        $results = $db->prepare($query = "
			SELECT
				A.adnDBPrefix AS `%dbprf%`,
				C.adnDBPrefix AS `%artprf%`,
				E.adnDBPrefix AS `%relprf%`
			FROM
				base_admins AS A
			INNER JOIN
				base_datasets AS B ON (
					B.adsAdministratie = A.adnAdminkode
						AND
					B.adsDatasetType = 'ART'
				)
			INNER JOIN
				base_admins AS C ON C.adnAdminkode = B.adsBestemmingsAdmin
			INNER JOIN
				base_datasets AS D ON (
					D.adsAdministratie = A.adnAdminkode
						AND
					D.adsDatasetType = 'REL'
				)
			INNER JOIN
				base_admins AS E ON E.adnAdminkode = D.adsBestemmingsAdmin
				WHERE
				A.adnAdminkode = :adminCode

		");

        $results->bindParam("adminCode", $adminCode);
        $results->execute();
        $prefixes = $results->fetchAll();
        return current($prefixes[0]);
    }

    public function getAllBrands()
    {
        $language = strtoupper(WEBSITE_LANG);
        $websiteCode = HAKVOORT_WEBSITE_CODE;

        $results = $this->_dbh->prepare("
			SELECT
				M.mrkMerkkode,
				M.mrkMerknaam,
				M.mrkWebsite,
				MT.mktTekst
			FROM
				`%dbprf%merk` AS M
			LEFT JOIN
				`%dbprf%merkt` AS MT ON (
					M.mrkMerkkode = MT.mktMerkkode
						AND
					MT.mktTaal = :language)
			INNER JOIN
				`%artprf%artbase` AS A ON M.mrkMerkkode = A.artMerknaam
			INNER JOIN
				`%dbprf%zksart` AS SA ON A.artKode = SA.zkaArtikelkode
			INNER JOIN
				`%dbprf%zkstrc` AS SR ON SR.zstKode = SA.azlZoekkode
			INNER JOIN
				`%dbprf%websites` AS W ON SR.zksWebsite = W.wbsKode
			WHERE
				W.wbsKode = :websiteCode
			AND
				M.mrkTonenOpWeb = 1
			GROUP BY
				M.mrkMerkkode");

        $results->bindParam('language', $language);
        $results->bindParam('websiteCode', $websiteCode);

        $results->execute();
        $merken = $results->fetchAll();
        return $merken;
    }


    public function getBrands()
    {
        $dbh = Zend_Registry::get('dbh');
        $websiteDetails = Zend_Registry::get('website');

        $websiteId = $websiteDetails['website_id'];
        $websiteCode = $websiteDetails['website_code'];

        $config = Zend_Registry::get("config");
        $db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host' => $config->digiself->externalDb->params->host,
            'username' => $config->digiself->externalDb->params->username,
            'password' => $config->digiself->externalDb->params->password,
            'dbname' => $config->digiself->externalDb->params->dbname
        ));

        $query = "
			SELECT 
				*
			FROM
				hkv_website_settings
			WHERE 
				website_id = :websiteId
		";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam('websiteId', $websiteId);
        $stmt->execute();

        $websiteSettings = $stmt->fetch();

        $dbprf = $this->getDbPrefixByAdminCode($websiteSettings['admin_code'], $db);

        $query = "SELECT mrkMerkkode FROM " . $dbprf . "merk";
        $stmt = $db->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $results;
    }

    public function getBrandImage($brandId)
    {
        $query = $this->_dbh->prepare("
            SELECT
                AO.mrkLogo
            FROM
                `%artprf%merk` AS AO
            WHERE
                AO.mrkMerkkode = :brandId
           ");

        $query->bindParam("brandId", $brandId);
        $query->execute();
        $result = $query->fetchAll();
        return $result[0];
    }

    public function getBrandsImages($brandIds)
    {
        $query = $this->_dbh->prepare("
            SELECT
				AO.mrkMerkkode,
                AO.mrkLogo			
            FROM
                `%artprf%merk` AS AO
            WHERE " . $this->_dbh->quoteInto('AO.mrkMerkkode IN (?)', $brandIds) . "
                
           ");

        $query->execute();

        $result = $query->fetchAll();
        $merken = array();
        foreach ($result as $res) {
            $merken[$res['mrkMerkkode']] = $res['mrkLogo'];
        }

        return $merken;
    }

    public function getBrandImageAndCodeByProductId($productId)
    {
        $sql = "SELECT
                    m.*
                FROM
                    `%dbprf%artbase` a
                INNER JOIN
                    `%dbprf%merk` m ON a.artMerknaam = m.mrkMerkkode
                WHERE
                    a.artKode = :productId";

        $stmt = $this->_dbh->prepare($sql);
        $stmt->bindParam('productId', $productId);
        $stmt->execute();

        $brand = $stmt->fetch();

        if (sizeof($brand) > 0) {
            return $brand;
        } else {
            return false;
        }
    }

    public function getBrandDescription($brandId)
    {
        $language = strtoupper(WEBSITE_LANG);
        $query = $this->_dbh->prepare("
            SELECT
                MT.mktTekst
            FROM
                `%artprf%merkt` AS MT
            WHERE
                MT.mktMerkkode = :brandId
                AND
                MT.mktTaal = '$language'
           ");

        $query->bindParam("brandId", $brandId);
        $query->execute();
        $result = $query->fetchAll();
        if (isset($result[0]['mktTekst'])) {
            return $result[0]['mktTekst'];
        } else {
            return '';
        }
    }

    public function getRandomBrandsImages($count)
    {
        $query = $this->_dbh->prepare("
            SELECT
                AO.mrkLogo,
                AO.mrkMerkkode
            FROM
                `%artprf%merk` AS AO
            ORDER BY RAND() LIMIT $count
           ");

        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }

    public function getBrand($brandId)
    {

        $language = strtoupper(WEBSITE_LANG);

        $query = $this->_dbh->prepare("
            SELECT M.mrkMerkkode,
				M.mrkMerknaam,
				M.mrkWebsite,
				MT.mktTekst
				
			FROM `%artprf%merk` AS M
			LEFT JOIN `%artprf%merkt` AS MT 
				ON (MT.mktMerkkode = M.mrkMerkkode AND MT.mktTaal = :language)
			WHERE M.mrkMerkkode = :brandId 
           ");

        $query->bindParam("brandId", $brandId);
        $query->bindParam("language", $language);
        $query->execute();
        $result = $query->fetch();

        if ($result == false)
            return array();

        return $result;

    }


}
