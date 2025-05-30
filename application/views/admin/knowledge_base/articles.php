<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
$has_permission_edit   = staff_can('edit', 'knowledge_base');
$has_permission_create = staff_can('create', 'knowledge_base');
?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 tw-mb-3">
                <h4 class="tw-my-0 tw-font-bold tw-text-xl">
                    <?= _l('knowledge_base'); ?>
                </h4>
                <?php if ($has_permission_edit || $has_permission_create) { ?>
                <a href="<?= admin_url('knowledge_base/manage_groups'); ?>"
                    class="tw-mr-4">
                    <?= _l('als_kb_groups'); ?>
                    &rarr;
                </a>
                <?php } ?>
                <a href="#"
                    class="hidden-xs toggle-articles-list tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"
                    onclick="initKnowledgeBaseTableArticles(); return false;">
                    <?= _l('view_kanban'); ?>
                </a>
            </div>

            <div class="col-md-12">
                <div class="_buttons tw-mb-2">
                    <?php if ($has_permission_create) { ?>
                    <a href="<?= admin_url('knowledge_base/article'); ?>"
                        class="btn btn-primary mright5">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?= _l('kb_article_new_article'); ?>
                    </a>
                    <?php } ?>
                    <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data hide"
                        data-toggle="tooltip"
                        data-title="<?= _l('filter_by'); ?>">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                            <li class="active">
                                <a href="#" data-cview="all"
                                    onclick="dt_custom_view('','.table-articles',''); return false;"><?= _l('view_articles_list_all'); ?></a>
                            </li>
                            <?php foreach ($groups as $group) { ?>
                            <li><a href="#"
                                    data-cview="kb_group_<?= e($group['groupid']); ?>"
                                    onclick="dt_custom_view('kb_group_<?= e($group['groupid']); ?>','.table-articles','kb_group_<?= e($group['groupid']); ?>'); return false;"><?= e($group['name']); ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="_hidden_inputs _filters">
                        <?php foreach ($groups as $group) {
                            echo form_hidden('kb_group_' . $group['groupid']);
                        } ?>
                    </div>
                </div>

                <div class="mtop5">
                    <div class="row">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane kb-kan-ban kan-ban-tab" id="kan-ban">
                                <div class="container-fluid">
                                    <?php
                                                   if (count($groups) == 0) {
                                                       echo _l('kb_no_articles_found');
                                                   }

foreach ($groups as $group) {
    $kanban_colors = '';

    foreach (get_system_favourite_colors() as $color) {
        $color_selected_class = 'cpicker-small';
        $kanban_colors .= "<div class='kanban-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ';border:1px solid ' . $color . "'></div>";
    } ?>
                                    <ul class="kan-ban-col<?php if (! $has_permission_edit) {
                                        echo ' sortable-disabled';
                                    } ?>"
                                        data-col-group-id="<?= e($group['groupid']); ?>">
                                        <li class="kan-ban-col-wrapper">
                                            <div class="border-right panel_s">
                                                <?php
                                         $group_color = 'style="background:' . $group['color'] . ';border:1px solid ' . $group['color'] . '"'; ?>
                                                <div class="panel-heading tw-bg-neutral-700" <?= $group_color; ?>
                                                    data-group-id="<?= e($group['groupid']); ?>">
                                                    <?php if ($has_permission_edit) { ?>
                                                    <i class="fa fa-reorder pointer tw-text-white"></i>
                                                    <?php } ?>
                                                    <a href="#" class="tw-text-white hover:tw-text-neutral-200"
                                                        <?php if ($has_permission_create || $has_permission_edit) { ?>
                                                        onclick="edit_kb_group(this,<?= e($group['groupid']); ?>);
                                                        return false;"
                                                        data-name="<?= e($group['name']); ?>"
                                                        data-slug="<?= e($group['group_slug']); ?>"
                                                        data-color="<?= $group['color']; ?>"
                                                        data-description="<?= clear_textarea_breaks($group['description']); ?>"
                                                        data-order="<?= e($group['group_order']); ?>"
                                                        data-active="<?= e($group['active']); ?>"
                                                        <?php } ?>>
                                                        <?= e($group['name']); ?>
                                                    </a>
                                                    <small class="tw-text-white"> -
                                                        <?= total_rows(db_prefix() . 'knowledge_base', 'articlegroup=' . $group['groupid']); ?></small>
                                                    <?php if ($has_permission_edit) { ?>
                                                    <a href="#" onclick="return false;"
                                                        class="pull-right color-white kanban-color-picker"
                                                        data-placement="bottom" data-toggle="popover"
                                                        data-content="<div class='kan-ban-settings cpicker-wrapper'><?= e($kanban_colors); ?></div>"
                                                        data-html="true" data-trigger="focus">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <?php } ?>
                                                </div>
                                                <?php
                                         $this->db->select('*, (SELECT COUNT(*) FROM ' . db_prefix() . 'views_tracking WHERE rel_type="kb_article" AND rel_id=' . db_prefix() . 'knowledge_base.articleid) as total_views')->from(db_prefix() . 'knowledge_base')->where('articlegroup', $group['groupid'])->order_by('article_order', 'asc');
    if (! $has_permission_create && ! $has_permission_edit) {
        $this->db->where('active', 1);
    }
    $articles = $this->db->get()->result_array(); ?>
                                                <div class="kan-ban-content-wrapper">
                                                    <div class="kan-ban-content">
                                                        <ul class="sortable article-group groups<?php if (! $has_permission_edit) {
                                                            echo 'sortable-disabled';
                                                        } ?>"
                                                            data-group-id="<?= e($group['groupid']); ?>">
                                                            <?php foreach ($articles as $article) { ?>
                                                            <li class="<?php if ($article['active'] == 0) {
                                                                echo 'line-throught';
                                                            } ?>"
                                                                data-article-id="<?= e($article['articleid']); ?>">
                                                                <div class="panel-body">
                                                                    <?php if ($article['staff_article'] == 1) { ?>
                                                                    <a
                                                                        href="<?= admin_url('knowledge_base/view/' . $article['slug']); ?>">
                                                                        <?= e($article['subject']); ?>
                                                                    </a>
                                                                    <?php } else { ?>
                                                                    <a href="<?= site_url('knowledge-base/article/' . $article['slug']); ?>"
                                                                        target="_blank">
                                                                        <?= e($article['subject']); ?>
                                                                    </a>
                                                                    <?php } ?>
                                                                    <?php if ($has_permission_edit) { ?>
                                                                    <a href="<?= admin_url('knowledge_base/article/' . $article['articleid']); ?>"
                                                                        target="_blank" class="pull-right"><span><i
                                                                                class="fa-regular fa-pen-to-square"
                                                                                aria-hidden="true"></i></span></a>
                                                                    <?php } ?>
                                                                    <div class="clearfix"></div>
                                                                    <hr class="hr-10" />
                                                                    <p class="pull-left no-mbot">
                                                                        <small><?= _l('article_total_views'); ?>:
                                                                            <?= e($article['total_views']); ?></small>
                                                                    </p>
                                                                    <?php if ($article['staff_article'] == 1) { ?>
                                                                    <span
                                                                        class="label label-default pull-right"><?= _l('internal_article'); ?></span>
                                                                    <?php } ?>
                                                                </div>
                                                            </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                        </li>
                                    </ul>
                                    <?php
} ?>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane active" id="list_tab">
                                <div class="col-md-12 panel-table-full">
                                    <div class="panel_s">
                                        <div class="panel-body">
                                            <?php render_datatable(
                                                [
                                                    _l('kb_dt_article_name'),
                                                    _l('kb_dt_group_name'),
                                                    _l('date_published'),
                                                ],
                                                'articles',
                                                [],
                                                [
                                                    'data-last-order-identifier' => 'kb-articles',
                                                    'data-default-order'         => get_table_last_order('kb-articles'),
                                                ]
                                            ); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include_once APPPATH . 'views/admin/knowledge_base/group.php'; ?>
