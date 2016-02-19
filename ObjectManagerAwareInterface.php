<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\ORM;

use Doctrine\Common\Persistence\ObjectManager;

interface ObjectManagerAwareInterface
{
    public function setObjectManager(ObjectManager $objectManager);

    public function getObjectManager();
}
