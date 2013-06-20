<?php  namespace Chekun\Bcms;

Interface BcmsInterface
{
    /**
     * 百度云消息API地址
     */
    const API_HOST = 'http://bcms.api.duapp.com/rest/2.0/bcms/';

    /**
     * 百度云消息HOST头
     */
    const HEADER_HOST = 'bcms.api.duapp.com';

    /**
     * 创建队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#create
     */
    public function create();

    /**
     * 删除队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#drop
     */
    public function drop($queueName = '');

    /**
     * 注册订阅队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#subscribe
     */
    public function subscribe($destination, $queueName = '');

    /**
     * 取消注册订阅队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#unsubscribe
     */
    public function unSubscribe($destination, $queueName = '');

    /**
     * 取消全部注册订阅队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#unsubscribeall
     */
    public function unSubscribeAll($queueName = '');

    /**
     * 授权分享队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#grant
     */
    public function grant($params = array(), $queueName = '');

    /**
     * 回收授权分享队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#revoke
     */
    public function revoke($label = '', $queueName = '');

    /**
     * 暂停队列消息存取功能
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#suspend
     */
    public function suspend($queueName = '');

    /**
     * 恢复队列存取功能
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#resume
     */
    public function resume($queueName = '');

    /**
     * 发布单条消息到队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#publish
     */
    public function publish($message = '', $queueName = '');

    /**
     * 发布多条消息到队列
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#publishes
     */
    public function multiPublish($messages = array(), $queueName = '');

    /**
     * 获取消息
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#fetch
     */
    public function fetch($queueName = '');

    /**
     * 发邮件
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#mail
     */
    public function mail($params = array(), $queueName = '');

    /**
     * 通过token确认注册订阅生效
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#confirm
     */
    public function confirm($destination, $token, $queueName = '');

    /**
     * 通过token取消注册订阅
     * @url http://developer.baidu.com/wiki/index.php?title=docs/cplat/mq/api#confirm
     */
    public function cancel($destination, $token, $queueName = '');

}
