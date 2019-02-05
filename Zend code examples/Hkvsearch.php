<?php

class Custom_Webshop_Model_DbTable_Hkvsearch
{

    protected $dbh;
    protected $commonWords = array(
        'NL' => array(),
        'EN' => array(),
        'DE' => array()
    );
    protected $languageAliases = array(
        'EN' => 'E',
        'FR' => 'F',
        'DE' => 'D',
        'NL' => 'NL'
    );
    protected $products = array();
    protected $categories = array();
    protected $products_count = 0;
    public $cache;


    public function __construct()
    {
        $this->dbh = Zend_Registry::get('hakvoortDbh');
        $oBackend = new Zend_Cache_Backend_Memcached(
            array(
                'servers' => array(array(
                    'host' => 'localhost',
                    'port' => '11211'
                )),
                'compression' => true
            ));
        $oFrontend = new Zend_Cache_Core(
            array(
                'lifetime' => 36000,
                'caching' => true,
                'write_control' => true,
                'ignore_user_abort' => true,
                'automatic_serialization' => true
            ));
        $this->cache = Zend_Cache::factory($oFrontend, $oBackend);
    }

    public function escape($keywords, $regexp = false)
    {
        if ($regexp == true) {
            $keywords = preg_quote($keywords);
        } else {
            $keywords = addcslashes($keywords, '%\\');
        }

        $keywords = $this->dbh->quote($keywords);

        $sub = substr($keywords, 1, strlen($keywords) - 2);
        return $sub;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getProductsCount()
    {
        return $this->products_count;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function redirectIfProductFound($keyword, $view)
    {
        $isProductId = strpos($keyword, ".");
        if ($isProductId == false) {
            $lenght = strlen($keyword);
            $keywords = array();
            for ($i = 1; $i < $lenght; $i++) {
                $sub1 = substr($keyword, 0, $i);
                $sub2 = substr($keyword, $i, $lenght);
                $temp = $sub1 . "." . $sub2;
                array_push($keywords, $temp);
            }
            $webshopProduct = Webshop_Frontend_Product::getInstance('Hkv');
            $foundProduct = $webshopProduct->getProducts($keywords);
            if (count($foundProduct) == 1) {
                header('Location: ' . $view->productUrl($foundProduct[0]['productId'], $foundProduct[0]['description']));
                exit;
            }

        } else {
            $webshopProduct = Webshop_Frontend_Product::getInstance('Hkv');
            $foundProduct = $webshopProduct->getProduct($keyword);
            if ($foundProduct !== false) {
                header('Location: ' . $view->productUrl($foundProduct['productId'], $foundProduct['omschrijving']));
                exit;
            }
        }

    }

    public function redirectIfOccasionProductFound($keyword, $view)
    {
        $occasionsInfo = new Custom_Webshop_Model_DbTable_Hkvoccasionproducts();
        $isProductId = strpos($keyword, ".");
        if ($isProductId == false) {
            $lenght = strlen($keyword);
            $keywords = array();
            for ($i = 1; $i < $lenght; $i++) {
                $sub1 = substr($keyword, 0, $i);
                $sub2 = substr($keyword, $i, $lenght);
                $temp = $sub1 . "." . $sub2;
                array_push($keywords, $temp);
            }
            $webshopProduct = Webshop_Frontend_Product::getInstance('Hkv');
            $foundProduct = $webshopProduct->getProducts($keywords);
            if (count($foundProduct) == 1) {
                header('Location: ' . $view->productUrl($foundProduct[0]['productId'], $foundProduct[0]['description']));
                exit;
            }

        } else {
            $webshopProduct = Webshop_Frontend_Product::getInstance('Hkv');
            $foundProduct = $occasionsInfo->getProduct($keyword);
            if ($foundProduct !== false) {
                header('Location: ' . $view->productUrl($foundProduct['productId'], $foundProduct['omschrijving']));
                exit;
            }
        }

    }

    private function subQuery($fieldName)
    {
        // Some variables vor query.
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $lang = $this->languageAliases[HAKVOORT_LANGUAGE];
        $accessibilityIds_query_part = "";
        if (@$this->accessibilityIds) {
            $accessibilityIds_query_part = " AND za.azlZoekkode IN ({$this->accessibilityIds}) ";
        }

        $subSelect = "
            AND ( SELECT COUNT(*) FROM `%dbprf%zoektekst` as zoek
            INNER JOIN %dbprf%zksart AS za ON za.zkaArtikelkode = zoek.zkiKode
            WHERE (zoek.zkiZoektekst LIKE CONCAT('%', {$fieldName}, '%' ))
            AND (zoek.zkiIndextype='A')
            AND (zoek.zkiWebsite='{$websiteCode}')
            AND (zoek.zkiTaal='{$lang}')
            LIMIT 1 ) > 0
        ";

        return $subSelect;
    }

    public function explode_keyword($keyword)
    {
        $tmp_keywords = explode(" ", $keyword);
        $keywords = array();
        for ($i = 0; $i < count($tmp_keywords); $i++) {
            $tmp_keywords[$i] = trim($tmp_keywords[$i]);
            if ($tmp_keywords[$i] !== '') {
                $keywords[] = $tmp_keywords[$i];
            }
        }
        return $keywords;
    }

    public function getSynonyms($keyword, $catIds = NULL)
    {
        $limit = 10;
        if (defined('NEW_HAKPRO')) {
            $limit = 2;
        }
        $lang = $this->languageAliases[HAKVOORT_LANGUAGE];
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $synonyms = array();

        if (isset($catIds)) {
            $this->accessibilityIds = $catIds;
        }
        $query = "
            SELECT `wrdSynoniem`
            FROM `base_woordlijst`
            WHERE `wrdVervangtype` = 'C'
            AND `wrdZoekwoord` LIKE :keyword AND `wrdTaal` = :language
            LIMIT 1";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam('keyword', $keyword);
        $stmt->bindParam('language', $lang);
        $stmt->execute();
        $correction = $stmt->fetch();
        if ($correction) {
            $keyword = $correction['wrdSynoniem'];
        }

        // get synonym from database
        $query = "
            SELECT DISTINCT `wrdSynoniem`
            FROM `base_woordlijst` as wr
            WHERE (`wrdVervangtype` = 'S'
            AND `wrdZoekwoord` LIKE :keyword AND `wrdTaal` = :language)
			{$this->subQuery( 'wr.wrdSynoniem' )}
            LIMIT 1";
        $keywordWildCards = '' . $keyword . '%';
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam('keyword', $keywordWildCards);
        $stmt->bindParam('language', $lang);

        $stmt->execute();

        $result = $stmt->fetch();
        if ($result) {
            $synonym = $result['wrdSynoniem'];
        }

        // get related keywords from database
        $stmt_synonym = '';
        if (isset($synonym)) {
            $synonyms[] = $synonym;
            $stmt_synonym = " OR `wrdSynoniem` LIKE :synonym ";
        }
        $query = "
            SELECT DISTINCT `wrdZoekwoord`
            FROM `base_woordlijst` AS wr            
            WHERE (`wrdVervangtype` = 'S'
					AND (`wrdSynoniem` LIKE :keyword {$stmt_synonym})
					AND `wrdTaal` = :language)
			    {$this->subQuery( 'wr.wrdZoekwoord' )}
			LIMIT 0, :limit
           ";
        $keywordWildCards = trim($keyword) . '%';
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam('keyword', $keywordWildCards);
        $stmt->bindParam('limit', $limit);
        if (isset($synonym)) {
            $tmp = trim($synonym) . '%';
            $stmt->bindParam('synonym', $tmp);
        }
        $stmt->bindParam('language', $lang);

        $stmt->execute();

        $results = $stmt->fetchAll();
        if (is_array($results)) {
            foreach ($results as $result) {
                $synonyms[] = $result['wrdZoekwoord'];
            }
        }

        $synonyms = array_unique($synonyms);
        return $synonyms;
    }


    public function autocomplete($keyword)
    {

        $lang = $this->languageAliases[HAKVOORT_LANGUAGE];
        $websiteCode = HAKVOORT_WEBSITE_CODE;
        $query = "
				SELECT `zkiZoekwoord` as word, count(`zkiZoekwoord`) as cnt
					FROM %dbprf%zoekindex
					WHERE (`zkiZoekwoord` LIKE '" . implode("%' OR `zkiZoekwoord` LIKE '", $this->explode_keyword($this->escape($keyword))) . "%') AND
							zkiTaal = :language AND
							zkiWebsite = :websiteCode
					GROUP BY word
					ORDER BY cnt DESC
					LIMIT 0,10";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam('websiteCode', $websiteCode);
        $stmt->bindParam('language', $lang);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;

    }


    public function search($keyword, $categoryId = false)
    {

        $dbh = Zend_Registry::get('hakvoortDbh');
        $lang = $this->languageAliases[HAKVOORT_LANGUAGE];
        $groupKode = 'zkiKode';
        $ifCategory = "";
        $query = " SELECT DISTINCT zkiKode, za.azlZoekkode AS category 
					FROM %dbprf%zoektekst zi 
					INNER JOIN %artprf%artbase ab ON ab.artKode = zi.zkiKode
					INNER JOIN %dbprf%zksart za ON za.zkaArtikelkode = zi.zkiKode 
					INNER JOIN %dbprf%zkstrc zt ON zt.zstKode = za.azlZoekkode "; // temp, for handendrogers
        $keywords = $this->explode_keyword($keyword);
        $LIKE_statement = " (zkiZoektekst LIKE '%" . $this->escape($keywords[0]) . "%') ";
        if (isset($keywords[1])) {
            $LIKE_statement .= " AND (zkiZoektekst LIKE '%" . $this->escape($keywords[1]) . "%') ";
        }
        $tmp = array();
        for ($i = 2; $i < count($keywords); $i++) {
            $tmp[] = " (zkiZoektekst LIKE '%" . $this->escape($keywords[$i]) . "%') ";
        }
        if (count($tmp)) {
            $tmp = implode(' OR ', $tmp);
            $tmp = ' AND (' . $tmp . ') ';
            $LIKE_statement .= $tmp;
        }
        $LIKE_statement = ' ( ' . $LIKE_statement . ' ) ';

        $where[] = $LIKE_statement;
        $where[] = " zkiIndextype = 'A'";
        $where[] = " zkiTaal = '$lang'";
        $where[] = " zkiWebsite = '" . HAKVOORT_WEBSITE_CODE . "'";
        $where[] = " zt.zksWebsite = '" . HAKVOORT_WEBSITE_CODE . "'"; // temp, for handendrogers
        $query .= " WHERE " . implode(" AND ", $where);
        $query .= " ORDER BY zkiKode ";
        $stmt = $this->dbh->prepare($query);
        $stmt->execute();

        $searchResults = $stmt->fetchAll();
        $available_categories = array();
        $foundArtCount = array();
        foreach ($searchResults as $tmp) {
            $available_categories[$tmp['category']] = $tmp['category'];
            if (isset($foundArtCount[$tmp['category']])) {
                $foundArtCount[$tmp['category']]++;
            } else {
                $foundArtCount[$tmp['category']] = 1;
            }
        }

        $categoriesModel = new Custom_Webshop_Model_DbTable_Hkvproductcategories();
        $categories = $categoriesModel->getCategories();
        $head_categories = array();
        foreach ($categories as $category) {
            if ($category['zstHoofdgroep'] == HAKVOORT_ZOEKSTRUCTUUR_ID) {
                $head_categories[$category['zstKode']] = $category;
            }
        }
        $categories_tree = Ds_Tree::treealize($categories, 'zstKode', 'zstHoofdgroep', HAKVOORT_ZOEKSTRUCTUUR_ID);
        $size = count($categories_tree);
        for ($i = 0; $i < $size; $i++) {
            if (isset($categories_tree[$i]['children'])) {
                $head_categories[$categories_tree[$i]['zstKode']]['children'] = $categoriesModel->getAllChildCategories($categories_tree[$i]['children']);
                $head_categories[$categories_tree[$i]['zstKode']]['children'] = array_intersect_key($head_categories[$categories_tree[$i]['zstKode']]['children'], $available_categories);
                if (count($head_categories[$categories_tree[$i]['zstKode']]['children']) == 0) {
                    unset($head_categories[$categories_tree[$i]['zstKode']]['children']);
                }
            }
        }
        $products = array();
        if ($categoryId) {
            $allowed_categories = array($categoryId);
            if (array_key_exists($categoryId, $head_categories)) {
                if (isset($head_categories[$categoryId]['children'])) {
                    foreach ($head_categories[$categoryId]['children'] as $key => $value) {
                        $allowed_categories[] = $key;
                    }
                }
            }
            foreach ($searchResults as $key => $product) {
                if (in_array($product['category'], $allowed_categories)) {
                    $products[$product['zkiKode']] = $product;
                }
            }
        } else {
            foreach ($searchResults as $key => $product) {
                $products[$product['zkiKode']] = $product;
            }
        }
        foreach ($head_categories as &$tmp) {
            if (isset($tmp['children'])) {
                foreach ($tmp['children'] as $key => &$child) {
                    $child['foundArtCount'] = $foundArtCount[$key];
                }
            }
        }

        $this->products = $products;
        $this->products_count = count($products);
        $this->categories = $head_categories;
    }


    public function logKeyword($keyword, $amount = 1, $userId = null)
    {
        // register keyword
        $admincode = HAKVOORT_ADMIN_CODE;
        $lang = $this->languageAliases[HAKVOORT_LANGUAGE];
        $startTime = Zend_Registry::get('startTime');

        if (is_null($userId)) {
            $userId = '';
        }

        $query = "  INSERT
                  INTO %dbprf%webzoek
                   SET zoekstring = :keyword,
                       zoeksoundex = SOUNDEX(:keyword),
                       taal = :lang,
                       aantal = :amount,
                       datum = :date,
                       tijd = :time,
                       ipadres = :ip,
                       webrelatieID = :userId,
                       generatietijd = :executionTime
        ";

        $date = date('Ymd');
        $time = date('His');
        $stmt = $this->dbh->prepare($query);

        $stmt->bindParam('keyword', $keyword);
        $stmt->bindParam('lang', $lang);
        $stmt->bindParam('amount', $amount);
        $stmt->bindParam('date', $date);
        $stmt->bindParam('time', $time);
        $stmt->bindParam('ip', $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam('userId', $userId);

        $executionTime = microtime(true) - $startTime;

        $stmt->bindParam('executionTime', $executionTime);

        $stmt->execute();
    }


    public function advanced_search($keyword, $categoryId = false)
    {
        $dbh = Zend_Registry::get('hakvoortDbh');
        $lang = $this->languageAliases[HAKVOORT_LANGUAGE];
        $keywords = $this->explode_keyword($keyword);

        $query = " SELECT 	zkaArtikelkode AS zkiKode, 
							azlZoekkode AS category, 
							LOCATE(" . "'" . $this->escape($keywords[0]) . "'" . " , aotFaktuurOmschrijving) AS T, 
							aotFaktuurOmschrijving  REGEXP '^([^\n]*)" . $this->escape($keywords[0], true) . "' AS RE,
							aotFaktuurOmschrijving
					FROM %dbprf%artdesc AS AD 
					INNER JOIN %artprf%artbase AS AB 
							ON (AD.aotArtikelkode = AB.artKode AND AD.aotTaalkode = '$lang')
					INNER JOIN %dbprf%zksart AS ZA 
							ON ZA.zkaArtikelkode = AD.aotArtikelkode
					INNER JOIN %dbprf%zkstrc AS ZTC 
							ON (ZTC.zstKode = ZA.azlZoekkode AND ZTC.zksWebsite = '" . HAKVOORT_WEBSITE_CODE . "')					
				";


        $M_A_keywords = $this->escape($keywords[0]);
        $LIKE_statement = " (AD.aotFaktuurOmschrijving LIKE '%" . $this->escape($keywords[0]) . "%') ";
        $LIKE_statement_description = " (zkiZoektekst LIKE '%" . $this->escape($keywords[0]) . "%') ";
        if (isset($keywords[1])) {
            $M_A_keywords .= " " . $this->escape($keywords[1]);
            $LIKE_statement .= " AND (AD.aotFaktuurOmschrijving LIKE '%" . $this->escape($keywords[1]) . "%') ";
            $LIKE_statement_description .= " AND (zkiZoektekst LIKE '%" . $this->escape($keywords[1]) . "%') ";
        }
        $tmp = array();
        $tmp_desc = array();
        for ($i = 2; $i < count($keywords); $i++) {
            $M_A_keywords .= " " . $this->escape($keywords[$i]);
            $tmp[] = " (AD.aotFaktuurOmschrijving LIKE '%" . $this->escape($keywords[$i]) . "%') ";
            $tmp_desc = " (zkiZoektekst LIKE '%" . $this->escape($keywords[$i]) . "%') ";
        }

        if (count($tmp)) {
            $tmp = implode(' OR ', $tmp);
            $tmp = ' AND (' . $tmp . ') ';
            $tmp_desc = ' AND ' . $tmp_desc;
            $LIKE_statement .= $tmp;
            $LIKE_statement_description .= $tmp_desc;
        }

        $LIKE_statement = ' ( ' . $LIKE_statement . ' ) ';
        $LIKE_statement_description = ' ( ' . $LIKE_statement_description . ' ) ';

        $where[] = $LIKE_statement;
        $query .= " WHERE " . implode(" AND ", $where);
        $query .= " HAVING RE != 0
					ORDER BY T LIMIT 250
					";

        $querybyDesc = "
					SELECT 	zkiKode, 
							za.azlZoekkode AS category, 
							MATCH(zkiZoektekst) AGAINST('$M_A_keywords' ) AS rel
					FROM %dbprf%zoektekst zi 
					INNER JOIN %artprf%artbase ab 
							ON ab.artKode = zi.zkiKode
					INNER JOIN %dbprf%zksart za 
							ON za.zkaArtikelkode = zi.zkiKode
					INNER JOIN %dbprf%zkstrc AS ztc 
							ON (ztc.zstKode = za.azlZoekkode AND ztc.zksWebsite = '" . HAKVOORT_WEBSITE_CODE . "')
					WHERE zkiTaal = '$lang' AND zkiWebsite = '" . HAKVOORT_WEBSITE_CODE . "' AND zkiIndextype = 'A' 
								AND	" . $LIKE_statement_description . "
					ORDER BY rel LIMIT 250					
		";

        $stmt = $this->dbh->prepare($query);
        $stmtDesc = $this->dbh->prepare($querybyDesc);
        $stmt->execute();
        $stmtDesc->execute();

        /* search results by title */
        $searchResultsbyTitle = $stmt->fetchAll();

        /* search results by description */
        $searchResultsbyDesc = $stmtDesc->fetchAll();

        // Give high priority to items that have exact search word
        $exactWordSearch_both = array(); //Array with word with left and right spaces
        $exactWordSearch_left = array(); //Array with word with left spaces
        $exactWordSearch_right = array(); //Array with word with right spaces

        foreach ($searchResultsbyTitle as $key => $value) {
            if (isset($searchResultsbyTitle[$key])) {
                unset($searchResultsbyTitle[$key]);
                $searchResultsbyTitle[$value['zkiKode'] . '_' . $value['category']] = $value;
            }

            if (strpos(strtolower($value['aotFaktuurOmschrijving']), " " . $this->escape($keywords[0]) . " ") !== false) {
                $exactWordSearch_both[$value['zkiKode'] . '_' . $value['category']] = $value;
                unset($searchResultsbyTitle[$key]);
            } elseif (strpos(strtolower($value['aotFaktuurOmschrijving']), $this->escape($keywords[0]) . " ") !== false) {
                $exactWordSearch_right[$value['zkiKode'] . '_' . $value['category']] = $value;
                unset($searchResultsbyTitle[$key]);
            } elseif (strpos(strtolower($value['aotFaktuurOmschrijving']), " " . $this->escape($keywords[0])) !== false) {
                $exactWordSearch_left[$value['zkiKode'] . '_' . $value['category']] = $value;
                unset($searchResultsbyTitle[$key]);
            }
        }

        foreach ($searchResultsbyDesc as $key => $value) {
            if (isset($searchResultsbyDesc[$key])) {
                unset($searchResultsbyDesc[$key]);
                $searchResultsbyDesc[$value['zkiKode'] . '_' . $value['category']] = $value;
            }
        }

        $searchResultsbyTitle = array_merge($exactWordSearch_both, $exactWordSearch_right, $exactWordSearch_left, $searchResultsbyTitle);
        $searchResults = array_merge($searchResultsbyTitle, $searchResultsbyDesc);
        $available_categories = array();
        $foundArtCount = array();
        foreach ($searchResults as $tmp) {
            $available_categories[$tmp['category']] = $tmp['category'];
            if (isset($foundArtCount[$tmp['category']])) {
                $foundArtCount[$tmp['category']]++;
            } else {
                $foundArtCount[$tmp['category']] = 1;
            }
        }

        $categoriesModel = new Custom_Webshop_Model_DbTable_Hkvproductcategories();
        $categories = $categoriesModel->getCategories();
        $head_categories = array();
        foreach ($categories as $category) {
            if ($category['zstHoofdgroep'] == HAKVOORT_ZOEKSTRUCTUUR_ID) {
                $head_categories[$category['zstKode']] = $category;
            }
        }

        $categories_tree = Ds_Tree::treealize($categories, 'zstKode', 'zstHoofdgroep', HAKVOORT_ZOEKSTRUCTUUR_ID);

        $size = count($categories_tree);
        for ($i = 0; $i < $size; $i++) {
            if (isset($categories_tree[$i]['children'])) {
                $head_categories[$categories_tree[$i]['zstKode']]['children'] = $categoriesModel->getAllChildCategories($categories_tree[$i]['children']);
                $head_categories[$categories_tree[$i]['zstKode']]['children'] = array_intersect_key($head_categories[$categories_tree[$i]['zstKode']]['children'], $available_categories);
                if (count($head_categories[$categories_tree[$i]['zstKode']]['children']) == 0) {
                    unset($head_categories[$categories_tree[$i]['zstKode']]['children']);
                }
            }
        }

        $products = array();
        if ($categoryId) {
            $allowed_categories = array($categoryId);
            if (array_key_exists($categoryId, $head_categories)) {
                if (isset($head_categories[$categoryId]['children'])) {
                    foreach ($head_categories[$categoryId]['children'] as $key => $value) {
                        $allowed_categories[] = $key;
                    }
                }
            }
            foreach ($searchResults as $key => $product) {
                if (in_array($product['category'], $allowed_categories)) {
                    $products[$product['zkiKode']] = $product;
                }
            }
        } else {
            foreach ($searchResults as $key => $product) {
                $products[$product['zkiKode']] = $product;
            }
        }

        foreach ($head_categories as &$tmp) {
            $tmp['foundArtCount'] = isset($foundArtCount[$tmp['zstKode']]) ? $foundArtCount[$tmp['zstKode']] : 0;
            if (isset($tmp['children'])) {
                foreach ($tmp['children'] as $key => &$child) {
                    $child['foundArtCount'] = $foundArtCount[$key];
                    $tmp['foundArtCount'] += $child['foundArtCount'];
                }
            }
        }

        $this->products = $products;
        $this->products_count = count($products);
        $this->categories = $head_categories;
    }

    public function advanced_search_occasions($keyword, $categoryId = false)
    {

        $dbh = Zend_Registry::get('hakvoortDbh');
        $lang = $this->languageAliases[HAKVOORT_LANGUAGE];
        $keywords = $this->explode_keyword($keyword);

        $query = " SELECT 	occKode AS zkiKode, 
							azlZoekkode AS category, 
							LOCATE(" . "'" . $this->escape($keywords[0]) . "'" . " , occOmschrijving) AS T, 
							occOmschrijving  REGEXP '^([^\n]*)" . $this->escape($keywords[0], true) . "' AS RE
							
					FROM %dbprf%occasion AS AD 
					INNER JOIN %dbprf%zksart AS ZA 
							ON ZA.zkaArtikelkode = AD.occKode  
					INNER JOIN %dbprf%zkstrc AS ZTC 
							ON (ZTC.zstKode = ZA.azlZoekkode AND ZTC.zksWebsite = '" . HAKVOORT_WEBSITE_CODE . "')					
				";


        $M_A_keywords = $this->escape($keywords[0]);
        $LIKE_statement = " (AD.occOmschrijving LIKE '%" . $this->escape($keywords[0]) . "%') ";
        $LIKE_statement_description = " (zkiZoektekst LIKE '%" . $this->escape($keywords[0]) . "%') ";
        if (isset($keywords[1])) {
            $M_A_keywords .= " " . $this->escape($keywords[1]);
            $LIKE_statement .= " AND (AD.occOmschrijving LIKE '%" . $this->escape($keywords[1]) . "%') ";
            $LIKE_statement_description .= " AND (zkiZoektekst LIKE '%" . $this->escape($keywords[1]) . "%') ";
        }
        $tmp = array();
        $tmp_desc = array();
        for ($i = 2; $i < count($keywords); $i++) {
            $M_A_keywords .= " " . $this->escape($keywords[$i]);
            $tmp[] = " (AD.occOmschrijving LIKE '%" . $this->escape($keywords[$i]) . "%') ";
            $tmp_desc = " (zkiZoektekst LIKE '%" . $this->escape($keywords[$i]) . "%') ";
        }

        if (count($tmp)) {
            $tmp = implode(' OR ', $tmp);
            $tmp = ' AND (' . $tmp . ') ';
            $tmp_desc = ' AND ' . $tmp_desc;
            $LIKE_statement .= $tmp;
            $LIKE_statement_description .= $tmp_desc;
        }

        $LIKE_statement = ' ( ' . $LIKE_statement . ' ) ';
        $LIKE_statement_description = ' ( ' . $LIKE_statement_description . ' ) ';

        $where[] = $LIKE_statement;
        $query .= " WHERE " . implode(" AND ", $where);
        $query .= " HAVING RE != 0
					ORDER BY T LIMIT 250
					";

        $querybyDesc = "
					SELECT 	zkiKode, 
							za.azlZoekkode AS category, 
							MATCH(zkiZoektekst) AGAINST('$M_A_keywords' ) AS rel
					FROM %dbprf%zoektekst zi 
					INNER JOIN %artprf%artbase ab 
							ON ab.artKode = zi.zkiKode
					INNER JOIN %dbprf%zksart za 
							ON za.zkaArtikelkode = zi.zkiKode
					INNER JOIN %dbprf%zkstrc AS ztc 
							ON (ztc.zstKode = za.azlZoekkode AND ztc.zksWebsite = '" . HAKVOORT_WEBSITE_CODE . "')
					WHERE zkiTaal = '$lang' AND zkiWebsite = '" . HAKVOORT_WEBSITE_CODE . "' AND zkiIndextype = 'A' 
								AND	" . $LIKE_statement_description . "
					ORDER BY rel					
		";

        $stmt = $this->dbh->prepare($query);
        $stmtDesc = $this->dbh->prepare($querybyDesc);
        $stmt->execute();
        $stmtDesc->execute();

        /* search results by title */
        $searchResultsbyTitle = $stmt->fetchAll();

        /* search results by description */
        $searchResultsbyDesc = $stmtDesc->fetchAll();

        foreach ($searchResultsbyTitle as $key => $value) {
            if (isset($searchResultsbyTitle[$key])) {
                unset($searchResultsbyTitle[$key]);
                $searchResultsbyTitle[$value['zkiKode'] . '_' . $value['category']] = $value;
            }
        }

        foreach ($searchResultsbyDesc as $key => $value) {
            if (isset($searchResultsbyDesc[$key])) {
                unset($searchResultsbyDesc[$key]);
                $searchResultsbyDesc[$value['zkiKode'] . '_' . $value['category']] = $value;
            }
        }
        unset($searchResultsbyDesc);
        $searchResults = $searchResultsbyTitle;
        $available_categories = array();
        $foundArtCount = array();
        foreach ($searchResults as $tmp) {
            $available_categories[$tmp['category']] = $tmp['category'];
            if (isset($foundArtCount[$tmp['category']])) {
                $foundArtCount[$tmp['category']]++;
            } else {
                $foundArtCount[$tmp['category']] = 1;
            }
        }

        $categoriesModel = new Custom_Webshop_Model_DbTable_Hkvproductcategories();
        $categories = $categoriesModel->getOccasionCategories();

        $head_categories = array();
        foreach ($categories as $category) {
            if ($category['zstHoofdgroep'] == OCCASIONS_ZOEKSTRUCTUUR_ID) {
                $head_categories[$category['zstKode']] = $category;
            }
        }

        $categories_tree = Ds_Tree::treealize($categories, 'zstKode', 'zstHoofdgroep', OCCASIONS_ZOEKSTRUCTUUR_ID);

        $size = count($categories_tree);
        for ($i = 0; $i < $size; $i++) {
            if (isset($categories_tree[$i]['children'])) {
                $head_categories[$categories_tree[$i]['zstKode']]['children'] = $categoriesModel->getAllChildCategories($categories_tree[$i]['children']);
                $head_categories[$categories_tree[$i]['zstKode']]['children'] = array_intersect_key($head_categories[$categories_tree[$i]['zstKode']]['children'], $available_categories);
                if (count($head_categories[$categories_tree[$i]['zstKode']]['children']) == 0) {
                    unset($head_categories[$categories_tree[$i]['zstKode']]['children']);
                }
            }
        }

        $products = array();
        if ($categoryId) {
            $allowed_categories = array($categoryId);
            if (array_key_exists($categoryId, $head_categories)) {
                if (isset($head_categories[$categoryId]['children'])) {
                    foreach ($head_categories[$categoryId]['children'] as $key => $value) {
                        $allowed_categories[] = $key;
                    }
                }
            }
            foreach ($searchResults as $key => $product) {
                if (in_array($product['category'], $allowed_categories)) {
                    $products[$product['zkiKode']] = $product;
                }
            }
        } else {
            foreach ($searchResults as $key => $product) {
                $products[$product['zkiKode']] = $product;
            }
        }

        foreach ($head_categories as &$tmp) {
            $tmp['foundArtCount'] = isset($foundArtCount[$tmp['zstKode']]) ? $foundArtCount[$tmp['zstKode']] : 0;
            if (isset($tmp['children'])) {
                foreach ($tmp['children'] as $key => &$child) {
                    $child['foundArtCount'] = $foundArtCount[$key];
                    $tmp['foundArtCount'] += $child['foundArtCount'];
                }
            }
        }
        $this->products = array_slice($products, 0, 6000, true);
        $this->products_count = count($products);
        $this->categories = $head_categories;
    }
}