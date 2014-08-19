<?php namespace spec\Cornford\Googlitics;

use Cornford\Googlitics\Analytics;
use PhpSpec\ObjectBehavior;
use Mockery;

class AnalyticsSpec extends ObjectBehavior
{

	function let()
	{
		$application = Mockery::mock('Illuminate\Foundation\Application');
		$application->shouldReceive('environment')->andReturn('dev');
		$view = Mockery::mock('Illuminate\View\Factory');
		$view->shouldReceive('make')->andReturn($view);
		$view->shouldReceive('withItems')->andReturn($view);
		$view->shouldReceive('render')->andReturn('SCRIPT');
		$this->beConstructedWith($application, $view, ['enabled' => true, 'id' => 'test']);
	}

	function it_is_initializable()
	{
		$this->shouldHaveType('Cornford\Googlitics\Analytics');
	}

	function it_throws_an_exception_with_incorrect_options()
	{
		$application = Mockery::mock('Illuminate\Foundation\Application');
		$view = Mockery::mock('Illuminate\View\Factory');
		$this->shouldThrow('Cornford\Googlitics\Exceptions\AnalyticsArgumentException')
			->during('__construct', [$application, $view]);
	}

	function it_can_render_analytics_code()
	{
		$this->render()->shouldReturn('SCRIPT');
	}

	function it_can_be_enabled()
	{
		$this->enableTracking();
		$this->isEnabled()->shouldReturn(true);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(1);
		$this->getItems()->shouldReturn([
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_be_disabled()
	{
		$this->disableTracking();
		$this->isEnabled()->shouldReturn(false);
		$this->render()->shouldReturn(null);
		$this->getItems()->shouldHaveCount(0);
	}

	function it_can_be_set_to_anonymised_tracking()
	{
		$this->enableAnonymisedTracking();
		$this->isAnonymised()->shouldReturn(true);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('set', 'anonymizeIp', true);",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_be_set_to_none_anonymised_tracking()
	{
		$this->disableAnonymisedTracking();
		$this->isAnonymised()->shouldReturn(false);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(1);
		$this->getItems()->shouldReturn([
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_be_set_to_automatically_track()
	{
		$this->enableAutomaticTracking();
		$this->isAutomatic()->shouldReturn(true);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'pageview');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_be_set_to_not_automatically_track()
	{
		$this->disableAutomaticTracking();
		$this->isAutomatic()->shouldReturn(false);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(1);
		$this->getItems()->shouldReturn([
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_a_page()
	{
		$this->trackPage();
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'pageview');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_a_page_with_parameters()
	{
		$this->trackPage('Page', 'Title', Analytics::TYPE_PAGEVIEW);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', { 'hitType': 'pageview', 'page': 'Page', 'title': 'Title' });",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_a_screen()
	{
		$this->trackScreen('Name');
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'screenview', { 'screenName': 'Name' });",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_an_event()
	{
		$this->trackEvent('Category', 'Action');
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'event', 'Category', 'Action');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_an_event_with_parameters()
	{
		$this->trackEvent('Category', 'Action', 'Label', 'Value');
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'event', 'Category', 'Action', 'Label');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_a_transaction()
	{
		$this->trackTransaction('ID');
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(4);
		$this->getItems()->shouldReturn([
			"ga('require', 'ecommerce');",
			"ga('ecommerce:addTransaction', { 'id': 'ID' });",
			"ga('ecommerce:send');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_a_transaction_with_parameters()
	{
		$this->trackTransaction('ID', ['affiliation' => 1, 'revenue' => 2, 'shipping' => 3, 'tax' => 4]);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(4);
		$this->getItems()->shouldReturn([
			"ga('require', 'ecommerce');",
			"ga('ecommerce:addTransaction', { 'affiliation': '1', 'revenue': '2', 'shipping': '3', 'tax': '4', 'id': 'ID' });",
			"ga('ecommerce:send');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_an_item()
	{
		$this->trackItem('ID', 'Name');
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(4);
		$this->getItems()->shouldReturn([
			"ga('require', 'ecommerce');",
			"ga('ecommerce:addItem', { 'id': 'ID', 'name': 'Name' });",
			"ga('ecommerce:send');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_an_item_with_parameters()
	{
		$this->trackItem('ID', 'Name', ['affiliation' => 1, 'revenue' => 2, 'shipping' => 3, 'tax' => 4]);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(4);
		$this->getItems()->shouldReturn([
			"ga('require', 'ecommerce');",
			"ga('ecommerce:addItem', { 'affiliation': '1', 'revenue': '2', 'shipping': '3', 'tax': '4', 'id': 'ID', 'name': 'Name' });",
			"ga('ecommerce:send');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_a_metric()
	{
		$this->trackMetric('Category', ['metric1' => 1]);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'event', 'Category', 'action', { 'metric1': 1 });",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_an_exception()
	{
		$this->trackException();
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'exception');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_an_exception_with_parameters()
	{
		$this->trackException('Description', true);
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('send', 'exception', { 'exDescription': 'Description', 'exFatal': true });",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

	function it_can_track_a_custom_item()
	{
		$this->trackCustom("ga('custom', 'test');");
		$this->render()->shouldReturn('SCRIPT');
		$this->getItems()->shouldHaveCount(2);
		$this->getItems()->shouldReturn([
			"ga('custom', 'test');",
			"ga('create', 'test', { 'cookieDomain': 'none' });"
		]);
	}

}
