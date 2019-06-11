<?php namespace Prismify\Facebook\Components;

use Cms\Classes\ComponentBase;
use Prismify\Facebook\Models\Settings;
use Illuminate\Pagination\LengthAwarePaginator;

class Feed extends ComponentBase
{
    /**
     * A collection of posts to display
     * @var Collection
     */
    public $posts;

    /**
     * Facebook app id
     * @var int
     */
    private $app_id;

    /**
     * Facebook app secret
     * @var string
     */
    private $app_secret;

    /**
     * Facebook page id
     * @var int
     */
    private $page_id;

    /**
     * Facebook page access token
     * @var string
     */
    private $access_token;

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
            'showPagination' => [
                'title'             => 'Show pagintion',
                'description'       => 'Leave cheked if u want to show pagination',
                'type'              => 'checkbox',
            ],
            'postsPerPage' => [
                'title'             => 'Posts per page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Invalid format of the posts per page value.',
                'default'           => '6',
                'depends'  =>    ['showPagination'],
            ],
            'noPostsMessage' => [
                'title'             => 'No posts message',
                'description'       => 'Message to display in the blog post list in case if there are no posts. This property is used by the default component partial.',
                'type'              => 'string',
                'default'           => 'No posts found',
                'showExternalParam' => false,
            ]
        ];
    }

    public function onInit(){

    }

    public function onRun()
    {

        $this->app_id = $this->page['app_id'] = Settings::get('fb_app_id'); // Set facebook app id
        $this->app_secret = Settings::get('fb_app_secret'); // Set facebook app secret
        $this->page_id = Settings::get('fb_page_id'); // Set facebook page id
        $this->access_token = Settings::get('fb_access_token'); // Set facebook page access token;

        $this->addJs('https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.3&appId='.$this->app_id.'&autoLogAppEvents=1'); // Inject facebook javascript sdk with our facebook app id

        $this->prepareVars();
        $this->posts = $this->page['posts'] = $this->listPosts();


    }

    protected function prepareVars()
    {
        $this->noPostsMessage = $this->page['noPostsMessage'] = $this->property('noPostsMessage');
    }

    public function listPosts()
    {

        $fb = new \Facebook\Facebook([
            'app_id'        => $this->app_id,
            'app_secret'    => $this->app_secret,
            'page_id'       => $this->page_id
        ]);



        $result = [];


        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get(
                '/'.$this->page_id.'/feed?fields=permalink_url',
                $this->access_token
            );




        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        $graphEdge = $pagesEdge = $response->getGraphEdge();

        if($this->property('showPagination') == 1){

            // Collect and paginate graphedge
            $items = collect($graphEdge); // Collect posts
            $currentPage = input('page') ? input('page') : 1; // Current page
            $perPage = $this->property('postsPerPage'); // Items per page

            $paginate = new LengthAwarePaginator($items->forPage($currentPage, $perPage), $items->count(), $perPage, $currentPage);

            return $paginate;

        } else {
            
            $num = 1;
            
            foreach ($graphEdge as &$post) {
                $perPage = $this->property('postsPerPage'); // Items per page
                array_push($result, $post);
            }

            return $result;
        }
    }
}
