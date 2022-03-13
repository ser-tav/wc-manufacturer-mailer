<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>"/>
		<title><?php echo get_bloginfo('name', 'display'); ?></title>
		<style>
			<?php wc_get_template('emails/email-styles.php'); ?>
		</style>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<div id="wrapper" dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				<tr>
					<td align="center" valign="top">
						<div id="template_header_image">
							<?php
								if ($img = get_option('woocommerce_email_header_image')) {
									echo '<p style="margin-top:0;"><img src="' . esc_url($img) . '" alt="' . get_bloginfo('name', 'display') . '" /></p>';
								}
							?>
						</div>
						<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container">
							<tr>
								<td align="center" valign="top">
									<!-- Header -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header">
										<tr>
											<td id="header_wrapper">
												<h1><?php echo $email_heading; ?></h1>
											</td>
										</tr>
									</table>
									<!-- End Header -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Body -->
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
										<tr>
											<td valign="top" id="body_content">
												<!-- Content -->
												<table border="0" cellpadding="20" cellspacing="0" width="100%">
													<tr>
														<td valign="top">
															<div id="body_content_inner">
																<?php
																	$show_sku = true;
																	$show_image = false;
																	$image_size = array(32, 32);
																	$plain_text = false;
																	$sent_to_admin = false;
																	$text_align = is_rtl() ? 'right' : 'left';
																	$margin_side = is_rtl() ? 'left' : 'right';
																?>
																<div id="body_content_inner_mr_css_attr">
																	<p><?= 'Hi "' . $email . '" [' . $manufacturer_id . '], you have new order on website <a href="'.home_url().'">'.get_bloginfo('name').'</a>'; ?></p>
																	<h2>
																		<?php
																			if ($sent_to_admin) {
																				$before = '<a class="link" href="' . esc_url($order->get_edit_order_url()) . '">';
																				$after = '</a>';
																				} else {
																				$before = '';
																				$after = '';
																			}
																			/* translators: %s: Order ID. */
																			echo wp_kses_post($before . sprintf(__('[Order #%s]', 'woocommerce') . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format('c'), wc_format_datetime($order->get_date_created())));
																		?>
																	</h2>
																	<div style="margin-bottom:40px">
																		<table class="td_mr_css_attr" cellspacing="0" cellpadding="6" style="width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" border="1">
																			<tbody>
																				<tr>
																					<th class="td_mr_css_attr" scope="col" style="text-align:;">Product</th>
																					<th class="td_mr_css_attr" scope="col" style="text-align:;">Quantity</th>
																					<th class="td_mr_css_attr" scope="col" style="text-align:;">Price</th>
																				</tr>
																			</tbody>
																			<tbody>
																				<?php foreach ($products as $product) : ?>
																				<tr class="order_item_mr_css_attr">
																					<td class="td_mr_css_attr"
																					style="text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word">
																						<h3 style="text-decoration: underline;"><?= $product['name']; ?></h3>
																						<ul class="wc-item-meta_mr_css_attr">
																							<?php foreach ($product['attributes'] as $attribute) : ?>
																							<li>
																								<strong class="wc-item-meta-label_mr_css_attr" style="float:left;margin-right:.25em;clear:both"><?= $attribute['name']; ?>:</strong>
																								<p><?= $attribute['value']; ?></p>
																							</li>
																							<?php endforeach; ?>
																						</ul>
																					</td>
																					<td class="td_mr_css_attr" style="text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
																						<?= $product['quantity']; ?>
																					</td>
																					<td class="td_mr_css_attr" style="text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif">
																						<span class="woocommerce-Price-amount_mr_css_attr amount_mr_css_attr">
																							<?= get_woocommerce_currency_symbol(); ?>
																							<?= $product['price'] ?>
																						</span>
																					</td>
																				</tr>
																				<?php endforeach; ?>
																			</tbody>
																			<tfoot>
																				<?php if ( $order->get_customer_note() ) { ?>
																					<tr>
																						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
																							<?php esc_html_e( 'Note:', 'woocommerce' ); ?>
																						</th>
																						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
																							<?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?>
																						</td>
																					</tr>
																				<?php } ?>
																			</tfoot>
																		</table>
																	</div>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td align="center" valign="top" style="padding-top: 0!important;">
															<!-- Footer -->
															<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
																<tr>
																	<td valign="top" style="padding-top: 0!important;">
																		<table border="0" cellpadding="10" cellspacing="0" width="100%">
																			<tr>
																				<td colspan="2" valign="middle" id="credit" style="padding-top: 0!important;">
																					<a href="<?= home_url(); ?>">
																						<img src="<?= get_custom_logo(); ?>" alt="">
																					</a>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>