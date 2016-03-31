<?php

/**
* This file is part of the Meup TagCommander Bundle.
*
* (c) 1001pharmacies <http://github.com/1001pharmacies/tagcommander-bundle>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Meup\Bundle\TagcommanderBundle\EventDispatcher\Subscriber;

use Meup\Bundle\TagcommanderBundle\DataCollector\DataLayerCollector;
use Meup\Bundle\TagcommanderBundle\EventDispatcher\Subscriber\CollectorSubscriber;
use Meup\Bundle\TagcommanderBundle\EventDispatcher\Event\DeployContainer;
use Meup\Bundle\TagcommanderBundle\EventDispatcher\Event\Track;

/**
 *
 */
class CollectorSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     */
    public function testConstruct()
    {
        $deployContainer = new DeployContainer('name', 'http://');
        $track = new Track('tracker', 'event', array('foo' => 'bar'));

        $collector = $this
            ->getMockBuilder('Meup\Bundle\TagcommanderBundle\DataCollector\DataLayerCollector')
            ->disableOriginalConstructor()
            ->setMethods(array('collectContainer', 'collectEvent'))
            ->getMock()
        ;

        $collector
            ->expects($this->once())
            ->method('collectContainer')
            ->with(
                $this->equalTo($deployContainer->getContainerName()),
                $this->equalTo($deployContainer->getContainerScript()),
                $this->equalTo($deployContainer->getContainerVersion()),
                $this->equalTo($deployContainer->getContainerAlternative())
            )
        ;
        $collector
            ->expects($this->once())
            ->method('collectEvent')
            ->with(
                $this->equalTo($track->getTrackerName()),
                $this->equalTo($track->getEventName()),
                $this->equalTo($track->getValues())
            )
        ;

        $collectorSubscriber = new CollectorSubscriber($collector);
        $collectorSubscriber->onTcContainer($deployContainer);
        $collectorSubscriber->onTcEvent($track);

        $this->assertEquals(
            array('tc_container', 'tc_event'),
            array_keys($collectorSubscriber->getSubscribedEvents())
        );
    }
}
