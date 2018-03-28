<?php

namespace OWC_PDC_Base\Core\PostsToPosts;

use OWC_PDC_Base\Core\Plugin\ServiceProvider;

class PostsToPostsServiceProvider extends ServiceProvider
{

	/**
	 * @var array
	 */
	private $connectionDefaults = [
		'can_create_post'       => false,
		'reciprocal'            => true,
		'sortable'              => 'any',
		'cardinality'           => 'many-to-many',
		'duplicate_connections' => false
	];

	public function register()
	{

		$this->plugin->loader->addAction('init', $this, 'registerPostsToPostsConnections');
		$this->plugin->loader->addFilter('p2p_connectable_args', $this, 'filterP2PConnectableArgs', 10);
	}

	/**
	 * register P2P connections
	 */
	public function registerPostsToPostsConnections()
	{
		if ( function_exists('p2p_register_connection_type') ) {

			$posttypesInfo          = apply_filters('owc/pdc_base/config/p2p_posttypes_info', $this->plugin->config->get('p2p_connections.posttypes_info'));
			$defaultConnectionArgs = apply_filters('owc/pdc_base/p2p_connection_defaults', $this->connectionDefaults);

			$connections             = apply_filters('owc/pdc_base/config/p2p_connections', $this->plugin->config->get('p2p_connections.connections'));

			foreach ( $connections as $connectionArgs ) {

				$args = array_merge($defaultConnectionArgs, $connectionArgs);

				$connectionType = [
					'id'              => $posttypesInfo[ $connectionArgs['from'] ]['id'] . '_to_' . $posttypesInfo[ $connectionArgs['to'] ]['id'],
					'from'            => $connectionArgs['from'],
					'to'              => $connectionArgs['to'],
					'sortable'        => $args['sortable'],
					'admin_column'    => 'any',
					'from_labels'     => [
						'column_title' => $posttypesInfo[ $connectionArgs['to'] ]['title']
					],
					'title'           => [
						'from' => 'Koppel met een ' . $posttypesInfo[ $connectionArgs['to'] ]['title'] . ' ',
						'to'   => 'Koppel met een ' . $posttypesInfo[ $connectionArgs['from'] ]['title'] . ' '
					],
					'can_create_post' => $args['can_create_post'],
					'reciprocal'      => $args['reciprocal'],
				];

				if ( $connectionArgs['from'] == $connectionArgs['to'] ) {
					$connectionType['title']['to'] = '';
					$connectionType['admin_box']   = 'from';
				}

				$connectionType = apply_filters("owc/pdc_base/before_register_p2p_connection/{$posttypesInfo[ $connectionArgs['from'] ]['id']}/{$posttypesInfo[ $connectionArgs['to'] ]['id']}", $connectionType );

				p2p_register_connection_type($connectionType);
			}
		}
	}

	/*
	 * method for changing default P2P behaviour. Override by adding additional filter with higher priority (=larger number)
	 */
	public function filterP2PConnectableArgs($args)
	{
		$args['orderby']      = 'title';
		$args['order']        = 'asc';
		$args['p2p:per_page'] = 25;

		return $args;
	}
}