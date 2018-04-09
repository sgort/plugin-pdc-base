<?php

namespace OWC_PDC_Base\Core\RestApi;

use OWC_PDC_Base\Core\Plugin\ServiceProvider;

//use OWC_PDC_Base\Core\PostType\PostTypes\PdcItem;

class RestApiServiceProvider extends ServiceProvider
{

	public function register()
	{
		//TODO: check proper usage and timing of filter from config-expander
		//$this->plugin->loader->addFilter('owc/config-expander/settings', $this, 'filterConfigExpanderSettings', 10, 1);
		//$this->plugin->loader->addFilter('config_expander_rest_endpoints_whitelist', $this,'filterEndpointsWhitelist', 10, 1);
		$this->plugin->loader->addFilter('rest_api_init', $this, 'registerRestApiEndpointsFields', 10);
		$this->plugin->loader->addFilter('rest_prepare_pdc-item', $this, 'filterRestPreparePdcItem', 10, 3);
	}

	/**
	 * register endpoint fields for use in the RestApi.
	 */
	public function registerRestApiEndpointsFields()
	{

		$restApiFieldsPerPostType = apply_filters('owc/pdc_base/config/rest_api_fields_per_posttype', $this->plugin->config->get('rest_api_fields'));

		foreach ( $restApiFieldsPerPostType as $postType => $restApiFields ) {

			$this->registerRestFieldByPostType($postType, $restApiFields);
		}
	}

	private function registerRestFieldByPostType($postType, $restApiFields)
	{
		if ( post_type_exists($postType) ) {

			foreach ( $restApiFields as $attribute => $restApiField ) {

				register_rest_field($postType, $attribute, $restApiField);
			}
		}
	}

	public function filterConfigExpanderSettings($settings)
	{
//		var_dump( $settings );
//		exit;
		$defaults['DISABLE_REST_API'] = false;

		return $defaults;
	}

	public function filterEndpointsWhitelist($endpointsWhitelist)
	{

		//remove default root endpoint
		unset($endpointsWhitelist['wp/v2']);

		$endpointsWhitelist['wp/v2'] = [
			'endpoint_stub' => '/wp/v2/pdc',
			'methods'       => ['GET']
		];

		return $endpointsWhitelist;
	}

	public function filterRestPreparePdcItem($response, $post, $request)
	{

		$requestAttributes = $request->get_attributes();
		$requestParams     = $request->get_params();

		//check for usage in list of pdc_items via check for 'get_items' callback method.
		if ( 'get_items' == $requestAttributes['callback'][1] && ! isset($requestParams['slug']) ) {

			$response->data['connected']['pdc-item_to_pdc-subcategory'] = [];
			if ( ! empty($response->data['connected']['pdc-item_to_pdc-subcategory'] = $post->connected) ) {
				$response->data['connected']['pdc-item_to_pdc-subcategory'] = $post->connected;
			}
		}

		return $response;
	}

}