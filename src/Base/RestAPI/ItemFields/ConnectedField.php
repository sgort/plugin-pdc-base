<?php

namespace OWC\PDC\Base\RestAPI\ItemFields;

use OWC\PDC\Base\Support\CreatesFields;
use WP_Post;

class ConnectedField extends CreatesFields
{

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
            $type = $connection['from'].'_to_'.$connection['to'];
            $result[$type] = $this->getConnectedItems($post->ID, $type);
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
    private function getConnectedItems(int $postID, string $type): array
    {
        $connection = p2p_type($type);

        if ( ! $connection) {
            return [
                'error' => sprintf(__('Connection type "%s" does not exist', 'pdc-base'), $type)
            ];
        }

        return array_map(function ($post) {
            return [
                'id'    => $post->ID,
                'title' => $post->post_title,
                'slug'  => $post->post_name
            ];
        }, $connection->get_connected($postID)->posts);
    }

}