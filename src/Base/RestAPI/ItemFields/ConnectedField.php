<?php

/**
 * Adds connected/related fields to the output.
 */

namespace OWC\PDC\Base\RestAPI\ItemFields;

use OWC\PDC\Base\RestAPI\Controllers\ItemController;
use OWC\PDC\Base\Support\CreatesFields;
use OWC\PDC\Base\Support\Traits\CheckPluginActive;
use WP_Post;

/**
 * Adds connected/related fields to the output.
 */
class ConnectedField extends CreatesFields
{
    use CheckPluginActive;

    /**
     * Creates an array of connected posts.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function create(WP_Post $post): array
    {
        $connections = array_filter($this->plugin->config->get('p2p_connections.connections'), function ($connection) {
            return in_array('pdc-item', $connection, true);
        });

        $result = [];

        foreach ($connections as $connection) {
            $type                      = $connection['from'] . '_to_' . $connection['to'];
            $result[$connection['to']] = $this->getConnectedItems($post->ID, $type, $this->extraQueryArgs($type));
        }

        return $result;
    }

    /**
     * Get connected items of a post, for a specific connection type.
     *
     * @param int    $postID
     * @param string $type
     *
     * @return array
     */
    protected function getConnectedItems(int $postID, string $type, array $extraQueryArgs = []): array
    {
        $connection = \p2p_type($type);

        if (! $connection) {
            return [
                'error' => sprintf(__('Connection type "%s" does not exist', 'pdc-base'), $type),
            ];
        }

        return array_map(function (WP_Post $post) {
            return [
                'id'      => $post->ID,
                'title'   => $post->post_title,
                'slug'    => $post->post_name,
                'excerpt' => $post->post_excerpt,
                'date'    => $post->post_date,
            ];
        }, $connection->get_connected($postID, $extraQueryArgs)->posts);
    }

    protected function extraQueryArgs(string $type): array
    {
        $query = [];

        $connectionsExcludeInActive = $this->plugin->config->get('p2p_connections.connections_exclude_inactive');

        if (in_array($type, $connectionsExcludeInActive)) {
            $query = array_merge_recursive($query, ItemController::excludeInactiveItems());
        }

        if ($this->isPluginPDCInternalProductsActive()) {
            $connectionsExcludeInternal = $this->plugin->config->get('p2p_connections.connections_exclude_internal');

            if (in_array($type, $connectionsExcludeInternal)) {
                $query = array_merge_recursive($query, ItemController::excludeInternalItems());
            }
        }

        $query['connected_query'] = ['post_status' => ['publish', 'draft']];

        return $query;
    }
}
