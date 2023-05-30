<?php
namespace Elementor\Modules\NestedAccordion\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Modules\NestedElements\Base\Widget_Nested_Base;
use Elementor\Modules\NestedElements\Controls\Control_Nested_Repeater;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Nested Accordion widget.
 *
 * Elementor widget that displays a collapsible display of content in an
 * accordion style.
 *
 * @since 3.15.0
 */
class Nested_Accordion extends Widget_Nested_Base {

	const NESTED_ACCORDION = 'nested-accordion';

	public function get_name() {
		return static::NESTED_ACCORDION;
	}

	public function get_title() {
		return esc_html__( 'Accordion', 'elementor' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

	public function get_keywords() {
		return [ 'nested', 'tabs', 'accordion', 'toggle' ];
	}

	public function show_in_panel(): bool {
		return Plugin::$instance->experiments->is_feature_active( static::NESTED_ACCORDION );
	}

	private function add_accordion_style_section() {
		$this->start_controls_section(
			'section_accordion_style',
			[
				'label' => esc_html__( 'Accordion', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control('accordion_item_title_space_between', [
			'label' => esc_html__( 'Space between Items', 'elementor' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'default' => [
				'size' => 0,
			],
			'selectors' => [
				'{{WRAPPER}}' => '--n-accordion-item-title-space-between: {{SIZE}}{{UNIT}}',
			],
		]);

		$this->add_responsive_control('accordion_item_title_distance_from_content', [
			'label' => esc_html__( 'Distance from content', 'elementor' ),
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'default' => [
				'size' => 0,
			],
			'selectors' => [
				'{{WRAPPER}}' => '--n-accordion-item-title-distance-from-content: {{SIZE}}{{UNIT}}',
			],
		]);

		$this->start_controls_tabs( 'accordion__border_and_background' );

		foreach ( array( 'normal', 'hover', 'active' ) as &$state ) {
			$this->add_border_and_radius_style( $state );
		}

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'accordion_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--n-accordion-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'accordion_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} ' => '--n-accordion-padding : {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'color' => [
						'label' => esc_html__( 'Background Color', 'elementor' ),
					],
				],
				'selector' => '{{WRAPPER}}  .e-n-accordion-item > .e-con ',
			]
		);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'accordion_border',
					'selector' => '{{WRAPPER}} .e-n-accordion-item > .e-con',
				]
			);

			$this->add_responsive_control(
				'content_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
					'selectors' => [
						'{{WRAPPER}}' => '--n-content-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'content_padding',
				[
					'label' => esc_html__( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'selectors' => [
						'{{WRAPPER}} ' => '--n-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}
	/**
	 * @string $state
	 * @return void
	 */
	private function add_border_and_radius_style( $state ) {

		$selector = '{{WRAPPER}}  .e-n-accordion-item';
		$translated_tab_text = esc_html__( 'Normal', 'elementor' );

		switch ( $state ) {
			case 'hover':
				$selector .= ':hover';
				$translated_tab_text = esc_html__( 'Hover', 'elementor' );
				break;
			case 'active':
				$selector .= '[open]';
				$translated_tab_text = esc_html__( 'Active', 'elementor' );
				break;
		}

		$this->start_controls_tab('accordion_' . $state . '_border_and_background', [
			'label' => $translated_tab_text,
		]);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'accordion_background_' . $state,
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'fields_options' => [
					'color' => [
						'label' => esc_html__( 'Border Color', 'elementor' ),
					],
				],
				'selector' => $selector,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'accordion_border_' . $state,
				'selector' => $selector,
			]
		);

		$this->end_controls_tab();

	}

	/**
	 * @return void
	 */
	private function add_style_section() {

		$this->add_accordion_style_section();
		$this->add_content_style_section();

	}

	protected function item_content_container( int $index ) {
		return [
			'elType' => 'container',
			'settings' => [
				'_title' => sprintf( __( 'item #%s', 'elementor' ), $index ),
				'content_width' => 'full',
			],
		];
	}

	protected function get_default_children_elements() {
		return [
			$this->item_content_container( 1 ),
			$this->item_content_container( 2 ),
			$this->item_content_container( 3 ),
		];
	}

	protected function get_default_repeater_title_setting_key() {
		return 'item_title';
	}

	protected function get_default_children_title() {
		return esc_html__( 'Item #%d', 'elementor' );
	}

	protected function get_default_children_placeholder_selector() {
		return '.e-n-accordion';
	}

	protected function get_html_wrapper_class() {
		return 'elementor-widget-n-accordion';
	}

	protected function register_controls() {
		$this->start_controls_section( 'section_items', [
			'label' => esc_html__( 'Accordion', 'elementor' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'item_title', [
			'label' => esc_html__( 'Title', 'elementor' ),
			'type' => Controls_Manager::TEXT,
			'default' => esc_html__( 'Item Title', 'elementor' ),
			'placeholder' => esc_html__( 'Item Title', 'elementor' ),
			'label_block' => true,
			'dynamic' => [
				'active' => true,
			],
		] );

		$repeater->add_control(
			'element_css_id',
			[
				'label' => esc_html__( 'CSS ID', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
				'style_transfer' => false,
			]
		);

		$this->add_control( 'items', [
			'label' => esc_html__( 'Items', 'elementor' ),
			'type' => Control_Nested_Repeater::CONTROL_TYPE,
			'fields' => $repeater->get_controls(),
			'default' => [
				[
					'item_title' => esc_html__( 'Item #1', 'elementor' ),
				],
				[
					'item_title' => esc_html__( 'Item #2', 'elementor' ),
				],
				[
					'item_title' => esc_html__( 'Item #3', 'elementor' ),
				],
			],
			'title_field' => '{{{ item_title }}}',
			'button_text' => 'Add Item',
		] );

		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
				'separator' => 'before',

			]
		);
		$this->end_controls_section();

		$this->add_style_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items = $settings['items'];
		$id_int = substr( $this->get_id_int(), 0, 3 );
		$items_title_html = '';
		$this->add_render_attribute( 'elementor-accordion', 'class', 'e-n-accordion' );
		$tag = Utils::validate_html_tag( $settings['title_tag'] );

		foreach ( $items as $index => $item ) {
			$item_setting_key = $this->get_repeater_setting_key( 'item_title', 'items', $index );
			$item_classes = [ 'e-n-accordion-item', 'e-normal' ];
			$item_id = empty( $item['element_css_id'] ) ? 'e-n-accordion-item-' . $id_int : $item['element_css_id'];
			$item_title = $item['item_title'];

			$this->add_render_attribute( $item_setting_key, [
				'id' => $item_id,
				'class' => $item_classes,
			] );

			$title_render_attributes = $this->get_render_attribute_string( $item_setting_key );

			// items content.
			ob_start();
			$this->print_child( $index );
			$item_content = ob_get_clean();

			$items_title_html .= "\t<details {$title_render_attributes}>";
			$items_title_html .= "\t\t<summary class='e-n-accordion-item-title'><{$tag}>{$item_title}</{$tag}></summary>";
			$items_title_html .= "\t\t{$item_content}";
			$items_title_html .= "\t</details>";
		}

		?>
		<div <?php $this->print_render_attribute_string( 'elementor-accordion' ); ?>>
			<?php echo $items_title_html;// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<div class="e-n-accordion" role="tablist" aria-orientation="vertical">
			<# if ( settings['items'] ) {
			const elementUid = view.getIDInt().toString().substr( 0, 3 );
			const titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_tag );
			#>

			<# _.each( settings['items'], function( item, index ) {
			const itemCount = index + 1,
				itemUid = elementUid + itemCount,
				itemWrapperKey = itemUid,
				itemTitleKey = 'item-' + itemUid;

			if ( '' !== item.element_css_id ) {
				itemId = item.element_css_id;
			} else {
				itemId = 'e-n-accordion-item-' + itemUid;
			}

			view.addRenderAttribute( itemWrapperKey, {
				'id': itemId,
				'class': [ 'e-n-accordion-item','e-normal' ],
			} );

			view.addRenderAttribute( itemTitleKey, {
				'class': [ 'e-n-accordion-item-title' ],
			} );

			#>

			<details {{{ view.getRenderAttributeString( itemWrapperKey ) }}}>
				<summary {{{ view.getRenderAttributeString( itemTitleKey ) }}}>
					<{{{ titleHTMLTag }}}>
						{{{ item.item_title }}}
					</ {{{ titleHTMLTag }}}>
				</summary>
			</details>
			<# } ); #>
		<# } #>
	</div>
		<?php
	}


}
