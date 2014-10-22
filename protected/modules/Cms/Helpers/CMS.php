<?php

use Cms\Forms\CommentForm,
    Cms\Models\Article,
    Cms\Models\Category,
    Cms\Models\Comment,
    Cms\Models\Page,
    DBScribe\ArrayCollection,
    DBScribe\Table,
    DScribe\Core\AUser,
    DScribe\Core\Engine,
    DScribe\Core\Repository,
    DScribe\Core\Request,
    DScribe\View\View,
    DSLive\Models\User;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CMS
 *
 * @author Ezra Obiwale <contact@ezraobiwale.com>
 */
class CMS {

    /**
     *
     * @var Page
     */
    private static $page;

    /**
     *
     * @var Article
     */
    private static $article;

    /**
     *
     * @var Category
     */
    private static $pageCategory;

    /**
     *
     * @var User
     */
    private static $user;

    /**
     *
     * @var Repository
     */
    private static $repository;

    /**
     *
     * @var Request
     */
    private static $request;

    /**
     *
     * @var View
     */
    private static $view;

    /**
     * CMS Settings
     * @var array
     */
    private static $settings;

    /**
     * @var string
     */
    private static $category;
    private static $countView = true;
    private static $commentStatus;
    private static $commentResponseStatus;
    private static $commentStatusType;
    private static $commentResponseStatusType;

    /**
     *
     * @var CommentForm
     */
    private static $commentForm;
    private static $votes;
    private static $rawPageMap;
    private static $map;
    private static $links;

    /**
     * 
     * @param User $user
     * @param Repository $repository
     * @param Request $request
     */
    public static function init(AUser $user, Repository $repository, Request $request, View $view) {
        self::setUser($user);
        self::setRepository($repository);
        self::setRequest($request);
        self::setView($view);
    }

    public function __call($name, $arg) {
        if (method_exists('CMS', $name)) {
            return call_user_func_array(array('CMS', $name), $arg);
        }
    }

    /**
     * Sets the current page
     * @param Page $page
     */
    public static function setPage(Page $page) {
        self::$page = $page;
    }

    /**
     * Sets the current article
     * @param Article $article
     */
    public static function setArticle(Article $article) {
        self::$article = $article;
    }

    /**
     * Sets the current page's category
     * @param Category $category
     */
    public static function setPageCategory(Category $category) {
        self::$pageCategory = $category;
    }

    /**
     * Fetches the current page
     * @return Page
     */
    public static function getPage() {
        if (self::$countView && self::$user->is('guest')) {
            self::$page->increaseViewCount();
            self::$repository->update(self::$page)->execute();
            Engine::getDB()->flush();

            self::$countView = false;
        }

        return self::$page;
    }

    /**
     * Fetches the current article
     * @return Article
     */
    public static function getArticle() {
        if (self::$countView && self::$user->is('guest')) {
            self::$article->increaseViewCount();
            self::$repository->update(self::$article)->execute();
            Engine::getDB()->flush();

            self::$countView = false;
        }
        return self::$article;
    }

    public static function getDescription() {
        if (self::$page) {
            return self::$page->getDescription();
        }
        else if (self::$article) {
            return self::$article->getDescription();
        }
    }

    /**
     * Sets the current article category
     * @param string $category
     */
    public static function setArticleCategory($category) {
        self::$category = $category;
    }

    /**
     * Fetches the current article category
     * @return string
     */
    public static function getArticleCategory() {
        if (self::$category) {
            return self::$category;
        }
        else if (self::$article) {
            return self::$article->getCategory();
        }
    }

    public static function getArticleCategoryLink() {
        return self::getView()->url('cms', 'article', 'view-category', array(
                    str_replace(' ', '-', strtolower(self::getArticleCategory())),
        ));
    }

    /**
     * Fetches the current page's category
     * @return Category
     */
    public static function getPageCategory() {
        return self::$pageCategory;
    }

