<?php namespace Prismify\Facebook\Components;

use Cms\Classes\ComponentBase;
use Prismify\Facebook\Models\Settings;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

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
        $fb = new Facebook([
            'app_id'        => Settings::get('fb_app_id'),
            'app_secret'    => Settings::get('fb_app_secret'),
            'page_id'       => Settings::get('fb_page_id')
        ]);

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get(
                '/'.$this->pageId.'/feed?limit=25',
                '{access-token}'
            );
        } catch(FacebookResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
        } catch(FacebookSDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        $posts = $response->getGraphNode()->asArray()['data'];

        $num = 1;

        foreach ($posts as &$post){

            // load only a limited number of posts (maxItems)
            if($this->property('postsPerPage') < $num++) break;

            if (isset($post->message))
                $post->short = substr ($post->message, 0, $this->property('postsMaxDesc')).'...';
            else
                $post->short = '';

            array_push($result, $post);
        }

        return $result;
    }
}
