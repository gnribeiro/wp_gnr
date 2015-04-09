<?php

class Gwp_Breadcumbs{

    protected $showOnHome;
    protected $delimiter;
    protected $url;
    protected $post ;
    protected $home        = 'Home';
    protected $showCurrent = 1;
    protected $before      = '<span class="current">';
    protected $after       = '</span>';
    protected $breadcumb   = array();


    public function __construct($showOnHome = true , $delimiter = ' &raquo; '){
        global $post;

        $this->post       = $post;
        $this->showOnHome = $showOnHome;
        $this->delimiter  = $delimiter;
        $this->url        = get_bloginfo('url');

        $this->created();
    }


    protected function created(){
        if( (is_home() || is_front_page() ) && $this->showOnHome ){
            $this->breadcumb[] = sprintf('<div id="crumbs"><a href="%s">%s</a></div>' ,$this->url  , $this->home );

            return;
        }
        else{
            $this->breadcumb[] = sprintf('<div id="crumbs"><a href="%s">%s</a>%s' ,$this->url, $this->home, $this->delimiter );

            if ( is_category() ) {
                $this->categorie();
            }
            elseif(is_search()){
               $this->search();
            }
            elseif(is_day()){
               $this->day();
            }
            elseif(is_month()){
               $this->month();
            }
            elseif(is_year()){
               $this->year();
            }
            elseif(is_single() && !is_attachment() ){
               $this->single();
            }
            elseif(is_attachment() ){
               $this->attachment();
            }
            elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $this->content_type();
            }
            elseif ( is_page() && !$this->post->post_parent ) {
                $this->page();
            }
            elseif ( is_page() && $this->post->post_parent ) {
                $this->page_parents();
            }
            elseif ( is_tag() ) {
                $this->tag();
            }
            elseif ( is_author() ) {
                $this->author();
            }
            elseif ( is_404() ) {
                $this->error_404();
            }

            $this->breadcumb[] = '</div>';
        }

    }


    protected function categorie(){
        $category = get_category(get_query_var('cat'), false);

        if ($category->parent != 0) {
            $this->breadcumb[] = get_category_parents($category->parent, TRUE, ' ' . $this->delimiter . ' ');
        }
        $this->breadcumb[] = $this->before . single_cat_title('', false) . $this->after;
    }


    protected function search(){
        $this->breadcumb[] = $this->before . get_search_query() . $this->after;
    }


    protected function day(){
        $this->breadcumb[] = sprintf('<a href="%s">%s</a>%s', get_year_link(get_the_time('Y')), get_the_time('Y'),  $this->delimiter);
        $this->breadcumb[] = sprintf('<a href="%s">%s</a>%s', get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'), $this->delimiter);
        $this->breadcumb[] = $this->before . get_the_time('d') . $this->after;
    }


    protected function month(){
        $this->breadcumb[] = sprintf('<a href="%s">%s</a>%s', get_year_link(get_the_time('Y')), get_the_time('Y'), $this->delimiter);
        $this->breadcumb[] = $this->before . get_the_time('F') . $this->after;
    }


    protected function year(){
        $this->breadcumb[] = $this->before .  get_the_time('Y') . $this->after;
    }


    protected function single(){
        if ( get_post_type() != 'post' ) {

            $post_type = get_post_type_object(get_post_type());
            $slug      = $post_type->rewrite;

            $this->breadcumb[] = sprintf('<a href="%s/%s/" >%s</a>', $this->url, $slug['slug'], $post_type->labels->singular_name);

            if ($this->showCurrent == 1)
                $this->breadcumb[] = $this->delimiter . $this->before .  get_the_title() . $this->after;
        }
        else {
            $cat  = get_the_category();
            $cat  = $cat[0];
            $cats = get_category_parents($cat, TRUE, ' ' . $this->delimiter . ' ');

            if ($this->showCurrent == 0)
                $cats = preg_replace("#^(.+)\s$this->delimiter\s$#", "$1", $cats);

            $this->breadcumb[] = $cats;

            if ($this->showCurrent == 1)
                $this->breadcumb[] = $this->before .  get_the_title() . $this->after;
        }
    }


    protected function attachment(){
        $parent = get_post($post->post_parent);
        $cat    = get_the_category($parent->ID);
        $cat    = $cat[0];

       $this->breadcumb[] = get_category_parents($cat, TRUE, ' ' . $this->delimiter . ' ');
       $this->breadcumb[] = sprintf('<a href="%s" >%s</a>', get_permalink($parent), $parent->post_title);

       if ($this->showCurrent == 1)
            $this->breadcumb[] = $this->delimiter . $this->before .  get_the_title() . $this->after;
    }


    protected function content_type(){
        $post_type         = get_post_type_object(get_post_type());
        $this->breadcumb[] = $this->before .  $post_type->labels->singular_name . $this->after;
    }


    protected function page(){
        if ($this->showCurrent == 1)
            $this->breadcumb[] = $this->before .  get_the_title() . $this->after;
    }


    protected function page_parents(){
        $parent_id   = $this->post->post_parent;
        $breadcrumbs = array();

        while ($parent_id) {
            $page          = get_page($parent_id);
            $breadcrumbs[] = sprintf('<a href="%s">%s</a>' , get_permalink($page->ID), get_the_title($page->ID));
            $parent_id     = $page->post_parent;
        }

        $breadcrumbs = array_reverse($breadcrumbs);

        for ($i = 0; $i < count($breadcrumbs); $i++) {
            $this->breadcumb[] = $breadcrumbs[$i];

            if ($i != count($breadcrumbs)-1)
                $this->breadcumb[] = $this->delimiter;
        }

        if ($this->showCurrent == 1)
            $this->breadcumb[] = $this->delimiter . $this->before .  get_the_title() . $this->after;
    }


    protected function tag(){
        $this->breadcumb[] = $this->before .  single_tag_title('', false) . $this->after;
    }


    protected function error_404(){
        $this->breadcumb[] = $this->before .  single_tag_title('', false) . $this->after;
    }


    protected function author(){
        global $author;

        $userdata          = get_userdata($author);
        $this->breadcumb[] = $this->before .  $userdata->display_name . $this->after;
    }


    public function __toString(){
        return implode('', $this->breadcumb);
    }
}
?>