    /**
     * Creates all page links
     * @param string $ulClass Css class to assign to the ul
     * @return string
     */
    public static function createPageLinks() {
        if (!self::$links) {
            self::getView();
            $categories = Engine::getDB()
                    ->table('category')
                    ->orderBy('parent')
                    ->orderBy('position')
                    ->orderBy('name')
                    ->customWhere('`:TBL:`.`id`="ROOT" OR `:TBL:`.`parent`="ROOT"')
                    ->join('category', array(
                        'push' => true,
                        'join' => array(
                            'page' => array(
                                'where' => array(array('status' => 1)),
                            )
                        )
                    ))
//                    ->join('page', array(
//                        'where' => array(array('status' => 1)),
//                    ))
                    ->select(array(array(
                    'status' => 1,
            )));

            foreach ($categories as $category) {
                $pages = $category->page(array(
                    'orderBy' => 'position',
                    'where' => array(array(
                            'status' => 1
                        ))
                ));
                $subCategories = $category->category(array(
                    'orderBy' => 'position',
                    'push' => true,
                    'where' => array(array(
                            'status' => 1
                        ))
                ));

                $active = false;
                ob_start();
                foreach ($pages as $page) {
                    if (self::$page && self::$page->getId() === $page->id) {
                        self::$rawPageMap[$category->link . '/' . $page->link] = $page->title;
                        self::$rawPageMap[$category->link] = $category->name;
                        $active = true;
                    }
                    ?>
                    <li <?= (self::$page && self::$page->getId() === $page->id) ? 'class="active"' : '' ?>><a class="auto" tabindex="-1" href="<?= self::getView()->url('cms', 'page', 'view', array($category->link, $page->link)) ?>"><?= $page->title ?></a></li>
                    <?php
                }
                $pageList = ob_get_clean();

                ob_start();
                if ($category->id === 'ROOT') {
                    echo $pageList;
                }
                else {
                    $subCatLinks = self::subCategoryLinks($subCategories, $active);
                    ?>
                    <li class="dropdown <?= $active ? 'active' : '' ?>">
                        <a tabindex="-1" href="#" <?= ($pages->count() || $subCategories->count()) ? 'class="dropdown-toggle" data-toggle="dropdown"' : '' ?>><?= $category->name ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                            <?= $pageList ?>
                            <?= $subCatLinks ?>
                        </ul>
                    </li>
                    <?php
                }
                self::$links .= ob_get_clean();
            }
        }

        return self::$links;
    }

    private static function subCategoryLinks(ArrayCollection $categories, &$superActive) {
        ob_start();
        foreach ($categories as $sub) {
            $pages = $sub->page(array(
                'orderBy' => 'position',
                'where' => array(array(
                        'status' => 1
                    ))
            ));
            $active = false;
            ob_start();
            foreach ($pages as $page) {
                if (self::$page && self::$page->getId() === $page->id) {
                    self::$rawPageMap[$sub->link] = $sub->name;
                    $superActive = $active = true;
                }
                ?>
                <li <?= (self::$page && self::$page->getId() === $page->id) ? 'class="active"' : '' ?>><a tabindex="-1" class="auto" href="<?= self::getView()->url('cms', 'page', 'view', array($sub->link, $page->link)) ?>"><?= $page->title ?></a></li>
                <?php
            }
            $lis = ob_get_clean();
            ob_start();

            $dummyActive = false;
            $subLinks = self::subCategoryLinks($sub->category(array(
                                'orderBy' => 'position',
                                'push' => true,
                                'where' => array(array(
                                        'status' => 1
                                    ))
                            )), $dummyActive);

            if ($dummyActive && !$superActive) {
                $superActive = true;
                self::$rawPageMap[$sub->link] = $sub->name;
            }
            ?>
            <li class="dropdown-submenu <?= $active ? 'active' : '' ?>">
                <a href="#" tabindex="-1"class="dropdown-toggle" data-toggle="dropdown"><?= $sub->name ?></b></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                <?= $lis ?>
                <?= $subLinks; ?>
            </ul>
            </li>
            <?php
        }
        return ob_get_clean();
    }