<?php init_tail(); ?>
<script>
    $(function() {
        fix_kanban_height(290, 360);
        initKnowledgeBaseTableArticles();
        $(".groups").sortable({
            connectWith: ".article-group",
            helper: 'clone',
            appendTo: '#kan-ban',
            placeholder: "ui-state-highlight-kan-ban-kb",
            revert: true,
            scroll: true,
            scrollSensitivity: 50,
            scrollSpeed: 70,
            start: function(event, ui) {
                $('body').css('overflow', 'hidden');
            },
            stop: function(event, ui) {
                $('body').removeAttr('style');
            },
            update: function(event, ui) {
                if (this === ui.item.parent()[0]) {
                    var articles = $(ui.item).parents('.article-group').find('li');
                    i = 1;
                    var order = [];
                    $.each(articles, function() {
                        i++;
                        order.push([$(this).data('article-id'), i]);
                    });
                    setTimeout(function() {
                        $.post(admin_url + 'knowledge_base/update_kan_ban', {
                            order: order,
                            groupid: $(ui.item.parent()[0]).data('group-id')
                        });
                    }, 100);
                }
            }
        }).disableSelection();

        $('.groups').sortable({
            cancel: '.sortable-disabled'
        });

        setTimeout(function() {
            $('.kb-kan-ban').removeClass('hide');
        }, 200);

        $(".container-fluid").sortable({
            helper: 'clone',
            item: '.kan-ban-col',
            cancel: '.sortable-disabled',
            update: function(event, ui) {
                var order = [];
                var status = $('.kan-ban-col');
                var i = 0;
                $.each(status, function() {
                    order.push([$(this).data('col-group-id'), i]);
                    i++;
                });
                var data = {}
                data.order = order;
                $.post(admin_url + 'knowledge_base/update_groups_order', data);
            }
        });
        // Status color change
        $('body').on('click', '.kb-kan-ban .cpicker', function() {
            var color = $(this).data('color');
            var group_id = $(this).parents('.panel-heading').data('group-id');
            $.post(admin_url + 'knowledge_base/change_group_color', {
                color: color,
                group_id: group_id
            });
        });
        $('.toggle-articles-list').on('click', function() {
            var list_tab = $('#list_tab');
            if (list_tab.hasClass('active')) {
                list_tab.css('display', 'none').removeClass('active');
                $('.kan-ban-tab').css('display', 'block');
                fix_kanban_height(290, 360);
                mainWrapperHeightFix();
            } else {
                list_tab.css('display', 'block').addClass('active');
                $('.kan-ban-tab').css('display', 'none');
            }
        });
    });

    function initKnowledgeBaseTableArticles() {
        var KB_Articles_ServerParams = {};
        $.each($('._hidden_inputs._filters input'), function() {
            KB_Articles_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });
        $('._filter_data').toggleClass('hide');
        initDataTable('.table-articles', window.location.href, undefined, undefined, KB_Articles_ServerParams, [2,
            'desc'
        ]);
    }
</script>
</body>

</html>