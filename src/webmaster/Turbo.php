<?php

namespace c7v\yii_yandex_turbo_pages_api\webmaster;

use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\Response;

class Turbo extends Component
{
    const BASE_URL = 'https://api.webmaster.yandex.net/v4/';

    /** @var Client */
    public $client;
    /** @var Request */
    public $request;
    /** @var Response */
    public $response;
    /** @var string */
    public $token;
    /** @var string */
    public $host;

    public function __construct(array $config = [])
    {
        $this->token = $config['token'];
        if ($this->token == null) {
            throw new \Exception('Не указан token', 0);
        }

        $this->host = $config['host'];
        if ($this->host == null) {
            throw new \Exception('Не указан host', 0);
        }

        $this->client = new Client([
            'baseUrl' => self::BASE_URL,
        ]);

        $this->request = $this->client->createRequest();
        $this->request = $this->request->setMethod('GET')->addHeaders([
            'Authorization' => 'OAuth ' . $this->token
        ]);
    }

    /**
     * @return mixed
     * @throws \yii\httpclient\Exception
     */
    public function getIdUser()
    {
        $this->response = $this->request->setUrl('user')->send();

        if ($this->response->getIsOk()) {
            return $this->response->getData()['user_id'];
        }
        throw new \Exception($this->response->getData()['error_message']);
    }

    public function getStatus(int $id_user, string $status)
    {
        $this->response = $this->request->setUrl('user/' . $id_user . '/hosts/' . $this->host . '/turbo/tasks/' . $status)->send();

        if ($this->response->getIsOk()) {
            return $this->response->getData();
        }
        throw new \Exception($this->response->getData()['error_message']);
    }

    /**
     * @param int $id_user
     * @return mixed
     * @throws \yii\httpclient\Exception
     */
    public function getUploadAddress(int $id_user)
    {
        $this->response = $this->request
            ->setUrl('user/' . $id_user . '/hosts/' . $this->host . '/turbo/uploadAddress?mode=PRODUCTION')
            ->send();

        if ($this->response->getIsOk()) {
            return $this->response->getData();
        }
        throw new \Exception($this->response->getData()['error_message']);
    }

    /**
     * @param string $url
     * @param string $dir_file
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function uploadFile(string $url, string $dir_file)
    {
        $this->response = $this->request->setFullUrl($url)
            ->setMethod('POST')
            ->addHeaders([
                'content-type' => 'application/rss+xml',
            ])->setContent(file_get_contents($dir_file))->send();

        if ($this->response->getIsOk()) {
            return $this->response->getData();
        }
        throw new \Exception($this->response->getData()['error_message']);
    }
}