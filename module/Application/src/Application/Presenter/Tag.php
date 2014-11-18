<?php

namespace Application\Presenter;

use Application\Entity\Info\Tag as TagModel;

/**
 * Description of Tag
 *
 * @author mice
 */
class Tag implements PresenterInterface
{
	public function __toArray()
	{
		return [];
	}
}
