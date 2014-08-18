<?php namespace Cornford\Googlitics\Contracts;

interface AnalyticalInterface {

	/**
	 * Renders and returns Google Analytics code.
	 *
	 * @return string
	 */
	public function render();

	/**
	 * Track a page view.
	 *
	 * @param string $page
	 * @param string $title
	 * @param string $type
	 *
	 * @return boolean
	 */
	public function trackPage($page = null, $title = null, $type = self::TYPE_PAGEVIEW);

	/**
	 * Track a screen view.
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function trackScreen($name);

	/**
	 * Track an event.
	 *
	 * @param string  $category
	 * @param string  $action
	 * @param string  $label
	 * @param integer $value
	 *
	 * @return boolean
	 */
	public function trackEvent($category, $action, $label = null, $value = null);

	/**
	 * Track a metric.
	 *
	 * @param string $category
	 * @param array  $options
	 *
	 * @return boolean
	 */
	public function trackMetric($category, array $options = []);

	/**
	 * Track an exception.
	 *
	 * @param string  $description
	 * @param boolean $fatal
	 *
	 * @return boolean
	 */
	public function trackException($description = null, $fatal = false);

	/**
	 * Track a custom event.
	 *
	 * @param string $code
	 *
	 * @return boolean
	 */
	public function trackCustom($code);

}