    /**
     * Creates links for categories of articles
     * @param int $quantity Number of categories to create link for. This will 
     * start from the latest
     */
    public static function createArticleCategoryLinks($quantity = null, array $attributes = array()) {
        $artTable = Engine::getDB()->table('article');

        if ($quantity)
            $artTable->limit($quantity);

        if (self::getArticleCategory())
            $artTable->customWhere('`:TBL:`.`category` != "' . self::getArticleCategory() . '"');

        ob_start();
        foreach ($artTable->orderBy('dateCreated', Table::ORDER_DESC)->distinct('category') as $article) {
            ?>
            <li><a href="<?= self::getView()->url('cms', 'article', 'view-category', array(str_replace(' ', '-', strtolower($article->category)))) ?>"><?= $article->category ?></a></li>
            <?php
        }
        return '<ul' . self::parseAttributes($attributes) . '>' . ob_get_clean() . '</ul>';
    }

    /**
     * Creates links for articles
     * @param int $quantity Number of articles to create link for. This will start
     * from the latest
     */
    public static function createArticleLinks($quantity = null, array $attributes = array()) {
        $artTable = Engine::getDB()->table('article');

        if ($quantity)
            $artTable->limit($quantity);

        if (self::$article) {
            $artTable->customWhere('`:TBL:`.`id` != "' . self::$article->getId() . '"');
        }
        ob_start();
        foreach ($artTable->orderBy('dateCreated', Table::ORDER_DESC)->select() as $article) {
            ?>
            <li><a href="<?= self::getView()->url('cms', 'article', 'view', array(str_replace(' ', '-', strtolower($article->category)), $article->link)) ?>"><?= $article->title ?></a></li>
            <?php
        }
        return '<ul' . self::parseAttributes($attributes) . '>' . ob_get_clean() . '</ul>';
    }

    /**
     * Fetches the default page layout specified in the configuration for Cms
     * @return string
     */
    public static function getDefaultPageLayout() {
        return Engine::getConfig('modules', 'Cms', 'defaults', 'pageLayout');
    }

    /**
     * Fetches the default article layout specified in the configuration for Cms
     * @return string
     */
    public static function getDefaultArticleLayout() {
        return Engine::getConfig('modules', 'Cms', 'defaults', 'articleLayout');
    }

    /**
     * Fetches the current user
     * @return AUser
     */
    public static function getUser() {
        return (self::$user) ? self::$user : Engine::getUserIdentity()->getUser();
    }

    /**
     * Fetches the owner of the article/page
     * @return AUser
     */
    public static function getOwner() {
        if (self::$article) {
            $user = self::$article->user();
        }
        else if (self::$page) {
            $user = self::$page->user();
        }

        if ($user->count()) {
            return $user->first();
        }
    }

    /**
     * Fetches a copy of the view class
     * @return View
     */
    private static function getView() {
        return (self::$view) ? self::$view : new View();
    }

    /**
     * Creates page map
     * @param string $join What to join the location with
     * @param boolean $linked Indicates whether the location should be linked to
     * their pages
     * @return string
     */
    public static function createPageMap($join = ' / ', $linked = true) {
//        self::createPageLinks();
        if (!self::$map && self::$rawPageMap) {
            self::$map = '';
            (print_r(self::$rawPageMap));
            foreach (array_reverse(self::$rawPageMap) as $link => $label) {
                if (!empty(self::$map)) {
                    self::$map .= $join;
                }

                if ($linked)
                    self::$map .= '<a href="' . $link . '">';
                self::$map .= $label;
                if ($linked)
                    self::$map .= '</a>';
            }
        }
        return self::$map;
    }

    /**
     * Fetches the page map in raw form
     * @return array()
     */
    public static function getRawPageMap() {
        return self::$rawPageMap;
    }

