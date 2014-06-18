<?php

namespace CsnUser\Entity\Extras;

/**
 *
 * @author mice
 */
trait MagicTrait
{

	public function __set($name, $value)
	{
//		if ( isset ( $this->$name) )
		{
			$this->$name = $value;
		}
	}
}
