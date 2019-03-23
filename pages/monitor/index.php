<?php
use \system\classes\Core as Core;
use \system\classes\Configuration as Configuration;
use \system\classes\Database as Database;
?>

<script src="<?php echo Core::getJSscriptURL('jquery-ui-1.11.1.js', 'duckietown'); ?>" type="text/javascript"></script>
<script src="<?php echo Core::getJSscriptURL('packery.pkgd.min.js', 'duckietown'); ?>" type="text/javascript"></script>
<script src="<?php echo Core::getJSscriptURL('draggabilly.pkgd.min.js', 'duckietown'); ?>" type="text/javascript"></script>
<script src="<?php echo Core::getJSscriptURL('roslibjs.min.js', 'duckietown'); ?>" type="text/javascript"></script>

<?php
$mission_name = 'watchtower_default';

// define parameters for the mission control grid
$grid_width = 966; // do not use 970px to accomodate for differences between browsers
$resolution = 8;
$block_gutter = 10;
$block_border_thickness = 1;
$watchtower_name = Core::getSetting('watchtower_name', 'duckietown_watchtower');

// get WebSocket hostname (defaults to HTTP_HOST if not set)
$ws_hostname = Core::getSetting('rosbridge_host', 'ros');
if(strlen($ws_hostname) < 2){
  $ws_hostname = $_SERVER['HTTP_HOST'];
}
// compile the Websocket URL
$ws_url = sprintf(
  "ws://%s:%d",
  $ws_hostname,
  Core::getSetting('rosbridge_port', 'ros')
);

// read mission details
$db = new Database( 'duckietown_watchtower', 'mission' );
$res = $db->read($mission_name);
if( !$res['success'] ){
  Core::throwError( $res['data'] );
}
$mission_control_grid = $res['data'];

// append name of the watchtower to each topic
for ($i = 0; $i < count($mission_control_grid['blocks']); $i++) {
  if( array_key_exists('topic', $mission_control_grid['blocks'][$i]['args']) ){
    $mission_control_grid['blocks'][$i]['args']['topic'] = sprintf(
      '/%s/%s',
      $duckiebot_name,
      $mission_control_grid['blocks'][$i]['args']['topic']
    );
  }
}

// define allowed block sizes
$sizes = [
  [1,1],
  [1,2],
  [1,3],
  [2,2],
  [2,4],
  [3,8],
  [4,8],
  [6,8],
  [8,8]
];

// create mission control grid
$mission_control = new MissionControl(
  "watchtower-mission-control-grid",
  $grid_width,
  $resolution,
  $block_gutter,
  $block_border_thickness,
  $sizes,
  $mission_control_grid['blocks']
);
?>

<div style="width:100%; margin:auto">

  <table style="width:100%; margin-bottom:42px">
    <tr>
      <td colspan="4" style="border-bottom:1px solid #ddd">
        <h2>
          Watchtower - Mission Control
        </h2>
      </td>
    </tr>
    <tr>
      <td class="text-left" style="width:50%; padding-top:10px">
        <i class="fa fa-video-camera" aria-hidden="true"></i> Watch Tower:
        <strong><?php echo $watchtower_name ?></strong>
      </td>
      <td class="text-right" style="width:50%; padding-top:10px">
        <span id="watchtower_bridge_status">
          <i class="fa fa-spinner fa-pulse"></i> Connecting...
        </span>
      </td>
    </tr>
  </table>

  <?php
  $mission_control->create();
  ?>

  <script type="text/javascript">
  $( document ).ready(function() {
    window.mission_control_page_blocks_data = {};

    // Connect to ROS
    window.ros = new ROSLIB.Ros({
      url : "<?php echo $ws_url ?>"
    });
    ros.on('connection', function() {
      console.log('Connected to websocket server.');
      $('#watchtower_bridge_status').html('<span class="glyphicon glyphicon-ok-sign" aria-hidden="true" style="color:green"></span> Bridge: <strong>Connected</strong>');
      $(document).trigger('ROSBridge_connected');
    });
    ros.on('error', function(error) {
      console.log('Error connecting to websocket server: ', error);
      $('#watchtower_bridge_status').html('<span class="glyphicon glyphicon-remove-sign" aria-hidden="true" style="color:red"></span> Bridge: <strong>Error</strong>');
    });
    ros.on('close', function() {
      console.log('Connection to websocket server closed.');
      $('#watchtower_bridge_status').html('<span class="glyphicon glyphicon-off" aria-hidden="true" style="color:red"></span> Bridge: <strong>Closed</strong>');
    });
  });
  </script>

</div>
