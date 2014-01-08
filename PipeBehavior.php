<?php

/**
 * Pipe owner`s method to allow more chained call style.
 *
 * For example owner has method <em>gimmeAll</em>, that returns array that we want to transform by another owner`s method, let it be <em>toSomething</em>. In old style we call:
 * <pre>
 *     $bla = Something::create()->toSomething(Something::create()->one()->two()->three()->gimmeAll());
 * </pre>
 *
 * But with this behavior we can do this in more elegant way:
 * <pre>
 *     $bla = Something::create()->one()->two()->three()->pipe('gimmeAll')->unpipe('toSomething', '{r}');
 * </pre>
 *
 * If unpiped method has single parameter, then we can omit '{r}' parameter and call it like:
 * <pre>
 *     ..->unpipe('toSomething')
 * </pre>
 *
 * @version 0.1
 * @category Syntactic sugar for method chaining fanats
 * @see http://en.wikipedia.org/wiki/Method_chaining
 * @author Ustimenko Alexander <a@ustimen.co>
 * @copyright 2014 Ustimenko Alexander <a@ustimen.co>
 */
class PipeBehavior extends CBehavior {

	/**
	 * Result of pipe operation, that stored internally and substituted during unpipe operation instead of parameter with value '{r}'
	 *
	 * @var mixed
	 */
	private $_result;

	/**
	 * Call owner`s method, saving result internally and returning owner to chain next methods.
	 *
	 * @param string $method Method, that will be piped and who`s result will be saved
	 * @param mixed $params,... Optional variable length parameters to piped method
	 * @return CComponent the owner component that this behavior is attached to.
	 */
	public function pipe($method, $params = null) {
		$arguments = func_get_args();
		array_shift($arguments);
		$this->_result = call_user_func_array(array(
			$this->getOwner(),
			$method
		), $arguments);
		return $this->getOwner();
	}

	/**
	 * Unpipe method, using internally saved result as one of parameters (substituted as '{r}').
	 * Parameters to unpiped method always expected and if they omited, it's means, that we passing single '{r}' parameter.
	 *
	 * @param string $method Method, that will use saved result as one of it's parameter
	 * @param mixed $params,... Optional variable length parameters to unpiped method
	 * @return mixed The result of unpiped method
	 */
	public function unpipe($method, $params = null) {
		$arguments = func_get_args();
		array_shift($arguments);
		if (empty($arguments)) {
			$arguments = array($this->_result);
		} else {
			foreach ($arguments as &$arg) {
				if ($arg === '{r}') {
					$arg = $this->_result;
				}
			}
		}
		$result = call_user_func_array(array(
			$this->getOwner(),
			$method
		), $arguments);
		$this->_result = null;
		return $result;
	}

}
