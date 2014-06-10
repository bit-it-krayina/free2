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
		error_log(
	print_r(
		array(
			$name, $value,isset ( $this->$name)

	), true));
		if ( isset ( $this->$name) )
		{
			$this->$name = $value;
		}
	}
}
