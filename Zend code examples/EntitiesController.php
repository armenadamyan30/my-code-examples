<?php

class Cms_EntitiesController extends Cms_BaseController
{

    protected $lang;
    protected $websites;
    protected $websiteIds;
    protected $websiteDetails;
    protected $locales;
    protected $localeId;
    protected $defaultLocaleId;
    protected $isMultiLang;

    public function init()
    {

        $this->view->jsLang['datepickerFormat'] = $this->lang['datepickerFormat'];
        $this->view->lang = $this->lang;

        $websitesModel = new Cms_Model_DbTable_Websites();

        $this->websites = $websitesModel->getWebsites();

        $this->websiteIds = array();

        foreach ($this->websites as $website) {
            $this->websiteIds[] = $website['website_id'];
        }

        $this->view->websites = $this->websites;

        $entityId = (isset($this->_request->entity) && (int)$this->_request->entity > 0) ? (int)$this->_request->entity : false;

        if ($entityId) {

            $entitiesModel = new Cms_Model_DbTable_Entities();

            $entity = $entitiesModel->getEntityById($entityId);

            if ($entity) {
                $this->view->entityTitle = $entity['entity_title_plural'];
            }
        }

        $this->websiteDetails = $this->_helper->website();

        $this->isMultiLang = count($this->websiteDetails['locales']) > 1;
        $this->view->isMultiLang = $this->isMultiLang;

        if (isset($this->_request->site)) {
            if (in_array($this->_request->site, $this->websiteIds) || $this->_request->site == 'all') {
                $this->selectedWebsiteId = $this->_request->site;
            } else {
                $this->selectedWebsiteId = $this->websiteDetails['website_id'];
            }
        } else {
            $this->selectedWebsiteId = $this->websiteDetails['website_id'];
        }

        $this->view->websiteId = $this->selectedWebsiteId;
        $this->view->leftContent = $this->view->render('entities/leftContent.phtml');

        $locales = $this->websiteDetails['locales'];
        $this->locales = $locales;

        $this->defaultLocaleId = $this->websiteDetails['default_locale_id'];

        $this->localeId = (isset($this->_request->locale) && (int)$this->_request->locale > 0) ? (int)$this->_request->locale : $this->defaultLocaleId;
        $this->view->localeId = $this->localeId;
    }

    public function indexAction()
    {
        $this->view->jsLang['areYouSureEntity'] = $this->lang['areYouSureEntity'];
        $this->view->jsLang['yes'] = $this->lang['yes'];
        $this->view->jsLang['cancel'] = $this->lang['cancel'];
        $this->view->jsLang['notAllFields'] = $this->lang['notAllFields'];
        $browser = new Cms_Browser_Entities();
        $this->view->browserData['entitiesBrowser'] = $browser->toArray();
    }

    public function addAction()
    {

        $entitiesModel = new Cms_Model_DbTable_Entities();
        $this->view->jsLang['entityExists'] = $this->lang['entityExists'];
        $this->view->jsLang['notEnoughFields'] = $this->lang['notEnoughFields'];
        $this->view->jsLang['notAllFields'] = $this->lang['notAllFields'];
        $this->view->jsLang['atLeastOneInBrowser'] = $this->lang['atLeastOneInBrowser'];

        if ($this->getRequest()->isPost()) {

            $entityId = $entitiesModel->addEntity($this->getRequest()->getPost());

            $rolesActionsModel = new Model_DbTable_RolesActions();
            $rolesEntitiesModel = new Model_DbTable_RolesEntities();
            $roles = $rolesActionsModel->rolesWithEntityActions();

            foreach ($roles as $roleId) {
                $rolesEntitiesModel->addRoleEntity(array(
                    'role_id' => $roleId,
                    'entity_id' => $entityId
                ));
            }

            $this->_helper->redirector->goToRoute(array('controller' => 'entities', 'action' => 'index'), 'website');

        } else {
            //What fieldtypes are available? If there is a new one, add it here
            $fieldTypes = array(
                'singleline' => $this->lang['singleline'],
                'multiplelines' => $this->lang['multiplelines'],
                'datetime' => $this->lang['datetime'],
                'wysiwyg' => $this->lang['wysiwyg'],
                'image' => $this->lang['image'],
                'boolean' => $this->lang['boolean'],
                'choice' => $this->lang['choice'],
                'multiplechoice' => $this->lang['multipleChoice']
            );

            //What validators are available? If there is a new one, add it here
            $fieldValidators = array(
                '' => $this->lang['none'],
                'text' => $this->lang['text'],
                'email' => $this->lang['email'],
                'datetime' => $this->lang['datetime']
            );

            $this->view->fieldTypes = $fieldTypes;
            $this->view->fieldValidators = $fieldValidators;

            $this->view->entityFieldHtmlTemplate = $this->view->render('entities/entityField.phtml');

            $entities = $entitiesModel->getEntities();
            $this->view->entities = $entities;
        }
    }


