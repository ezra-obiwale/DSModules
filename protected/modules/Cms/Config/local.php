<?php

return array(
    'settings' => array(
        'siteName' => 'DScribe CMS', // Name of the site
        'template' => '', // Template to use
        'listOnHomePage' => false, // Indicates whether to list articles on home page
    ),
    /* CAUTION:
     * 
     * EDITING ANYTHING BELOW HERE MIGHT MAKE THE MODULE UNSTABLE
     */
    'defaults' => array(
        'controller' => 'Page',
        'action' => 'view',
        'defaultLayout' => 'cms-main',
        'homeLayout' => 'cms-page',
        'pageLayout' => 'cms-page',
        'articleLayout' => 'cms-article',
        'articleCategoriesLayout' => 'cms-article-categories',
        'articleCategoryLayout' => 'cms-article-category',
        'errorLayout' => 'cms-error',
    ),
    'autoload' => array(
        'files' => array(
            0 => 'Helpers/CMS.php',
        ),
        'dirs' => array(
        ),
    ),
);
