<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

class PhpVersionNotice extends AbstractMessage
{
    protected $alertClass            = 'warning';
    protected $recommendedPhpVersion = '8.2';

    public function isVisible()
    {
        return version_compare(PHP_VERSION, $this->recommendedPhpVersion, '<') && get_option('show_php_version_notice') == '1' && is_admin();
    }

    public function getMessage()
    {
        ?>
<h4><strong>Outdated PHP Version Detected!</strong></h4>
<p>
    The system detected that the version of <b>PHP
        (<?= PHP_VERSION; ?>)</b> your server is using is
    outdated and no longer supported.
</p>
<p>
    As the PHP core developers recently released new and improved versions, it's strongly recommended to <b>upgrade to
        PHP version newer or equal than
        <?= $this->recommendedPhpVersion; ?></b> to get the
    best results, you can consult with your hosting provider or server administrator to help you with this process.
</p>
<hr />
<a href="<?= admin_url('misc/dismiss_php_version_notice'); ?>"
    class="alert-link">Got it! Don't show this message again</a>
<?php
    }
}
?>