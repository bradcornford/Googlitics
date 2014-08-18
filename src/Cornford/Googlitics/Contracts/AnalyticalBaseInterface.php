<?php namespace Cornford\Googlitics\Contracts;

interface AnalyticalBaseInterface {

	/**
	 * Is tracking enabled?
	 *
	 * @return boolean
	 */
	public function isEnabled();

	/**
	 * Enable tracking.
	 *
	 * @return void
	 */
	public function enableTracking();

	/**
	 * Disable tracking.
	 *
	 * @return void
	 */
	public function disableTracking();

	/**
	 * Is anonymised tracking enabled?
	 *
	 * @return boolean
	 */
	public function isAnonymised();

	/**
	 * Enable anonymised tracking.
	 *
	 * @return void
	 */
	public function enableAnonymisedTracking();

	/**
	 * Disable anonymised tracking.
	 *
	 * @return void
	 */
	public function disableAnonymisedTracking();

	/**
	 * Is automatic tracking enabled?
	 *
	 * @return boolean
	 */
	public function isAutomatic();

	/**
	 * Enable automatic tracking.
	 *
	 * @return void
	 */
	public function enableAutomaticTracking();

	/**
	 * Disable automatic tracking.
	 *
	 * @return void
	 */
	public function disableAutomaticTracking();

}
