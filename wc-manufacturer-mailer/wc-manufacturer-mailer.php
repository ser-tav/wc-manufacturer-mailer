<?php
	/*
		Plugin Name: WooCommerce Product Manufacturer Mailer
		Description: Send different emails to manufacturers depending on custom product taxonomy
		Author: Sergey T.
	*/
	
	
	class WC_Manufacturer_Mailer
	{
		
		function __construct()
		{
			add_action('woocommerce_checkout_order_processed', [$this, 'order_processed']);
			//add_action('wp_loaded', [ $this, 'on_load' ]);
		}
		
		public function on_load()
		{
			/*		if(isset($_REQUEST['test_order_process'])){
				$order_id = 9279;
				$this->order_processed($order_id);
			}*/
		}
		
		public function order_processed($order_id)
		{
			
			$emails_data = [];
			
			$order = wc_get_order($order_id);
			$order_items = $order->get_items();
			foreach ($order_items as $order_item_id => $order_item) {
				$product_id = $order_item->get_product_id();
				$order_item_meta_data = $order_item->get_formatted_meta_data();
				$product = $order_item->get_product();
				$name = $order_item->get_name();
				$quantity = $order_item->get_quantity();
				$price = $order_item->get_total();
				
				$product_data = [
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => []
				];
				
				foreach ($order_item_meta_data as $order_item_meta) {
					$display_key = '';
					$key = $order_item_meta->key;
					$display_key = $order_item_meta->display_key;
					$value = $order_item_meta->value;
					$display_value = $order_item_meta->display_value;
					
					if ($key == 'comment_text') {
						$display_key = 'Product comment';
					}
					if ($key == 'chest_cm') {
						$display_key = 'Chest (cm)';
					}
					if ($key == 'waist_cm') {
						$display_key = 'Waist (cm)';
					}
					if ($key == 'hips_cm') {
						$display_key = 'Hips (cm)';
					}
					$product_data['attributes'][] = [
                    'name' => $display_key,
                    'value' => $display_value
					];
				}
				
				$manufacturers = get_the_terms($product_id, 'manufacturer');
				foreach ($manufacturers as $manufacturer) {
					$manufacturer_id = $manufacturer->term_id;
					$manufacturer_email = get_term_meta($manufacturer_id, 'email_matufactures', true);
					$emails_data[$manufacturer_id]['email'] = $manufacturer_email;
					$emails_data[$manufacturer_id]['products'][$product_id] = $product_data;
				}
			}
			
			foreach ($emails_data as $manufacturer_id => $email_data) {
				ob_start();
				$products = $email_data['products'];
				$text_align = is_rtl() ? 'right' : 'left';
				$email_heading = 'New Order: #' . $order->get_order_number();
				
				wc_get_template('emails/template.php', array(
                'products' => $products,
                'order' => $order,
                'text_align' => $text_align,
                'email_heading' => $email_heading,
                'email' => $email_data['email'],
                'manufacturer_id' => $manufacturer_id
				));
				
				$email_content = ob_get_contents();
				ob_end_clean();
				$headers = array('Content-Type: text/html; charset=UTF-8');
				wp_mail($email_data['email'], 'New order #' . $order_id, $email_content, $headers);
			}
		}
	}
	
new WC_Manufacturer_Mailer();