<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="horizontal-scrollable-tabs tw-min-h-0 tw-px-3">
    <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs -tw-mx-[calc(theme(spacing.3)-1px)]">
        <ul class="nav nav-tabs nav-tabs-segmented nav-tabs-horizontal project-tabs" role="tablist">
            <?php
        foreach (filter_project_visible_tabs($tabs, $project->settings->available_features) as $key => $tab) {
            $dropdown = isset($tab['collapse']) ? true : false; ?>
            <li class="<?php if ($key == 'project_overview' && !$this->input->get('group')) {
                echo 'active ';
            } ?>project_tab_<?php echo e($key); ?><?php if ($dropdown) {
                echo ' nav-tabs-submenu-parent';
            } ?>">
                <a data-group="<?php echo e($key); ?>" role="tab" <?php if ($dropdown) { ?> data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true" class="dropdown-toggle" href="#"
                    id="dropdown_<?php echo e($key); ?>" <?php } else { ?>
                    href="<?php echo admin_url('projects/view/' . $project->id . '?group=' . $key); ?>" <?php } ?>>
                    <i class="<?php echo e($tab['icon']); ?> menu-icon" aria-hidden="true"></i>
                    <?php echo e($tab['name']); ?>

                    <?php if (isset($tab['badge'], $tab['badge']['value']) && !empty($tab['badge'])) {?>
                    <span
                        class="badge pull-right mleft5
                            <?=isset($tab['badge']['type']) && $tab['badge']['type'] != '' ? "bg-{$tab['badge']['type']}" : 'bg-info' ?>"
                        <?=(isset($tab['badge']['type']) && $tab['badge']['type'] == '') ||
                                    isset($tab['badge']['color']) ? "style='background-color: {$tab['badge']['color']}'" : '' ?>>
                        <?= e($tab['badge']['value']) ?>
                    </span>
                    <?php } ?>

                    <?php if ($dropdown) { ?> <span class="caret"></span> <?php } ?>
                </a>
                <?php if ($dropdown) { ?>
                <?php if (!is_rtl()) { ?>
                <div class="tabs-submenu-wrapper">
                    <?php } ?>
                    <ul class="dropdown-menu" aria-labelledby="dropdown_<?php echo e($key); ?>">
                        <?php
                            foreach ($tab['children'] as $d) {
                                echo '<li class="nav-tabs-submenu-child"><a href="' . admin_url('projects/view/' . $project->id . '?group=' . $d['slug']) . '" data-group="' . $d['slug'] . '">' . e($d['name']);

                                if (isset($d['badge'], $d['badge']['value']) && !empty($d['badge'])) {?>
                        <span
                            class="badge pull-right
                                    <?=isset($d['badge']['type']) && $d['badge']['type'] != '' ? "bg-{$d['badge']['type']}" : 'bg-info' ?>"
                            <?=(isset($d['badge']['type']) && $d['badge']['type'] == '') ||
                                    isset($d['badge']['color']) ? "style='background-color: {$d['badge']['color']}'" : '' ?>>
                            <?= e($d['badge']['value']) ?>
                        </span>
                        <?php }

                                echo '</a></li>';
                            }
                            ?>
                    </ul>
                    <?php if (!is_rtl()) { ?>
                </div>
                <?php } ?>
                <?php } ?>
            </li>
            <?php
        } ?>
        </ul>
    </div>
</div>