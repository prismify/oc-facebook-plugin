<?php namespace Prismify\Facebook\Components;

use Cms\Classes\ComponentBase;

class Feed extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * Message to display when there are no messages.
     * @var string
     */
    public $noPostsMessage;

    public function componentDetails()
    {
        return [
            'name'        => 'Facebook Feed',
            'description' => 'Displays a list of latest facebook posts on the page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'postsPerPage' => [
                'title'             => 'Posts per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value.',
                'default'           => '3',
            ],
            'postsMaxDesc'   => [
                'title'			    => 'Posts description length',
                'description'	    => 'This value is used to determine what a maximal length post description.',
                'default'		    => 100,
                'type'			    => 'string'
            ],
            'noPostsMessage' => [
                'title'             => 'No posts message',
                'description'       => 'Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.',
                'type'              => 'string',
                'default'           => 'No posts found',
                'showExternalParam' => false,
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();

        $this->posts = $this->page['posts'] = $this->listPosts();

    }

    protected function prepareVars()
    {
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');
    }

    public function listPosts()
    {
        return;
    }
}
