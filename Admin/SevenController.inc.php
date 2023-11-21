<?php use Sms77\Api\Client;

require_once __DIR__ . '/../vendor/autoload.php';

MainFactory::load_class('AdminHttpViewController');

class SevenController extends AdminHttpViewController {
    /** @var CI_DB_query_builder $db */
    private $db;

    /** @var string[] $errors */
    private $errors;

    /** @var string[] $infos */
    private $infos;

    /** @var LanguageTextManager $languageTextManager */
    private $languageTextManager;

    public function __construct(HttpContextReaderInterface $httpContextReader,
                                HttpResponseProcessorInterface $httpResponseProcessor,
                                ContentViewInterface $defaultContentView
    ) {
        parent::__construct($httpContextReader, $httpResponseProcessor, $defaultContentView);

        /** @var GXCoreLoader $gxCoreLoader */
        $gxCoreLoader = MainFactory::create(
            'GXCoreLoader', MainFactory::create('GXCoreLoaderSettings'));
        $this->db = $gxCoreLoader->getDatabaseQueryBuilder();

        $this->languageTextManager = MainFactory::create('LanguageTextManager', 'seven', $_SESSION['languages_id']);
    }

    private function getApiKey() {
        return gm_get_conf('SEVEN_API_KEY');
    }

    private function findCustomerCountryById($id) {
        /** @var CustomerCountryReader $customerCountryReader */
        $customerCountryReader = MainFactory::create('CustomerCountryReader', $this->db);
        return $customerCountryReader->findById($id);
    }

    private function filterCustomersByArray(array $filters) {
        /** @var CustomerServiceFactory $factory */
        $factory = MainFactory::create('CustomerServiceFactory', $this->db);
        return $factory->createCustomerReadService()->filterCustomers($filters);
    }

    private function searchCustomersByCondition(CustomerSearchCondition $condition) {
        /** @var CustomerServiceFactory $factory */
        $factory = MainFactory::create('CustomerServiceFactory', $this->db);
        return $factory->createCustomerReadService()->searchCustomers($condition);
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

    private function getLanguageCode() {
        return new LanguageCode(new NonEmptyStringType(
            $this->_getQueryParameter('lang') !== null
                ? $this->_getQueryParameter('lang') : 'de'));
    }

    public function actionDefault() {
        return MainFactory::create('AdminLayoutHttpControllerResponse',
            new NonEmptyStringType($this->languageTextManager->get_text('BULK_SMS_TITLE')),
            $this->getTemplateFile('bulk_sms.html'),
            MainFactory::create('KeyValueCollection', [
                'action' => xtc_href_link('admin.php', 'do=Seven/BulkSms'),
                'apiKey' => $this->getApiKey(),
                'customerGroups' => $this->getCustomerGroups(),
                'errors' => $this->errors,
                'infos' => $this->infos,
                'languageCode' => $this->getLanguageCode(),
                'settingsLink' => xtc_href_link('admin.php', 'do=SevenModuleCenterModule'),
            ]),
            $this->_getAssets(),
            MainFactory::create('ContentNavigationCollection', []));

    }

    /** @return \AssetCollection|bool */
    protected function _getAssets() {
        /** @var AssetCollection $assets */
        $assets = MainFactory::create('AssetCollection');

        return $assets;
    }

    public function actionBulkSms() {
        require_once 'TextPhrases/' . $_SESSION['language'] . '/seven.lang.inc.php';

        $filters = [];
        if ('' !== $_POST['filter_customer_group'])
            $filters['customers_status'] = $_POST['filter_customer_group'];
        $customers = $this->filterCustomersByArray($filters);

        if (empty($customers))
            $this->errors[] = $this->languageTextManager->get_text('NO_RECIPIENTS');
        else {
            $client = (new Client($this->getApiKey(), 'gambio'));
            $params = [
                'flash' => isset($_POST['flash']),
                'foreign_id' => '' === $_POST['foreign_id'] ? null : $_POST['foreign_id'],
                'from' => '' === $_POST['from'] ? null : $_POST['from'],
                'no_reload' => isset($_POST['no_reload']),
                'label' => '' === $_POST['label'] ? null : $_POST['label'],
                'json' => true,
                'performance_tracking' => isset($_POST['performance_tracking']),
            ];
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
                    $this->infos[] = $client->sms($to, $text, $params);
                } catch (Exception $e) {
                    $this->errors[] = $e->getMessage();
                }
            }
        }

        $this->actionDefault();
    }
}