    /**
     * Fetches the title of the page/article
     * @return string;
     */
    public static function getTitle() {
        if (self::$page) {
            return self::$page->getTitle();
        }
        else if (self::$article) {
            return self::$article->getTitle();
        }
        else if (Engine::getAction() === 'viewCategory') {
            return 'Category - ' . self::getArticleCategory();
        }
        else if (Engine::getAction() === 'listCategories') {
            return 'Categories';
        }
    }

    /**
     * Parse array of attributes into attribute = "value"
     * @param array $attributes
     * @return string
     */
    public static function parseAttributes(array $attributes) {
        $return = '';
        foreach ($attributes as $attr => $value) {
            $return .= ' ' . $attr . '="' . $value . '"';
        }
        return $return;
    }

    /**
     * Creates a subscription form
     * @return string
     */
    public static function createSubscriptionForm() {
        ob_start();
        ?>
        <form id="subscriptionForm" method="post" action="<?= self::getView()->url('cms', 'subscription', 'add') ?>">
            <input type="email" name="email" autocomplete="false" class="input-block-level" required="required" /><br />
            <input type="submit" value="Subscribe" class="btn btn-small" />
        </form>
        <?php
        return ob_get_clean();
    }

    private static function showWithComments() {
        return ((self::$page && self::$page->getAllowComments()) || (self::$article && self::$article->getAllowComments()));
    }

    /**
     * Fetches the comment form
     * @param boolean $withComments Indicates whether to return comments with the form
     * @return string
     */
    public static function getCommentForm($withComments = false) {
        if (!self::showWithComments())
            return null;

        ob_start();
        if (self::$user->is('guest')):
            if ($pc = self::getPageCategory()):
                $controller = 'page';
                $param = $pc->getLink() . ':' . self::getPage()->getLink();
            else:
                $controller = 'article';
                $param = self::getArticleCategoryLink() . ':' . self::getArticle()->getLink();
            endif;
            ?>
            <a class="safe" href="<?= self::getView()->url('cms', 'guest', 'login', array('cms', $controller, 'view', $param), 'comment-form-box') ?>">Login</a> to comment.
            <?php
            if ($withComments):
                echo '<hr />';
                echo self::showComments();
            endif;
        else:
            ?>
            <style>
                #comment-form-box {
                    background-color:#f5f5f5;
                }
            </style>
            <div class="row-fluid">
                <div id="comment-form-box" class="span12 well well-small">
                    <?php
                    if (CMS::getCommentStatus()):
                        ?>
                        <div class="alert alert-<?= self::$commentStatusType ?>">
                            <?= CMS::getCommentStatus() ?>
                        </div>
                        <?php
                    endif;
                    $commentForm = (self::$commentForm) ? self::$commentForm : new CommentForm();
                    $commentForm->setAttribute('action', '#comment-form-box');
                    echo $commentForm->render();

                    if ($withComments)
                        echo '<hr/>' . self::showComments();
                    ?>
                </div>
            </div>
        <?php
        endif;

