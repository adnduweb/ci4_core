<?php

namespace Adnduweb\Ci4Core\Traits;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Config\Services;
use \DrewM\MailChimp\MailChimp;
use \GuzzleHttp;
use \SendinBlue;
use Adnduweb\Ci4Core\Exceptions\DataException;

trait EmailTrait
{

    public $mailChimp;

    public $mailchimpApiKey;

    public $mailChimpListId1; // $this->mailChimpListId = service('Settings')->setting_mailchimp_id_list_1;

    public function SendEmail(object $dataPost, $template = '')
    {

        $email = \Config\Services::email();

        $email->setFrom($this->company->email, $this->company->raison_social);
        $email->setTo($dataPost->email, (isset($dataPost->nom)) ? $dataPost->nom : '');

        // Mode DEBUG
        if (service('Settings')->setting_email_bcc)
            $email->setBCC(service('Settings')->setting_email_bcc);

        $email->setSubject($dataPost->subject);
        if (!empty($template)) {
            $email->setMessage($template);
        } else {
            $email->setMessage($dataPost->message);
        }


        try {
            $email->send();
        } catch (\Exception $e) {
            return $this->failResourceExists($e->getMessage());
        }
    }

    public function SendEmailAdmin(object $dataPost, $template = '')
    {

        $email = \Config\Services::email();

        $email->setFrom($this->company->email, $this->company->raison_social);
        $email->setTo($this->company->email);
        $email->setReplyTo($dataPost->email, (isset($dataPost->nom)) ? $dataPost->nom : '');

        // Mode DEBUG
        if (service('Settings')->setting_email_bcc)
            $email->setBCC(service('Settings')->setting_email_bcc);

        $email->setSubject($dataPost->subject);
        if (!empty($template)) {
            $email->setMessage($template);
        } else {
            $email->setMessage($dataPost->message);
        }


        try {
            $email->send();
        } catch (\Exception $e) {
            return $this->failResourceExists($e->getMessage());
        }
    }

    protected function mailChimpConnect()
    {
        $this->mailchimpApiKey = service('Settings')->setting_mailchimp_api_key;
        $this->mailChimp = new MailChimp($this->mailchimpApiKey);
    }

    public function mailChimpAllList()
    {
        $this->mailChimpConnect();
        $result =  $this->mailChimp->get('lists');
        print_r($result);
    }

    public function mailChimpList(string $list_id)
    {

        $this->mailChimpConnect();
        $result =  $this->mailChimp->get("lists/$list_id");
        print_r($result);
    }

    public function mailChimpListMembers(string $list_id)
    {

        $this->mailChimpConnect();
        $result =  $this->mailChimp->get("lists/$list_id/members");
        print_r($result);
    }

    public function mailChimpPost($pfb_data)
    {

        $this->mailChimpConnect();

        $pfb_data = array(
            'email_address' => $email,
            'status'        => 'subscribed',
            'merge_fields'  => array(
                'FNAME'       => $fname,
                'LNAME'       => $lname,
                'MMERGE3'     => $pays,
                'MMERGE4'     => $id_currency,
            ),
        );

        $result = $this->mailChimp->post("lists/$list_id/members", $pfb_data);

        print_r($result);
    }

    /*    print_r($this->mailChimpList(service('Settings')->setting_mailchimp_id_list_1));*/

    protected function sendinBlueConnect()
    {

        // Configure API key authorization: api-key
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', service('Settings')->setting_sendinblue_api_key);
        // Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
        // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('api-key', 'Bearer');
        // Configure API key authorization: partner-key
        //$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', 'YOUR_API_KEY');
        // Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
        // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('partner-key', 'Bearer');

        $apiInstance = new SendinBlue\Client\Api\AccountApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getAccount();
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling AccountApi->getAccount: ', $e->getMessage(), PHP_EOL;
        }
    }
}
