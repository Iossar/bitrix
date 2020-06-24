<?php

namespace app\controllers\bitrix;

use app\models\User;
use app\models\Lead;
use app\models\LeadSearch;
use Yii;
use yii\httpclient\Client;
use yii\web\Controller;

class RestController extends Controller
{
    protected $domain;
    protected $auth_id;

    public function __construct($id, $module, $config = [])
    {
        $this->domain = 'https://' . $_REQUEST['DOMAIN'];
        $this->auth_id = $_REQUEST['AUTH_ID'];
        parent::__construct($id, $module, $config);
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionCallback()
    {
        $external_user = $this->getUser();
        if ($external_user != null) {
            $internal_user = User::login($external_user);
            $this->getLeads($internal_user);
            $this->actionRender($internal_user);
        }
    }

    public function actionRender($user)
    {
        $leads = Lead::find()->where(['user_id' => $user->id])->orderBy('id ASC')->asArray()->all();
        if ($leads == null) {
            $leads = $user->leads;
        }
        $this->out($leads);
    }

    public function out($leads)
    {
        $this->render('leads', compact('leads'));
    }


    private function getUser()
    {
        $uri = $this->domain . '/rest/user.current';
        $params = ['auth' => $this->auth_id];

        return $this->request($uri, $params);
    }

    private function getLeads($internal_user)
    {
        $leads = $this->leadsRequest($internal_user->id);
        $i = 0;
        foreach ($leads as $lead) {
            if ($i <= 4) {
                $internal_lead = Lead::find()->where(['lead_id' => $lead["ID"]])->one();
                if ($internal_lead != null) {
                    $internal_lead->name = $lead["TITLE"];
                    $internal_lead->status = $lead['STATUS_ID'];
                    $internal_lead->update();
                } else {
                    $model = new Lead();
                    $model->user_id = $internal_user->id;
                    $model->lead_id = $lead['ID'];
                    $model->name = $lead["TITLE"];
                    $model->status = $lead["STATUS_ID"];
                    $model->save();
                }
                $i++;
            }
        }
    }

    private function leadsRequest($user_id)
    {
        $uri = $this->domain . '/rest/crm.lead.list';
        $params = ['auth' => $this->auth_id];
        $params['filter'] = [
            'CREATED_BY_ID' => $user_id,
        ];
        $params['order'] = [
            'TITLE' => 'ASC'
        ];
        $params['SELECT'] = [
            'ID', 'TITLE', 'STATUS_ID'
        ];
        return $this->request($uri, $params);
    }

    private function request(string $uri, array $params = [])
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($uri)
            ->setData($params)
            ->send();
        return $response->data['result'];
    }


}
