<?php 
if(!class_exists('element_gva_service_interne')):
   class element_gva_service_interne{

      public function render_form(){
         $fields = array(
            'type' => 'gva_service_interne',
            'title' => t('service interne'),
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
         <?php ob_start() ?>         <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css'>
          <div class='app'>    <div class='app_inner'>

                  
                                         <?php for($i=1; $i<=10; $i++){ ?>                        
										 <?php                     $icon = "icon_{$i}";                           
										 $title = "title_{$i}";                          
										 $link = "link_{$i}";						   
										 $title1 = "link_1_title_{$i}";						   
										 $link2 = "link_2_{$i}";						   
										 $title2 = "title_2_{$i}";
										 $link3 = "link_3_{$i}";						   
										 $title3 = "title_3_{$i}";
										 $checkbox_url = "checkbox_url_{$i}";										 ?>                       
										 <?php if($$link && $$title ){ ?>	
                                         <?php 
if ($$checkbox_url == "interne") {
	$blnk = ""; 
} else {
	
	$blnk = "target='_blank'";
}
?>										 
										 <input checked='' id='tab-<?php print $i ?>' name='buttons' type='radio'>                               <label for='tab-<?php print $i ?>'>        
										 <div class='app_inner__tab service-<?php print $i ?>'>          
										 <h2>                          
										 <?php print t($$title) ?>          
										 </h2>
<div class='tab_left'>
            
            <div class='tab_left__image1'>
              <span class='img-service-<?php print $i ?>'><img class="imgserint"src="/themes/gavias_castron/images/<?php print $i ?>.svg" width='80px' height='80px'></span>
            </div>
          </div>										 
										 <div class='tab_right'>		               
										 <a class="" href="<?php print t($$link) ?>" <?php echo $blnk ?> ><?php print t($$title1) ?> </a>			
										 <?php if($$link2 && $$title2 ){ ?> 
										 <a class="" href="<?php print t($$link2) ?>" <?php echo $blnk ?> ><?php print t($$title2) ?> </a> 
										 <?php } ?>
										<?php if($$link3 && $$title3 ){ ?> 
										 <a class="" href="<?php print t($$link3) ?>" <?php echo $blnk ?> ><?php print t($$title3) ?> </a> 
										 <?php } ?>
										 </div> 
										  


										 </div>		   </label>        

                        <?php } ?>    
                     <?php } ?>
</div></div></div>
           
         <?php return ob_get_clean();
      }
   }
 endif;  



