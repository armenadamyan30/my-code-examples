<?php

class AdvancedSearch_Controller extends Base_Element_Controller
{

    public function index()
    {
        if ($this->element->getEditMode()) {

        } else {

            if (isset($this->request['keyword'])) {
                $keyword = htmlentities($this->request['keyword'], ENT_COMPAT, 'latin1');
            } elseif (isset($_POST['getsynonyms'])) {
                $this->getsynonyms($_POST['getsynonyms']);
            } else {
                header("Location: " . $this->view->url(array('page' => '404.html'), null, true));
                exit;
            }

            $websiteSettings = Zend_Registry::get('hkvWebsiteSettings');
            $keyword = trim(strtolower($keyword));
            $cache_key = md5($websiteSettings['website_code'] . "_" . $keyword . "_" . $this->request['locale']);
            $cache_key_search = md5($websiteSettings['website_code'] . "_" . 'search_' . $keyword . "_" . $this->request['locale']);
            $statisticsFactory = new Custom_Webshop_Model_DbTable_Detailedstatistics();

            $searchApi = new Custom_Webshop_Model_DbTable_Hkvsearch();
            $categoryId = false;

            $keywords = $searchApi->explode_keyword($keyword);
            foreach ($keywords as $_keyword) {
                $statisticsFactory->addStatisticsData(HAKVOORT_WEBSITE_CODE, $_keyword, $keyword, "S");
            }
            $searchApi->redirectIfProductFound($keyword, $this->view);

            $searchApi->advanced_search($keyword, $categoryId);
            $products = $searchApi->getProducts();


            $categories = $searchApi->getCategories();

            $countofResults = count($products);
            $dump = array(
                'products' => $products,
                'productsCount' => $countofResults,
                'categories' => $categories
            );
            $searchApi->cache->save($dump, $cache_key_search);

            // log to DB
            $userSession = new Zend_Session_Namespace('WebUser');
            $userId = isset($userSession->userId) ? $userSession->userId : null;
            $searchApi->logKeyword($keyword, $countofResults, $userId);

            $productIds = array();
            foreach ($products as $key => $value) {
                if (isset($value['zkiKode'])) {
                    $productIds[] = $value['zkiKode'];
                }
            }

            $cacheModel = new Base_Filters_Cache_Model();
            $filtersModel = new Base_Filters_Filter_Model();
            $productModel = new Base_Filters_Products_Model();
            $productArrayModel = new Base_Filters_ProductsArray_Model();

            Zend_Registry::set('codes_of_dimensions', array('2080', '2078', '2076', '8306', '8307', '8308', '1040', '1041', '1042'));
            $moneyFormat = isset($settings['money_format']) ? $settings['money_format'] : '%!.2n';
            $valuta_symbol = htmlentities(HAKVOORT_VALUTA_SYMBOL, ENT_COMPAT, 'UTF-8');
            $productFactory = Webshop_Frontend_Product::getInstance('Hkv');

            if (!HAKVOORT_DISPLAY_VAT) {
                $vat_text = htmlentities($this->view->templateVars['exclVat'], ENT_COMPAT, 'UTF-8');
            } else {
                $vat_text = htmlentities($this->view->templateVars['inclVat'], ENT_COMPAT, 'UTF-8');
            }

            $categoryId = (isset($this->request['category'])) ? $this->request['category'] : false;
            $filter_state = new Zend_Session_Namespace('filter_state');
            $post_data = array();
            $restoring_state = false;
            if (isset($filter_state->cache_key) && $filter_state->cache_key == $cache_key) {
                $restoring_state = true;
                $post_data = $filter_state->post_data;
            }

            $page = isset($post_data['pagik']) ? $post_data['pagik'] : 1;
            $order = isset($post_data['order']) ? $post_data['order'] : false;

            if (SHOW_PRODUCTS_WITHOUT_PRICE || HAKVOORT_WEBSITE_CODE == 'HOM' || defined('NEW_HAKPRO')) {
                $price_on_request = isset($post_data['price_on_request']) ? $post_data['price_on_request'] : true;
            } else {
                $price_on_request = false;
            }

            $brands = array();
            if (isset($post_data['merk'])) {
                $brands_post_array = $post_data['merk'];
                foreach ($brands_post_array as $brand) {
                    $brands[] = $brand;
                }
            }
            $max_price = false;
            $min_price = false;
            if (isset($post_data['p2'])) {
                $max_price = (int)$post_data['p2'] + 0.5;
            }

            if (isset($post_data['p1'])) {
                $min_price = (int)$post_data['p1'] - 0.5;
            }

            $filterIds = array();
            $filters = array();
            $displayedFilters = $filtersModel->getFiltersTypes();

            $dimensions = $filtersModel->getDimensions();

            foreach ($displayedFilters as $filter) {
                $filterIds[] = $filter['CODE'];
                $filters[$filter['CODE']] = array();
            }
            if (isset($post_data['values'])) {
                $filters_post_array = $post_data['values'];
                foreach ($filters_post_array as $filter_array) {
                    $lenght = count($filter_array);
                    $tmp = Array();
                    for ($i = 1; $i < $lenght; $i++) {
                        $tmp[] = $filter_array[$i];
                    }
                    $filters[$filter_array[0]] = $tmp;
                }
            }

            $dimensions_values = array();
            if (isset($post_data['dimensions'])) {
                $this->view->post_data_dimension = $post_data['dimensions'];
                foreach ($post_data['dimensions'] as $dimension_value) {
                    $dimensions_values[$dimension_value[0]]['min'] = is_numeric($dimension_value[1]) != '' ? (float)$dimension_value[1] : false;
                    $dimensions_values[$dimension_value[0]]['max'] = is_numeric($dimension_value[2]) != '' ? (float)$dimension_value[2] : false;
                }
            }

            $products = $productModel->getProductsByIds($productIds, $filterIds);
            if (!defined('NEW_HAKPRO')) {
                $products_en = $productModel->getProductsByIds($productIds, $filterIds, 'E');
                foreach ($products as $product_id => &$product) {
                    $product['short_text_en'] = $products_en[$product_id]['short_text'];
                }
            }
            $dump = array(
                'dimensions' => $dimensions,
                'displayedFilters' => $displayedFilters,
                'products' => $products,
                'categories' => $categories
            );

            $cacheModel->set($cache_key, $dump);

            $this->view->cache_key = $cache_key;
            $view_mode = 0;
            if (isset($_COOKIE['Hkv_displayMode'])) {
                $view_mode = $_COOKIE['Hkv_displayMode'];
            }
            $this->view->view_mode = $view_mode;


            $catIds = array();
            if (isset($post_data['categories'])) {
                foreach ($post_data['categories'] as $cats) {
                    $catIds[] = $cats;
                }
            }

            $productArrayModel->setCategories($categories, $catIds);

            $productArrayModel->process($products, $filters, $dimensions_values, $brands, $min_price, $max_price, $order, $price_on_request);

            $categories = $productArrayModel->getCategories();

            $productsCount = $productArrayModel->getProductsCount();
            $perPage = 16;
            if (defined('NEW_HAKPRO')) {
                $perPage = 9;
            }
            if ($productsCount < $perPage * ($page - 1)) {
                $page = ceil($productsCount / $perPage);
            }
            foreach ($displayedFilters as $key => $value) {
                $displayedFilters[$key]['values'] = $productArrayModel->getFilterValues($value['CODE']);
            }
            $dimensions_min_max = $productArrayModel->getDimensionsMinMax();
            $current_dimensions_min_max = array();
            foreach ($dimensions_min_max as $k => $v) {
                if ($v['max'] > 0) {
                    $current_dimensions_min_max[$k]['min'] = (isset($dimensions_values[$k]['min']) && $dimensions_values[$k]['min'] !== false) ? $dimensions_values[$k]['min'] : $dimensions_min_max[$k]['min'];
                    $current_dimensions_min_max[$k]['max'] = (isset($dimensions_values[$k]['max']) && $dimensions_values[$k]['max'] !== false) ? $dimensions_values[$k]['max'] : $dimensions_min_max[$k]['max'];
                }
            }
            $this->view->current_dimensions_min_max = $current_dimensions_min_max;
            $dimensions_min_max_out_range = $productArrayModel->getDimensionsMinMaxOutRange();
            $this->view->dimensions_min_max_out_range = $dimensions_min_max_out_range;
            $merks = $productArrayModel->getFilteredBrands();
            $price_on_request = $productArrayModel->getPriceOnRequest();

            $products_array = array();
            $products_array = $productArrayModel->getProductIdsByPages($page, $perPage);
            $prod_ids_per_page = array();
            foreach ($products_array as $prod_id => $value) {
                $prod_ids_per_page[] = $prod_id;
            }
            $products_trade_in = $productFactory->getTradeIn($prod_ids_per_page);
            $prod_properties_per_page = $productFactory->getProductsProperties($prod_ids_per_page);

            $dim_array = array("50", "100");
            if (defined('NEW_HAKPRO')) {
                $dim_array[] = "210";
            }
            if (HAKVOORT_WEBSITE_CODE == 'CBS') {//gastroseals case
                $dim_array[] = "160";
            }
            $products_array = $productFactory->createProductsImages($products_array, $dim_array);
            foreach ($products_array as $key => &$product) {
                $product["priceToShow_wf"] = $product["priceToShow"];
                $product["priceToShow"] = htmlentities(HAKVOORT_VALUTA_SYMBOL, ENT_COMPAT, 'UTF-8') . ' ' . money_format($moneyFormat, $product["priceToShow"]);
                if (isset($product['discount']['oldPrice'])) {
                    $product['discount']['oldPrice_wf'] = $product['discount']['oldPrice'];
                    $product['discount']['oldPrice'] = htmlentities(HAKVOORT_VALUTA_SYMBOL, ENT_COMPAT, 'UTF-8') . ' ' . money_format($moneyFormat, $product['discount']['oldPrice']);
                }
                if (defined('NEW_HAKPRO')) {
                    $count_alternatives = $productFactory->getProductAlternativesCount($product['productId']);
                    $product['count_alternatives'] = $count_alternatives;
                }
            }

            $prices = $productArrayModel->getPrices();
            $prices['max'] = round($prices['max']);
            $prices['min'] = round($prices['min']);

            $url = '';

            $pagination = new Base_Filters_Pagination_Model($page, $perPage, $productsCount, $url);
            $pagina = $pagination->getPrevPagesString($this->view->templateVars['page']);
            $pagina .= $pagination->getPagesLinksString();
            $pagina .= $pagination->getNextPagesString();


            $displaySubCats = (sizeof($categories) > 1) ? false : true;

            $this->view->price_on_request = $price_on_request;
            $this->view->countofResults = $countofResults;
            $this->view->products_trade_in = $products_trade_in;
            $this->view->productsCount = $productsCount;
            $this->view->displaySubCats = $displaySubCats;
            $this->view->prod_properties_per_page = $prod_properties_per_page;
            $this->view->categories = $categories;
            $this->view->currentCategory = $categoryId;
            $this->view->pagina = $pagina;
            $this->view->products_array = $products_array;
            $this->view->brands = $merks;
            $this->view->prices = $prices;
            $this->view->displayedFilters = $displayedFilters;
            $this->view->dimensions = $dimensions;
            $this->view->dimensions_min_max = $dimensions_min_max;
            $this->view->prodModel = $productModel;
            $this->view->valuta_symbol = $valuta_symbol;
            $this->view->vat_text = $vat_text;
            $this->view->no_result = count($products) == 0 ? true : false;
            $this->view->keyword = $keyword;
            //session data
            $current_prices = array();
            $static_prices = array();
            $current_prices['min'] = (!isset($post_data['p1'])) ? $prices['min'] : (int)$post_data['p1'];
            $current_prices['max'] = (!isset($post_data['p2'])) ? $prices['max'] : (int)$post_data['p2'];
            $static_prices['min'] = (!isset($post_data['static_minPrice'])) ? $prices['min'] : $post_data['static_minPrice'];
            $static_prices['max'] = (!isset($post_data['static_maxPrice'])) ? $prices['max'] : $post_data['static_maxPrice'];
            $this->view->static_prices = $static_prices;
            $this->view->current_prices = $current_prices;
            $this->view->restoring_state = $restoring_state;
            $this->view->post_data = $post_data;
            $this->view->order = $order;
            $this->view->dimensions_values = $dimensions_values;
            $this->view->filters = $filters;
            $this->view->productFactory = $productFactory;

        }
    }

