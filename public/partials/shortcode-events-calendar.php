<?php 
$months = array(
	strtotime("first day of previous month"),
	strtotime("first day of this month"),
	strtotime("first day of next month"),
);

$args = array(
	'post_type'	=> 'event',
  'meta_key' => 'date',
  'orderby'	=> 'meta_value',
	'order'		=> 'ASC',
	'meta_query' => array(
		array(
			'key'     => 'date',
			'value'   => strtotime("first day of previous month"),
			'compare' => '>', 
		),
		array(
			'key'     => 'date',
			'value'   => strtotime("last day of next month"),
			'compare' => '<', 
		),
	),
);
foreach(query_posts( $args ) as $event) : 
	$events[date('Y-m-d', get_post_meta($event->ID, 'date')[0])] = $event;
endforeach;

?>
<ul class="events-calendar-carousel owl-carousel" data-items="1" data-start-item="1" data-autoplay="false">
	<?php foreach($months as $m) : ?>
	<li>
  	<h4><?php echo __(date( 'F', $m)); ?> <?php echo date('Y', $m); ?></h4>
  	<table class="events-calendar table table-rounded">
    	<thead>
      	<th width="14.28%"><?php echo __('M', 'cf-events' ); ?></th>
        <th width="14.28%"><?php echo __('T', 'cf-events' ); ?></th>
        <th width="14.28%"><?php echo __('W', 'cf-events' ); ?></th>
        <th width="14.28%"><?php echo __('T', 'cf-events' ); ?></th>
        <th width="14.28%"><?php echo __('F', 'cf-events' ); ?></th>
        <th width="14.28%"><?php echo __('S', 'cf-events' ); ?></th>
        <th width="14.28%"><?php echo __('S', 'cf-events' ); ?></th>
      </thead>
      <tr>
      <?php for($i = 1; $i < date( 'N', $m); $i++) : //Week into last month?>
      <td>&nbsp;</td>
      <?php endfor; ?>
      <?php 
			for($d = 1; $d <= date('t', $m); $d++): //Days in month
			$current_day 	= date('Y-m-', $m) . $d;
			$today 				= date('Y-m-d');
      $classes 			= array(); 
			if(!empty($events[$current_day]))
				$classes[] = 'event';
			if($current_day == $today)
				$classes[] = 'today';
			
			?>
     
      <td class="<?php echo implode(" ", $classes); ?>">
      	<span class="day"><?php echo $d; ?></span>
       	<?php if(!empty($events[date('Y-m-', $m) . $d])): ?>
        	<div class="event">
          	<a href="<?php echo get_permalink($events[$current_day]->ID); ?>" title="<?php echo get_the_title($events[$current_day]->ID); ?>" class="tooltip-init">
            	<i class="cf-event-icon cf-event-icon-<?php switch(get_post_meta($events[$current_day]->ID, 'eventType')[0]) { 
              	case 'BusinessEvent':
									echo 'building';
								break;
              	case 'ChildrensEvent':
									echo 'child';
								break;
              	case 'ComedyEvent':
									echo 'emo-laugh';
								break;
              	case 'ComedyEvent':
									echo 'emo-laugh';
								break;
              	case 'DanceEvent':
									echo 'music';
								break;
              	case 'EducationEvent':
									echo 'graduation-cap';
								break;
              	case 'FoodEvent':
									echo 'food';
								break;
              	case 'LiteraryEvent':
									echo 'book-open';
								break;
              	case 'MusicEvent':
									echo 'music';
								break;
              	case 'PublicationEvent':
									echo 'book-open';
								break;
              	case 'ScreeningEvent':
									echo 'cinema';
								break;
              	case 'SportsEvent':
									echo 'soccer-ball';
								break;
              	case 'TheaterEvent':
									echo 'theatre';
								break;
              	default:
									echo 'circle';
								break;
              } ?>"></i>
            </a>
          </div>
        <?php endif; ?>
      </td>
      
      <?php if(($i + $d - 1) % 7 == 0) echo '</tr><tr>'; ?>
      <?php endfor; ?>
      <?php for($i = $d + $i - 2; $i % 7 > 0; $i++) :  //Week into next month ?>
      <td></td>
      <?php endfor; ?>
      </tr>
    </table>
    </li>
    <?php endforeach; ?>
</ul>