    public function editAction()
    {

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();

            $entitiesModel = new Cms_Model_DbTable_Entities();

            $entitiesModel->updateEntity($data);

            $this->_helper->Messages->addNotice('flash', $this->lang['moduleSaved']);
            $this->_helper->redirector->goToRoute(array('controller' => 'entities', 'action' => 'index'), 'website');

        } else {

            if (isset($this->_request->entity)) {
                $entityId = $this->_request->entity;
            }

            $entitiesModel = new Cms_Model_DbTable_Entities();

            $entity = $entitiesModel->getEntityById($entityId);
            $entityFields = $entitiesModel->getEntityFields($entityId);

            //Removing the relation fields
            $relations = $entitiesModel->getRelationsForEntity($entityId);

            foreach ($relations as $relation) {

                if ($relation['entity2_id'] == $entity['id']) {

                    $relationEntity = $entitiesModel->getEntityById($relation['entity1_id']);

                    $relationField = strtolower($relationEntity['entity_title_singular'] . '_id');

                    foreach ($entityFields as $key => $field) {

                        if (strtolower($field['field_name']) == $relationField) {
                            unset($entityFields[$key]);
                        }
                    }
                }
            }

            //What field types are available? If there is a new one, add it here
            $fieldTypes = array(
                'singleline' => $this->lang['singleline'],
                'multiplelines' => $this->lang['multiplelines'],
                'datetime' => $this->lang['datetime'],
                'textarea' => $this->lang['textarea'],
                'wysiwyg' => $this->lang['wysiwyg'],
                'image' => $this->lang['image'],
                'boolean' => $this->lang['boolean'],
                'choice' => $this->lang['choice'],
                'multiplechoice' => $this->lang['multipleChoice']
            );

            //What validators are available? If there is a new one, add it here
            $fieldValidators = array(
                '' => $this->lang['none'],
                'text' => $this->lang['text'],
                'number' => $this->lang['number'],
                'email' => $this->lang['email'],
                'datetime' => $this->lang['datetime']
            );

            $this->view->jsLang['notEnoughFields'] = $this->lang['notEnoughFields'];
            $this->view->fieldTypes = $fieldTypes;
            $this->view->fieldValidators = $fieldValidators;
            $this->view->fields = $entityFields;
            $this->view->entity = $entity;

            $entities = $entitiesModel->getEntities();
            $this->view->entities = $entities;

            $this->view->entityFieldHtmlTemplate = $this->view->render('entities/entityField.phtml');

            $this->view->relations = $relations;
        }
    }


    public function instanceAction()
    {

        $entityId = $this->_request->entity;
        $this->view->entityId = $entityId;

        $entitiesModel = new Cms_Model_DbTable_Entities();

        $locales = $this->locales;

        $this->view->locales = $locales;

        // make editMode false :')
        $editMode = false;

        $webuserRelations = array();

        $entity = $entitiesModel->getEntityById($entityId);
        $this->view->title_singular = $entity['entity_title_singular'];
        $this->view->title_plural = $entity['entity_title_plural'];

        $this->view->webuserRelations = $webuserRelations;

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();

        }
        if (isset($data['locales']) && count($data['locales'] > 0)) {

            //Data has been posted, save it to the right table
            $entitiesModel = new Cms_Model_DbTable_Entities();
            $nextInstanceId = $entitiesModel->getNextInstanceId($entityId);

            foreach ($data['locales'] as $locale_id => $entityData) {
                if (isset($entityData['instance_id']) && is_numeric($entityData['instance_id']) && is_array($entitiesModel->getInstance($entityId, $entityData['instance_id'], $locale_id))) {
                    $move_to = $entityData['order'];
                    $move_from = $entityData['instance_id'];

                    $entitiesModel->updateInstance($entityData, $move_from, $move_to);

                    if (isset($entityData['websites']) && is_array($entityData['websites']) && count($entityData['websites']) > 0) {

                        $entitiesModel->linkWebsites($entityData['entity_id'], $entityData['instance_id'], $entityData['websites']);
                    } elseif (count($this->websites) == 1) {

                        $entitiesModel->linkWebsites($entityData['entity_id'], $entityData['instance_id'], array($this->websiteDetails['website_id']));
                    }

                }

                if (isset($entityData['websites']) && is_array($entityData['websites']) && count($entityData['websites']) > 0) {

                    $websites = $entityData['websites'];

                }
            }

            if (isset($websites) && is_array($websites) && count($websites) > 0) {

                $entitiesModel->linkWebsites($entityId, $nextInstanceId, $websites);
            } elseif (count($this->websites) == 1) {

                $entitiesModel->linkWebsites($entityId, $nextInstanceId, array($this->websiteDetails['website_id']));
            }

            $this->_helper->Messages->addNotice('flash', $this->lang['dataSaved']);
            if (isset($this->_request->parentinstance) && is_numeric($this->_request->parentinstance) && $this->_request->parentinstance > 0 && isset($this->_request->parententity) && is_numeric($this->_request->parententity) && $this->_request->parententity > 0) {
                $this->_helper->redirector->goToRoute(array('controller' => 'entities', 'action' => 'instance', 'entity' => $this->_request->parententity, 'instance' => $this->_request->parentinstance, 'parentinstance' => null, 'parententity' => null), 'website');
            } else {
                $this->_helper->redirector->goToRoute(array('controller' => $this->_request->controller, 'action' => 'instances', 'entity' => $entityId), 'website');
            }
            exit;

        } else {
            foreach ($locales as $localeId => $locale) {

                if (isset($this->_request->instance) && is_numeric($this->_request->instance)) {
                    $editMode = true;
                    $instanceId = $this->_request->instance;
                    $webuserRelations = $entitiesModel->getWebuserRelationsByEntityIdAndInstanceId($entityId, $instanceId);//gets webuser relations by entityId and instanceId  :')
                    $instance = $entitiesModel->getInstance($entityId, $instanceId, $locale['locale_id']);
                }

                //Generate the form that enables users to add entities of the right kind
                $form = new Zend_Form();

                $form->addDecorators(array('fieldset'));

                $form->setAction($this->_helper->url->url());
                $form->setMethod('post');
                $form->setAttrib('id', 'addEntity');
                $form->setElementsBelongTo('locales[' . $locale['locale_id'] . ']');
                $form->removeDecorator('form');

                $relations = $entitiesModel->getRelationsForEntity($entityId);

                $fieldsToConvert = array();

                foreach ($relations as $relation) {

                    if ($relation['type_relation'] == '1-n' && $relation['entity2_name'] == $entity['entity_name']) {
                        $relationEntity = $entitiesModel->getEntityByName($relation['entity1_name']);

                        $fieldsToConvert[strtolower($relationEntity['entity_title_singular']) . '_id'] = $relationEntity['id'];
                    }
                }

                $defaultLocaleId = $this->defaultLocaleId;

                if ($localeId != $defaultLocaleId) {

                    $elementName = 'defaultText_' . $localeId;
                    $showDefaultText = new Ds_Form_Element_Html($elementName);

                    $showDefaultText->setValue('
                        <span class="anchor copyDefaultLocaleValues icon icon16 iconCopy" id="copy-locale-' . $localeId . '">' . $this->lang['copyDefaultLanguageValues'] . '</span>
                    ');
                    $form->addElement($showDefaultText);
                }

                $fields = $entitiesModel->getEntityFields($entityId);

                foreach ($fields as $field) {

                    if (array_key_exists($field['field_name'], $fieldsToConvert)) {

                        $relation = $entitiesModel->getEntityById($fieldsToConvert[$field['field_name']]);
                        $relationInstances = $entitiesModel->getInstances($fieldsToConvert[$field['field_name']], array('sort_field' => $relation['display_field'], 'sort_direction' => 'ASC'));

                        $parentInstance = null;
                        if (isset($this->_request->parentinstance)) {
                            foreach ($relationInstances as $relationInstance) {
                                if ($relationInstance['instance_id'] == $this->_request->parentinstance) {
                                    $parentInstance = $relationInstance;
                                    break;
                                }
                            }
                        }

                        if (!$editMode && isset($this->_request->parententity) && $this->_request->parententity == $fieldsToConvert[$field['field_name']] && !is_null($parentInstance)) {
                            $element = new Ds_Form_Element_Html($field['field_name'] . "_display");
                            $element->setValue($parentInstance['klanten_naam']);
                            $form->addElement($element);

                            $element = new Zend_Form_Element_Hidden($field['field_name']);
                            $element->setValue($this->_request->parentinstance);
                            $element->setDecorators(array('ViewHelper'));
                        } else {
                            $element = new Zend_Form_Element_Select($field['field_name']);

                            if (!$field['field_required']) {
                                $element->addMultiOption('', '');
                            }


                            foreach ($relationInstances as $relationInstance) {
                                $relationEntity = $entitiesModel->getEntityById($fieldsToConvert[$field['field_name']]);
                                $displayField = $relationEntity['display_field'];

                                if ($displayField != '') {

                                    if (isset($relationInstance[$relationEntity['entity_name'] . '_' . $displayField])) {

                                        $waarde = $relationInstance[$relationEntity['entity_name'] . '_' . $displayField];
                                    }
                                } else {

                                    $waarde = $relationInstance[$relationEntity['entity_name'] . '_id'];
                                }
                                $element->addMultiOption($relationInstance['instance_id'], $waarde);
                            }
                        }
                    } else {

                        //Create right type of field
                        switch ($field['field_type']) {

                            case 'singleline':

                                $element = new Zend_Form_Element_Text($field['field_name']);
                                break;

                            case 'multiplelines':
                                $element = new Zend_Form_Element_Textarea($field['field_name']);
                                break;

                            case 'wysiwyg':
                                $element = new Zend_Form_Element_Textarea($field['field_name']);
                                $element->setAttrib('class', 'ckeditor');
                                break;

                            case 'image':
                                $element = new Zend_Form_Element_File($field['field_name'] . '_' . $locale['locale_id']);
                                $element->setAttrib('class', 'imageUploader');
                                break;

                            case 'datetime':
                                $element = new Zend_Form_Element_Text($field['field_name']);
                                $element->setAttrib('class', 'datepicker');
                                break;

                            case 'boolean':
                                $element = new Zend_Form_Element_Checkbox($field['field_name']);
                                $element->setAttrib('class', 'noStyle');
                                break;

                            case 'choice':
                                $element = new Zend_Form_Element_Select($field['field_name']);

                                $options = $entitiesModel->getFieldOptions($entity, $field['field_name']);

                                foreach ($options as $option) {
                                    $element->addMultiOption($option, $option);
                                }
                                break;

                            case 'multiplechoice':

                                $options = split(';', $field['field_options']);
                                $elementHtml = '';

                                if ($editMode) {
                                    $values = explode(";", $instance[$field['field_name']]);
                                }

                                foreach ($options as $option) {
                                    $elementHtml .= '<input type="checkbox" name="locales[' . $locale['locale_id'] . '][' . $field['field_name'] . '][]" id="locales-' . $locale['locale_id'] . '-' . $field['field_name'] . '_' . $option . '" value="' . $option . '" ' . (($editMode && in_array($option, $values)) ? 'checked="checked"' : '') . '/><label for="locales-' . $locale['locale_id'] . '-' . $field['field_name'] . '_' . $option . '">' . $option . '</label><br/>';
                                }

                                $element = new Ds_Form_Element_Html($field['field_name']);
                                $element->setValue($elementHtml);
                                break;

                        }
                    }

                    $this->validators = array();

                    switch ($field['field_validation']) {

                        case 'number':
                            $this->validators[] = 'number';
                            $element->addValidator('Digits');
                            break;

                        case 'email':
                            $this->validators[] = 'email';
                            $element->addValidator('EmailAddress');
                            break;

                        case 'datetime':
                            $this->validators[] = 'date';
                            $element->addValidator('Date');
                            break;

                    }

                    //Check whether the field should be made mandatory
                    if ((int)$field['field_required'] == 1) {

                        $this->validators[] = 'required';
                        $element->setRequired(true);
                    }

                    $element->setAttrib('class', trim($element->getAttrib('class') . ' ' . join(' ', $this->validators)));

                    if ($editMode) {
                        if ($field['field_type'] != "multiplechoice") {
                            if ($field['field_type'] == 'datetime') {
                                $value = date($this->lang['dateFormat'], $instance[$field['field_name']]);
                            } else {
                                $value = $instance[$field['field_name']];
                            }
                            $element->setValue($value);
                        }
                    }

                    $element->setLabel($field['field_title']);
                    $form->addElement($element);

                    if ($editMode && $field['field_type'] == 'image' && $instance[$field['field_name']] != '') {
                        $websiteIds = $entitiesModel->getWebsitesByEntityIdAndInstanceId($entityId, $instanceId);

                        $websitesModel = new Cms_Model_DbTable_Websites();

                        $filesUrl = false;
                        if (in_array($this->websiteDetails['website_id'], $websiteIds)) {
                            $filesUrl = Ds_String::addTrailingSlash($this->websiteDetails['url']) . 'files/';
                            $filesPath = Ds_String::addTrailingSlash($this->websiteDetails['website_path']) . 'files/';
                        } else {
                            foreach ($websiteIds as $websiteId) {
                                $website = $websitesModel->getWebsite($websiteId);
                                if ($website !== false) {
                                    $filesUrl = Ds_String::addTrailingSlash($website['url']) . 'files/';
                                    $filesPath = Ds_String::addTrailingSlash($website['website_path']) . 'files/';
                                }
                            }
                        }

                        $file = new Ds_Form_Element_Html('file_' . $field['field_name']);
                        if ($filesUrl !== false && file_exists($filesPath . $entity['entity_name'] . '/' . $instance[$field['field_name']])) {
                            $file->setValue(' &raquo; <a href="' . Ds_String::addTrailingSlash($filesUrl) . '' . $entity['entity_name'] . '/' . $instance[$field['field_name']] . '" target="_blank">' . $instance[$field['field_name']] . '</a><a href="' . $this->_helper->simpleUrl(array('action' => 'deletefilefromentity', 'entity' => $entityId, 'instance' => $instanceId, 'field' => $field['field_name'])) . '" class="removeButton">&nbsp;</a>');
                        } else {
                            $file->setValue(' &raquo; <span class="error" title="' . $this->lang['fileNotFound'] . '">' . $instance[$field['field_name']] . '</span><a href="' . $this->_helper->simpleUrl(array('action' => 'deletefilefromentity', 'entity' => $entityId, 'instance' => $instanceId, 'field' => $field['field_name'])) . '" class="removeButton">&nbsp;</a>');
                        }
                        $form->addElement($file);
                    }

                }

                if ($editMode) {
                    $element = new Zend_Form_Element_Hidden('instance_id');
                    $element->setValue($instanceId);
                    $form->addElement($element);
                }
                if ($this->_request->entity == 7) {
                    $element = new Zend_Form_Element_Text("overlay_text");
                    $element->setLabel("Overlay text");
                    if (isset($instance['overlay_text'])) {
                        $element->setValue($instance['overlay_text']);
                    }
                    $form->addElement($element);
                }
                if ($this->_request->entity == 7 and $this->_request->instance != "entity") {
                    $relationEntity = $entitiesModel->getInstance($this->_request->entity, $this->_request->instance, $locale['locale_id'], $this->selectedWebsiteId);
                    $options = $entitiesModel->getVisualsInstancesIds($relationEntity['visuals_categorie'], $this->selectedWebsiteId);
                    $element = new Zend_Form_Element_Select("order");
                    foreach ($options as $key => $option) {
                        if ($option['instance_id'] == $this->_request->instance) {
                            $element->addMultiOption($option['instance_id'], $option['instance_id'] . '(Current)');
                        } else {
                            $element->addMultiOption($option['instance_id'], $option['instance_id']);
                        }
                    }
                    $element->setValue($this->_request->instance);
                    $element->setLabel("Change order to");
                    $form->addElement($element);
                } elseif ($this->_request->entity == 7 and $this->_request->instance == "entity") {
                    $categories = $entitiesModel->getVisualsCategoriesEnum();
                    $category_instance = array();
                    $first = true;
                    foreach ($categories as $category) {
                        if ($first) {
                            $first_category_name = $category;
                        }
                        $first = false;
                        $instance_ids = $entitiesModel->getVisualsInstancesIds($category, $this->selectedWebsiteId);
                        $category_instance[$category] = $instance_ids;
                    }
                    $this->view->first_category_name = $first_category_name;
                    $this->view->category_instance = $category_instance;
                    $element = new Zend_Form_Element_Select("order");
                    $element->setAttrib('id', 'visuals_order');
                    $first_category_instance_ids = $entitiesModel->getVisualsInstancesIds($first_category_name, $this->selectedWebsiteId);
                    $element->setValue($this->_request->instance);
                    $element->setLabel("Change order to");
                    $form->addElement($element);
                }
                if ($this->_request->entity == 2) {
                    $element = new Zend_Form_Element_Text("Product_ids");
                    if (isset($instance['product_ids'])) {
                        $element->setValue($instance['product_ids']);
                    }
                    $element->setLabel("Product Ids (comma-seperated)")->setAttribs(array('style' => 'width: 650px;'));
                    $form->addElement($element);
                }
                $element = new Zend_Form_Element_Hidden('entity_id');
                $element->setValue($entityId);
                $element->removeDecorator('label');
                $element->removeDecorator('DtDdWrapper');
                $form->addElement($element);

                $element = new Zend_Form_Element_Hidden('locale_id');
                $element->setValue($locale['locale_id']);
                $element->removeDecorator('label');
                $element->removeDecorator('DtDdWrapper');
                $form->addElement($element);


                if (count($this->websites) > 1) {

                    $element = new Zend_Form_Element_MultiCheckbox('websites[]');
                    $element->setLabel($this->lang['websites']);
                    $element->setAttrib('class', 'noStyle websiteCheck');

                    foreach ($this->websites as $website) {
                        $element->addMultiOption($website['website_id'], $website['website_code']);
                    }

                    if (!$editMode) {
                        $element->setValue($this->websiteDetails['website_id']);
                    } else {
                        $websiteIds = $entitiesModel->getWebsitesByEntityIdAndInstanceId($entityId, $instanceId);
                        $element->setValue($websiteIds);
                    }

                    $form->addElement($element);

                }

                $formVariable = $locale['locale_code'] . '_form';
                $this->view->$formVariable = $form;

                $this->view->fields = $fields;
                $this->view->defaultLocaleId = $this->defaultLocaleId;
            }

            // End form creation
            if (isset($entity['accept_comments']) && $entity['accept_comments'] == 1 && $editMode) {

                $browser = new Cms_Browser_Comments(array('entity_id' => $entityId, 'instance_id' => $instanceId));
                $this->view->browserData['commentsBrowser'] = $browser->toArray();
                $this->view->comments = $entitiesModel->getCommentsForInstance($entityId, $instanceId);

            }

            $relations = $entitiesModel->getRelationsForEntity($entityId);

            $viewBrowsers = array();

            $websiteId = $this->selectedWebsiteId;

            if ($editMode) {
                foreach ($relations as $relation) {

                    if ($relation['entity1_id'] == $entityId) {

                        $relationEntity = $entitiesModel->getEntityById($relation['entity2_id']);

                        $parentEntity = array(
                            'name' => strtolower($entity['entity_title_singular']),
                            'value' => $instanceId
                        );

                        $browserId = 'instancesBrowser_' . $relation['entity2_id'];

                        $browser = new Cms_Browser_EntityInstances(array(
                                'entityId' => $relation['entity2_id'],
                                'websiteId' => $websiteId,
                                'localeId' => $this->localeId,
                                'parentEntity' => $parentEntity,
                                'browserId' => $browserId

                            )
                        );

                        $viewBrowsers[] = array(
                            'id' => $relation['entity2_id'],
                            'title' => $relationEntity['entity_display_name']
                        );

                        $this->view->browserData[$browserId] = $browser->toArray();
                        $browser = null;
                    }
                }

                $this->view->instanceId = $instanceId;
            }

            $this->view->browsers = $viewBrowsers;
        }
    }

    public function addreferentiescategoryAction()
    {
        $entitiesModel = new Cms_Model_DbTable_Entities();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if (isset($data['referentie_category']) and $data['referentie_category'] == "add") {
                $categoryName = $data['addReferentiescategory'];
                if (trim($categoryName) != "") {
                    $entitiesModel->addReferentiesCategory($categoryName);
                    $this->view->addedReferentiesCategory = true;
                } else {
                    $this->view->empty_catrgory = true;
                }
            }
            if (isset($data['remove_referentie_category']) and $data['remove_referentie_category'] == "remove") {
                $category = $data['category'];
                $entitiesModel->removeReferentiesCategory($category);
                $this->view->removedReferentiesCategory = true;
            }

        }
        $form = new Zend_Form();
        $form->addDecorators(array('fieldset'));

        $form->setMethod('post');
        $form->setAttrib('id', 'addReferentiesCategory');
        $element = new Zend_Form_Element_Text("addReferentiescategory");
        $element->setLabel("Category Name");
        $form->addElement($element);
        $element = new Zend_Form_Element_Hidden('referentie_category');
        $element->setValue("add");
        $form->addElement($element);
        $element = new Zend_Form_Element_Submit("Add Category");
        $form->addElement($element);
        $this->view->addreferentiesCategoryForm = $form;
        $remove_category_form = new Zend_Form();
        $remove_category_form->addDecorators(array('fieldset'));
        $remove_category_form->setMethod('post');
        $remove_category_form->setAttrib('id', 'removeReferentiesCategory');
        $elem = new Zend_Form_Element_Select("category");
        $categories = $entitiesModel->getReferentiesCategoriesEnum();
        foreach ($categories as $category) {
            $elem->addMultiOption($category, $category);
        }
        $elem->setValue("category");
        $elem->setLabel("Category");
        $remove_category_form->addElement($elem);
        $elem = new Zend_Form_Element_Hidden('remove_referentie_category');
        $elem->setValue("remove");
        $remove_category_form->addElement($elem);
        $elem = new Zend_Form_Element_Submit("Remove Category");
        $remove_category_form->addElement($elem);
        $this->view->removereferentiesCategoryForm = $remove_category_form;
    }

    public function instancesAction()
    {

        /* figure out the right locale id */
        $locales = $this->websiteDetails['locales'];

        $this->view->locales = $locales;

        $entityId = (int)$this->_request->entity;
        if ($entityId == 9) {
            $currentPath = $_SERVER['PHP_SELF'];
            $pathInfo = pathinfo($currentPath);
            $hostName = $_SERVER['HTTP_HOST'];
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';
            $protocol .= $hostName . $pathInfo['dirname'];
            $url = $protocol . '/' . $this->_request->module . '/' . $this->_request->website . '/entities/vacatures';
            $this->_redirect($url);
        } else {
            $entitiesModel = new Cms_Model_DbTable_Entities();

            $entity = $entitiesModel->getEntityById($entityId);

            $this->view->subscribable = (bool)$entity['subscribable'];

            $this->view->jsLang['areYouSure'] = $this->lang['areYouSure'];
            $this->view->jsLang['yes'] = $this->lang['yes'];
            $this->view->jsLang['cancel'] = $this->lang['cancel'];
            $this->view->jsLang['deletionFailed'] = $this->lang['deletionFailed'];
            $this->view->jsLang['message'] = $this->lang['message'];

            $this->view->title_singular = $entity['entity_title_singular'];
            $this->view->title_plural = $entity['entity_title_plural'];
            $this->view->entity_id = $entityId;

            $browser = new Cms_Browser_EntityInstances(array('entityId' => $entityId, 'websiteId' => $this->selectedWebsiteId, 'localeId' => $this->localeId));

            $this->view->browserData['instancesBrowser'] = $browser->toArray();
        }
    }

    public function vacaturesAction()
    {
        $token = 'vacatures' . uniqid();
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
                'lifetime' => 120,
                'caching' => true,
                'write_control' => true,
                'ignore_user_abort' => true,
                'automatic_serialization' => true
            ));
        $cache = Zend_Cache::factory($oFrontend, $oBackend);
        $cache->save(true, $token);
        $this->view->site = $this->_request->website;
        $this->view->token = $token;
    }


    public function activaterelationAction()
    {

        if (isset($this->_request->entityWebuserId) && (int)$this->_request->entityWebuserId > 0) {

            $active = $this->_request->active;
            $entitiesModel = new Cms_Model_DbTable_Entities();
            $entitiesModel->activeWebuserRelation($this->_request->entityWebuserId, $active);
        }


        $this->_helper->redirector->goToRoute(array('controller' => 'entities', 'action' => 'instance', 'entity' => $this->_request->entity, 'instance_id' => $this->_request->instance_id), 'website');
    }

    public function deleteinstanceAction()
    {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $entityId = isset($this->_request->entity) ? $this->_request->entity : false;
        $instanceIds = isset($this->_request->instance) ? $this->_request->instance : false;

        if (is_array($instanceIds)) {
            foreach ($instanceIds as $instanceId) {
                $entitiesModel = new Cms_Model_DbTable_Entities();
                $entitiesModel->deleteInstance($entityId, $instanceId);
            }
        }
        $success = true;
        $message = $this->lang['resultsRemoved'];

        $this->_helper->json(array(
            'success' => (bool)$success,
            'message' => $message
        ));
    }


    public function deleteentityAction()
    {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $entityIds = isset($this->_request->entity) ? $this->_request->entity : false;

        foreach ($entityIds as $entityId) {
            $entitiesModel = new Cms_Model_DbTable_Entities();
            $entitiesModel->deleteEntity($entityId);
        }

        $success = true;
        $message = $this->lang['resultsRemoved'];

        $this->_helper->json(array(
            'success' => (bool)$success,
            'message' => $message
        ));

    }

    public function deletecommentsAction()
    {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $entityId = isset($this->_request->entity) ? $this->_request->entity : false;
        $commentIds = isset($this->_request->comment) ? $this->_request->comment : false;

        if (is_array($commentIds)) {
            foreach ($commentIds as $commentId) {
                $entitiesModel = new Cms_Model_DbTable_Entities();
                $entitiesModel->deleteComment($entityId, $commentId);
            }
        }

        $success = true;
        $message = $this->lang['resultsRemoved'];

        $this->_helper->json(array(
            'success' => (bool)$success,
            'message' => $message
        ));

    }


    public function getentityfieldsAction()
    {

        $entityId = isset($this->_request->entity) ? $this->_request->entity : false;

        if ($entityId !== false) {
            $entitiesModel = new Cms_Model_DbTable_Entities();
            $this->_helper->json($entitiesModel->getEntityFields($entityId));
        } else {
            echo "FAILURE";
        }

    }


    public function getentitytemplatesAction()
    {

        $entityId = isset($this->_request->entity) ? $this->_request->entity : false;

        if ($entityId !== false) {
            $entitiesModel = new Cms_Model_DbTable_Entities();
            $this->_helper->json($entitiesModel->getEntityTemplates($entityId, $this->websiteDetails['website_path']));
        } else {
            echo "FAILURE";
        }

    }


    public function checkentitynameAction()
    {
        $entityName = isset($this->_request->entity) ? $this->_request->entity : false;
        $entityName = ($entityName != '') ? $entityName : false;


        if ($entityName !== false) {
            $entitiesModel = new Cms_Model_DbTable_Entities();
            $check = $entitiesModel->doesEntityExist($entityName);
            if ($check == true) {
                $this->_helper->json(false);
            } else {
                $this->_helper->json(true);
            }
        } else {
            $this->_helper->json("FAILURE");
        }

    }

    public function createcommentstableAction()
    {

        $entityId = isset($this->_request->entity) ? $this->_request->entity : false;

        $entitiesModel = new Cms_Model_DbTable_Entities();

        if ($entityId != false) {
            $success = $entitiesModel->createCommentsTable($entityId);

            if ($success) {
                $this->_helper->redirector->goToRoute(array('controller' => 'entities', 'action' => 'instances', 'entity' => $entityId), 'website');
            }
        }
    }

    public function deletefilefromentityAction()
    {

        $instanceId = isset($this->_request->instance) ? $this->_request->instance : false;
        $entityId = isset($this->_request->entity) ? $this->_request->entity : false;
        $fieldName = isset($this->_request->field) ? $this->_request->field : false;

        $entitiesModel = new Cms_Model_DbTable_Entities();

        $entitiesModel->removeAttachment($entityId, $instanceId, $fieldName);

        $this->_helper->redirector->goToRoute(array('controller' => 'entities', 'action' => 'instance', 'entity' => $entityId, 'instance' => $instanceId, 'field' => null), 'website');

    }

}
