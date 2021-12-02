<?php

namespace RebelCode\Spotlight\Instagram\Modules\Dev;

/**
 * Dev tool that deletes all media from the DB.
 */
class DevDeleteMedia
{
    /** @var callable */
    protected $action;

    /** Constructor */
    public function __construct(callable $action)
    {
        $this->action = $action;
    }

    /**
     * @since 0.1
     */
    public function __invoke()
    {
        $deleteNonce = filter_input(INPUT_POST, 'sli_delete_meta');
        if (!$deleteNonce) {
            return;
        }

        if (!wp_verify_nonce($deleteNonce, 'sli_delete_media')) {
            wp_die('You cannot do that!', 'Unauthorized', [
                'back_link' => true,
            ]);
        }

        $result = ($this->action)();

        add_action('admin_notices', function () use ($result) {
            if ($result === false) {
                echo '<div class="notice notice-error"><p>WordPress failed to delete the media</p></div>';
            } else {
                printf('<div class="notice notice-success"><p>Deleted %d records from the database</p></div>', $result);
            }
        });
    }
}
