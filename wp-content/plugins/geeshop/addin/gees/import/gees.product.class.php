<?php
/**
Desc: Klasa obsługi produktu w WooCommerce 
 */
//error_reporting(0);
if ( ! class_exists( 'GeesProductClass' ) ) :

	final class GeesProductClass {  
		public $version = '1.0.0';
		public $product_id = null;
		public $user_id = null;
		public $actual_name = 0;
		public $new_product = 0;
		public $actual_desc = 0;
		public $actual_excerpt = 0;
		public $actual_category = 0;
		public $actual_enabled = 0;
		public $actual_ean = 0;
		public $actual_symbol = 0;
		public $actual_price = 0;
		public $actual_allegro_exclude = 0;
		public $symb = "";
		protected static $_instance = null;
		
		public function __construct() {
			if (empty($this->user_id))
				$this->user_id = 1;
			
		}
		
		public function importProduct($product = array()){
			//print_t($product);
			$this->actual_name = (isset($product["actual_name"])? $product["actual_name"] : 0);
			$this->actual_desc = (isset($product["actual_desc"])? $product["actual_desc"] : 0);
			$this->actual_excerpt = (isset($product["actual_excerpt"])? $product["actual_excerpt"] : 0);
			$this->actual_enabled = (isset($product["actual_enabled"])? $product["actual_enabled"] : 0);
			$this->actual_category = (isset($product["actual_category"])? $product["actual_category"] : 0);
			$this->actual_ean = (isset($product["actual_ean"])? $product["actual_ean"] : 0);
			$this->actual_symbol = (isset($product["actual_symbol"])? $product["actual_symbol"] : 0);
			$this->actual_price = (isset($product["actual_price"])? $product["actual_price"] : 0);
			$this->actual_allegro_exclude = (isset($product["actual_allegro_exclude"])? $product["actual_allegro_exclude"] : 0);
			
			$product_id = (isset($product["product_id"])? $product["product_id"] : '');
		
			if (!empty($product_id))
				$this->product_id = $this->getProductByID( $product_id );
			if (empty($this->product_id)){
				$sku = (isset($product["sku"])? $product["sku"] : '');
				if (!empty($sku))
					$this->product_id = $this->getProductBySku( $sku );
			}
				//print "here";
				$new_prd_id = $this->product_id;
			if (empty($this->product_id)){
				$new_prd_id = $this->ProductAdd($product);
			}
			$this->ProductUpdate($product);
			//$this->productAttrsUpdate($attrs, $values)	
			//print $new_prd_id;			
			return $new_prd_id;
		}
		 
		public function ProductAdd($product = array()){
			//print " ProductAdd ";
			$this->new_product = 1;
			$this->product_id = null;
			
						$post_status = "publish";
			if (!empty($this->new_product) and !empty($this->actual_enabled)) {
				$post_status = 'draft';
			}		
			//print_r($product );
			
			$title = str_replace("gees_tag4321", "&", isset($product['title']) ? $product['title'] : '');

			$post_content = str_replace("gees_tag4321", "&", isset($product['content']) ? $product['content'] : '');
			
			if ( !empty( $post_content ) )
			{
				$post_content = html_entity_decode($post_content);//isset($product['content']) ? html_entity_decode($product['content']) : '';
			}
				
			$prd = array(
				'post_author' => $this->user_id,
				//'post_content' => $content,				
				'comment_status' => 'closed',
				'post_status' 	=> $post_status,
				'post_title' 	=> $title,//isset($product['title']) ? $product['title'] : '',
				'post_parent' 	=> '',
				'post_type' 	=> "product",
				'post_content' 	=> $post_content
			);
			$this->product_id = wp_insert_post( $prd, $wp_error );
			$new_prd_id = $this->product_id;
			print_r($wp_error );
			//print_r($prd );
			if($this->product_id){
				
					update_post_meta( $this->product_id, '_downloadable', 'no');
					update_post_meta( $this->product_id, '_virtual', 'no');
					$this->ProductUpdate($product);
				//	$attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
					//add_post_meta($this->product_id, '_thumbnail_id', $attach_id);
				}	
			return $new_prd_id;
		}
		
		public function ProductUpdate($product){
			//print " ProductUpdate ";

			//print_r($product);

			foreach( $product as $key=>$p){
				$product[$key] = (($p==Array())? "": $p);  					
			}
//			print_t($product);
			if (!empty($this->product_id)){
				//Check if category already exists
			//	$category ='Nazwa2';
				//$cat_ID = get_cat_ID( $category );

				//If it doesn't exist create new category
			//	if($cat_ID == 0) {
				//		$cat_name = array('cat_name' => $category);
				//	wp_insert_category($cat_name);
			/*	$term  =  wp_insert_term(
					  "$category", // the term 
					  'product_cat', // the taxonomy
					  array(
						'description'=> '',
						'slug' => strtolower( str_ireplace( ' ', '-', $category) )
					  )
					);*/
				//}
				
						
			if (empty($this->actual_enabled)) 
			{
				update_post_meta( $this->product_id, '_visibility', 'visible' );
			}

				
				if (!empty($this->new_product) or (!empty($this->actual_category))){
					$cat = isset( $product["category"]) ? $product["category"] : "";		
					$term = get_term_by('slug', $cat, 'product_cat');
					//print_r($term);
					if (isset($term->term_id) ){
						$wyniczek = wp_set_object_terms($this->product_id, array($term->term_id), 'product_cat');
						//if (isset($wyniczek[0]))
						{
							//print $cat_id = $wyniczek[0];
						//	wp_set_post_terms( $this->product_id, $cat_id, 'product_cat');
						}
						//print_r($wyniczek);
						//$_product = wc_get_product( $this->product_id );
						//print_r($_product);
					}
				}
				//Get ID of category again incase a new one has been created
				//$new_cat_ID = get_cat_ID($category);
				$prd_info = array();
				$prd_info['ID'] = $this->product_id;
				
				$title = str_replace("gees_tag4321", "&", isset($product['title']) ? $product['title'] : '');
				if (!empty($this->actual_name))
					$prd_info['post_title']= $title;//isset($product['title']) ? $product['title'] : '';

				
				$excerpt = str_replace("gees_tag4321", "&", isset($product['excerpt']) ? $product['excerpt'] : '');
				
				if (!empty($this->actual_excerpt))
					$prd_info['post_excerpt']= $excerpt;//isset($product['excerpt']) ? html_entity_decode($product['excerpt']) : '';

				$post_content = str_replace("gees_tag4321", "&", isset($product['content']) ? $product['content'] : '');
				
				if (/*empty($this->new_product) or */ !empty($this->actual_desc)){
					 $prd_info['post_content']= html_entity_decode($post_content);//isset($product['content']) ? html_entity_decode($product['content']) : '';
				}
				
				/*$prd = array(
					'ID'           => $this->product_id,
					'post_content' => isset($product['content']) ? $product['content'] : '',
					'post_title' => isset($product['title']) ? $product['title'] : '',
					'post_excerpt' => isset($product['excerpt']) ? $product['excerpt'] : '',
					//'post_category' => array($new_cat_ID)
				);*/
				$prd = $prd_info;
				wp_update_post( $prd );	
				
				wp_set_object_terms($this->product_id, 'simple', 'product_type');
				
				//dodawanie kategorii
				//$term = get_term_by('name', 'FREZARKA',0);
				//print 'term';
				//print_t($term );
				//if (isset($term->term_id))
				//	wp_set_object_terms($this->product_id, $term->term_id, 'product_cat');
//					wp_set_object_terms($this->product_id, array('_new'), 'product_cat');

				

//				else
				update_post_meta( $this->product_id, '_stock_status', 'instock');
				update_post_meta( $this->product_id, 'total_sales', '0');
				if (!empty($this->new_product) or !empty($this->actual_price)) {
					update_post_meta( $this->product_id, '_regular_price',(isset($product["price"]) ? $product["price"] : 0));
					//update_post_meta( $this->product_id, '_sale_price', (isset($product["sale_price"])? $product["sale_price"] : 0) );
				}
				
				if (isset($product["tax_id"]))
					if (!empty( $product["tax_id"] )) 
						update_post_meta( $this->product_id, '_tax_class', $product["tax_id"] );
				update_post_meta( $this->product_id, '_purchase_note', "" );
				update_post_meta( $this->product_id, '_featured', "no" );
				update_post_meta( $this->product_id, '_weight', (isset($product["weight"])? (empty( $product["weight"] )?'':$product["weight"])  : "") );
				update_post_meta( $this->product_id, '_length', (isset($product["length"])? (empty( $product["length"] )?'':$product["length"])  : "") );
				update_post_meta( $this->product_id, '_width', (isset($product["width"])? (empty( $product["width"] )?'':$product["width"])  : '') );
				update_post_meta( $this->product_id, '_height', (isset($product["height"])? (empty( $product["height"] )?'':$product["height"]) : '') );
				if (!empty($this->new_product) or !empty($this->actual_symbol)) 
					update_post_meta( $this->product_id, '_sku', (isset($product["sku"])? $product["sku"] : ''));
				if (!empty($this->new_product) or !empty($this->actual_price)) {
					update_post_meta( $this->product_id, '_sale_price_dates_from', (isset($product["sale_price_dates_from"])? $product["sale_price_dates_from"] : '') );
					update_post_meta( $this->product_id, '_sale_price_dates_to', (isset($product["sale_price_dates_to"])? $product["sale_price_dates_to"] : '') );
					update_post_meta( $this->product_id, '_price', (isset($product["price"]) ? $product["price"] : 0) );
				}
				
				if (!empty($this->new_product) or !empty($this->actual_allegro_exclude)) 
					update_post_meta( $this->product_id, '_gees_allegro_exclude', (isset($product["allegro_exclude"]) ? $product["allegro_exclude"] : 0) );
				
				 
				update_post_meta( $this->product_id, '_sold_individually', "" );
				update_post_meta( $this->product_id, '_manage_stock', "no" );
				update_post_meta( $this->product_id, '_backorders', "no" );
				if (!empty($this->new_product)) 
					update_post_meta( $this->product_id, '_stock', "1" );
				
				update_post_meta( $this->product_id, '_gees_external_prd_id', (isset($product["prd_id"])? $product["prd_id"] : '') );
				if (!empty($this->new_product) or !empty($this->actual_ean)) {
					update_post_meta( $this->product_id, '_gees_ean', (isset($product["ean"])? $product["ean"] : '') );
				}
				update_post_meta( $this->product_id, '_gees_manaf', (isset($product["manaf"])? $product["manaf"] : '') );
				update_post_meta( $this->product_id, '_gees_allegro_cena', (isset($product["price_allegro"])? $product["price_allegro"] : '') );
				//print_t($product);
				if (isset($product["actual_allegro"]) and !empty($product["actual_allegro"]) ){
					if (isset($product["allegro_title"]) and  $product["allegro_title"] != "")
						update_post_meta( $this->product_id, '_gees_allegro_title', (isset($product["allegro_title"])? $product["allegro_title"] : '') );
					if (isset($product["allegro_title2"]) and  $product["allegro_title2"] != "")
						update_post_meta( $this->product_id, '_gees_allegro_title2', (isset($product["allegro_title2"])? $product["allegro_title2"] : '') );
					if (isset($product["allegro_desc"]) and  $product["allegro_desc"] != "")
						update_post_meta( $this->product_id, '_gees_allegro_desc', (isset($product["allegro_desc"])? $product["allegro_desc"] : '') );				
				}
				if (isset($product["actual_ceneo"]) and !empty($product["actual_ceneo"])){
					if (isset($product["ceneo_name"]) and  $product["ceneo_name"] != "")
						update_post_meta( $this->product_id, '_gees_ceneo_name', (isset($product["ceneo_name"])? $product["ceneo_name"] : '') );
				}

				//opinie
//				update_post_meta( $this->product_id, 'comment_status', "closed" );


				//update_post_meta( $this->product_id, '_gees_external_prd_id', (isset($product["price"])? $product["price"] : 0) );
				//update_post_meta( $this->product_id, '_gees_external_prd_id', (isset($product["price"])? $product["price"] : 0) );
				
				/*
				// file paths will be stored in an array keyed off md5(file path)
				$downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);

				$file_path =md5($uploadDIR['baseurl']."/video/".$video);


				$_file_paths[  $file_path  ] = $downdloadArray;
				// grant permission to any newly added files on any existing orders for this product
				//do_action( 'woocommerce_process_product_file_download_paths', $this->product_id, 0, $downdloadArray );
				update_post_meta( $this->product_id, '_downloadable_files ', $_file_paths);
				update_post_meta( $this->product_id, '_download_limit', '');
				update_post_meta( $this->product_id, '_download_expiry', '');
				update_post_meta( $this->product_id, '_download_type', '');
				*/
				$filename = (isset($product["img_url"])? $product["img_url"] : '');
				if (!empty($filename ) and ( !has_post_thumbnail( $this->product_id )))
					$this->AddImage($filename);
				//update_post_meta( $this->product_id, '_product_image_gallery', '');
			
			}
		}
		
		public function getProductBySku( $sku ) {
			global $wpdb;
//			$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
			$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT p.post_id FROM $wpdb->postmeta pm inner join $wpdb->posts p on p.post_id = pm.post_id WHERE p.post_type = 'product' and p.post_status = 'publish' and pm.meta_key='_sku' AND pm.meta_value='%s' LIMIT 1", $sku ) );

			if ( $product_id ) 
				return $product_id;
			else
				return null;

		}

		public function getProductByID( $id ) {
			global $wpdb;
			$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID post_id FROM $wpdb->posts WHERE  post_type = 'product' and ID = '%s' LIMIT 1", $id ) );

			if ( $product_id ) 
				return $product_id;
			else
				return null;

		}

			public function AddImages($params){
				$obj = isset($params['imgs'])?$params['imgs']:"";
				$params = @json_decode($obj);
				$res = "";;
				//if ($params!= null and is_array($params))
				{
					
					//foreach($params as $key => $value)
					//if  (is_array($params))
					{
						$filename = "";
						$this->symb = str_replace(" ", "_", isset($params->symbol)?$params->symbol:"");
						$data_img = isset($params->img)?$params->img:"";
						$id = isset($params->id)?$params->id:"";
						$file_path = isset( $params->name )?$this->symb."_". str_replace(" ", "_",$params->name):"";
						$file_path = str_replace("#", "_", $file_path);
						$file_path = str_replace("/", "_", $file_path);
						$file_path = str_replace("*", "_", $file_path);
						$file_path = str_replace("\\", "_", $file_path);
						$file_path = str_replace("@", "_", $file_path);
						$file_path = str_replace("(", "_", $file_path);
						$file_path = str_replace(")", "_", $file_path);
						$imgd = isset($params->imgd)?$params->imgd:"";
					$res = $this->SaveImage($id, $file_path, $data_img, $imgd);
					}
				}
				return $res;
			}
		
		private function SaveImage($id, $file_path, $img, $glowne)
		{
			$attach_id = "";
			$res = "";
			try{
				$uid = $this->symbol.'_'.uniqid(); 
				
				$wp_upload_dir = @wp_upload_dir();
//				print	$file_path2 = GEESHOP_PLUGIN_DIR . "addin/gees/download/imgs/in/".$file_path;
				$file_path2 = $wp_upload_dir["path"]."/".$file_path;
				$data = base64_decode($img);
				$success = file_put_contents($file_path2, $data);
			}Catch(exception  $ex)
			{
				
				print_r( $ex);
			}
			$filename = $file_path2;
	   		$filetype = @wp_check_filetype( basename( $filename ), null );

			// Get the path to the upload directory.
			//$wp_upload_dir = @wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = array(
				'guid'           => $wp_upload_dir['url'] . '' . basename( $filename ), 
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);
	   
	   
		   {
		   $attach_id = @wp_insert_attachment( $attachment, $filename, $id );
		   // you must first include the image.php file
		   // for the function wp_generate_attachment_metadata() to work
		   require_once(ABSPATH . 'wp-admin/includes/image.php');
		   $attach_data = @wp_generate_attachment_metadata( $attach_id, $filename );
		   @wp_update_attachment_metadata( $attach_id, $attach_data );
		   if (!empty($glowne)) {
				@set_post_thumbnail( $id, $attach_id );
			//add_post_meta($id, '_thumbnail_id', $attach_id, true);
		   }
		   else
		   if (!empty($attach_id)){
			   $res = get_post_meta($id,'_product_image_gallery', true);
			   if (!empty($res))
				$res.=",$attach_id";
			   else
				$res.="$attach_id";
			   update_post_meta($id, '_product_image_gallery', $res);

			   
		   }
		 }
		 return $jsonReturn = array(
				  'Status'  =>  ''
				  ,'g'  => $res
				  ,'id'  => $id
				  ,'aid'  => $attach_id
				  );
				  print_r($jsonReturn);
		}
		
		
			private function AddImage($filename){
				// $filename should be the path to a file in the upload directory.
					$filename = '/path/to/uploads/2013/03/filename.jpg';
					//$filename = $wp_upload_dir['url']."/uploads/revslider/AutoXpert/3_axpert.jpg";
					// The ID of the post this attachment is for.
					$parent_post_id = $this->product_id;

					// Check the type of file. We'll use this as the 'post_mime_type'.
					$filetype = @wp_check_filetype( basename( $filename ), null );

					// Get the path to the upload directory.
					$wp_upload_dir = @wp_upload_dir();

					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);

					// Insert the attachment.
					$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

					// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					// Generate the metadata for the attachment, and update the database record.
					$attach_data = @wp_generate_attachment_metadata( $attach_id, $filename );
					@wp_update_attachment_metadata( $attach_id, $attach_data );

					@set_post_thumbnail( $parent_post_id, $attach_id );

			}
			public function wcproduct_set_attributes($post_id, $attributes) {
				$i = 0;
				$product_attributes = array();
				// Loop through the attributes array
				foreach ($attributes as $name => $value) {
					$product_attributes[$name] = array (
						'name' => htmlspecialchars( stripslashes( $name ) ), // set attribute name
						'value' => $value, // set attribute value
						//'position' => 1,
						'is_visible' => 1,
						'is_variation' => 0,
						'is_taxonomy' => 1
					);

					$i++;
				}

				// Now update the post with its new attributes
				update_post_meta($post_id, '_product_attributes', $product_attributes);
			}
		
	
		public function productAttrsUpdate($attrs = array(), $values = array()){			
		//	if (is_array($attrs))
			//	update_post_meta( $this->product_id, '_product_attributes', $attrs);
			$this->wcproduct_set_attributes($this->product_id, $values);
		}
	}
	

endif;