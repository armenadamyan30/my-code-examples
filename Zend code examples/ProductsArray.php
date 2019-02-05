<?php

class Base_Filters_ProductsArray_Model
{

    private $valid_productIds = array();
    private $valid_products = array();
    private $filters = array();
    private $dimensions_min_max = array();
    private $brands = array();
    private $valid_products_count = false;
    private $price_min = false;
    private $price_max = false;
    private $price_on_request = false;
    private $post_categories = array();
    private $all_categories = array();
    private $products_model = null;
    private $dimensionMinMaxOutRange = array();
    private $getAlternativesPropertiesFlag = true;
    public $poductsAlternativeIds = array();
    public $poductsAlternativesProperties = array();

    public function getDimensionsMinMaxOutRange()
    {
        return $this->dimensionMinMaxOutRange;
    }

    public function getPriceOnRequest()
    {
        return $this->price_on_request;
    }

    public function getProductsCount()
    {
        return $this->valid_products_count;
    }

    public function getDimensionsMinMax()
    {
        return $this->dimensions_min_max;
    }

    public function getProductIdsByPages($page, $perPage)
    {
        $return = array();
        $i = 0;
        foreach ($this->valid_products as $productID => $product) {
            if ($i < (($page - 1) * $perPage) || $i >= ($page * $perPage)) {
                $i++;
                continue;
            }
            $return[$productID] = $product;
            $i++;
        }
        return $return;
    }

    public function getFilterValues($filterdId)
    {
        if (isset($this->filters[$filterdId]) && $this->filters[$filterdId] !== false) {
            return $this->filters[$filterdId];
        }
        return array();
    }

    public function getPrices()
    {
        return array('min' => $this->price_min, 'max' => $this->price_max);
    }

    public function getFilteredBrands()
    {
        return $this->brands;
    }

    public function setCategories($all_categories, $post_categories = array())
    {
        $this->all_categories = $all_categories;
        foreach ($all_categories as $cat) {
            if (in_array($cat['zstKode'], $post_categories, true) && isset($cat["children"])) {
                array_merge(array_keys($cat["children"]));
            }
        }
        $post_categories = array_unique($post_categories);
        $this->post_categories = $post_categories;
    }

    public function getCategories()
    {
        $valid_categories = $this->valid_categories;
        $all_categories = $this->all_categories;
        $post_categories = $this->post_categories;
        foreach ($all_categories as $key => $cat) {
            if (isset($all_categories[$key]["children"])) {
                foreach ($all_categories[$key]["children"] as $ch_key => $child) {
                    if (!in_array($child['zstKode'], $valid_categories, true) && !in_array($child['zstKode'], $post_categories, true)) {
                        unset ($all_categories[$key]["children"][$ch_key]);
                    } elseif (in_array($child['zstKode'], $post_categories, true)) {
                        $child['checked'] = true;
                    } else {
                        $child['checked'] = false;
                    }
                }
            }
            if (!in_array($all_categories[$key]['zstKode'], $valid_categories, true) && (!isset($all_categories[$key]["children"]) || empty($all_categories[$key]["children"]))) {
                unset ($all_categories[$key]);
            } elseif (in_array($all_categories[$key]['zstKode'], $post_categories, true)) {
                $all_categories[$key]['checked'] = true;
            } else {
                $all_categories[$key]['checked'] = false;
            }
        }

        return $all_categories;
    }

    public function getOccasionsCategories()
    {
        $valid_categories = $this->valid_categories;
        $all_categories = $this->all_categories;
        $post_categories = $this->post_categories;

        foreach ($all_categories as $key => $cat) {

            if (isset($all_categories[$key]["children"])) {
                foreach ($all_categories[$key]["children"] as $ch_key => $child) {

                    if (!in_array($child['zstKode'], $valid_categories, true) && !in_array($child['zstKode'], $post_categories, true)) {
                        unset ($all_categories[$key]["children"][$ch_key]);
                    } elseif (in_array($child['zstKode'], $post_categories, true)) {
                        $child['checked'] = true;
                    } else {
                        $child['checked'] = false;
                    }
                }
            }
            if (!in_array($all_categories[$key]['zstKode'], $valid_categories, true) && (!isset($all_categories[$key]["children"]) || empty($all_categories[$key]["children"]))) {

                unset ($all_categories[$key]);

            } elseif (in_array($all_categories[$key]['zstKode'], $post_categories, true)) {
                $all_categories[$key]['checked'] = true;
            } else {
                $all_categories[$key]['checked'] = false;
            }
        }

        return $all_categories;
    }

