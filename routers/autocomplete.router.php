<?php

// Get user
$app->get(
	'/autocomplete', function () use ( $app ) {

		$autocomplete = new Autocomplete();

		$app->contentType( 'application/json' );
		echo json_encode( $autocomplete->fetch() );
	}
);
