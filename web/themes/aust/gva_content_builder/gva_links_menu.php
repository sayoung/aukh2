<?php 
if(!class_exists('element_gva_links_menu')):
   class element_gva_links_menu{

      public function render_form(){
         $fields = array(
            'type' => 'gva_links_menu',
            'title' => t('Links footer'),
            'size' => 3,
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title For Admin'),
                  'admin'     => true
               ),
               array(
                  'id'        => 'animate',
                  'type'      => 'select',
                  'title'     => t('Animation'),
                  'desc'      => t('Entrance animation for element'),
                  'options'   => gavias_content_builder_animate(),
                  'class'     => 'width-1-2'
               ), 
               array(
                  'id'        => 'animate_delay',
                  'type'      => 'select',
                  'title'     => t('Animation Delay'),
                  'options'   => gavias_content_builder_delay_aos(),
                  'desc'      => '0 = default',
                  'class'     => 'width-1-2'
               ), 
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
               ),   
            ),                                     
         );

         for($i=1; $i<=10; $i++){
            $fields['fields'][] = array(
               'id'     => "info_${i}",
               'type'   => 'info',
               'desc'   => "Information for item {$i}"
            );
            $fields['fields'][] = array(
               'id'        => "title_{$i}",
               'type'      => 'text',
               'title'     => t("Title {$i}")
            );
            $fields['fields'][] = array(
               'id'        => "link_{$i}",
               'type'      => 'text',
               'title'     => t("link {$i}")
            );
		   $fields['fields'][] = array(
               'id'        => "checkbox_url_{$i}",
               'type'      => 'select',
			   'required'      => 'required',
               'title'     => t("lien intenrne ou externe {$i}"),
			   'options'   => aust_url(),
			   'desc'      => '0 = default',
               'class'     => 'width-1-2'
            );
         }
         return $fields;
      }

      public static function render_content( $attr = array(), $content = '' ){
         global $base_url;
         $default = array(
            'title'           => '',
            'el_class'        => '',
            'animate'         => '',
            'animate_delay'   => ''
         );

         for($i=1; $i<=10; $i++){
            $default["title_{$i}"] = '';
            $default["link_{$i}"] = '';
			$default["checkbox_url_{$i}"] = '';
         }

         extract(gavias_merge_atts($default, $attr));

         $_id = gavias_content_builder_makeid();
         
         if($animate) $el_class .= ' wow ' . $animate; 
         ?>
         <?php ob_start() ?>

         <div class="gsc-links gv-sticky-menu <?php echo $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>> 
            <a class="btn-hidden-links" href="#"><span class="ion-ios-close-outline"></span></a>
            <div class="content-wrapper">
               <div class="content-inner">
                  <ul>              
                     <?php for($i=1; $i<=10; $i++){ ?>
                        <?php 
                           $title = "title_{$i}";
                           $link = "link_{$i}";
						   $checkbox_url = "checkbox_url_{$i}"; 
                        ?>
						<?php 
if ($$checkbox_url == "interne") {
	$url_home = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME']; 
} else {
	$url_home = "";
}
?>
                        <?php if($$link && $$title){ ?>
							<li>
                                 <a href="<?php echo ($url_home); ?><?php print t($$link) ?>">
  
                                    <span class="title"><?php print t($$title) ?></span>
                                 </a></li>
                        <?php } ?>    
                     <?php } ?>
                  </ul> 
               </div>  
            </div>    
         </div>   
         <?php return ob_get_clean();
      }
   }
 endif;  