    public function getAlternativesProperties($products)
    {
        if (!$this->getAlternativesPropertiesFlag) {

            return;
        }

        $this->getAlternativesPropertiesFlag = false;

        $this->products_model = new Custom_Webshop_Model_DbTable_Hkvproducts();
        $productIds = array();
        foreach ($products as $prodId => $value) {
            $productIds[] = $prodId;
        }

        $this->poductsAlternativeIds = $this->products_model->getProductsAlternativeIds($productIds);

        $alternatives = array();
        foreach ($this->poductsAlternativeIds as $prod) {
            foreach ($prod as $alt) {
                $alternatives[] = $alt;
            }
        }
        $this->poductsAlternativesProperties = $this->products_model->getProductsProperties($alternatives);
    }

    public function process($products, $values, $dimensions = array(), $brands = array(), $min = false, $max = false, $order_by = false, $price_on_request = false, $is_prod_filter = false)
    {

        $this->products_model = new Custom_Webshop_Model_DbTable_Hkvproducts();

        $categories = $this->post_categories;
        $valid_categories = $categories;
        $valid_products = array();
        $valid_ids = array();
        $all_ids = array();
        $filter_values = array();   //filter[486] == array('zwart','green')  ...486-kleur
        $dimensions_min_max = array();
        $current_dimensions = array();
        foreach ($values as $key => $value) {
            $filter_values[$key] = $value;
        }

        $this->brands = array();
        foreach ($brands as $brand_code) {
            foreach ($products as $p) {
                if (isset($p['merkCode']) && $p['merkCode'] == $brand_code) {
                    $this->brands[$brand_code] = array('merkCode' => $brand_code, 'merk' => $p['merk']);
                }
            }
        }

        $magic = array(); // magic[key] - products, filtered by 'key'- filter (key = color or key  = price)
        $magic_intersect = array();

        switch ($order_by) {
            case 'volgordenummer':
                $sort_field = 'volgordenummer';
                $destination = SORT_ASC;
                break;
            case 'ASC':
                $sort_field = 'priceToShow';
                $destination = SORT_ASC;
                break;
            case 'DESC':
                $sort_field = 'priceToShow';
                $destination = SORT_DESC;
                break;
            case 'name':
                $sort_field = 'short_text';
                $destination = SORT_ASC;
                break;
            case 'new':
                $sort_field = 'date';
                $destination = SORT_DESC;
                break;
            case 'old':
                $sort_field = 'date';
                $destination = SORT_ASC;
                break;
            default:
                $sort_field = 'volgordenummer';
                $destination = SORT_ASC;
        }
        $sort_array = array();
        $products_to_sort = array();
        $no_integer = 'no_integer';

        foreach ($products as $productId => $product) {
            $products_to_sort[$no_integer . $productId] = $products[$productId];
            $sort_array[$no_integer . $productId] = $product[$sort_field];
        }
        $products = array();

        if ($is_prod_filter === true) {
            array_multisort($sort_array, $destination, $products_to_sort);
        } else {
            if ($order_by != 'volgordenummer' && $order_by != false) {
                array_multisort($sort_array, $destination, $products_to_sort);
            }
        }

        foreach ($products_to_sort as $key => $value) {
            $products[substr($key, 10)] = $value;
        }

        foreach ($products as $productId => $product) {
            $is_valid = true;
            $all_ids[] = $productId;

            if ($price_on_request == false && ((SHOW_PRODUCTS_WITHOUT_PRICE && $product['agpPrijsOpNet'] == 0) || (HAKVOORT_WEBSITE_CODE == "HOM" && $product['priceToShow'] == 0))) {
                $is_valid = FALSE;
            }

            if ($min !== false && $max !== false) {
                if (($product['priceToShow'] >= $min && $product['priceToShow'] < $max) || ($product['agpPrijsOpNet'] == 0 && SHOW_PRODUCTS_WITHOUT_PRICE) || (HAKVOORT_WEBSITE_CODE == "HOM" && $product['priceToShow'] == 0)) {
                    $magic['price'][] = $productId;
                } else {
                    $is_valid = FALSE;
                }
            }

            if (!empty($brands)) {
                if (in_array($product['merkCode'], $brands)) {
                    $magic['brand'][] = $productId;
                } else {
                    $is_valid = FALSE;
                }
            }

            if (!empty($categories)) {
                if (array_intersect($product['categoryid'], $categories)) {
                    $magic['categories'][] = $productId;
                } else {
                    $is_valid = FALSE;
                }
            }


            foreach ($dimensions as $key => $value) {

                if ($value['min'] !== false || $value['max'] !== false) {
                    if (!is_null($product['dimensions'][$key])) {
                        $current_dimensions[$key][] = $product['dimensions'][$key];
                    }
                    if (isset($product['dimensions'][$key]) && ($value['min'] === false || $product['dimensions'][$key] >= $value['min']) && ($value['max'] === false || $product['dimensions'][$key] <= $value['max'])) {
                        $magic[$key][] = $productId;
                    } else {
                        $alt_array = $this->products_model->getProductAlternatives($productId);
                        if (!empty($alt_array)) {
                            $alt_found = false;

                            foreach ($alt_array as $alt) {

                                $alt_properties = $this->products_model->getProductProperties($alt['artikelkode']);
                                foreach ($alt_properties as $k => $v) {
                                    if (array_search($key, $v)) {
                                        $dimension = $alt_properties[$k]['waarde'];
                                        if (!is_null($dimension)) {
                                            $current_dimensions[$key][] = $dimension;
                                        }
                                        if (isset($dimension) && ($value['min'] === false || $dimension >= $value['min']) && ($value['max'] === false || $dimension <= $value['max'])) {
                                            $alt_found = true;
                                            break 2;
                                        }
                                    }
                                }
                            }
                            if ($alt_found) {
                                $magic[$key][] = $productId;
                            } else {
                                $is_valid = FALSE;
                            }
                        } else {
                            $is_valid = FALSE;
                        }
                    }

                }

            }

            foreach ($values as $key => $value) {
                if (!empty($value)) {
                    if (isset($product['properties'][$key]) && in_array($product['properties'][$key], $value)) {
                        $magic[$key][] = $productId;
                    } else {
                        $is_valid = FALSE;
                    }
                }
            }

            if ($is_valid) {
                $valid_products[$productId] = $product;
                $valid_ids[] = $productId;
            }
        }

        foreach ($dimensions as $key => $value) {
            $magic_intersect[$key] = $all_ids;
            foreach ($magic as $tmp_key => $tmp_value) {
                if ($tmp_key != $key) {
                    $magic_intersect[$key] = array_intersect($magic_intersect[$key], $tmp_value);
                }
            }
        }

        foreach ($values as $key => $value) {
            $magic_intersect[$key] = $all_ids;
            foreach ($magic as $tmp_key => $tmp_value) {
                if ($tmp_key != $key) {
                    $magic_intersect[$key] = array_intersect($magic_intersect[$key], $tmp_value);
                }
            }
        }

        $magic_intersect['brand'] = $all_ids;
        foreach ($magic as $tmp_key => $tmp_value) {
            if ($tmp_key != 'brand') {
                $magic_intersect['brand'] = array_intersect($magic_intersect['brand'], $tmp_value);
            }
        }


        $magic_intersect['categories'] = $all_ids;

        foreach ($magic as $tmp_key => $tmp_value) {
            if ($tmp_key != 'categories') {
                $magic_intersect['categories'] = array_intersect($magic_intersect['categories'], $tmp_value);
            }
        }

        $magic_intersect['price'] = $all_ids;
        foreach ($magic as $tmp_key => $tmp_value) {
            if ($tmp_key != 'price') {
                $magic_intersect['price'] = array_intersect($magic_intersect['price'], $tmp_value);
            }
        }


        foreach ($magic_intersect as $filter_id => $filter_product_ids) {

            if ($filter_id == 'price') {
                $prices_array = array();
                foreach ($filter_product_ids as $productId) {
                    if (isset($products[$productId]['priceToShow'])) {
                        if ((SHOW_PRODUCTS_WITHOUT_PRICE && $products[$productId]['agpPrijsOpNet'] == 0) || (HAKVOORT_WEBSITE_CODE == "HOM" && $products[$productId]['priceToShow'] == 0)) {
                            continue;
                        }
                        $prices_array[] = $products[$productId]['priceToShow'];
                    }
                }

                $this->price_min = !empty ($prices_array) ? min($prices_array) : 0;
                $this->price_max = !empty ($prices_array) ? max($prices_array) : 0;
                continue;
            }
            if ($filter_id == 'brand') {
                foreach ($filter_product_ids as $productId) {
                    if (isset($products[$productId]['merkCode']) && !isset ($this->brands[$products[$productId]['merkCode']]) && trim($products[$productId]['merkCode']) != '') {
                        $this->brands[$products[$productId]['merkCode']] = array('merkCode' => $products[$productId]['merkCode'], 'merk' => $products[$productId]['merk']);
                    }
                }
                asort($this->brands);
                continue;
            }

            if ($filter_id == 'categories') {
                foreach ($filter_product_ids as $key => $productId) {
                    if (isset($products[$productId]['categoryid']) && !array_intersect($products[$productId]['categoryid'], $valid_categories)) {
                        $categories = $products[$productId]['categoryid'];
                        foreach ($categories as $cat) {
                            $valid_categories[] = $cat;
                        }
                    }
                }
                continue;
            }

            $codes_of_dimensions = Zend_Registry::get('codes_of_dimensions');
            if (in_array($filter_id, $codes_of_dimensions)) {
                $products_dimesion = array();
                foreach ($filter_product_ids as $productId) {
                    if (isset($products[$productId]['dimensions'][$filter_id])) {
                        $products_dimesion[] = $products[$productId]['dimensions'][$filter_id];
                    }
                }

                $this->dimensions_min_max[$filter_id]['min'] = !empty ($products_dimesion) ? min($products_dimesion) : 0;
                $this->dimensions_min_max[$filter_id]['max'] = !empty ($products_dimesion) ? max($products_dimesion) : 0;
                continue;
            }

            foreach ($filter_product_ids as $productId) {
                if (isset($products[$productId]['properties'][$filter_id])) {
                    $filter_values[$filter_id][] = $products[$productId]['properties'][$filter_id];
                }
            }
            $filter_values[$filter_id] = array_unique($filter_values[$filter_id]);
            sort($filter_values[$filter_id]);
        }

        foreach ($dimensions as $key => $value) {
            $this->dimensionMinMaxOutRange[$key]['min'] = !empty ($current_dimensions[$key]) ? min($current_dimensions[$key]) : 0;
            $this->dimensionMinMaxOutRange[$key]['max'] = !empty ($current_dimensions[$key]) ? max($current_dimensions[$key]) : 0;
        }

        $this->filters = $filter_values;
        $this->price_on_request = $price_on_request;
        $this->valid_categories = array_unique($valid_categories);
        $this->valid_productIds = $valid_ids;
        $this->valid_products_count = count($valid_ids);
        $this->valid_products = $valid_products;
    }

