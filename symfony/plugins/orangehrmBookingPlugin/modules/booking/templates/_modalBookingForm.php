<?php

use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/moment.min.js'));
use_javascript(plugin_web_path('orangehrmBookingPlugin', 'js/jquery.datetimepicker.full.min.js'));

$partialParams = array(
  'form' => $form,
  'actionForm' => $actionForm,
  'buttons' => $buttons,
);

include_partial('bookingForm', $partialParams);
