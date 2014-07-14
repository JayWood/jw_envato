<?php
class JW_ENVATO {

	var $apiURL = "http://marketplace.envato.com/api/edge";

	public function query_popular( $site='codecanyon' ) {
		if ( false === ( $pTrans = get_transient( 'envato_popular_'.$site ) ) ) {
			$pTrans = $this->curl_it( '/popular:'.$site.'.json' );
			set_transient( 'envato_popular_'.$site, $pTrans, 60*60*24 );
		}
		return $pTrans->popular;
	}

	function curl_it( $string ) {
		$ch = curl_init( $this->apiURL.$string );
		$opts = array( CURLOPT_RETURNTRANSFER => TRUE );
		curl_setopt_array( $ch, $opts );
		return json_decode( curl_exec( $ch ) );
	}
}
?>
