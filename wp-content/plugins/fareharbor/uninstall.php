<?php

  if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

  delete_option( 'fareharbor_settings' );
  delete_option( 'fareharbor_version' );
  delete_option( 'fareharbor_kit_version' );