    public function search_content()
    {
        if (isset($this->request['keyword'])) {
            $keyword = $this->request['keyword'];
            $content = true;
        } else {
            header("Location: " . $this->view->url(array('page' => '404.html'), null, true));
            exit;
        }
        $db = Zend_Registry::get('dbh');
        $websiteDetails = Zend_Registry::get('website');
        $websiteId = $websiteDetails['website_id'];
        $localeId = $websiteDetails['default_locale_id'];
        $searchApi = new Custom_Webshop_Model_DbTable_Hkvsearch();
        $keyword = trim(strtolower($keyword));
        $newsLikes = "";
        $textLikes = "";
        $resultsPerPage = 8;
        $currentPage = (isset($this->request['productpage'])) ? (int)$this->request['productpage'] : 1;

        $keywords = $searchApi->explode_keyword($keyword);
        foreach ($keywords as $bkeyword) {
            $bkeyword = strtolower($bkeyword);
            $newsLikes .= " OR bericht LIKE " . $db->quoteInto('?', '%' . $bkeyword . '%') . " ";
            $newsLikes .= " OR titel LIKE " . $db->quoteInto('?', '%' . $bkeyword . '%') . " ";
            $textLikes .= " OR content LIKE " . $db->quoteInto('?', '%' . $bkeyword . '%') . " ";
        }

        $query = "
				SELECT DISTINCT n.* FROM cms_entity_nieuwsberichten n
				INNER JOIN cms_entities_websites AS w
				ON (n.instance_id = w.instance_id
				AND w.website_id = '{$websiteId}' AND w.entity_id = 1)
				WHERE locale_id = :localeId AND ( 1=2 " . $newsLikes . ")
				";

        $stmt = $db->prepare($query);
        $stmt->bindParam('localeId', $localeId);

        $stmt->execute();
        $news = $stmt->fetchAll();

        $query = " SELECT DISTINCT t.*, p.url, p.page_name 
					FROM view_translated_texts t
					INNER JOIN view_cms_elements_per_page AS e ON (e.website_id= '{$websiteId}' AND e.element_name='text' AND e.active=1 AND e.option_name = 'text_id' AND e.option_value = t.text_id)
					INNER JOIN view_translated_pages p ON ( e.page_id = p.page_id AND t.locale_id = p.locale_id )
					WHERE  t.locale_id = :localeId AND ( 1=2 {$textLikes} )
					";

        $stmt = $db->prepare($query);
        $stmt->bindParam('localeId', $localeId);

        $stmt->execute();
        $texts = $stmt->fetchAll();


        $content = array_merge($news, $texts);
        $resultSet = Zend_Paginator::factory($content);
        $resultSet->setPageRange(12);
        $resultSet->setItemCountPerPage($resultsPerPage);
        $currentPage = ($currentPage > $resultSet->count()) ? $resultSet->count() : $currentPage;
        $resultSet->setCurrentPageNumber($currentPage);
        $this->view->resluts = $resultSet->getCurrentItems();
        $this->view->pages = $resultSet->getPages();
        $this->view->currentPage = $currentPage;
        $this->view->countofResults = count($content);
        $this->view->keyword = $keyword;
        $this->view->no_result = count($content) == 0 ? true : false;

    }

