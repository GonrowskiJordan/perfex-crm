<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-7">
                <h4 class="tw-mt-0 tw-text-lg tw-font-semibold tw-text-neutral-700">
                    <?php echo e($article->subject); ?>
                </h4>

                <div class="panel_s">
                    <div class="panel-body tc-content">
                        <div class="kb-article">
                            <?php echo $article->description; ?>
                        </div>
                        <hr class="hr-panel-separator" />
                        <h4 class="tw-mb-0 tw-font-medium tw-text-base">
                            <?php echo _l('clients_knowledge_base_find_useful'); ?>
                        </h4>
                        <div class="answer_response"></div>
                        <input type="hidden" name="articleid" value="<?php echo e($article->articleid); ?>">
                        <div class="btn-group mtop15 article_useful_buttons" role="group">
                            <button type="button" data-answer="1" class="btn btn-success">
                                <?php echo _l('clients_knowledge_base_find_useful_yes'); ?>
                            </button>
                            <button type="button" data-answer="0" class="btn btn-danger">
                                <?php echo _l('clients_knowledge_base_find_useful_no'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (count($related_articles) > 0) { ?>
            <div class="col-md-5">
                <div class="panel_s">
                <h4 class="tw-mt-0 tw-text-lg tw-font-semibold tw-text-neutral-700">
                    <?php echo e($article->subject); ?>
                </h4>
                    <div class="panel-body">
                        <ul class="mtop10 articles_list">
                            <?php foreach ($related_articles as $rel_article_article) { ?>
                            <li>
                                <i class="fa-regular fa-file-lines"></i>
                                <a href="<?php echo admin_url('knowledge_base/view/' . $rel_article_article['slug']); ?>"
                                    class="article-heading"><?php echo e($rel_article_article['subject']); ?></a>
                                <div class="text-muted mtop10">
                                    <?php echo strip_tags(mb_substr($rel_article_article['description'], 0, 150)); ?>...
                                </div>
                            </li>
                            <hr />
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {

    // Lightbox for knowledge base images
    $.each($('.kb-article').find('img'), function() {
        if (!$(this).parent().is('a')) {
            $(this).wrap('<a href="' + $(this).attr('src') + '" data-lightbox="kb-attachment"></a>');
        }
    });

    $('.article_useful_buttons button').on('click', function(e) {
        e.preventDefault();
        var data = {};
        data.answer = $(this).data('answer');
        data.articleid = '<?php echo e($article->articleid); ?>';
        $.post(admin_url + 'knowledge_base/add_kb_answer', data).done(function(response) {
            response = JSON.parse(response);
            if (response.success == true) {
                $(this).focusout();
            }
            $('.answer_response').html(response.message);
        });
    });
});
</script>
</body>

</html>