    public function progressWithoutFilters($products, $order_by = false)
    {
        $valid_products = array();
        $valid_ids = array();
        switch ($order_by) {
            case 'volgordenummer':
                $sort_field = 'volgordenummer';
                $destination = SORT_ASC;
                break;
            case 'ASC':
                $sort_field = 'priceToShow';
                $destination = SORT_ASC;
                break;
            case 'DESC':
                $sort_field = 'priceToShow';
                $destination = SORT_DESC;
                break;
            case 'name':
                $sort_field = 'short_text';
                $destination = SORT_ASC;
                break;
            case 'new':
                $sort_field = 'date';
                $destination = SORT_DESC;
                break;
            case 'old':
                $sort_field = 'date';
                $destination = SORT_ASC;
                break;
            default:
                $sort_field = 'volgordenummer';
                $destination = SORT_ASC;
        }

        $sort_array = array();
        foreach ($products as $productId => $product) {
            $sort_array[$productId] = $product[$sort_field];
        }
        array_multisort($sort_array, $destination, $products);
        foreach ($products as $productId => $product) {
            $valid_products[$productId] = $product;
            $valid_ids[] = $productId;
        }
        $this->valid_productIds = $valid_ids;
        $this->valid_products_count = count($valid_ids);
        $this->valid_products = $valid_products;
    }
}

?>