    public function occasions()
    {
        if ($this->element->getEditMode()) {

        } else {
            if (isset($this->request['keyword'])) {
                $keyword = $this->request['keyword'];
            } elseif (isset($_POST['getsynonyms'])) {
                $this->getsynonyms($_POST['getsynonyms']);
            } else {
                header("Location: " . $this->view->url(array('page' => '404.html'), null, true));
                exit;
            }

            $websiteSettings = Zend_Registry::get('hkvWebsiteSettings');
            $keyword = trim(strtolower($keyword));
            $cache_key = md5($websiteSettings['website_code'] . "_" . $keyword . "_" . $this->request['locale']);
            $cache_key_search = md5($websiteSettings['website_code'] . "_" . 'search_' . $keyword . "_" . $this->request['locale']);

            $searchApi = new Custom_Webshop_Model_DbTable_Hkvsearch();
            $categoryId = false;

            $searchApi->redirectIfOccasionProductFound($keyword, $this->view);

            $searchApi->advanced_search_occasions($keyword, $categoryId);
            $products = $searchApi->getProducts();

            $categories = $searchApi->getCategories();

            $countofResults = count($products);
            $dump = array(
                'products' => $products,
                'productsCount' => $countofResults,
                'categories' => $categories
            );
            $searchApi->cache->save($dump, $cache_key_search);

            $productIds = array();
            foreach ($products as $key => $value) {
                if (isset($value['zkiKode'])) {
                    $productIds[] = $value['zkiKode'];
                }
            }

            $cacheModel = new Base_Filters_Cache_Model();
            $filtersModel = new Base_Filters_Filter_Model();
            $productModel = new Base_Filters_Products_Model();
            $productArrayModel = new Base_Filters_ProductsArray_Model();

            Zend_Registry::set('codes_of_dimensions', array('2080', '2078', '2076'));
            $moneyFormat = isset($settings['money_format']) ? $settings['money_format'] : '%!.2n';
            $valuta_symbol = htmlentities(HAKVOORT_VALUTA_SYMBOL, ENT_COMPAT, 'UTF-8');
            $productFactory = Webshop_Frontend_Product::getInstance('Hkv');

            if (!HAKVOORT_DISPLAY_VAT) {
                $vat_text = htmlentities($this->view->templateVars['exclVat'], ENT_COMPAT, 'UTF-8');
            } else {
                $vat_text = htmlentities($this->view->templateVars['inclVat'], ENT_COMPAT, 'UTF-8');
            }

            $categoryId = (isset($this->request['category'])) ? $this->request['category'] : false;
            $filter_state = new Zend_Session_Namespace('filter_state');
            $post_data = array();
            $restoring_state = false;
            if (isset($filter_state->cache_key) && $filter_state->cache_key == $cache_key) {
                $restoring_state = true;
                $post_data = $filter_state->post_data;
            }

            $page = isset($post_data['pagik']) ? $post_data['pagik'] : 1;
            $order = isset($post_data['order']) ? $post_data['order'] : false;

            if (SHOW_PRODUCTS_WITHOUT_PRICE) {
                $price_on_request = isset($post_data['price_on_request']) ? $post_data['price_on_request'] : true;
            } else {
                $price_on_request = false;
            }

            $brands = array();
            if (isset($post_data['merk'])) {
                $brands_post_array = $post_data['merk'];
                foreach ($brands_post_array as $brand) {
                    $brands[] = $brand;
                }
            }
            $max_price = false;
            $min_price = false;
            if (isset($post_data['p2'])) {
                $max_price = (int)$post_data['p2'] + 0.5;
            }

            if (isset($post_data['p1'])) {
                $min_price = (int)$post_data['p1'] - 0.5;
            }

            $filterIds = array();
            $filters = array();
            $displayedFilters = $filtersModel->getFiltersTypes();

            $dimensions = $filtersModel->getDimensions();

            foreach ($displayedFilters as $filter) {
                $filterIds[] = $filter['CODE'];
                $filters[$filter['CODE']] = array();
            }
            if (isset($post_data['values'])) {
                $filters_post_array = $post_data['values'];
                foreach ($filters_post_array as $filter_array) {
                    $lenght = count($filter_array);
                    $tmp = Array();
                    for ($i = 1; $i < $lenght; $i++) {
                        $tmp[] = $filter_array[$i];
                    }
                    $filters[$filter_array[0]] = $tmp;
                }
            }

            $dimensions_values = array();
            if (isset($post_data['dimensions'])) {
                foreach ($post_data['dimensions'] as $dimension_value) {
                    $dimensions_values[$dimension_value[0]]['min'] = is_numeric($dimension_value[1]) != '' ? (float)$dimension_value[1] : false;
                    $dimensions_values[$dimension_value[0]]['max'] = is_numeric($dimension_value[2]) != '' ? (float)$dimension_value[2] : false;
                }
            }

            $products = $productModel->getOccasionProductsByIds($productIds, $filterIds);
            $dump = array(
                'dimensions' => $dimensions,
                'displayedFilters' => $displayedFilters,
                'products' => $products,
                'categories' => $categories
            );

            $cacheModel->set($cache_key, $dump);

            $this->view->cache_key = $cache_key;
            $view_mode = 0;
            if (isset($_COOKIE['Hkv_displayMode'])) {
                $view_mode = $_COOKIE['Hkv_displayMode'];
            }
            $this->view->view_mode = $view_mode;


            $catIds = array();
            if (isset($post_data['categories'])) {
                foreach ($post_data['categories'] as $cats) {
                    $catIds[] = $cats;
                }
            }

            $productArrayModel->setCategories($categories, $catIds);

            $productArrayModel->process($products, $filters, $dimensions_values, $brands, $min_price, $max_price, $order, $price_on_request);

            $categories = $productArrayModel->getOccasionsCategories();
            $productsCount = $productArrayModel->getProductsCount();
            $perPage = 12;
            if ($productsCount < $perPage * ($page - 1)) {
                $page = ceil($productsCount / $perPage);
            }
            foreach ($displayedFilters as $key => $value) {
                $displayedFilters[$key]['values'] = $productArrayModel->getFilterValues($value['CODE']);
            }
            $dimensions_min_max = $productArrayModel->getDimensionsMinMax();

            $merks = $productArrayModel->getFilteredBrands();
            $price_on_request = $productArrayModel->getPriceOnRequest();

            $products_array = array();
            $products_array = $productArrayModel->getProductIdsByPages($page, $perPage);
            $prod_ids_per_page = array();
            foreach ($products_array as $prod_id => $value) {
                $prod_ids_per_page[] = $prod_id;
            }
            $products_trade_in = $productFactory->getTradeIn($prod_ids_per_page);
            $prod_properties_per_page = $productFactory->getProductsProperties($prod_ids_per_page);
            $dim_array = array("50", "100");
            $products_array = $productFactory->createProductsImages($products_array, $dim_array);
            foreach ($products_array as $key => &$product) {
                $product["priceToShow"] = htmlentities(HAKVOORT_VALUTA_SYMBOL, ENT_COMPAT, 'UTF-8') . ' ' . money_format($moneyFormat, $product["priceToShow"]);
                if (isset($product["oldPrice"])) {
                    $product["oldPrice"] = htmlentities(HAKVOORT_VALUTA_SYMBOL, ENT_COMPAT, 'UTF-8') . ' ' . money_format($moneyFormat, $product["oldPrice"]);
                }
            }

            $prices = $productArrayModel->getPrices();
            $prices['max'] = round($prices['max']);
            $prices['min'] = round($prices['min']);

            $url = '';

            $pagination = new Base_Filters_Pagination_Model($page, $perPage, $productsCount, $url);
            $pagina = $pagination->getPrevPagesString($this->view->templateVars['page']);
            $pagina .= $pagination->getPagesLinksString();
            $pagina .= $pagination->getNextPagesString();


            $displaySubCats = (sizeof($categories) > 1) ? false : true;

            $this->view->price_on_request = $price_on_request;
            $this->view->countofResults = $countofResults;
            $this->view->productsCount = $productsCount;
            $this->view->displaySubCats = $displaySubCats;
            $this->view->products_trade_in = $products_trade_in;
            $this->view->categories = $categories;
            $this->view->currentCategory = $categoryId;
            $this->view->pagina = $pagina;
            $this->view->prod_properties_per_page = $prod_properties_per_page;
            $this->view->products_array = $products_array;

            $this->view->brands = $merks;
            $this->view->prices = $prices;
            $this->view->displayedFilters = $displayedFilters;
            $this->view->dimensions = $dimensions;
            $this->view->dimensions_min_max = $dimensions_min_max;

            $this->view->prodModel = $productModel;
            $this->view->valuta_symbol = $valuta_symbol;
            $this->view->vat_text = $vat_text;
            $this->view->no_result = count($products) == 0 ? true : false;
            $this->view->keyword = $keyword;
            //session data
            $current_prices = array();
            $static_prices = array();
            $current_prices['min'] = (!isset($post_data['p1'])) ? $prices['min'] : (int)$post_data['p1'];
            $current_prices['max'] = (!isset($post_data['p2'])) ? $prices['max'] : (int)$post_data['p2'];
            $static_prices['min'] = (!isset($post_data['static_minPrice'])) ? $prices['min'] : $post_data['static_minPrice'];
            $static_prices['max'] = (!isset($post_data['static_maxPrice'])) ? $prices['max'] : $post_data['static_maxPrice'];
            $this->view->static_prices = $static_prices;
            $this->view->current_prices = $current_prices;
            $this->view->restoring_state = $restoring_state;
            $this->view->post_data = $post_data;
            $this->view->order = $order;
            $this->view->dimensions_values = $dimensions_values;
            $this->view->filters = $filters;
            $this->view->productFactory = $productFactory;

        }
    }

    public function getsynonyms($keyword)
    {
        $searchApi = new Custom_Webshop_Model_DbTable_Hkvsearch();
        $synonyms = array();
        $keywords = $searchApi->explode_keyword($keyword);

        foreach ($keywords as $keyword) {
            if (count($synonyms) <= 10) {
                $synonyms = array_merge($synonyms, $searchApi->getSynonyms($keyword));
            }
        }
        $html = array();
        if ($synonyms) {
            foreach ($synonyms as $i => $synonym) {
                $html[] = '<a href="' . $this->view->url(array('page' => 'search.html', 'locale' => $this->request['locale'], 'keyword' => strtolower($synonym)), null, true) . '">' . strtolower($synonym) . '</a>';
            }
            $html = implode(", ", $html);
        } else {
            $html = "<span class='no_synonyms'>" . $this->view->templateVars['no_result'] . "</span>";
        }

        @ob_clean();
        echo $html;
        exit;

    }

}

?>