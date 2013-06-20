<?php  namespace Chekun\Bcms;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Message\RequestInterface;
use Chekun\Bcms\Exception\InvalidQueueNameException;

class Bcms implements BcmsInterface
{

    private $clientId = '';
    private $clientSecret = '';
    private $accessToken = '';
    private $queueName = '';
    private $client = '';

    public function __construct($clientId, $clientSecret, $queueName = '', $accessToken = '')
    {
        if (! $clientId or ! $clientSecret) {
            throw new \InvalidArgumentException();
        }
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken = $accessToken;
        if ($queueName) {
            $this->queueName = $queueName;
        }
        $this->client = new Browser(new Curl());
    }

    public function create()
    {
        return $this->request('queue', 'create');
    }

    public function drop($queueName = '')
    {
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'drop');
    }

    public function subscribe($destination, $queueName = '')
    {
        if (! $destination) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'subscribe', array('destination' => $destination));
    }

    public function unSubscribe($destination, $queueName = '')
    {
        if (! $destination) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'unsubscribe', array('destination' => $destination));
    }

    public function unSubscribeAll($queueName = '')
    {
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'unsubscribeall');
    }

    public function grant($params = array(), $queueName = '')
    {
        if (empty($params)) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'grant', $params);
    }

    public function revoke($label = '', $queueName = '')
    {
        if (! $label) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'revoke', array('label' => $label));
    }

    public function suspend($queueName = '')
    {
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'suspend');
    }

    public function resume($queueName = '')
    {
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'resume');
    }

    public function publish($message = '', $queueName = '')
    {
        if (! $message) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'publish', array('message' => $message));
    }

    public function multiPublish($messages = array(), $queueName = '')
    {
        if (! is_array($messages) or empty($messages) ) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        $message = json_encode($messages);
        return $this->request($queueName, 'publishes', array('message' => $message));
    }

    public function fetch($queueName = '')
    {
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'fetch');
    }

    public function mail($params = array(), $queueName = '')
    {
        if (! is_array($params) or empty($params)) {
            throw new \InvalidArgumentException();
        }
        return $this->request($queueName, 'mail', $params);
    }

    public function confirm($destination, $token, $queueName = '')
    {
        if (! $destination or ! $token) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'confirm', array('destination' => $destination, 'token' => $token));
    }

    public function cancel($destination, $token, $queueName = '')
    {
        if (! $destination or ! $token) {
            throw new \InvalidArgumentException();
        }
        $queueName = $this->resolveQueueName($queueName);
        return $this->request($queueName, 'cancel', array('destination' => $destination, 'token' => $token));
    }

    private function resolveQueueName($queueName)
    {
        if ($queueName) {
            return $queueName;
        } elseif (! $this->queueName) {
            throw new InvalidQueueNameException;
        }
        return $this->queueName;
    }

    private function request($uri, $action, $messages = array())
    {
        $postData = $this->buildParams($uri, $action, $messages);
        $headers = $this->buildHeaders();
        $response = $this->client->submit(BcmsInterface::API_HOST . $uri, $postData, RequestInterface::METHOD_POST, $headers);
        $status = $response->getStatusCode();
        $content = json_decode($response->getContent());
        if ($status != 200)
        {
            throw new \Exception($content->error_msg, $content->error_code);
        }
        return (isset($content->response_params) ? $content->response_params : true);
    }

    private function buildParams($uri, $action, $messages = array())
    {
        $params = array();
        $params['client_id'] = $this->clientId;
        if ($this->accessToken) {
            $params['access_token'] = $this->accessToken;
        }
        $params['method'] = $action;
        if ($messages) {
            foreach ($messages as $key => $message) {
                $params[$key] = $message;
            }
        }
        $params['timestamp'] = time();
        $basicString = RequestInterface::METHOD_POST . BcmsInterface::API_HOST . $uri;
        asort($params);
        foreach ($params as $key => $param) {
            $basicString .= $key . '=' . $param;
        }
        $basicString .= $this->clientSecret;
        $params['sign'] = md5(urlencode($basicString));
        return $params;
    }

    private function buildHeaders()
    {
        $headers['Host'] = BcmsInterface::HEADER_HOST;
        return $headers;
    }

}
