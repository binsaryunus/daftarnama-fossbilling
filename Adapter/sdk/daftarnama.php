<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/apis/requests/register_domain_request.php';

/**
 * DaftarNama Module
 *
 * @link https://exabytes.co.id Exabytes Network Indonesia
 */
class Daftarnama extends RegistrarModule
{

    /**
     * Initializes the module
     */
    public function __construct()
    {
        // Load the language required by this module
        Language::loadLang('daftarnama', null, dirname(__FILE__) . DS . 'language' . DS);

        // Load components required by this module
        Loader::loadComponents($this, ['Input']);

        // Load module config
        $this->loadConfig(dirname(__FILE__) . DS . 'config.json');

        Configure::load('daftarnama', dirname(__FILE__) . DS . 'config' . DS);
    }

    /**
     * Returns the rendered view of the manage module page.
     *
     * @param mixed $module A stdClass object representing the module and its rows
     * @param array $vars An array of post data submitted to or on the manager module
     *  page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the manager module page
     * @throws Exception
     */
    public function manageModule($module, array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('manage', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        $this->view->set('module', $module);

        return $this->view->fetch();
    }

    /**
     * Returns the rendered view of the add module row page.
     *
     * @param array $vars An array of post data submitted to or on the add module
     *  row page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the add module row page
     * @throws Exception
     */
    public function manageAddRow(array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('add_row', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        if (!empty($vars)) {
            // Set unset checkboxes
            $checkbox_fields = ['is_sandbox'];

            foreach ($checkbox_fields as $checkbox_field) {
                if (!isset($vars[$checkbox_field])) {
                    $vars[$checkbox_field] = 'false';
                }
            }
        }

        $this->view->set('vars', (object) $vars);

        return $this->view->fetch();
    }

    /**
     * Returns the rendered view of the edit module row page.
     *
     * @param stdClass $module_row The stdClass representation of the existing module row
     * @param array $vars An array of post data submitted to or on the edit
     *  module row page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the edit module row page
     * @throws Exception
     */
    public function manageEditRow($module_row, array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('edit_row', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        if (empty($vars)) {
            $vars = $module_row->meta;
        } else {
            // Set unset checkboxes
            $checkbox_fields = ['is_sandbox'];

            foreach ($checkbox_fields as $checkbox_field) {
                if (!isset($vars[$checkbox_field])) {
                    $vars[$checkbox_field] = 'false';
                }
            }
        }

        $this->view->set('vars', (object) $vars);

        return $this->view->fetch();
    }

    /**
     * Adds the module row on the remote server. Sets Input errors on failure,
     * preventing the row from being added. Returns a set of data, which may be
     * a subset of $vars, that is stored for this module row.
     *
     * @param array $vars An array of module info to add
     * @return array A numerically indexed array of meta fields for the module row containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether this field should be encrypted (default 0, not encrypted) or not
     */
    public function addModuleRow(array &$vars)
    {
        $meta_fields = ['description', 'api_key', 'is_sandbox'];
        $encrypted_fields = ['api_key'];

        // Set unset checkboxes
        $checkbox_fields = ['is_sandbox'];

        foreach ($checkbox_fields as $checkbox_field) {
            if (!isset($vars[$checkbox_field])) {
                $vars[$checkbox_field] = 'false';
            }
        }

        $this->Input->setRules($this->getRowRules($vars));

        // Validate module row
        if ($this->Input->validates($vars)) {
            // Build the meta data for this row
            $meta = [];
            foreach ($vars as $key => $value) {
                if (in_array($key, $meta_fields)) {
                    $meta[] = [
                        'key' => $key,
                        'value' => $value,
                        'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
                    ];
                }
            }

            return $meta;
        }

        return [];
    }

    /**
     * Edits the module row on the remote server. Sets Input errors on failure,
     * preventing the row from being updated. Returns a set of data, which may be
     * a subset of $vars, that is stored for this module row.
     *
     * @param stdClass $module_row The stdClass representation of the existing module row
     * @param array $vars An array of module info to update
     * @return array A numerically indexed array of meta fields for the module row containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether this field should be encrypted (default 0, not encrypted) or not
     */
    public function editModuleRow($module_row, array &$vars)
    {
        return $this->addModuleRow($vars);
    }

    /**
     * Builds and returns the rules required to add/edit a module row (e.g. server).
     *
     * @param array $vars An array of key/value data pairs
     * @return array An array of Input rules suitable for Input::setRules()
     */
    private function getRowRules(&$vars)
    {
        return [
            'description' => [
                'valid' => [
                    'last' => true,
                    'rule' => 'isEmpty',
                    'negate' => true,
                    'message' => Language::_('Daftarnama.!error.description.valid', true)
                ],
            ],
            'api_key' => [
                'valid' => [
                    'last' => true,
                    'rule' => 'isEmpty',
                    'negate' => true,
                    'message' => Language::_('Daftarnama.!error.api_key.valid', true)
                ],
                'valid_connection' => [
                    'rule' => [
                        [$this, 'validateConnection'],
                        isset($vars['is_sandbox']) ? $vars['is_sandbox'] : 'false',
                        isset($vars['api_url']) ? $vars['api_url'] : null
                    ],
                    'message' => Language::_('Daftarnama.!error.api_key.valid_connection', true)
                ]
            ]
        ];
    }

    /**
     *
     * @param string $api_key
     * @param string $is_sandbox
     * @return bool
     */
    public function validateConnection($api_key, $is_sandbox, $api_url)
    {
        try {
            $api = $this->getApi($api_key, $is_sandbox == 'true', $api_url);

            $api->ping();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $api_key
     * @param bool $is_sandbox
     * @param string $api_url
     * @return DaftarnamaApi
     */
    private function getApi($api_key, $is_sandbox, $api_url)
    {
        Loader::load(dirname(__FILE__) . DS . 'apis' . DS . 'daftarnama_api.php');

        return new DaftarnamaApi($api_key, $is_sandbox, $api_url);
    }

    /**
     * Validates input data when attempting to add a package, returns the meta
     * data to save when adding a package. Performs any action required to add
     * the package on the remote server. Sets Input errors on failure,
     * preventing the package from being added.
     *
     * @param array|null $vars An array of key/value pairs used to add the package
     * @return array A numerically indexed array of meta fields to be stored for this package containing:
     *
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether this field should be encrypted (default 0, not encrypted) or not
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function addPackage(array $vars = null)
    {
        $meta = [];
        if (isset($vars['meta']) && is_array($vars['meta'])) {
            // Return all package meta fields
            foreach ($vars['meta'] as $key => $value) {
                $meta[] = [
                    'key' => $key,
                    'value' => $value,
                    'encrypted' => 0
                ];
            }
        }

        return $meta;
    }

    /**
     * Validates input data when attempting to edit a package, returns the meta
     * data to save when editing a package. Performs any action required to edit
     * the package on the remote server. Sets Input errors on failure,
     * preventing the package from being edited.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array An array of key/value pairs used to edit the package
     * @return array A numerically indexed array of meta fields to be stored for this package containing:
     *
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether this field should be encrypted (default 0, not encrypted) or not
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function editPackage($package, array $vars = null)
    {
        $meta = [];
        if (isset($vars['meta']) && is_array($vars['meta'])) {
            // Return all package meta fields
            foreach ($vars['meta'] as $key => $value) {
                $meta[] = [
                    'key' => $key,
                    'value' => $value,
                    'encrypted' => 0
                ];
            }
        }

        return $meta;
    }

    /**
     * Returns all fields used when adding/editing a package, including any
     * javascript to execute when the page is rendered with these fields.
     *
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containing the fields to
     *  render as well as any additional HTML markup to include
     * @throws Exception
     */
    public function getPackageFields($vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        // Set all TLD checkboxes
        $tld_options = $fields->label(Language::_('Daftarnama.package_fields.tld_options', true));
        $tlds = $this->getTlds();
        sort($tlds);
        foreach ($tlds as $tld) {
            $tld_label = $fields->label($tld, 'tld_' . $tld);
            $tld_options->attach(
                $fields->fieldCheckbox(
                    'meta[tlds][]',
                    $tld,
                    (isset($vars->meta['tlds']) && in_array($tld, $vars->meta['tlds'])),
                    ['id' => 'tld_' . $tld],
                    $tld_label
                )
            );
        }
        $fields->setField($tld_options);

        // Set nameservers
        for ($i = 1; $i <= 5; $i++) {
            $type = $fields->label(Language::_('Daftarnama.package_fields.ns' . $i, true), 'daftarnama_ns' . $i);
            $type->attach(
                $fields->fieldText(
                    'meta[ns][]',
                    (isset($vars->meta['ns'][$i - 1]) ? $vars->meta['ns'][$i - 1] : null),
                    ['id' => 'daftarnama_ns' . $i]
                )
            );
            $fields->setField($type);
        }

        return $fields;
    }

    /**
     * Attempts to validate service info. This is the top-level error checking method. Sets Input errors on failure.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array $vars An array of user supplied info to satisfy the request
     * @return bool True if the service validates, false otherwise. Sets Input errors when false.
     */
    public function validateService($package, array $vars = null)
    {
        $this->Input->setRules($this->getServiceRules($vars));
        return $this->Input->validates($vars);
    }

    /**
     * Attempts to validate an existing service against a set of service info updates. Sets Input errors on failure.
     *
     * @param stdClass $service A stdClass object representing the service to validate for editing
     * @param array $vars An array of user-supplied info to satisfy the request
     * @return bool True if the service update validates or false otherwise. Sets Input errors when false.
     */
    public function validateServiceEdit($service, array $vars = null)
    {
        $this->Input->setRules($this->getServiceRules($vars, true));
        return $this->Input->validates($vars);
    }

    /**
     * Returns the rule set for adding/editing a service
     *
     * @param array $vars A list of input vars
     * @param bool $edit True to get the edit rules, false for the add rules
     * @return array Service rules
     */
    private function getServiceRules(array $vars = null, $edit = false)
    {
        ////// For info on writing validation rules, see the
        ////// docs at https://docs.blesta.com/display/dev/Error+Checking
        ////
        // Validate the service fields
        $rules = [
            'domain' => [
                'valid' => [
                    'rule' => 'isEmpty',
                    'negate' => true,
                    'message' => Language::_('Daftarnama.!error.domain.valid', true)
                ]
            ],
            'auth_code' => [
                'valid' => [
                    'if_set' => true,
                    'rule' => 'isEmpty',
                    'negate' => true,
                    'message' => Language::_('Daftarnama.!error.auth_code.valid', true)
                ]
            ]
        ];

        if ($edit) {
            $edit_fields = [];

            foreach ($rules as $field => $rule) {
                if (!in_array($field, $edit_fields)) {
                    unset($rules[$field]);
                }
            }
        }

        return $rules;
    }

    /**
     * Adds the service to the remote server. Sets Input errors on failure,
     * preventing the service from being added.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array $vars An array of user supplied info to satisfy the request
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being added (if the current service is an addon service
     *  and parent service has already been provisioned)
     * @param string $status The status of the service being added. These include:
     *  - active
     *  - canceled
     *  - pending
     *  - suspended
     * @return array|void A numerically indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether this field should be encrypted (default 0, not encrypted) or not
     * @throws Exception
     * @see Module::getModuleRow()
     * @see Module::getModule()
     */
    public function addService(
        $package,
        array $vars = null,
        $parent_package = null,
        $parent_service = null,
        $status = 'pending'
    ) {
        if (isset($vars['use_module']) && $vars['use_module'] == 'true') {
            // Set registration period
            $vars['years'] = 1;
            foreach ($package->pricing as $pricing) {
                if ($pricing->id == $vars['pricing_id']) {
                    $vars['years'] = $pricing->term;
                    break;
                }
            }

            // Set nameservers from default nameservers
            $vars['nameservers'] = [];
            if (isset($package->meta->ns)) {
                foreach ($package->meta->ns as $ns) {
                    if ($ns) {
                        $vars['nameservers'][] = $ns;
                    }
                }
            }


            if (array_key_exists('auth_info', $vars)) {
                $this->transferDomain($vars['domain'], $package->module_row, $vars);
            } else {
                $this->registerDomain($vars['domain'], $package->module_row, $vars);
            }

            if ($this->Input->errors()) {
                return;
            }
        }

        return [['key' => 'domain', 'value' => $vars['domain'], 'encrypted' => 0]];
    }

    /**
     * Edits the service on the remote server. Sets Input errors on failure,
     * preventing the service from being edited.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $vars An array of user supplied info to satisfy the request
     * @param stdClass $parent_package A stdClass object representing the parent service's selected package
     *  (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent service of the service being edited
     *  (if the current service is an addon service)
     * @return array A numerically indexed array of meta fields to be stored for this service containing:
     *
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function editService($package, $service, array $vars = [], $parent_package = null, $parent_service = null)
    {
        // Manually renew the domain
        $renew = isset($vars['renew']) ? (int) $vars['renew'] : 0;
        if ($renew > 0 && $vars['use_module'] == 'true') {
            $this->renewService($package, $service, $parent_package, $parent_service, $renew);
            unset($vars['renew']);
        }

        return null; // All this handled by admin/client tabs instead
    }

    /**
     * Allows the module to perform an action when the service is ready to renew.
     * Sets Input errors on failure, preventing the service from renewing.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being renewed (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether this field should be encrypted (default 0, not encrypted) or not
     * @throws Exception
     * @see Module::getModuleRow()
     * @see Module::getModule()
     */
    public function renewService($package, $service, $parent_package = null, $parent_service = null, $years = null)
    {
        $fields = $this->serviceFieldsToObject($service->fields);

        // Set renew period
        if (!$years) {
            $vars['years'] = 1;
            foreach ($package->pricing as $pricing) {
                if ($pricing->id == $service->pricing_id) {
                    $vars['years'] = $pricing->term;
                    break;
                }
            }
        } else {
            $vars['years'] = $years;
        }

        // Renew domain
        $this->renewDomain($fields->domain, $package->module_row, $vars);

        if ($this->Input->errors()) {
            return;
        }

        return null;
    }

    /**
     * Fetches the HTML content to display when viewing the service info in the
     * admin interface.
     *
     * @param stdClass $service A stdClass object representing the service
     * @param stdClass $package A stdClass object representing the service's package
     * @return string HTML content containing information to display when viewing the service info
     * @throws Exception
     */
    public function getAdminServiceInfo($service, $package)
    {
        $row = $this->getModuleRow();

        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('admin_service_info', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $this->view->set('module_row', $row);
        $this->view->set('package', $package);
        $this->view->set('service', $service);
        $this->view->set('service_fields', $this->serviceFieldsToObject($service->fields));

        return $this->view->fetch();
    }

    /**
     * Fetches the HTML content to display when viewing the service info in the
     * client interface.
     *
     * @param stdClass $service A stdClass object representing the service
     * @param stdClass $package A stdClass object representing the service's package
     * @return string HTML content containing information to display when viewing the service info
     * @throws Exception
     */
    public function getClientServiceInfo($service, $package)
    {
        $row = $this->getModuleRow();

        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('client_service_info', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $this->view->set('module_row', $row);
        $this->view->set('package', $package);
        $this->view->set('service', $service);
        $this->view->set('service_fields', $this->serviceFieldsToObject($service->fields));

        return $this->view->fetch();
    }

    /**
     * Returns all fields to display to an admin attempting to add a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containing the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getAdminAddFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        // Set the Domain field
        $domain = $fields->label(Language::_('Daftarnama.service_fields.domain', true), 'daftarnama_domain');
        $domain->attach(
            $fields->fieldText(
                'domain',
                (isset($vars->domain) ? $vars->domain : null),
                ['id' => 'daftarnama_domain']
            )
        );
        $fields->setField($domain);

        // Handle transfer request
        if (isset($vars->transfer) || isset($vars->auth_code)) {
            $auth_code = $fields->label(Language::_('Daftarnama.service_fields.auth_code', true), 'daftarnama_auth_code');
            $auth_code->attach(
                $fields->fieldText(
                    'auth_code',
                    (isset($vars->auth_code) ? $vars->auth_code : null),
                    ['id' => 'daftarnama_auth_code']
                )
            );
            $fields->setField($auth_code);
        }

        return $fields;
    }

    /**
     * Returns all fields to display to an admin attempting to edit a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containing the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getAdminEditFields($package, $vars = null)
    {
        return $this->getAdminAddFields($package, $vars);
    }

    /**
     * Returns all fields to display to a client attempting to add a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containing the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getClientAddFields($package, $vars = null)
    {
        return $this->getAdminAddFields($package, $vars);
    }

    /**
     * Returns all tabs to display to an admin when managing a service
     *
     * @param stdClass $service A stdClass object representing the service
     * @return array An array of tabs in the format of method => title.
     *  Example: ['methodName' => "Title", 'methodName2' => "Title2"]
     */
    public function getAdminServiceTabs($service)
    {
        return [
            'tabWhois' => Language::_('Daftarnama.tab_whois.title', true),
            'tabNameservers' => Language::_('Daftarnama.tab_nameservers.title', true),
            'tabSettings' => Language::_('Daftarnama.tab_settings.title', true),
            'tabDocuments' => Language::_('Daftarnama.tab_documents.title', true)
        ];
    }

    /**
     * Returns all tabs to display to a client when managing a service.
     *
     * @param stdClass $service A stdClass object representing the service
     * @return array An array of tabs in the format of method => title, or method => array where array contains:
     *
     *  - name (required) The name of the link
     *  - icon (optional) use to display a custom icon
     *  - href (optional) use to link to a different URL
     *      Example:
     *      ['methodName' => "Title", 'methodName2' => "Title2"]
     *      ['methodName' => ['name' => "Title", 'icon' => "icon"]]
     */
    public function getClientServiceTabs($service)
    {
        return [
            'tabClientWhois' => [
                'name' => Language::_('Daftarnama.tab_whois.title', true),
                'icon' => 'fas fa-users'
            ],
            'tabClientNameservers' => [
                'name' => Language::_('Daftarnama.tab_nameservers.title', true),
                'icon' => 'fas fa-server'
            ],
            'tabClientSettings' => [
                'name' => Language::_('Daftarnama.tab_settings.title', true),
                'icon' => 'fas fa-cog'
            ],
            'tabClientDocuments' => [
                'name' => Language::_('Daftarnama.tab_documents.title', true),
                'icon' => 'fas fa-file'
            ]
        ];
    }

    /**
     * Admin Whois tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabWhois($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageWhois('tab_whois', $package, $service, $get, $post, $files);
    }

    /**
     * Client Whois tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabClientWhois($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageWhois('tab_client_whois', $package, $service, $get, $post, $files);
    }

    /**
     * Admin Nameservers tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabNameservers($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageNameservers('tab_nameservers', $package, $service, $get, $post, $files);
    }

    /**
     * Client Nameservers tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabClientNameservers($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageNameservers('tab_client_nameservers', $package, $service, $get, $post, $files);
    }

    /**
     * Admin Settings tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabSettings($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageSettings('tab_settings', $package, $service, $get, $post, $files);
    }

    /**
     * Client Settings tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabClientSettings($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageSettings('tab_client_settings', $package, $service, $get, $post, $files);
    }

    /**
     * Admin Settings tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabDocuments($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageDocuments('tab_documents', $package, $service, $get, $post, $files);
    }

    /**
     * Client Documents tab
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    public function tabClientDocuments($package, $service, array $get = null, array $post = null, array $files = null)
    {
        return $this->manageDocuments('tab_client_documents', $package, $service, $get, $post, $files);
    }

    /**
     * Handle updating whois information
     *
     * @param string $view The view to use
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    private function manageWhois($view, $package, $service, array $get = null, array $post = null, array $files = null)
    {
        $this->view = new View($view, 'default');

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $vars = new stdClass();
        $whois_fields = Configure::get('Daftarnama.whois_fields');
        $fields = $this->serviceFieldsToObject($service->fields);

        if (!empty($post)) {
            // Build contacts array
            Loader::loadHelpers($this, ['DataStructure']);
            $this->Array = $this->DataStructure->create('Array');

            $post = $this->Array->unflatten(
                array_intersect_key($this->Array->flatten($post), $whois_fields)
            );

            $this->setDomainContacts($fields->domain, $post['contact_set'], $package->module_row);

            $vars = (object)$this->Array->flatten($post);
        } else {
            // Build contacts array
            Loader::loadHelpers($this, ['DataStructure']);
            $this->Array = $this->DataStructure->create('Array');

            $contacts = $this->getDomainContacts($fields->domain, $package->module_row);
            $data = ['contact_set' => []];
            $contact_types = ['owner', 'admin', 'tech', 'billing'];
            foreach ($contacts as $index => $contact) {
                $data['contact_set'][$contact_types[$index]] = (array) $contact;
            }
            $data = $this->Array->flatten($data);

            // Format fields
            foreach ($data as $name => $value) {
                // Value must be a string
                if (!is_scalar($value)) {
                    $value = '';
                }
                $vars->{$name} = $value;
            }
        }

        $this->view->set('vars', $vars);
        $this->view->set('fields', $this->arrayToModuleFields($whois_fields, null, $vars)->getFields());
        $this->view->set('sections', ['owner', 'admin', 'tech', 'billing']);
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        return $this->view->fetch();
    }

    /**
     * Handle updating nameserver information
     *
     * @param string $view The view to use
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    private function manageNameservers(
        $view,
        $package,
        $service,
        array $get = null,
        array $post = null,
        array $files = null
    ) {
        $this->view = new View($view, 'default');

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $vars = new stdClass();
        $fields = $this->serviceFieldsToObject($service->fields);

        if (!empty($post)) {
            // Update domain nameservers
            $this->setDomainNameservers($fields->domain, $package->module_row, (isset($post['ns']) ? $post['ns'] : []));

            $vars = (object)$post;
        } else {
            // Get domain nameservers
            $nameservers = $this->getDomainNameServers($fields->domain, $package->module_row);

            $vars->ns = [];
            if (!empty($nameservers)) {
                foreach ($nameservers as $ns) {
                    $vars->ns[] = $ns['url'];
                }
            }
        }

        $this->view->set('vars', $vars);
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        return $this->view->fetch();
    }

    /**
     * Handle updating settings
     *
     * @param string $view The view to use
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    private function manageSettings(
        $view,
        $package,
        $service,
        array $get = null,
        array $post = null,
        array $files = null
    ) {
        try {
            $this->view = new View($view, 'default');

            // Load the helpers required for this view
            Loader::loadHelpers($this, ['Form', 'Html']);

            $row = $this->getModuleRow($package->module_row);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $vars = new stdClass();
            $fields = $this->serviceFieldsToObject($service->fields);

            if (!empty($post)) {
                // Set domain status
                if ($post['registrar_lock'] == 'true') {
                    $this->lockDomain($fields->domain, $package->module_row);
                } else {
                    $this->unlockDomain($fields->domain, $package->module_row);
                }
            }

            $vars->registrar_lock = $this->getDomainIsLocked($fields->domain, $package->module_row) ? 'true' : 'false';

            $vars->epp_code = $api->getDomainInfo($fields->domain)->getEppCode();
        } catch (Exception $e) {
            $vars->epp_code = 'ERROR';
        }

        $this->view->set('vars', $vars);
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        return $this->view->fetch();
    }

    /**
     * Handle documents
     *
     * @param string $view The view to use
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     * @throws Exception
     */
    private function manageDocuments(
        $view,
        $package,
        $service,
        array $get = null,
        array $post = null,
        array $files = null
    ) {
        $this->view = new View($view, 'default');

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $row = $this->getModuleRow($package->module_row);
        $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

        $vars = new stdClass();
        $fields = $this->serviceFieldsToObject($service->fields);

        $domain_info = $api->getDomainInfo($fields->domain);
        $document_status = $domain_info->getDocumentStatus();
        $upload_document_url = $api->getUploadDocumentUrl($fields->domain);

        $this->view->set('document_status', $document_status);
        $this->view->set('upload_document_url', $upload_document_url);
        $this->view->set('vars', $vars);
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'daftarnama' . DS);

        return $this->view->fetch();
    }

    /**
     * Verifies that the provided domain name is available
     *
     * @param string $domain The domain to lookup
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return bool True if the domain is available, false otherwise
     * @throws Exception
     */
    public function checkAvailability($domain, $module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $is_available = $api->getDomainAvailability($domain);
            $this->logRequest('checkAvailability', $api->getLastRequest(), $api->getLastResponse(), true);
            return $is_available;
        } catch (Exception $e) {
            $this->logRequest('checkAvailability', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Verifies that the provided domain name is available for transfer
     *
     * @param string $domain The domain to lookup
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return bool True if the domain is available for transfer, false otherwise
     * @throws Exception
     */
    public function checkTransferAvailability($domain, $module_row_id = null)
    {
        return !$this->checkAvailability($domain, $module_row_id);
    }

    /**
     * Gets the domain expiration date
     *
     * @param stdClass $service The service belonging to the domain to lookup
     * @param string $format The format to return the expiration date in
     * @return string The domain expiration date in UTC time in the given format
     * @throws Exception
     * @see Services::get()
     */
    public function getExpirationDate($service, $format = 'Y-m-d H:i:s')
    {
        try {
            Loader::loadHelpers($this, ['Date']);

            $domain = $this->getServiceDomain($service);
            $module_row_id = isset($service->module_row_id) ? $service->module_row_id : null;

            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $domainInfo = $api->getDomainInfo($domain);
            $expiryDate = $domainInfo->getExpiryDate();

            $this->logRequest('getExpirationDate', $api->getLastRequest(), $api->getLastResponse(), true);

            return isset($expiryDate)
                ? $this->Date->format(
                    $format,
                    $expiryDate
                )
                : false;
        } catch (Exception $e) {
            $this->logRequest('getExpirationDate', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Gets the domain name from the given service
     *
     * @param stdClass $service The service from which to extract the domain name
     * @return string The domain name associated with the service
     * @see Services::get()
     */
    public function getServiceDomain($service)
    {
        if (isset($service->fields)) {
            foreach ($service->fields as $service_field) {
                if ($service_field->key == 'domain') {
                    return $service_field->value;
                }
            }
        }

        return $this->getServiceName($service);
    }

    /**
     * Register a new domain through the registrar
     *
     * @param string $domain The domain to register
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @param array $vars A list of vars to submit with the registration request
     *
     *  - * The contents of $vars vary depending on the registrar
     * @return bool True if the domain was successfully registered, false otherwise
     * @throws Exception
     */
    public function registerDomain($domain, $module_row_id = null, array $vars = [])
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            if (!isset($this->Clients)) {
                Loader::loadModels($this, ['Clients']);
            }

            if (!isset($this->Contacts)) {
                Loader::loadModels($this, array("Contacts"));
            }

            $client = $this->Clients->get(isset($vars['client_id']) ? $vars['client_id'] : null);
            $contact_numbers = $this->Contacts->getNumbers($client->contact_id);
            $client->numbers = $contact_numbers;
            $phone_number = isset($client->numbers) && is_array($client->numbers) ? $this->formatPhone($client->numbers[0]->number, $client->country) : '+62.80000000000';

            $register_data = new RegisterDomainRequest(
                $domain,
                isset($vars['years']) ? $vars['years'] : 1,
                isset($vars['nameservers']) ? $vars['nameservers'] : [],
                $this->generateRandomUsername($client->id),
                substr(base64_encode(md5($client->id_value)), 0, 15),
                $client->first_name . ' ' . $client->last_name,
                $client->company ?: $client->first_name,
                $client->email,
                $client->address1 ?: '-',
                $client->address2 ?: '',
                '',
                $client->city ?: '-',
                $client->state ?: '-',
                $client->country ?: 'ID',
                $client->zip ?: '-',
                $phone_number,
                $phone_number
            );

            $api->registerDomain($register_data);
            $this->logRequest('registerDomain', $api->getLastRequest(), $api->getLastResponse(), true);

            return true;
        } catch (Exception $e) {
            $this->logRequest('registerDomain', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Renew a domain through the registrar
     *
     * @param string $domain The domain to renew
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @param array $vars A list of vars to submit with the renewal request
     *
     *  - * The contents of $vars vary depending on the registrar
     * @return bool True if the domain was successfully renewed, false otherwise
     * @throws Exception
     */
    public function renewDomain($domain, $module_row_id = null, array $vars = [])
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $domainInfo = $api->getDomainInfo($domain);
            $expiryDate = $domainInfo->getExpiryDate();
            $this->logRequest('renewDomain', $api->getLastRequest(), $api->getLastResponse(), true);

            $api->renewDomain($domain, new RenewDomainRequest(
                $expiryDate,
                isset($vars['qty']) ? $vars['qty'] : (isset($vars['years']) ? $vars['years'] : 1)
            ));
            $this->logRequest('renewDomain', $api->getLastRequest(), $api->getLastResponse(), true);

            return true;
        } catch (Exception $e) {
            $this->logRequest('renewDomain', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Transfer a domain through the registrar
     *
     * @param string $domain The domain to register
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @param array $vars A list of vars to submit with the transfer request
     *
     *  - * The contents of $vars vary depending on the registrar
     * @return bool True if the domain was successfully transferred, false otherwise
     * @throws Exception
     */
    public function transferDomain($domain, $module_row_id = null, array $vars = [])
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            if (!isset($this->Clients)) {
                Loader::loadModels($this, ['Clients']);
            }

            if (!isset($this->Contacts)) {
                Loader::loadModels($this, array("Contacts"));
            }

            $client = $this->Clients->get($vars['client_id']);
            $contact_numbers = $this->Contacts->getNumbers($client->contact_id);
            $client->numbers = $contact_numbers;
            $phone_number = isset($client->numbers) && is_array($client->numbers) ? $this->formatPhone($client->numbers[0]->number, $client->country) : '+62.80000000000';

            $transfer_data = new TransferDomainRequest(
                $domain,
                $vars['auth_info'],
                isset($vars['years']) ? $vars['years'] : 1,
                $this->generateRandomUsername($client->id),
                substr(base64_encode(md5($client->id_value)), 0, 15),
                $client->first_name . ' ' . $client->last_name,
                $client->company ?: $client->first_name,
                $client->email,
                $client->address1 ?: '-',
                $client->address2 ?: '',
                '',
                $client->city ?: '-',
                $client->state ?: '-',
                $client->country ?: 'ID',
                $client->zip ?: '-',
                $phone_number,
                $phone_number
            );

            $api->transferDomain($transfer_data);
            $this->logRequest('transferDomain', $api->getLastRequest(), $api->getLastResponse(), true);

            return true;
        } catch (Exception $e) {
            $this->logRequest('transferDomain', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Gets a list of contacts associated with a domain
     *
     * @param string $domain The domain to lookup
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return array A list of contact objects with the following information:
     *
     *  - external_id The ID of the contact in the registrar
     *  - email The primary email associated with the contact
     *  - phone The phone number associated with the contact
     *  - first_name The first name of the contact
     *  - last_name The last name of the contact
     *  - address1 The contact's address
     *  - address2 The contact's address line two
     *  - city The contact's city
     *  - state The 3-character ISO 3166-2 subdivision code
     *  - zip The zip/postal code for this contact
     *  - country The 2-character ISO 3166-1 country code
     * @throws Exception
     */
    public function getDomainContacts($domain, $module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $domain_info = $api->getDomainInfo($domain);
            $this->logRequest('getDomainContacts', $api->getLastRequest(), $api->getLastResponse(), true);
            return [
                [
                    'email' => $domain_info->getRegistrantEmail(),
                    'phone' => $domain_info->getRegistrantPhoneNumber(),
                    'first_name' => $domain_info->getFirstNameFromFullName($domain_info->getRegistrantFullName()),
                    'last_name' => $domain_info->getLastNameFromFullName($domain_info->getRegistrantFullName()),
                    'org_name' => $domain_info->getRegistrantOrganizationName(),
                    'address1' => $domain_info->getRegistrantAddress1(),
                    'address2' => $domain_info->getRegistrantAddress2(),
                    'city' => $domain_info->getRegistrantCity(),
                    'state' => $domain_info->getRegistrantProvince(),
                    'zip' => $domain_info->getRegistrantPostalCode(),
                    'country' => $domain_info->getRegistrantCountryCode()
                ],
                [
                    'email' => $domain_info->getAdminEmail(),
                    'phone' => $domain_info->getAdminPhoneNumber(),
                    'first_name' => $domain_info->getFirstNameFromFullName($domain_info->getAdminFullName()),
                    'last_name' => $domain_info->getLastNameFromFullName($domain_info->getAdminFullName()),
                    'org_name' => $domain_info->getAdminOrganizationName(),
                    'address1' => $domain_info->getAdminAddress1(),
                    'address2' => $domain_info->getAdminAddress2(),
                    'city' => $domain_info->getAdminCity(),
                    'state' => $domain_info->getAdminProvince(),
                    'zip' => $domain_info->getAdminPostalCode(),
                    'country' => $domain_info->getAdminCountryCode()
                ],
                [
                    'email' => $domain_info->getTechEmail(),
                    'phone' => $domain_info->getTechPhoneNumber(),
                    'first_name' => $domain_info->getFirstNameFromFullName($domain_info->getTechFullName()),
                    'last_name' => $domain_info->getLastNameFromFullName($domain_info->getTechFullName()),
                    'org_name' => $domain_info->getTechOrganizationName(),
                    'address1' => $domain_info->getTechAddress1(),
                    'address2' => $domain_info->getTechAddress2(),
                    'city' => $domain_info->getTechCity(),
                    'state' => $domain_info->getTechProvince(),
                    'zip' => $domain_info->getTechPostalCode(),
                    'country' => $domain_info->getTechCountryCode()
                ],
                [
                    'email' => $domain_info->getBillingEmail(),
                    'phone' => $domain_info->getBillingPhoneNumber(),
                    'first_name' => $domain_info->getFirstNameFromFullName($domain_info->getBillingFullName()),
                    'last_name' => $domain_info->getLastNameFromFullName($domain_info->getBillingFullName()),
                    'org_name' => $domain_info->getBillingOrganizationName(),
                    'address1' => $domain_info->getBillingAddress1(),
                    'address2' => $domain_info->getBillingAddress2(),
                    'city' => $domain_info->getBillingCity(),
                    'state' => $domain_info->getBillingProvince(),
                    'zip' => $domain_info->getBillingPostalCode(),
                    'country' => $domain_info->getBillingCountryCode()
                ]
            ];
        } catch (Exception $e) {
            $this->logRequest('getDomainContacts', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return [];
        }
    }

    /**
     * Updates the list of contacts associated with a domain
     *
     * @param string $domain The domain for which to update contact info
     * @param array $vars A list of contact arrays with the following information:
     *
     *  - external_id The ID of the contact in the registrar (optional)
     *  - email The primary email associated with the contact
     *  - phone The phone number associated with the contact
     *  - first_name The first name of the contact
     *  - last_name The last name of the contact
     *  - address1 The contact's address
     *  - address2 The contact's address line two
     *  - city The contact's city
     *  - state The 3-character ISO 3166-2 subdivision code
     *  - zip The zip/postal code for this contact
     *  - country The 2-character ISO 3166-1 country code
     *  - * Other fields required by the registrar
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return bool True if the contacts were updated, false otherwise
     * @throws Exception
     */
    public function setDomainContacts($domain, array $vars = [], $module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            // change key from owner, admin, tech, billing to 0, 1, 2, 3
            $vars = array_values($vars);

            if (isset($vars[0])) {
                $api->updateRegistrantContact($domain, new UpdateContactRequest(
                    $vars[0]['first_name'] . ' ' . $vars[0]['last_name'],
                    isset($vars[0]['org_name']) ? $vars[0]['org_name'] : $vars[0]['first_name'],
                    $vars[0]['email'],
                    $vars[0]['address1'],
                    isset($vars[0]['address2']) ? $vars[0]['address2'] : '',
                    '',
                    $vars[0]['city'],
                    $vars[0]['state'],
                    $vars[0]['country'],
                    $vars[0]['zip'],
                    $vars[0]['phone'],
                    $vars[0]['phone']
                ));
                $this->logRequest('setDomainContacts', $api->getLastRequest(), $api->getLastResponse(), true);
            }

            if (isset($vars[1])) {
                $api->updateAdminContact($domain, new UpdateContactRequest(
                    $vars[1]['first_name'] . ' ' . $vars[1]['last_name'],
                    isset($vars[1]['org_name']) ? $vars[1]['org_name'] : $vars[1]['first_name'],
                    $vars[1]['email'],
                    $vars[1]['address1'],
                    isset($vars[1]['address2']) ? $vars[1]['address2'] : '',
                    '',
                    $vars[1]['city'],
                    $vars[1]['state'],
                    $vars[1]['country'],
                    $vars[1]['zip'],
                    $vars[1]['phone'],
                    $vars[1]['phone']
                ));
                $this->logRequest('setDomainContacts', $api->getLastRequest(), $api->getLastResponse(), true);
            }

            if (isset($vars[2])) {
                $api->updateTechContact($domain, new UpdateContactRequest(
                    $vars[2]['first_name'] . ' ' . $vars[2]['last_name'],
                    isset($vars[2]['org_name']) ? $vars[2]['org_name'] : $vars[2]['first_name'],
                    $vars[2]['email'],
                    $vars[2]['address1'],
                    isset($vars[2]['address2']) ? $vars[2]['address2'] : '',
                    '',
                    $vars[2]['city'],
                    $vars[2]['state'],
                    $vars[2]['country'],
                    $vars[2]['zip'],
                    $vars[2]['phone'],
                    $vars[2]['phone']
                ));
                $this->logRequest('setDomainContacts', $api->getLastRequest(), $api->getLastResponse(), true);
            }

            if (isset($vars[3])) {
                $api->updateBillingContact($domain, new UpdateContactRequest(
                    $vars[3]['first_name'] . ' ' . $vars[3]['last_name'],
                    isset($vars[3]['org_name']) ? $vars[3]['org_name'] : $vars[3]['first_name'],
                    $vars[3]['email'],
                    $vars[3]['address1'],
                    isset($vars[3]['address2']) ? $vars[3]['address2'] : '',
                    '',
                    $vars[3]['city'],
                    $vars[3]['state'],
                    $vars[3]['country'],
                    $vars[3]['zip'],
                    $vars[3]['phone'],
                    $vars[3]['phone']
                ));
                $this->logRequest('setDomainContacts', $api->getLastRequest(), $api->getLastResponse(), true);
            }

            return true;
        } catch (Exception $e) {
            $this->logRequest('setDomainContacts', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Returns whether the domain has a registrar lock
     *
     * @param string $domain The domain to lookup
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return bool True if the domain has a registrar lock, false otherwise
     * @throws Exception
     */
    public function getDomainIsLocked($domain, $module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $domain_info = $api->getDomainInfo($domain);
            $this->logRequest('getDomainIsLocked', $api->getLastRequest(), $api->getLastResponse(), true);
            return $domain_info->isEnableTransferProtection();
        } catch (Exception $e) {
            $this->logRequest('getDomainIsLocked', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Gets a list of name server data associated with a domain
     *
     * @param string $domain The domain to lookup
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return array A list of name servers, each with the following fields:
     *
     *  - url The URL of the name server
     *  - ips A list of IPs for the name server
     * @throws Exception
     */
    public function getDomainNameServers($domain, $module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $domain_info = $api->getDomainInfo($domain);
            $this->logRequest('getDomainNameServers', $api->getLastRequest(), $api->getLastResponse(), true);
            $nameservers = $domain_info->getNameservers();

            $nameserversWithIps = [];
            foreach ($nameservers as $nameserver) {
                $nameserversWithIps[] = [
                    'url' => $nameserver,
                    'ips' => [
                        gethostbyname($nameserver)
                    ]
                ];
            }

            return $nameserversWithIps;
        } catch (Exception $e) {
            $this->logRequest('getDomainNameServers', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return [];
        }
    }

    /**
     * Locks the given domain
     *
     * @param string $domain The domain to lookup
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return bool True if the domain was successfully locked, false otherwise
     * @throws Exception
     */
    public function lockDomain($domain, $module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $api->updateEnableTransferProtection($domain, true);
            $this->logRequest('lockDomain', $api->getLastRequest(), $api->getLastResponse(), true);
            return true;
        } catch (Exception $e) {
            $this->logRequest('lockDomain', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Unlocks the given domain
     *
     * @param string $domain The domain to lookup
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return bool True if the domain was successfully unlocked, false otherwise
     * @throws Exception
     */
    public function unlockDomain($domain, $module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $api->updateEnableTransferProtection($domain, false);
            $this->logRequest('unlockDomain', $api->getLastRequest(), $api->getLastResponse(), true);
            return true;
        } catch (Exception $e) {
            $this->logRequest('unlockDomain', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Restore a domain through the registrar
     *
     * @param string $domain The domain to restore
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @param array $vars A list of vars to submit with the restore request
     *
     *  - * The contents of $vars vary depending on the registrar
     * @return bool True if the domain was successfully restored, false otherwise
     * @throws Exception
     */
    public function restoreDomain($domain, $module_row_id = null, array $vars = [])
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $api->restoreDomain($domain);
            $this->logRequest('restoreDomain', $api->getLastRequest(), $api->getLastResponse(), true);
            return true;
        } catch (Exception $e) {
            $this->logRequest('restoreDomain', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Assign new name servers to a domain
     *
     * @param string $domain The domain for which to assign new name servers
     * @param int|null $module_row_id The ID of the module row to fetch for the current module
     * @param array $vars A list of name servers to assign (e.g. [ns1, ns2])
     * @return bool True if the name servers were successfully updated, false otherwise
     * @throws Exception
     */
    public function setDomainNameservers($domain, $module_row_id = null, array $vars = [])
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            foreach ($vars as $key => $ns) {
                if (empty($ns)) {
                    unset($vars[$key]);
                }
            }

            $api->updateNameservers($domain, $vars);
            $this->logRequest('setDomainNameservers', $api->getLastRequest(), $api->getLastResponse(), true);
            return true;
        } catch (Exception $e) {
            $this->logRequest('setDomainNameservers', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return false;
        }
    }

    /**
     * Get a list of the TLDs supported by the registrar module
     *
     * @param int $module_row_id The ID of the module row to fetch for the current module
     * @return array A list of all TLDs supported by the registrar module
     * @throws Exception
     */
    public function getTlds($module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $row = !empty($row) ? $row : $this->getModuleRows()[0];
            if (!$row) {
                return [];
            }

            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $tlds = $api->getSellingTlds();
            $this->logRequest('getTlds', $api->getLastRequest(), $api->getLastResponse(), true);
            return $tlds;
        } catch (Exception $e) {
            $this->logRequest('getTlds', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return [];
        }
    }

    public function getTldPricing($module_row_id = null)
    {
        try {
            $row = $this->getModuleRow($module_row_id);
            $row = !empty($row) ? $row : $this->getModuleRows()[0];
            if (!$row) {
                return [];
            }

            $api = $this->getApi($row->meta->api_key, $row->meta->is_sandbox == 'true', $row->meta->api_url);

            $tldPricings = $api->getCustomerTldPricings();
            $this->logRequest('getTldPricing', $api->getLastRequest(), $api->getLastResponse(), true);
            return $tldPricings;
        } catch (Exception $e) {
            $this->logRequest('getTldPricing', $api->getLastRequest(), $api->getLastResponse(), false);

            $this->Input->setErrors(
                ['module_row' => ['error' => $e->getMessage()]]
            );

            return [];
        }
    }

    /**
     * @return string
     */
    private function generateRandomUsername($id)
    {
        $domain = $_SERVER['SERVER_NAME'];
        $domain = str_replace('.', '_', $domain);
        return $domain . '_' . $id;
    }

    /**
     * Formats a phone number into +NNN.NNNNNNNNNN
     *
     * @param string $number The phone number
     * @param string $country The ISO 3166-1 alpha2 country code
     * @return string The number in +NNN.NNNNNNNNNN
     */
    private function formatPhone($number, $country)
    {
        if (!isset($this->Contacts)) {
            Loader::loadModels($this, ['Contacts']);
        }

        $number = preg_replace('/[^0-9+]+/', '', $number);

        return trim($this->Contacts->intlNumber($number, $country, '.'));
    }

    /**
     * Logs the API request
     *
     * @param LastRequest $request The daftarnama API object
     * @param array|object|null $response The response
     * @throws Exception
     */
    private function logRequest($function_name, $request, $response, $is_success)
    {
        $this->log($function_name . '|' . $request->getUrl(), serialize($request->getBody()), 'input', true);
        $this->log($function_name . '|' . $request->getUrl(), serialize($response), 'output', $is_success);
    }
}
