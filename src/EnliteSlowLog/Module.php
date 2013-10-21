<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace EnliteSlowLog;

use Zend\EventManager\EventInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\Mvc\MvcEvent;

class Module implements BootstrapListenerInterface
{

    /**
     * @var float
     */
    protected $start;

    /**
     * Listen to the bootstrap event
     *
     * @param MvcEvent|EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $this->start = microtime(true);

        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
    }

    /**
     * @param MvcEvent $e
     * @throws \RuntimeException
     */
    public function onFinish(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $config = $serviceManager->get('config');

        if (!isset($config['EnliteSlowLog']['logger'])) {
            return;
        }

        $threshold = 1000;
        if (isset($config['EnliteSlowLog']['threshold'])) {
            $threshold = $config['EnliteSlowLog']['threshold'];
        }

        $elapse = microtime(true) - $this->start;

        if ($elapse > $threshold) {
            $request = $serviceManager->get('request');
            if (!$request instanceof Request) {
                return;
            }

            $logger = $serviceManager->get($config['EnliteSlowLog']['logger']);
            $message = sprintf(
                "slow %s %s (%fms)",
                $request->getMethod(),
                $request->getUriString(),
                $elapse
            );

            if ($logger instanceof LoggerInterface) {
                $logger->warn($message);
            } else {
                if (class_exists('Psr\Log\LoggerInterface') && $logger instanceof \Psr\Log\LoggerInterface) {
                    $logger->warning($message);
                } else {
                    throw new \RuntimeException("Cannot log slow pages, unknown type of logger");
                }
            }
        }
    }
}