# Object Oriented Wordpress Theme 

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

In the construtor of this class add the wordpress actions for ajax and the method that you will using

```sh
add_action( 'wp_ajax_contacts',       array( &$this, 'contacts' ) );
add_action( 'wp_ajax_nopriv_contacts', array( &$this, 'contacts' ) );
```sh

Then create the method

```sh
public function contacts()
{
    //our code
    die();
}
```sh


### Gwp_Hooks

This class should have all the wordpress hooks that you will create or use.

- The Actions  should be  in the method custom_hooks_actions_noadmin or custom_hooks_actions.
- The Filters  should be  in the method custom_hooks_filters_noadmin or custom_hooks_filters.
- There is already the method pre_get_posts for the Action pre_get_posts 

```sh 
    public function custom_hooks_actions() 
    {
      add_action('wploop', array($this, 'wploop') , 10 , 2);
    }
    
    public function wploop($content , $args)
    {
        //our code
    }
```sh


### Gwp_Shortcodes

This class should have all the wordpress shortcodes that you will create.

Add in the $shortcodes array, in the init method of this class, all the shortcode themes

```sh 
    public static function init() {
      
        $shortcodes = array(
            'youtube' => __CLASS__ . '::youtube'
        );
    }

```sh

Then create the static method where will be the shortcode code

```sh
    public static function youtube($atts, $content=null) {
        our code
    }

```sh


### Site

This class should have all the theme code. Basically hould be the  controller of your  theme. This Class have some methods that we will talk later


### Config System 

Configuration files are used to store any kind of configuration needed for the theme. They are plain PHP files, stored in the config/ folder, which return an associative array

```sh
    return array(
        'setting' => 'value',
        'options' => array(
            'foo' => 'bar',
        ),
    );
```sh

If the above configuration file was called myconf.php, you could access it using:

```sh
    $conf = Helper::load_config('myconf');
    echo  $conf['setting'] ;
```sh


### Theme Options 

Is a Configuration file where you could add some options that your theme will need. In this moment you can create only three type of inputs ( text , chekbox , select) 

```sh
    return array(
        'fields'  => array(
            array(
                'type'     => 'text',
                'id'       => 'teste_id',
                'title'    => 'Teste Id',
                'args'     => array(
                    'type'      => 'text',
                    'id'        => 'teste_id',
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
                    'id'        => 'teste_id2',
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
                    'id'        => 'responsive',
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
                    'id'        => 'cores',
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
```sh

### Menu Config 

Is a Configuration file (menu.php) where you create all your theme menus 

```sh
    return array(
        array('primary' => 'Primary Navigation'),
        array('footer'  => 'Footer Navigation')
    );
```sh

### Site Config 
Is a Configuration file (site.php) where you set all your theme configuration
```sh
    return array(
        'multilang'     => false,
        'translate_uri' => false,
        'default_lang'  => 'pt',
        'protocol'      => 'http://',
    );
```sh


### Lang Config 

If your theme is multilang, there is also Configuration file (lang.php). 

```sh
     return array(
        'name'   => 'namespace',
        'folder' => '/lang'
    );
```sh

The folder is where your files.po will be and the name is the namespace of _E("string" , namespace). Important in the configuration file site.php the option multilang should be true


### Flashdata

Supports flashdata, or session data that will only be available for the next server request, and are then automatically cleared. These can be very useful, and are typically used for informational or status messages (for example: "record 2 deleted").


Add Flashdata:

```sh
     Helper::set_flashdata("name"   , value);
```sh

Get Flashdata:

```sh
     Helper::get_flashdata("name");
```sh

Delete all Flashdata:

```sh
     Helper::unset_all_flashdata();
```sh



### Views System

A view is simply html file  that could include in any place of our code. This files are in the views folder

Create a view and set is variables

```sh
    $view = View::factory();
    $view->set('variable1', $data);
    $view->set('variable2', $data2);
    
    echo $view->render('viewfilename');

```sh

### Wordpress template files

In this theme you dont need to always call the header and the footer you just need to set what is the view that should be rendered

```sh
     global $site;
    
    $site->set_content('page' , array('template' =>'index'));
```sh

In the set_content method the first param is the view you want to call , and the second param is a array of the view variables


You could do that also through the Site Class , create a method where will be all our code for this wordpress template and them use in the template file

Site Class code :

```sh
     public function page()
     {
        $this->view->template  = "PAGE";
        $content = $this->view->render('page');
        $this->content($content);
    }
```sh

In themplate file 
```sh
    <?php 
        global $site;
        $site->page();
    ?>
```sh


The views header , head , footer , and main (the content view) are in views/template/