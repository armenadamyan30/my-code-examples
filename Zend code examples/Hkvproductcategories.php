<?php

class Custom_Webshop_Model_DbTable_Hkvproductcategories
{

    protected $languageAliases = array(
        'EN' => 'E',
        'FR' => 'F',
        'DE' => 'D',
        'NL' => 'NL'
    );
    private $_dbh;


    public function __construct()
    {
        //Define some variables
        $this->_dbh = Zend_Registry::get('hakvoortDbh');
    }


    public function getCategory($categoryId)
    {
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $defaultLanguage = HAKVOORT_DEFAULT_LANGUAGE;
        $language = $this->languageAliases[strtoupper(WEBSITE_LANG)];

        //Get the categorys
        $query = $this->_dbh->prepare("
            SELECT zstBeschrijving AS description, zstKode, zksType, zksLink
            FROM
                %dbprf%zkstrc AS Z
            LEFT JOIN
                %dbprf%zkstaal AS L ON (
                    L.zktZoekkode = Z.zstKode
                        AND
                    L.zktTaal = :language)
            LEFT JOIN
                %dbprf%zkstaal AS OL ON (
                    OL.zktZoekkode = Z.zstKode
                        AND
                    OL.zktTaal = :defaultLanguage)
            INNER JOIN
                %dbprf%websites AS W ON Z.ZksWebsite = W.wbsKode
            WHERE
                W.wbsKode = :websiteCode
            AND
                Z.zksAktief = 1
            AND
                Z.zstKode = :category
				");

        $query->bindParam('language', $language);
        $query->bindParam('defaultLanguage', $defaultLanguage);
        $query->bindParam('category', $categoryId);
        $query->bindParam('websiteCode', $websiteCode);
        $query->execute();
        $results = $query->fetchAll();
        $tmp = isset($results[0]) ? $results[0] : false;
        return $tmp;
    }

    public function getCategory_new($categoryId)
    {
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $defaultLanguage = HAKVOORT_DEFAULT_LANGUAGE;
        $language = $this->languageAliases[strtoupper(WEBSITE_LANG)];

        //Get the categorys
        $query = $this->_dbh->prepare("
            SELECT zstBeschrijving AS description, zstKode, zksType, zksLink
            FROM
                %dbprf%zkstrc AS Z
            LEFT JOIN
                %dbprf%zkstaal AS L ON (
                    L.zktZoekkode = Z.zstKode
                        AND
                    L.zktTaal = :language)
            LEFT JOIN
                %dbprf%zkstaal AS OL ON (
                    OL.zktZoekkode = Z.zstKode
                        AND
                    OL.zktTaal = :defaultLanguage)
            
            WHERE
                Z.zksAktief = 1
            AND
                Z.zstKode = :category
				");

        $query->bindParam('language', $language);
        $query->bindParam('defaultLanguage', $defaultLanguage);
        $query->bindParam('category', $categoryId);
        $query->execute();
        $results = $query->fetchAll();
        $tmp = isset($results[0]) ? $results[0] : false;
        return $tmp;
    }

    public function getMoreInfo($categoryId)
    {
        $query = "
            SELECT 
                DISTINCT zoekstring
            FROM 
                %dbprf%webzoekkop
            WHERE 
                taal = :taal
            AND 
                zoekstructID = :categoryId
            ORDER BY 
                zoekstring";


        $stmt = $this->_dbh->prepare($query);

        $stmt->bindValue('taal', HAKVOORT_LANGUAGE);
        $stmt->bindParam('categoryId', $categoryId);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getCategoryImage($categoryId)
    {
        $query = "
            SELECT 
                    io.inoData, io.inoFiletype, io.inoModDate, io.iniModTime , io.inoZoek
            FROM 
                %dbprf%infobj AS io
			INNER JOIN 
				%dbprf%zkstrc AS z ON (io.inoID = z.zstFoto)
            WHERE 
				z.zstKode  = :categoryId       
          ";

        $stmt = $this->_dbh->prepare($query);
        $stmt->bindParam('categoryId', $categoryId);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (empty ($result)) {
            $result = array(0 => array('inoFiletype' => 'jpg', 'aobVolgnummer' => '1', 'inoModDate' => '20110505', 'iniModTime' => '030444', 'inoData' => 'default'));
        }
        return $result;
    }

    public function getCategoriesImages($categoryIds)
    {
        if (empty($categoryIds)) {
            return array();
        }

        $query = "
            SELECT 
                    io.inoData, io.inoFiletype, io.inoModDate, io.iniModTime , io.inoZoek, z.zstKode
            FROM 
                %dbprf%infobj AS io
			INNER JOIN 
				%dbprf%zkstrc AS z ON (io.inoID = z.zstFoto)
            WHERE 
				" . $this->_dbh->quoteInto('z.zstKode IN (?)', $categoryIds) . "              
          ";

        $stmt = $this->_dbh->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $images = array();
        foreach ($result as $key => $value) {
            $images[$value['zstKode']] = $value;
        }

        return $images;
    }

    public function getFirstLevelSubcategory($categoryId)
    {
        $query = "
            SELECT
                zstKode, zstOmschrijving
            FROM
                %dbprf%zkstrc
            WHERE
                zstHoofdgroep=:categoryId
           AND 
              ( zksType = 'M' OR ( ZksType = 'L' AND zksArtCount > 0 ) )
             
        ";

        $stmt = $this->_dbh->prepare($query);
        $stmt->bindParam('categoryId', $categoryId);
        $stmt->execute();
        $firstLavelCategories = $stmt->fetchAll();
        foreach ($firstLavelCategories as $key => $firstLavelCategory) {
            $subCategotyProductsIds = $this->getSubCategotyProductsIds($firstLavelCategory['zstKode'], 1);
            if (empty ($subCategotyProductsIds)) {
                unset($firstLavelCategories[$key]);
                return $firstLavelCategories;
            }
        }
        return $firstLavelCategories;
    }

    public function getSubCategotyProductsIds($categoryId, $amount)
    {
        $subCategoryIds = array_values($this->getSubCategories($categoryId));
        $subCategorySimpleIds = array();
        for ($i = 0; $i < count($subCategoryIds); $i++) {
            if (gettype($subCategoryIds[$i]) == 'array') {
                for ($j = 0; $j < count($subCategoryIds[$i]); $j++) {
                    array_push($subCategorySimpleIds, (string)$subCategoryIds[$i][$j]);
                }
            } else {
                array_push($subCategorySimpleIds, (string)$subCategoryIds[$i]);
            }
        }

        if (empty ($subCategorySimpleIds)) {
            $subCategorySimpleIds[0] = $categoryId;
        }

        $query = "
           SELECT 
              zkaArtikelkode 
          FROM 
              %dbprf%zksart
          WHERE 
            azlZoekkode IN  (" . ($this->_dbh->quoteInto('?', $subCategorySimpleIds)) . ") LIMIT :amount";

        $stmt = $this->_dbh->prepare($query);
        $stmt->bindParam('amount', $amount);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRandomCategories($limit = 4)
    {
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $defaultLanguage = HAKVOORT_DEFAULT_LANGUAGE;
        $language = $this->languageAliases[strtoupper(WEBSITE_LANG)];

        $query = "
                SELECT zstKode, zstHoofdgroep, zksType, 
                        IFNULL(L.zktOmschrijving, IFNULL(OL.zktOmschrijving, Z.zstOmschrijving)) AS zstOmschrijving
                FROM %dbprf%zkstrc AS Z
                LEFT JOIN 
                `%dbprf%zkstaal` AS L ON (
                    L.zktZoekkode = Z.zstKode
                        AND
                    L.zktTaal = :language)
                LEFT JOIN 
                `%dbprf%zkstaal` AS OL ON (
                    OL.zktZoekkode = Z.zstKode
                        AND
                    OL.zktTaal = :defaultLanguage)
                WHERE Z.zksWebsite = :websiteCode AND Z.zksAktief = 1 AND
                        ( Z.zksType = 'M' OR ( Z.ZksType = 'L' AND Z.zksArtCount > 0 ))
                ORDER BY RAND()
				LIMIT $limit
                ";

        $query = $this->_dbh->prepare($query);
        $query->bindParam('websiteCode', $websiteCode);
        $query->bindParam('language', $language);
        $query->bindParam('defaultLanguage', $defaultLanguage);
        $query->execute();
        $results = $query->fetchAll();

        return $results;
    }


    public function getCategories()
    {

        if (Zend_Registry::isRegistered('categories')) {
            return Zend_Registry::get('categories');
        }
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $defaultLanguage = HAKVOORT_DEFAULT_LANGUAGE;
        $language = $this->languageAliases[strtoupper(WEBSITE_LANG)];
        $query = "
                SELECT zstKode, zstHoofdgroep, zksType, 
                        IFNULL(L.zktOmschrijving, IFNULL(OL.zktOmschrijving, Z.zstOmschrijving)) AS zstOmschrijving
                FROM %dbprf%zkstrc AS Z
                LEFT JOIN 
                `%dbprf%zkstaal` AS L ON (
                    L.zktZoekkode = Z.zstKode
                        AND
                    L.zktTaal = :language)
                LEFT JOIN 
                `%dbprf%zkstaal` AS OL ON (
                    OL.zktZoekkode = Z.zstKode
                        AND
                    OL.zktTaal = :defaultLanguage)
                WHERE Z.zksWebsite = :websiteCode AND Z.zksAktief = 1 AND
                        ( Z.zksType = 'M' OR ( Z.ZksType = 'L' AND Z.zksArtCount > 0 ))
                ORDER BY Z.zstHoofdgroep ASC, Z.zksVolgorde ASC
                ";

        $query = $this->_dbh->prepare($query);
        $query->bindParam('websiteCode', $websiteCode);
        $query->bindParam('language', $language);
        $query->bindParam('defaultLanguage', $defaultLanguage);
        $query->execute();
        $results = $query->fetchAll();
        $categories = array();
        foreach ($results AS $row) {
            $categories[$row['zstKode']] = $row;
        }

        Zend_Registry::set('categories', $categories);
        return $categories;
    }

    public function getCategories_new()
    {
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $defaultLanguage = HAKVOORT_DEFAULT_LANGUAGE;
        $language = $this->languageAliases[strtoupper(WEBSITE_LANG)];
        $query = "
                SELECT zstKode, zstHoofdgroep, zksType, 
                        IFNULL(L.zktOmschrijving, IFNULL(OL.zktOmschrijving, Z.zstOmschrijving)) AS zstOmschrijving
                FROM %dbprf%zkstrc AS Z
                LEFT JOIN 
                `%dbprf%zkstaal` AS L ON (
                    L.zktZoekkode = Z.zstKode
                        AND
                    L.zktTaal = :language)
                LEFT JOIN 
                `%dbprf%zkstaal` AS OL ON (
                    OL.zktZoekkode = Z.zstKode
                        AND
                    OL.zktTaal = :defaultLanguage)
                WHERE Z.zksWebsite = '' AND Z.zksAktief = 1 AND
                        ( Z.zksType = 'M' OR ( Z.ZksType = 'L' /*AND Z.zksArtCount > 0 */))
                ORDER BY Z.zksVolgorde ASC  
                ";

        $query = $this->_dbh->prepare($query);
        $query->bindParam('language', $language);
        $query->bindParam('defaultLanguage', $defaultLanguage);
        $query->execute();
        $results = $query->fetchAll();
        $categories = array();
        foreach ($results AS $row) {
            $categories[$row['zstKode']] = $row;
        }

        Zend_Registry::set('categories', $categories);
        return $categories;
    }

    public function getOccasionCategories()
    {

        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $defaultLanguage = HAKVOORT_DEFAULT_LANGUAGE;
        $language = $this->languageAliases[strtoupper(WEBSITE_LANG)];
        $query = "
                SELECT zstKode, zstHoofdgroep, zksType, 
                        IFNULL(L.zktOmschrijving, IFNULL(OL.zktOmschrijving, Z.zstOmschrijving)) AS zstOmschrijving
                FROM %dbprf%zkstrc AS Z
                LEFT JOIN 
                `%dbprf%zkstaal` AS L ON (
                    L.zktZoekkode = Z.zstKode
                        AND
                    L.zktTaal = :language)
                LEFT JOIN 
                `%dbprf%zkstaal` AS OL ON (
                    OL.zktZoekkode = Z.zstKode
                        AND
                    OL.zktTaal = :defaultLanguage)
                WHERE Z.zksWebsite = :websiteCode AND Z.zksAktief = 1 AND
                        ( Z.zksType = 'M' OR ( Z.ZksType = 'L' AND Z.zksSnfCount > 0 ))
                ORDER BY Z.zstHoofdgroep ASC, Z.zksVolgorde ASC
                ";

        $query = $this->_dbh->prepare($query);
        $query->bindParam('websiteCode', $websiteCode);
        $query->bindParam('language', $language);
        $query->bindParam('defaultLanguage', $defaultLanguage);
        $query->execute();
        $results = $query->fetchAll();
        $categories = array();
        foreach ($results AS $row) {
            $categories[$row['zstKode']] = $row;
        }
        return $categories;
    }

    public function getMenu($categoryId, $removeClosedCategories = true, $leave_only = false)
    {
        $categories = $this->getCategories();
        if ($leave_only !== false and is_array($leave_only)) {
            foreach ($categories as $zstKode => $value) {
                if ($value['zksType'] == "L" && !in_array($value['zstKode'], $leave_only)) {
                    unset($categories[$zstKode]);
                }
            }
        }
        $categories_tree = Ds_Tree::treealize($categories, 'zstKode', 'zstHoofdgroep', HAKVOORT_ZOEKSTRUCTUUR_ID);

        $this->removeEmptyCategories($categories_tree);
        if ($removeClosedCategories) {
            $this->removeClosedCategories($categories_tree, $categoryId);
        }

        return $categories_tree;
    }

    public function getMenuByCategoryId($curr_categoryId, $removeClosedCategories = true, $leave_only = false)
    {
        $categories = $this->getCategories();

        if (defined('NEW_HAKPRO')) {
            $tmp_curr_categoryId = $curr_categoryId;
            $cat_ids_tree = array();
            $cat_descs = array();
            while ($categories[$tmp_curr_categoryId]['zstHoofdgroep'] != '') {
                $catId = $categories[$tmp_curr_categoryId]['zstKode'];
                $desc = $categories[$tmp_curr_categoryId]['zstOmschrijving'];
                $tmp_curr_categoryId = $categories[$tmp_curr_categoryId]['zstHoofdgroep'];
                $cat_ids_tree[] = $catId;
                $cat_descs[] = $desc;
            }
            $count = count($cat_ids_tree);
            $categoryId = $cat_ids_tree[($count - 2)];
            $cat_desc = $cat_descs[($count - 2)];
        }
        if ($leave_only !== false and is_array($leave_only)) {
            foreach ($categories as $zstKode => $value) {
                if ($value['zksType'] == "L" && !in_array($value['zstKode'], $leave_only)) {
                    unset($categories[$zstKode]);
                }
            }
        }
        $categories_tree = Ds_Tree::treealize($categories, 'zstKode', 'zstHoofdgroep', $categoryId);
        $this->removeEmptyCategories($categories_tree);
        if ($removeClosedCategories) {
            $this->removeClosedCategories($categories_tree, $curr_categoryId);
        }
        $categories_tree['parent_cat_id'] = array('categoryId' => $categoryId, 'cat_desc' => $cat_desc);
        return $categories_tree;
    }

    public function getOccasionsMenu($categoryId, $removeClosedCategories = true)
    {
        $categories = $this->getOccasionCategories();
        $categories_tree = Ds_Tree::treealize($categories, 'zstKode', 'zstHoofdgroep', ' ');
        $this->removeEmptyCategories($categories_tree);
        if ($removeClosedCategories) {
            $this->removeClosedCategories($categories_tree, $categoryId);
        }
        return $categories_tree;
    }

    public function removeClosedCategories(&$categories_tree, $categoryId)
    {
        foreach ($categories_tree as $id => &$menuItem) {
            if ($menuItem['zstKode'] === $categoryId) {
                if (isset($menuItem['children'])) {
                    foreach ($menuItem['children'] as &$childs) {
                        if (isset($childs['children'])) {
                            unset($childs['children']);
                        }
                    }
                }
            } else {
                if (isset($menuItem['children']) && in_array($categoryId, $this->getAllChildCategoryIds($menuItem['children']), true)) {
                    $this->removeClosedCategories($menuItem['children'], $categoryId);
                } else {
                    if (isset ($menuItem['children'])) {
                        unset($menuItem['children']);
                    }
                }
            }
        }
    }

    public function removeEmptyCategories(&$categories_tree)
    {
        foreach ($categories_tree as $id => &$menuItem) {

            if (isset($menuItem['children']) && count($menuItem['children']) > 0) {
                $this->removeEmptyCategories($menuItem['children']);
            }
            if ($menuItem['zksType'] == 'M' && (!isset ($menuItem['children']) || count($menuItem['children']) == 0)) {
                unset($categories_tree[$id]);

            }
        }
    }

    public function getAllChildCategoryIds($tree)
    {

        $categoryIds = array();
        foreach ($tree as $branch) {
            $categoryIds[] = $branch['zstKode'];
            if (isset($branch['children'])) {
                $childCategoryIds = $this->getAllChildCategoryIds($branch['children']);
                $categoryIds = array_merge($categoryIds, $childCategoryIds);
            }
        }

        return $categoryIds;
    }

    public function getAllChildCategories($tree)
    {

        $categories = array();
        foreach ($tree as $branch) {
            $categories[$branch['zstKode']] = $branch;
            if (isset($branch['children'])) {
                unset($categories[$branch['zstKode']]['children']);
                $childCategories = $this->getAllChildCategories($branch['children']);
                $categories = ($categories + $childCategories);
            }
        }

        return $categories;
    }

    public function getAllChildCategoryIdsAndNames($tree)
    {

        $categoryIds = array();
        foreach ($tree as $branch) {

            $categoryIds[$branch['zstKode']] = $branch['zktOmschrijving'];

            if (isset($branch['children'])) {
                $childCategoryIds = $this->getAllChildCategoryIdsAndNames($branch['children']);
                $categoryIds = array_merge($categoryIds, $childCategoryIds);
            }
        }

        return $categoryIds;
    }

    public function getCategoryByProductId($productId)
    {

        return false;
    }


    public function getSubCategories($categoryId)
    {
        $cats = array();
        $categories = $this->getCategories();
        if (isset($categories[$categoryId])) {
            $cats[$categoryId] = $categoryId;
        }
        $search = true;
        while ($search) {
            $search = false;
            foreach ($categories as $key => $value) {
                if (isset($cats[$value['zstHoofdgroep']]) && !isset($cats[$key])) {
                    $cats[$key] = $key;
                    $search = true;
                }
            }
        }
        sort($cats);
        return $cats;
    }

    public function getSubOccasionCategories($categoryId)
    {
        $cats = array();
        $categories = $this->getOccasionCategories();
        $cats[$categoryId] = $categoryId;
        $search = true;
        while ($search) {
            $search = false;
            foreach ($categories as $key => $value) {
                if (isset($cats[$value['zstHoofdgroep']]) && !isset($cats[$key])) {
                    $cats[$key] = $key;
                    $search = true;
                }
            }
        }
        sort($cats);
        return $cats;
    }


    public function getCategoryName($categoryId)
    {
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $defaultLanguage = HAKVOORT_DEFAULT_LANGUAGE;
        $language = $this->languageAliases[strtoupper(WEBSITE_LANG)];

        //Get the categorys
        $query = $this->_dbh->prepare("
            SELECT
                Z.zstKode,
                IF(Z.zstHoofdgroep = '', NULL, Z.zstHoofdgroep) as zstHoofdgroep,
                IF(ISNULL(L.zktOmschrijving),
                    IFNULL(OL.zktOmschrijving, Z.zstOmschrijving), L.zktOmschrijving) AS omschrijving
            FROM
                %dbprf%zkstrc AS Z
            LEFT JOIN
                %dbprf%zkstaal AS L ON (
                    L.zktZoekkode = Z.zstKode
                        AND
                    L.zktTaal = :language)
            LEFT JOIN
                %dbprf%zkstaal AS OL ON (
                    OL.zktZoekkode = Z.zstKode
                        AND
                    OL.zktTaal = :defaultLanguage)
            INNER JOIN
                %dbprf%websites AS W ON Z.ZksWebsite = W.wbsKode
            WHERE
                W.wbsKode = :websiteCode
            AND
                Z.zksAktief = 1
            AND
                Z.zstKode = :category
            ORDER BY
                Z.zstHoofdgroep ASC,
                Z.zksVolgorde ASC");

        $query->bindParam('language', $language);
        $query->bindParam('defaultLanguage', $defaultLanguage);
        $query->bindParam('category', $categoryId);
        $query->bindParam('websiteCode', $websiteCode);
        $query->execute();
        $results = $query->fetchAll();
        $tmp = isset($results[0]['omschrijving']) ? $results[0]['omschrijving'] : '';
        return $tmp;
    }

    public function getBreadcrumbs($categoryId, $is_occasion)
    {
        $breadcrumbs = array();
        if ($is_occasion === true) {
            $categories = $this->getOccasionCategories();
            $z_id = OCCASIONS_ZOEKSTRUCTUUR_ID;
        } else {

            $categories = $this->getCategories();

            $z_id = HAKVOORT_ZOEKSTRUCTUUR_ID;
        }
        $c = $categoryId;
        while (isset($categories[$c]['zstHoofdgroep']) && ($c != $z_id)) {
            if ($categories[$c]) {
                $breadcrumbs[] = $categories[$c];
                $c = $categories[$c]['zstHoofdgroep'];
            }
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        return $breadcrumbs;
    }


}
