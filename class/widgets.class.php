<?
/* Envato Widget Classes */
$markets = array(
	'themeforest'=>'Themeforest', 
	'codecanyon'=>'Code Canyon', 
	'activeden'=>'Active Den', 
	'audiojungle'=>'Audio Jungle', 
	'videohive'=>'Video Hive', 
	'graphicriver'=>'Graphic River', 
	'3docean'=>'3D Ocean', 
	'photodune'=>'Photo Dune'
);
$timeframes = array(
	'items_last_week'	=>	'Last Week',
	'items_last_three_months'	=>	'3 Months'	
);
add_action('widgets_init', 'register_jw_widgets');
function register_jw_widgets(){
	register_widget('Envato_Popular');
}
class Envato_Popular extends WP_Widget{
	
	public function __construct(){
		parent::__construct(
			'envato_popular',
			'Envato Popular',
			array('description' => __('Display latest items from an Envato Marketplace based on parameters.'))
		);
	}
	
	public function widget($a, $i){
		global $envato, $markets;
		
		$title = apply_filters('widget_title', $i['title']);
		$tf = $i['timeframe'];
		$mk = $i['website'];
		
		echo $a['before_widget'];
		
		if(!empty($title)) echo $a['before_title'].$title.$a['after_title'];
		
		$envData = $envato->query_popular($i['website']);
		$sliced = array_slice($envData->$tf, 0, $i['number']);
		?><!-- transient arguments: <?=print_r($a);?> --><?
		?><!-- transient instance: <?=print_r($i);?> --><?
		?><div class="envato_item_wrapper"><?
		foreach($sliced as $mp){
		?>
        	<div id="item_<?=$mp->id;?>" class="envato_item"><a href="<?=$mp->url?>?ref=<?=$i['referral'];?>" title="<?=$mp->item;?> &#10;Created By: <?=$mp->user?> &#10;<?=$markets[$mk]?>"><img src="<?=$mp->thumbnail;?>" alt="[IMAGE] <?=$mp->item.' by '.$mp->user;?>" /></a></div>
        <?
		}
		?></div>
		<div style="clear:both;">&nbsp;</div><?
		
		
		echo $a['after_widget'];
		
		
	}
	
	public function form($i){
		global $markets, $timeframes;
		
		$title = isset($i['title']) ? $i['title'] : '';
		$referral = isset($i['referral']) ? $i['referral'] : '';
		?>
        	<p>
            	<label for="<?=$this->get_field_name('title'); ?>">Title:</label>
                <input class="widefat" id="<?=$this->get_field_id('title');?>" name="<?=$this->get_field_name('title');?>" type="text" value="<?=esc_attr($title);?>" />
            </p>
        	<p>
            	<label for="<?=$this->get_field_name('referral'); ?>">Envato Username:</label>
                <input class="widefat" id="<?=$this->get_field_id('referral');?>" name="<?=$this->get_field_name('referral');?>" type="text" value="<?=esc_attr($referral);?>" />
            </p>
            <p>
            	<label for="<?=$this->get_field_name('website');?>" />Website:</label>
                <select name="<?=$this->get_field_name('website');?>" id="<?=$this->get_field_id('website');?>" class="widefat">
				<? foreach($markets as $k => $v):?>
                    <option value="<?=$k;?>" <? selected($i['website'], $k, true);?>><?=$v;?></option>
                <? endforeach; ?>
                </select>
            </p>
            <p>
            	<label for="<?=$this->get_field_name('timeframe');?>" />Timeframe:</label>
                <select name="<?=$this->get_field_name('timeframe');?>" id="<?=$this->get_field_id('timeframe');?>" class="widefat">
				<? foreach($timeframes as $k => $v):?>
                    <option value="<?=$k;?>" <? selected($i['timeframe'], $k, true);?>><?=$v;?></option>
                <? endforeach; ?>
                </select>
            </p>
            <p>
            	<label for="<?=$this->get_field_name('number');?>" />Number:</label>
                <input type="text" name="<?=$this->get_field_name('number');?>" id="<?=$this->get_field_id('number');?>" value="<?=$i['number'];?>" >
            </p>
            <p class="description">Number range is 1 to 50.</p>
        <?
		
	}
	
	public function update($ni, $oi){
		
		$i = $oi;
		$sn = intval($ni['number']);
		if($sn < 1){$sn = '1';}elseif($sn > 50){$sn = '50';}
		$i['number'] = $sn;
		
		$i['timeframe'] = $ni['timeframe'];
		$i['website'] = $ni['website'];
		$i['title'] = !empty($ni['title']) ? strip_tags($ni['title']) : '';
		$i['referral'] = !empty($ni['referral']) ? strip_tags($ni['referral']) : 'jaywood';
		
		return $i;
		
	}
	
}
?>