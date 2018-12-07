<?php

declare(strict_types=1);

namespace NAttreid\Comgate\Helpers\Response;

use NAttreid\Comgate\Helpers\Exceptions\ComgateException;

/**
 * Class RefundResponse
 *
 * @author Attreid <attreid@gmail.com>
 */
class RefundResponse extends Response
{

	/**
	 * @return bool
	 * @throws ComgateException
	 */
	public function isOk(): bool
	{
		$code = $this->getParam('code');
		$message = $this->getParam('message');

		if ($code !== '0' || $message !== 'OK') {
			return false;
		}
		return true;
	}
}