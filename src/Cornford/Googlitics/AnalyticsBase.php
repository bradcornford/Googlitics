<?php namespace Cornford\Googlitics;

use Cornford\Googlitics\Contracts\AnalyticalBaseInterface;
use Cornford\Googlitics\Exceptions\AnalyticsArgumentException;
use Illuminate\Foundation\Application;
use Illuminate\View\Factory as View;

abstract class AnalyticsBase implements AnalyticalBaseInterface
{

	const TYPE_PAGEVIEW = 'pageview';
	const TYPE_APPVIEW = 'appview';
	const TYPE_SCREENVIEW = 'screenview';
	const TYPE_EVENT = 'event';
	const TYPE_TRANSACTION = 'transaction';
	const TYPE_ITEM = 'item';
	const TYPE_SOCIAL = 'social';
	const TYPE_EXCEPTION = 'exception';
	const TYPE_TIMING = 'timing';

	const ECOMMERCE_TRANSACTION = 'addTransaction';
	const ECOMMERCE_ITEM = 'addItem';

	/**
	 * App
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $application;

	/**
	 * View
	 *
	 * @var \Illuminate\View\Environment
	 */
	protected $view;

	/**
	 * Tracking enabled
	 *
	 * @var boolean
	 */
	protected $enabled;

	/**
	 * Google Analytics tracking id
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Domain
	 *
	 * @var string
	 */
	protected $domain;

	/**
	 * Anonymised tracking
	 *
	 * @var boolean
	 */
	protected $anonymised;

	/**
	 * Automatically track
	 *
	 * @var boolean
	 */
	protected $automatic;

	/**
	 * Tracking items
	 *
	 * @var array
	 */
	public $items = [];

	/**
	 * Construct Googlitics
	 *
	 * @param Application $application
	 * @param View        $view
	 * @param array       $options
	 *
	 * @throws AnalyticsArgumentException
	 *
	 * @return self
	 */
	public function __construct(Application $application, View $view, array $options = [])
	{
		$this->application = $application;
		$this->view = $view;

		if (!isset($options['id'])) {
			throw new AnalyticsArgumentException('Google Analytics tracking id is required.');
		}

		$this->setEnabled(isset($options['enabled']) ? $options['enabled'] : true);
		$this->setId($options['id']);
		$this->setDomain(isset($options['domain']) ? $options['domain'] : 'auto');
		$this->setAnonymised(isset($options['anonymise']) ? $options['anonymise'] : false);
		$this->setAutomatic(isset($options['automatic']) ? $options['automatic'] : false);
	}

	/**
	 * Set enabled status
	 *
	 * @param boolean $value
	 *
	 * @return void
	 */
	protected function setEnabled($value)
	{
		$this->enabled = $value;
	}

	/**
	 * Get the enabled status
	 *
	 * @return boolean
	 */
	protected function getEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Set the Google Analytics id
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	protected function setId($value)
	{
		$this->id = $value;
	}

	/**
	 * Get the Google Analytics id
	 *
	 * @return string
	 */
	protected function getId()
	{
		return $this->id;
	}

	/**
	 * Set the tracking domain
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	protected function setDomain($value)
	{
		$this->domain = $value;
	}

	/**
	 * Get the tracking domain
	 *
	 * @return string
	 */
	protected function getDomain()
	{
		return $this->domain;
	}

	/**
	 * Set anonymised tracking
	 *
	 * @param boolean $value
	 *
	 * @return void
	 */
	protected function setAnonymised($value)
	{
		$this->anonymised = $value;
	}

	/**
	 * Get anonymised tracking
	 *
	 * @return boolean
	 */
	protected function getAnonymised()
	{
		return $this->anonymised;
	}

	/**
	 * Set automatic tracking
	 *
	 * @param boolean $value
	 *
	 * @return void
	 */
	protected function setAutomatic($value)
	{
		$this->automatic = $value;
	}

	/**
	 * Get automatic tracking
	 *
	 * @return boolean
	 */
	protected function getAutomatic()
	{
		return $this->automatic;
	}

	/**
	 * Add tracking item
	 *
	 * @param string $value
	 *
	 * @return void
	 */
	protected function addItem($value)
	{
		return $this->items[] = $value;
	}

	/**
	 * Set tracking items
	 *
	 * @param array $array
	 *
	 * @return void
	 */
	protected function setItems(array $array)
	{
		return $this->items = $array;
	}

	/**
	 * Get the tracking items
	 *
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Is tracking enabled?
	 *
	 * @return boolean
	 */
	public function isEnabled()
	{
		return $this->getEnabled();
	}

	/**
	 * Enable tracking.
	 *
	 * @return void
	 */
	public function enableTracking()
	{
		$this->setEnabled(true);
	}

	/**
	 * Disable tracking.
	 *
	 * @return void
	 */
	public function disableTracking()
	{
		$this->setEnabled(false);
	}

	/**
	 * Is anonymised tracking enabled?
	 *
	 * @return boolean
	 */
	public function isAnonymised() {
		return $this->getAnonymised();
	}

	/**
	 * Enable anonymised tracking.
	 *
	 * @return void
	 */
	public function enableAnonymisedTracking()
	{
		$this->setAnonymised(true);
	}

	/**
	 * Disable anonymised tracking.
	 *
	 * @return void
	 */
	public function disableAnonymisedTracking()
	{
		$this->setAnonymised(false);
	}

	/**
	 * Is automatic tracking enabled?
	 *
	 * @return boolean
	 */
	public function isAutomatic()
	{
		return $this->getAutomatic();
	}

	/**
	 * Enable automatic tracking.
	 *
	 * @return void
	 */
	public function enableAutomaticTracking()
	{
		$this->setAutomatic(true);
	}

	/**
	 * Disable automatic tracking.
	 *
	 * @return void
	 */
	public function disableAutomaticTracking()
	{
		$this->setAutomatic(false);
	}

	/**
	 * Assemble parameters from an array to a string.
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	protected function assembleParameters($options)
	{
		$return = '';

		if (!empty($options)) {
			foreach ($options as $key => $value) {
				$return .= "'{$key}': '{$value}', ";
			}
		}

		return $return;
	}

    /**
     * Track an ecommerce item.
     *
     * @param string $type
     * @param array  $options
     *
     * @return void
     */
	protected function trackEcommerce($type = self::ECOMMERCE_TRANSACTION, array $options = [])
	{
        $this->addItem("ga('require', 'ecommerce');");
        $item = "ga('ecommerce:{$type}', { ";
        $item .= $this->assembleParameters($options);
        $item = rtrim($item, ', ') . " });";
        $this->addItem($item);
        $this->addItem("ga('ecommerce:send');");
	}

}