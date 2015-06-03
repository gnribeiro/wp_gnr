# Wordpress Object Oriented Theme 

This theme has several classes that will help you create a wordpress theme. You can find them in the folder libs. We will describe some of them

#### Version
1.0.0


### Installation - Dependencies

You need Node JS [node js](https://nodejs.org/)

You need grunt installed globally:

```sh
$ npm install -g grunt-cli
```
Bower installed. Bower is a command line utility.
```sh
$ npm install -g bower
```

### Installation

```sh
$ npm install
$ bower install
$ grunt
```

### Development

watch js, css, less:
```sh
$ grunt watch
```
### Production
distrubution task (compress less, minify css, uglify, minify js)
```sh
$ grunt prod
```


### Gwp_Ajax

This class should have all your Ajax code 

In the construct of this class add, the wordpress ajax action and the method that you will using

```sh
add_action( 'wp_ajax_contacts',        array($this, 'contacts' ) );
add_action( 'wp_ajax_nopriv_contacts', array($this, 'contacts' ) );
```

Then create the method

```sh
public function contacts()
{
    //our code
    die();
}
```


### Gwp_Hooks

This class should have all the wordpress hooks that you will create or use.

- The Actions  should be  in the method custom_hooks_actions_noadmin or custom_hooks_actions.
- The Filters  should be  in the method custom_hooks_filters_noadmin or custom_hooks_filters.
- There is already the method pre_get_posts for the action pre_get_posts 

```sh 
    public function custom_hooks_actions() 
    {
      add_action('wploop', array($this, 'wploop') , 10 , 2);
    }
    
    public function wploop($content , $args)
    {
        //our code
    }
```


### Gwp_Shortcodes

This class should have all the wordpress shortcodes that you will create.

Add in the $shortcodes array, in the init method of this class, all the shortcode themes

```sh 
    public static function init() {
      
        $shortcodes = array(
            'youtube' => __CLASS__ . '::youtube'
        );
    }

```

Then create the static method where will be the shortcode code

```sh
    public static function youtube($atts, $content=null) {
        our code
    }

```


### Site

This class should have all the theme code. Basically should be the  controller of your  theme. This Class have some methods that we will talk later


### Config System 

Configuration files are used to store any kind of configuration needed for the theme. They are plain PHP files, stored in the config/ folder, which return an associative array

```sh
    return array(
        'setting' => 'value',
        'options' => array(
            'foo' => 'bar',
        ),
    );
```

If the above configuration file was called myconf.php, you could access it using:

```sh
    $conf = Helper::load_config('myconf');
    echo  $conf['setting'] ;
```


### Theme Options 

Is a configuration file where you could add some options that your theme will need. In this moment you can create only three type of inputs ( text , chekbox , select) 

```sh
    return array(
        'fields'  => array(
            array(
                'type'     => 'text',
                'id'       => 'teste_id',
                'title'    => 'Teste Id',
                'args'     => array(
                    'type'      => 'text',
                    'name'      => 'teste_id',
                    'desc'      => 'descrição  teste Id',
                    'class'     => 'css_class'
                )
            ),
            
            array(
                'type'     => 'text',
                'id'       => 'teste_id2',
                'title'    => 'Teste Id2',
                'args'     => array(
                    'name'      => 'teste_id2',
                    'desc'      => 'descrição  teste Id2',
                    'class'     => 'css_class'
                )
            ),
            
            array(
                'type'     => 'checkbox',
                'id'       => 'responsive',
                'title'    => 'Teste responsive',
                'args'     => array(
                    'value'     => '1',
                    'name'      => 'responsive',
                    'desc'      => 'descrição  responsive',
                    'class'     => 'css_class'
                )
            ),
            array(
                'type'     => 'select',
                'id'       => 'cores',
                'title'    => 'cores',
                'args'     => array(
                    'options'    => array(
                        'azul'    => 'cor azul',
                        'amarelo' => 'cor amarelo',
                        'verde'   => 'cor verde',
                    ),
                    'name'      => 'cores',
                    'desc'      => 'descrição cores',
                    'class'     => 'cores'
                )
            ),
        )
    )
```

### Menu Config 

Is a configuration file (menu.php) where you create all your theme menus 

```sh
    return array(
        array('primary' => 'Primary Navigation'),
        array('footer'  => 'Footer Navigation')
    );
```

### Site Config 
Is a configuration file (site.php) where you set all your theme configuration
```sh
    return array(
        'multilang'     => false,
        'translate_uri' => false,
        'default_lang'  => 'pt',
        'protocol'      => 'http://',
    );
```


### Lang Config 

If your theme is multilang, there is also configuration file (lang.php). 

```sh
     return array(
        'name'   => 'namespace',
        'folder' => '/lang'
    );
```

The folder is where your files.po will be and the name is the namespace of _e("string" , namespace). Important in the configuration file site.php the option multilang should be true


### Content Post Types 
Is a  file (customPostTypes.php) in the config folder where will be all your content Post types. Use this site http://generatewp.com/post-type/ to help you to create your content Post types

### Flashdata

Supports flashdata, or session data that will only be available for the next server request, and are then automatically cleared. These can be very useful, and are typically used for informational or status messages (for example: "record 2 deleted").


Add Flashdata:

```sh
     Helper::set_flashdata("name"   , value);
```

Get Flashdata:

```sh
     Helper::get_flashdata("name");
```

Delete all Flashdata:

```sh
     Helper::unset_all_flashdata();
```



### Views System

A view is a simply php file that could be include in any place of our code, without any logic programming. This files are in the views folder

Create a view and set is variables

```sh
    $view = View::factory();
    $view->set('variable1', $data);
    $view->set('variable2', $data2);
    
    echo $view->render('viewfilename');

```

### Wordpress template files

In this theme you dont need to always call the header and the footer, you only need to set what is the view that should be render

```sh
     global $site;
    
    $site->set_content('page' , array('template' =>'PAGE'));
```

In the set_content method the first parameter is the view you want to call , and the second parameter is a array of the view variables.


You could do that also through the Site Class this way.

Site Class code :

```sh
     public function page()
     {
        $this->view->set('template', "PAGE");
        $content = $this->view->render('page');
        $this->content($content);
    }
```

In the template file 
```sh
    <?php 
        global $site;
        $site->page();
    ?>
```


The views header , head , footer , and main (the content view) are in views/template/

### CONSTANTS

- VIEWS  (view folder path)
- LIBS   (libs folder path)
- CONFIG (config folder path)
- THEMEURL
- THEMEPATH

### Methods of Site Class

- set_view($view , $data = array())

```sh
    $site->set_view($view , $data )
```

- get_view($view , $data = array())

```sh
    $view = $site->get_view($view , $data )
```

- pagination($num_pages) 


```sh
    echo $site->pagination(10)
```
### HELPER

This class have, like her name says, some helpers:

- current_uri() 
```sh
    HELPER::current_uri()
```

- current_url() 
```sh
    HELPER::current_url()
```

- get_protocol() 
```sh
    HELPER::get_protocol()
```

- redirect($location, $status=302)
```sh
    HELPER::redirect($location, $status)
```


- getSiteTitle()

```sh
    HELPER::getSiteTitle()
```

- siteInfo()

```sh
    HELPER::siteInfo()
```

- link_select($url , $class)

```sh
    HELPER::link_select("http://www.xpto.com" , "active")
```
