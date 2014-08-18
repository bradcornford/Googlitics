<?php namespace Cornford\Googlitics;

use Cornford\Googlitics\Contracts\AnalyticalInterface;
use Cornford\Googlitics\Exceptions\AnalyticsArgumentException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class Analytics extends AnalyticsBase implements AnalyticalInterface {

	/**
	 * Renders and returns Google Analytics code.
	 *
	 * @return string
	 */
	public function render()
	{
		if (!$this->isEnabled()) {
			return;
		}

		if ($this->isAutomatic()) {
			$this->addItem("ga('send', '" . self::TYPE_PAGEVIEW . "');");
		}

		if ($this->isAnonymised()) {
			$this->addItem("ga('set', 'anonymizeIp', true);");
		}

		if ($this->application->environment() === 'dev') {
			$this->addItem("ga('create', '{$this->id}', { 'cookieDomain': 'none' });");
		} else {
			$this->addItem("ga('create', '{$this->id}', '{$this->domain}');");
		}

		return $this->view->make('googlitics::analytics')->withItems(array_reverse($this->getItems()))->render();
	}

	/**
	 * Track a page view.
	 *
	 * @param string $page
	 * @param string $title
	 * @param string $type
	 *
	 * @throws AnalyticsArgumentException
	 *
	 * @return void
	 */
	public function trackPage($page = null, $title = null, $type = self::TYPE_PAGEVIEW)
	{
		if (!defined('self::TYPE_'. strtoupper($type))) {
			throw new AnalyticsArgumentException('Type variable can\'t be of this type.');
		}

		$item = "ga('send', 'pageview');";

		if ($page !== null || $title !== null) {
			$page = ($page === null ? "window.location.href" : "'{$page}'");
			$title = ($title === null ? "document.title" : "'{$title}'");
			$item = "ga('send', { 'hitType': '{$type}', 'page': {$page}, 'title': {$title} });";
		}

		$this->addItem($item);
	}

	/**
	 * Track a screen view.
	 *
	 * @param string $name
	 *
	 * @throws AnalyticsArgumentException
	 *
	 * @return void
	 */
	public function trackScreen($name)
	{
		$item = "ga('send', '" . self::TYPE_SCREENVIEW . "', { 'screenName': '{$name}' });";
		$this->addItem($item);
	}

	/**
	 * Track an event.
	 *
	 * @param string  $category
	 * @param string  $action
	 * @param string  $label
	 * @param integer $value
	 *
	 * @return void
	 */
	public function trackEvent($category, $action, $label = null, $value = null)
	{
		$item = "ga('send', 'event', '{$category}', '{$action}'" .
			($label !== null ? ", '{$label}'" : '') .
			($value !== null && is_numeric($value) ? ", {$value}" : '') .
			");";
		$this->addItem($item);
	}

	/**
	 * Track a transaction.
	 *
	 * @param string $id
	 * @param array  $options (affiliation|revenue|shipping|tax)
	 *
	 * @return void
	 */
	public function trackTransaction($id, array $options = [])
	{
		$this->addItem("ga('require', 'ecommerce');");
		$item = "ga('ecommerce:addTransaction', { " .
			"'id': '{$id}', ";

		if (!empty($options)) {
			foreach ($options as $key => $value) {
				$item .= "'{$key}': '{$value}', ";
			}
		}

		$item = rtrim($item, ', ') . " });";
		$this->addItem($item);
		$this->addItem("ga('ecommerce:send');");
	}

	/**
	 * Track a transaction item.
	 *
	 * @param string $id
	 * @param string $name
	 * @param array  $options (sku|category|price|quantity)
	 *
	 * @return void
	 */
	public function trackItem($id, $name, array $options = [])
	{
		$this->addItem("ga('require', 'ecommerce');");
		$item = "ga('ecommerce:addItem', { " .
			"'id': '{$id}', " .
			"'name': '{$name}', ";

		if (!empty($options)) {
			foreach ($options as $key => $value) {
				$item .= "'{$key}': '{$value}', ";
			}
		}

		$item = rtrim($item, ', ') . " });";
		$this->addItem($item);
		$this->addItem("ga('ecommerce:send');");
	}

	/**
	 * Track a metric.
	 *
	 * @param string $category
	 * @param array  $options
	 *
	 * @return void
	 */
	public function trackMetric($category, array $options = [])
	{
		$item = "ga('send', 'event', '{$category}'";

		if (!empty($options)) {
			$item .= ", 'action', { ";

			foreach ($options as $key => $value) {
				$item .= "'{$key}': {$value}, ";
			}

			$item = rtrim($item, ', ') . " }";
		}

		$item .= ");";
		$this->addItem($item);
	}

	/**
	 * Track an exception.
	 *
	 * @param string  $description
	 * @param boolean $fatal
	 *
	 * @return void
	 */
	public function trackException($description = null, $fatal = false)
	{
		$item = "ga('send', '" . self::TYPE_EXCEPTION . "'";

		if ($description !== null && is_bool($fatal)) {
			$item .= ", { " .
				"'exDescription': '{$description}', " .
				"'exFatal': " . ($fatal ? 'true' : 'false') .
				" }";
		}

		$item .= ");";

		$this->addItem($item);
	}

	/**
	 * Track a custom event.
	 *
	 * @param string $item
	 *
	 * @return void
	 */
	public function trackCustom($item)
	{
		$this->addItem($item);
	}

}
