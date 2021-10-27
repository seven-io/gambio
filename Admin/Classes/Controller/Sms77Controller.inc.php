<?php use Sms77\Api\Client;

require_once __DIR__ . '/../../../vendor/autoload.php';

MainFactory::load_class('AdminHttpViewController');

class Sms77Controller extends AdminHttpViewController {
    /** @var CI_DB_query_builder $db */
    private $db;

    /** @var string[] $errors */
    private $errors;

    /** @var string[] $infos */
    private $infos;

    /** @var LanguageTextManager $languageTextManager */
    private $languageTextManager;

    public function __construct(HttpContextReaderInterface     $httpContextReader,
                                HttpResponseProcessorInterface $httpResponseProcessor,
                                ContentViewInterface           $defaultContentView
    ) {
        parent::__construct($httpContextReader, $httpResponseProcessor, $defaultContentView);

        /** @var GXCoreLoader $gxCoreLoader */
        $gxCoreLoader = MainFactory::create(
            'GXCoreLoader', MainFactory::create('GXCoreLoaderSettings'));
        $this->db = $gxCoreLoader->getDatabaseQueryBuilder();

        $this->languageTextManager = MainFactory::create(
            'LanguageTextManager', 'sms77', $_SESSION['languages_id']);
    }

    /**
     * @return array
     */
    private function getMessageParams($type) {
        $params = [
            'debug' => isset($_POST['debug']),
            'from' => '' === $_POST['from'] ? null : $_POST['from'],
            'json' => true,
        ];

        if ('sms' === $type) {
            $params = array_merge($params, [
                'flash' => isset($_POST['flash']),
                'foreign_id' => '' === $_POST['foreign_id'] ? null : $_POST['foreign_id'],
                'no_reload' => isset($_POST['no_reload']),
                'label' => '' === $_POST['label'] ? null : $_POST['label'],
                'performance_tracking' => isset($_POST['performance_tracking']),
            ]);
        }
        else {
            $params = array_merge($params, [
                'xml' => isset($_POST['xml']),
            ]);
        }

        return $params;
    }

    public function actionBulkSms() {
        $filters = [];
        if ('' !== $_POST['filter_customer_group'])
            $filters['customers_status'] = $_POST['filter_customer_group'];
        $customers = $this->filterCustomersByArray($filters);

        if (empty($customers))
            $this->errors[] = $this->languageTextManager->get_text('NO_RECIPIENTS');
        else {
            $client = (new Client($this->getApiKey(), 'gambio'));
            $type = $_POST['msg_type'];
            $params = $this->getMessageParams($type);
            $excludedProps = ['addonValues', 'defaultAddress', 'password'];
            foreach ($customers as $customer) {
                /** @var Customer $customer */
                $to = (string)$customer->getTelephoneNumber();
                if ('' === $to) continue;

                $text = $_POST['text'];
                foreach ((new ReflectionClass($customer))->getProperties() as $property) {
                    $name = $property->getName();
                    if (in_array($name, $excludedProps)) continue;
                    $search = '{{' . $name . '}}';
                    if (false === strpos($text, $search)) continue;

                    $property->setAccessible(true);
                    $value = $property->getValue($customer);

                    if (is_bool($value))
                        $value = true === $value ? 'true' : 'false';
                    elseif ($value instanceof CustomerDateOfBirth)
                        $value = $value->format('d-m-Y H:i:s');
                    elseif ('' === (string)$value)
                        continue;

                    $text = str_replace($search, $value, $text);
                }

                try {
                    $this->infos[] = $client->{$type}($to, $text, $params);
                } catch (Exception $e) {
                    $this->errors[] = $e->getMessage();
                }
            }
        }

        $this->actionDefault();
    }

    private function filterCustomersByArray(array $filters) {
        /** @var CustomerServiceFactory $factory */
        $factory = MainFactory::create('CustomerServiceFactory', $this->db);
        return $factory->createCustomerReadService()->filterCustomers($filters);
    }

    private function getApiKey() {
        return gm_get_conf('SMS77_API_KEY');
    }

    public function actionDefault() {
        return MainFactory::create('AdminLayoutHttpControllerResponse',
            new NonEmptyStringType($this->languageTextManager->get_text('BULK_MSG_TITLE')),
            $this->getTemplateFile('bulk_messaging.html'),
            MainFactory::create('KeyValueCollection', [
                'action' => xtc_href_link('admin.php', 'do=Sms77/BulkSms'),
                'apiKey' => $this->getApiKey(),
                'customerGroups' => $this->getCustomerGroups(),
                'errors' => $this->errors,
                'infos' => $this->infos,
                'languageCode' => $this->getLanguageCode(),
                'settingsLink' => xtc_href_link('admin.php', 'do=Sms77ModuleCenterModule'),
            ]),
            MainFactory::create('AssetCollection'),
            MainFactory::create('ContentNavigationCollection', [])
        );
    }

    private function getCustomerGroups() {
        /** @var CustomerGroupServiceFactory $customerGroupServiceFactory */
        $customerGroupServiceFactory = MainFactory::create(
            'CustomerGroupServiceFactory', $this->db);
        /** @var CustomerGroup[] $groups */
        $groups = $customerGroupServiceFactory->createReadService()->getAll()->getArray();

        foreach ($groups as $group)
            $groups[$group->getId()] = $group->getName($this->getLanguageCode());

        return $groups;
    }

    private function getLanguageId() {
        return (new LanguageProvider($this->db))->getIdByCode($this->getLanguageCode());
    }

    private function getLanguageCode() {
        return new LanguageCode(new NonEmptyStringType(
            $this->_getQueryParameter('lang') !== null
                ? $this->_getQueryParameter('lang') : 'de'));
    }

    private function findCustomerCountryById($id) {
        /** @var CustomerCountryReader $customerCountryReader */
        $customerCountryReader = MainFactory::create('CustomerCountryReader', $this->db);
        return $customerCountryReader->findById($id);
    }

    private function searchCustomersByCondition(CustomerSearchCondition $condition) {
        /** @var CustomerServiceFactory $factory */
        $factory = MainFactory::create('CustomerServiceFactory', $this->db);
        return $factory->createCustomerReadService()->searchCustomers($condition);
    }
}
