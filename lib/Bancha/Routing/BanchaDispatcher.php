<?php
/**
 * @copyright     Copyright 2011 Bancha Project
 * @link          http://banchaproject.org Bancha Project
 * @since         Bancha v1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author        Florian Eckerstorfer <f.eckerstorfer@gmail.com>
 */

App::uses('Dispatcher', 'Routing');

/**
 * BanchaDispatcher
 *
 * @package bancha.libs
 */
class BanchaDispatcher {

/**
 * Dispatches a BanchaRequest object. It uses the standard CakePHP dispatcher to dispatch the single CakeRequest
 * objects returned by BanchaRequest. Further it uses BanchaResponse to transform the responses into a single
 * CakeResponse object. If the 'return' option in the $additionalParams argument is TRUE, the body of the response is
 * returned instead of directly sent to the browser.
 *
 * @param BanchaRequest $requests A BanchaRequest can contain multiple CakeRequest objects.
 * @param array $additionalParams If 'return' is TRUE, the body is returned instead of sent to the browser.
 * @return string|void If 'return' is TRUE, the body is returned otherwise void is returned.
 */
	public function dispatch(BanchaRequest $requests, $additionalParams = array()) {
		$transformer = new BanchaResponse();
		
		// Iterate through all requests, dispatch them and add the response to the transformer object.
		foreach ($requests->getRequests() as $request) {
			// Call dispatcher for the given CakeRequest.
			$dispatcher = new BanchaSingleDispatcher();
			$transformer->addResponse($dispatcher->dispatch($request, array('return' => true)));
		}
		
		// Combine the responses and return or output them.
		$responses = $transformer->getResponses();
		if (isset($additionalParams['return']) && $additionalParams['return']) {
			return $responses->body();
		}
		$responses->send();
	}

}
