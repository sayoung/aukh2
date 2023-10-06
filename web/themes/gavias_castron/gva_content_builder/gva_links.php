<?php 
 
if(!class_exists('element_gva_links')):
   class element_gva_links{

      public function render_form(){
         $fields = array(
            'type' => 'gva_links',
            'title' => t('Services'),
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
                  'options'   => gavias_content_builder_animate_aos(),
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
               'id'        => "icon_{$i}",
               'type'      => 'text',
               'title'     => t("Icon {$i}")
            );
            $fields['fields'][] = array(
               'id'        => "title_{$i}",
               'type'      => 'text',
               'title'     => t("Title {$i}")
            );
            $fields['fields'][] = array(
               'id'        => "link_{$i}",
               'type'      => 'text',
               'title'     => t("Lien Button service{$i}")
            );
			$fields['fields'][] = array(
               'id'        => "link_1_title_{$i}",
               'type'      => 'text',
               'title'     => t("title Button service{$i}")
            );
			 $fields['fields'][] = array(
               'id'        => "link_2_{$i}",
               'type'      => 'text',
               'title'     => t("Lien Button suivi de service {$i}")
            );
			$fields['fields'][] = array(
               'id'        => "title_2_{$i}",
               'type'      => 'text',
               'title'     => t("title Button suivi de service {$i}")
            );
			 $fields['fields'][] = array(
               'id'        => "link_3_{$i}",
               'type'      => 'text',
               'title'     => t("Lien Button suivi de service {$i}")
            );
			$fields['fields'][] = array(
               'id'        => "title_3_{$i}",
               'type'      => 'text',
               'title'     => t("title Button suivi de service {$i}")
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
            $default["icon_{$i}"] = '';
            $default["title_{$i}"] = '';
            $default["link_{$i}"] = '';
			$default["link_1_title_{$i}"] = '';
			$default["link_2_{$i}"] = '';
			$default["title_2_{$i}"] = '';
			$default["link_3_{$i}"] = '';
			$default["title_3_{$i}"] = '';
			$default["checkbox_url_{$i}"] = '';
         }

         extract(gavias_merge_atts($default, $attr));

         $_id = gavias_content_builder_makeid();
         
         if($animate) $el_class .= ' wow ' . $animate; 
         ?>
         <?php ob_start() ?>

            <a class="btn-hidden-links" href="#"><span class="ion-ios-close-outline"></span></a>
		             	   <div id="heading-aust" class="widget gsc-heading  align-left-2 style-1 text-light ">
							   <div class="heading-top">
								   <h2 class="title fsize-00"><span><?php print t("E-Services") ?></span></h2>
							   </div>
							</div>
				     <div class="flex-container" >

							

                     <?php for($i=1; $i<=10; $i++){ ?>
                        <?php 
                           $icon = "icon_{$i}";
                           $title = "title_{$i}";
                           $link = "link_{$i}";
						   $title1 = "link_1_title_{$i}";
						   $link2 = "link_2_{$i}";
						   $title2 = "title_2_{$i}";
						   $link3 = "link_3_{$i}";
						   $title3 = "title_3_{$i}";
						   $checkbox_url = "checkbox_url_{$i}";
						   
                        ?>
                        <?php if($$link && $$title ){ ?>
<?php 
$blnk = "";
if ($$checkbox_url == "interne") {
	$url_home = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME']; 
} else {
	$url_home = "";
	$blnk = "target='_blank'";
}
?>
                           	<div class="flex-slide <?php $text = preg_replace('~[^-\w]+~', '', $$title); print $text  ?>">
		<div class="flex-title"><?php print t($$title) ?></div>
		<div class="flex-about button-1"><a class="hover-servi" href="<?php echo ($url_home); ?><?php print t($$link) ?>" <?php echo $blnk ?> ><?php print t($$title1) ?> </a> 
		<?php if($$link2 && $$title2 ){ ?> <a class="hover-ser" href="<?php echo ($url_home); ?><?php print t($$link2)?>" <?php echo $blnk ?> ><?php print t($$title2) ?> </a><?php } ?>
		<?php if($$link3 && $$title3 ){ ?> <a class="hover-ser" href="<?php echo ($url_home); ?><?php print t($$link3)?>"  <?php echo $blnk ?> > <?php print t($$title3) ?> </a><?php } ?>
		</div> 
	 
	</div>
                              
                        <?php } ?>    
                     <?php } ?>
                </div> 
	
	
         <?php return ob_get_clean();
      }
   }
 endif;  