        return ob_get_clean();
    }

    private static function getResponseForm($commentId) {
        ob_start();

        if (self::$user->is('guest')):
            if ($pc = self::getPageCategory()):
                $controller = 'page';
                $param = $pc->getLink() . ':' . self::getPage()->getLink();
            else:
                $controller = 'article';
                $param = self::getArticleCategoryLink() . ':' . self::getArticle()->getLink();
            endif;
            ?>
            <a class="safe" href="<?= self::getView()->url('cms', 'guest', 'login', array('cms', $controller, 'view', $param), 'comment' . $commentId) ?>">Login</a> to respond.
            <?php
        else:
            ?>
            <div class="row-fluid">
                <div id="response-form-box" class="span12">
                    <?php
                    if (CMS::getCommentResponseStatus()):
                        ?>
                        <div class="alert alert-<?= self::$commentResponseStatusType ?>">
                            <?= CMS::getCommentResponseStatus() ?>
                        </div>
                        <?php
                    endif;
                    $commentForm = new CommentForm();
                    $commentForm->setAttribute('action', '#comment' . $commentId);
                    $commentForm->get('content')->options->label = '<span class="text-info pull-right" style="font-size:smaller;font-style:italic">(250 characters maximum)</span>';
                    $commentForm->get('submit')->options->value = 'Respond';
                    $commentForm->get('parent')->options->value = $commentId;
                    echo $commentForm->render();
                    ?>
                </div>
            </div>
        <?php
        endif;

        return ob_get_clean();
    }

    private static function sortVotes() {
        self::$votes = array();
        foreach (self::$user->commentVote() as $vote) {
            self::$votes[$vote->getComment()] = $vote->getUpVote();
        }
    }

    /**
     * 
     * @param \Cms\Models\Comment $comment
     * @return int|null 0 - voted down<br />1 - voted up<br />null - no vote
     */
    private static function checkVote(Comment $comment) {
        if (!self::$votes)
            self::sortVotes();

        return @self::$votes[$comment->getId()];
    }

    /**
     * Checks if current user voted a comment up
     * @param \Cms\Models\Comment $comment
     * @return boolean
     */
    private static function votedUp(Comment $comment) {
        return (self::checkVote($comment) == 1);
    }

    /**
     * Checks if current user voted a comment down
     * @param \Cms\Models\Comment $comment
     * @return boolean
     */
    private static function votedDown(Comment $comment) {
        return (self::checkVote($comment) !== null && self::checkVote($comment) == 0);
    }

    /**
     * Displays the given comment
     * 
     * @param \Cms\Models\Comment $comment
     * @param boolean $withResponses Indicates whether to show the comment's responses or not
     * @return string
     */
    public static function displayComment(Comment $comment, $withResponses = true) {
        $owner = $comment->user()->first();
        if ($withResponses)
            $responses = $comment->comment(array('push' => true));

        $img = new FileOut();
        ob_start();
        ?>
        <li id="comment<?= $comment->getId() ?>">
            <div class="box">
                <div class="owner-img">
                    <?= $img($owner->getPicture(), array('attrs' => array('class' => 'img-polaroid'))) ?>
                </div>
                <div class="content">
                    <div class="info">
                        <span class="owner-name"><?= $owner->getFullName() ?></span>
                        <span class="date"><?= \DBScribe\Util::createTimestamp($comment->getDateCreated(), 'jS M, Y (H:i)') ?></span>
                    </div>
                    <div><?= $comment->getContent() ?></div>
                </div>
                <div class="actions">
                    <a data-loading="voting up..." data-id="vote-up" 
                       href="<?= self::$view->url('cms', 'comment', 'vote-up', array($comment->getId())) ?>" 
                       class="btn btn-mini vote <?= (self::votedUp($comment)) ? 'btn-primary disabled' : '' ?>" 
                       title="vote this comment up">
                        (<span class="count"><?= $comment->getUpVotes() ?></span>) 
                        <i class="icon-thumbs-up"></i> vote up
                    </a>
                    <a data-loading="voting down..." data-id="vote-down" 
                       href="<?= self::$view->url('cms', 'comment', 'vote-down', array($comment->getId())) ?>" 
                       class="btn btn-mini vote <?= (self::votedDown($comment)) ? 'btn-primary disabled' : '' ?>" 
                       title="vote this comment down">
                        (<span class="count"><?= $comment->getDownVotes() ?></span>) 
                        <i class="icon-thumbs-down"></i> vote down
                    </a>
                    <?php if ($withResponses): ?>
                        <a data-loading="loading responses..." data-id="responses" href="<?= self::$view->url('cms', 'comment', 'responses', array($comment->getId())) ?>" class="btn btn-mini" title="view responses for/reply this comment">(<span class="count"><?= $responses->count() ?></span>) <i class="icon-comment"></i> responses</a>
                    <?php endif; ?>
                    <small class="status alert"></small>
                </div>
                <?php if ($withResponses): ?>
                    <div class="responses">
                        <div class="box">
                            <div class="progress progress-info progress-striped active">
                                <div class="bar bar-danger" style="width:100%">Loading responses ...</div>
                            </div>
                        </div>
                        <?= self::getResponseForm($comment->getId()) ?>
                    </div>
                <?php endif; ?>
            </div>
        </li>
        <?php
        return ob_get_clean();
    }

    /**
     * Show the comments on the current page/article
     * @return string
     */
    public static function showComments($ajax = true) {
        if (!self::showWithComments())
            return null;

        if (!$ajax) {
            $comments = self::$page ? self::$page->comment() : self::$article->comment();
            ob_start();
            ?>
            <style>
                #comment-count, #response-count {
                    margin-bottom:5px;
                }

                #comments, #comments ul {
                    margin-left:0;
                }

                #comments li {
                    list-style: none;
                    display:block;
                }

                #comments > li > .box, .responses li > .box {
                    background-color:#eee;  
                    color:#000;
                    margin-bottom:10px;
                    min-height:60px;
                    padding:5px;
                    border-radius:10px;
                }

                #comments .owner-img {
                    width:60px;
                    height:60px;
                    text-align:center;
                    float:left;
                }

                #comments .info {
                    background-color:#f5f5f5;
                    font-size:smaller;
                    font-weight: bolder;
                    border-radius:5px;
                    padding:0 5px;
                    margin-bottom:5px;
                }

                #comments .info > .date {
                    float:right;
                }

                #comments .content,
                #comments .actions {
                    margin-left:80px;
                    margin-bottom:5px;
                }

                #comments .content {
                    border:1px solid #eee;
                    min-height:50px;
                    margin-bottom:5px;
                }

                #comments .actions .status {
                    padding-top:3px;
                    padding-bottom:5px;
                    display:none;
                }

                #comments .responses {
                    margin-left:80px;
                    border-left:4px solid #ddd;
                    min-height: 50px;
                    display:none;
                }
            </style>
            <div id="comment-count">
                <?= $comments->count() ?> Comments
            </div>
            <ul id="comments" class="well well-small">
                <?php
                foreach ($comments as $comment) {
                    echo self::displayComment($comment);
                }
                ?>
                <script>
                    function alertOut($actions) {
                        $actions.children('.status').fadeOut();
                    }
                    $(document).ready(function() {
                        $('#comments a:not(.safe)').live('click', function(e) {
                            e.preventDefault();
                            if ($(this).hasClass('disabled'))
                                return false;

                            if ($(this).data('loading') && !$(this).hasClass('disabled') && !$(this).hasClass('open') && !$(this).hasClass('loaded')) {
                                $(this).siblings('.status').html($(this).data('loading')).fadeIn();
                            }
                            $(this).addClass('disabled');

                            $actions = $(this).parent();
                            $responses = $actions.siblings('.responses');
                            if ($(this).data('id') === 'responses') {
                                if ($(this).hasClass('open')) {
                                    $responses.slideUp();
                                    $(this).removeClass('open');
                                }
                                else {
                                    $responses.slideDown();
                                    $(this).addClass('open');
                                }

                                $(this).removeClass('disabled');
                                if ($(this).children('.count').html() === '0' || !$(this).hasClass('open') || $(this).hasClass('loaded')) {
                                    if ($(this).children('.count').html() === '0') {
                                        $responses.children('.box').empty();
                                        $(this).addClass('loaded');
                                    }

                                    $actions.children('.status').fadeOut();
                                    return false;
                                }
                            }

                            var self = this;
                            $.get($(this).attr('href'), {}, function(data) {
                                if ($(self).hasClass('vote')) {
                                    if (data.message.indexOf('login') === -1) {
                                        $actions.children('.vote').removeClass('btn-primary disabled');
                                        $actions.children('.vote').children('i').removeClass('icon-white');

                                        $(self).addClass('btn-primary disabled').children('i').addClass('icon-white');
                                    }
                                    else {
                                        $(self).removeClass('disabled');
                                    }

                                    $actions.children('a[data-id="vote-up"]').children('.count').html(data.comment.up_votes);
                                    $actions.children('a[data-id="vote-down"]').children('.count').html(data.comment.down_votes);
                                    $(self).siblings('.status').html(data.message).fadeIn();
                                    setTimeout(alertOut, 3500, $actions);
                                } else if ($(self).data('id') === 'responses') {
                                    $responses.children('.box').html(data);
                                    $actions.children('.status').fadeOut();
                                    $(self).addClass('loaded');
                                }
                            });
                        });
                    });
                </script>
            </ul>
            <?php
            return ob_get_clean();
        }
        else {
            ?>
            <div id="comment-box">
                <div class="progress progress-striped active">
                    <div class="bar" style="width:100%">Loading Comments ...</div>
                </div>
                <script>
                    $(document).ready(function() {
                        $.get('/cms/<?= self::$page ? 'page' : 'article' ?>/comments/<?= self::$page ? self::$page->getId() : self::$article->getId() ?>', {}, function(data) {
                            $('#comment-box').html(data);
                        });
                    });
                </script>
            </div>
            <?php
        }
    }

    /**
     * Show the responses to a comment
     * @param Comment $comment
     * @param boolean $ajax
     * @return string
     */
    public static function showResponses(Comment $comment) {
        $responses = $comment->comment(array('push' => true));

        ob_start();
        ?>
        <ul class="well well-small">
            <?php
            foreach ($responses as $response) {
                echo self::displayComment($response, false);
            }
            ?>
        </ul>
        <?php
        return ob_get_clean();
    }

    /**
     * Sets the status of a new comment
     * @param string $status
     */
    public static function setCommentStatus($status) {
        self::$commentStatus = $status;
    }

    /**
     * Fetches the status of a new comment
     * @return string
     */
    public static function getCommentStatus() {
        return self::$commentStatus;
    }

    public static function getCommentStatusType() {
        return self::$commentStatusType;
    }

    public static function setCommentStatusType($commentStatusType) {
        self::$commentStatusType = $commentStatusType;
        return self;
    }

    public static function getCommentResponseStatus() {
        return self::$commentResponseStatus;
    }

    public static function getCommentResponseStatusType() {
        return self::$commentResponseStatusType;
    }

    public static function setCommentResponseStatus($commentResponseStatus) {
        self::$commentResponseStatus = $commentResponseStatus;
    }

    public static function setCommentResponseStatusType($commentResponseStatusType) {
        self::$commentResponseStatusType = $commentResponseStatusType;
    }

    /**
     * 
     * @param AUser $user
     */
    public static function setUser(AUser $user) {
        self::$user = $user;
    }

    /**
     * Sets the repository of the page/article
     * @param Repository $repository
     */
    public static function setRepository(Repository $repository) {
        self::$repository = $repository;
    }

    /**
     * Sets the current request
     * @param Request $request
     */
    public static function setRequest(Request $request) {
        self::$request = $request;
    }

    /**
     * Sets the view class
     * @param View $view
     */
    public static function setView(View $view) {
        self::$view = $view;
    }

    /**
     * Sets the form for comments
     * @param CommentForm $commentForm
     */
    public static function setCommentForm(CommentForm $commentForm) {
        self::$commentForm = $commentForm;
    }

    /**
     * Sets the CMS settings
     * @param array $settings
     */
    public static function setSettings(array $settings) {
        self::$settings = $settings;
    }

    public static function getSettings() {
        if (!self::$settings) {
            $config = include Engine::getModulePath() . 'Config' . DIRECTORY_SEPARATOR . 'local.php';
            self::$settings = $config['settings'];
        }
        return self::$settings;
    }

    public static function getSiteName() {
        $settings = self::getSettings();
        return $settings['siteName'];
    }

    public static function getLogoPath() {
        return self::getView()->getRenderer()->getAsset('images/logo');
    }

}
