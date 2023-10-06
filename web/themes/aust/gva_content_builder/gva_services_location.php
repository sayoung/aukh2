<?php 
if(!class_exists('element_gva_services_location')):
   class element_gva_services_location{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_services_location',
            'title' => t('Services Location'),
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'admin'     => true
               ),
               array(
                  'id'        => 'skin_text',
                  'type'      => 'select',
                  'title'     => 'Skin Text for box',
                  'options'   => array(
                     'text-dark'  => t('Text Dark'), 
                     'text-light' => t('Text Light')
                  ) 
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
               'id'        => "title_{$i}",
               'type'      => 'text',
               'title'     => t("Title {$i}")
            );
            $fields['fields'][] = array(
               'id'           => "address_{$i}",
               'type'         => 'text',
               'title'        => t("Address {$i}"),
            );
            $fields['fields'][] = array(
               'id'        => "phone_{$i}",
               'type'      => 'text',
               'title'     => t("Phone {$i}")
            );
            $fields['fields'][] = array(
               'id'        => "email_{$i}",
               'type'      => 'text',
               'title'     => t("Email {$i}")
            );
         }
         return $fields;
      }

      public static function render_content( $attr = array(), $content = '' ){
         global $base_url;
         $default = array(
            'title'           => '',
            'skin_text'       => '',
            'el_class'        => 'text-dark',
            'animate'         => '',
            'animate_delay'   => ''
         );

         for($i=1; $i<=10; $i++){
            $default["title_{$i}"] = '';
            $default["address_{$i}"] = '';
            $default["phone_{$i}"] = '';
            $default["email_{$i}"] = '';
         }

         extract(gavias_merge_atts($default, $attr));

         $el_class .= ' ' . $skin_text;
         if($animate) $el_class .= ' wow ' . $animate; 
         ?>
         <?php ob_start() ?>
         <div class="gsc-service-location <?php echo $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>> 
            <div class="uk-slideset-init nav-slideset-bottom" data-uk-slideset="{animation: 'scale',xlarge:3, large:3, medium:2, small:1}">
               <div class="uk-slidenav-position uk-margin">
                  <ul class="uk-grid uk-slideset">
                     <?php for($i=1; $i<=10; $i++){ ?>
                        <?php 
                           $title   = "title_{$i}";
                           $address = "address_{$i}";
                           $phone   = "phone_{$i}";
                           $email   = "email_{$i}";
                        ?>
                        <?php if($$title){ ?>
                           <li>
                              <div class="box-item">
                                 <?php if($$title){ ?><div class="title"><?php print $$title ?></div><?php } ?>         
                                 <?php if($$address){ ?><div class="address"><i class="fas fa-map-marker-alt"></i><?php print $$address ?></div><?php } ?>
                                 <?php if($$phone){ ?><div class="phone"><i class="fas fa-phone-volume"></i><?php print $$phone ?></div><?php } ?>
                                 <?php if($$email){ ?><div class="email"><i class="far fa-envelope"></i><?php print $$email ?></div><?php } ?>
                              </div>
                           </li>
                        <?php } ?>    
                     <?php } ?>
                  </ul>
                  <ul class="uk-slideset-nav uk-dotnav uk-flex-center"></ul>
               </div>      
            </div>  
         </div>   

         <?php return ob_get_clean();
      }

   }
 endif;  



