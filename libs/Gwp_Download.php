<?php

Class Gwp_Download{

    public function __construct(){}

    public function download($id){
        global $is_IE;

       $file_path =  get_attached_file($id);

       $parsed_file_path = parse_url( $file_path );

        $wp_uploads       = wp_upload_dir();
        $wp_uploads_dir   = $wp_uploads['basedir'];
        $wp_uploads_url   = $wp_uploads['baseurl'];

        if ( ( ! isset( $parsed_file_path['scheme'] ) || ! in_array( $parsed_file_path['scheme'], array( 'http', 'https', 'ftp' ) ) )
            && isset( $parsed_file_path['path'] ) && file_exists( $parsed_file_path['path'] ) ) {

            $file_path = realpath( $file_path );
        }

        // Get extension and type
        $file_extension  = strtolower( substr( strrchr( $file_path, "." ), 1 ) );
        $ctype           = "application/force-download";

        foreach ( get_allowed_mime_types() as $mime => $type ) {
            $mimes = explode( '|', $mime );
            if ( in_array( $file_extension, $mimes ) ) {
                $ctype = $type;
                break;
            }
        }

        // Start setting headers
        if ( ! ini_get('safe_mode') ) {
            @set_time_limit(0);
        }

        if ( function_exists( 'get_magic_quotes_runtime' ) && get_magic_quotes_runtime() ) {
            @set_magic_quotes_runtime(0);
        }

        if ( function_exists( 'apache_setenv' ) ) {
            @apache_setenv( 'no-gzip', 1 );
        }

        @session_write_close();
        @ini_set( 'zlib.output_compression', 'Off' );

        /**
         * Prevents errors, for example: transfer closed with 3 bytes remaining to read
         */
        if ( ob_get_length() ) {

            if ( ob_get_level() ) {

                $levels = ob_get_level();

                for ( $i = 0; $i < $levels; $i++ ) {
                    ob_end_clean(); // Zip corruption fix
                }

            } else {
                ob_end_clean(); // Clear the output buffer
            }
        }

        if ( $is_IE && is_ssl() ) {
            // IE bug prevents download via SSL when Cache Control and Pragma no-cache headers set.
            header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
            header( 'Cache-Control: private' );
        } else {
            nocache_headers();
        }


        $filename = basename( $file_path );



        header( "X-Robots-Tag: noindex, nofollow", true );
        header( "Content-Type: " . $ctype );
        header( "Content-Description: File Transfer" );
        header( "Content-Disposition: attachment; filename=\"" . $filename . "\";" );
        header( "Content-Transfer-Encoding: binary" );

        $this->readfile_chunked($file_path);
        exit;
    }


    protected function readfile_chunked( $file, $retbytes = true ){
        $chunksize = 1 * ( 1024 * 1024 );
        $buffer = '';
        $cnt = 0;

        if ( file_exists( $file ) ) {
            $handle = fopen( $file, 'r' );
            if ( $handle === FALSE ) {
                return FALSE;
            }
        }
        elseif ( version_compare( PHP_VERSION, '5.4.0', '<' ) && ini_get( 'safe_mode' ) ) {
            $handle = @fopen( $file, 'r' );
               if ( $handle === FALSE ) {
                    return FALSE;
                }
        } else {
            return FALSE;
        }

        while ( ! feof( $handle ) ) {
            $buffer = fread( $handle, $chunksize );
            echo $buffer;
            if ( ob_get_length() ) {
                ob_flush();
                flush();
            }
            if ( $retbytes ) {
                $cnt += strlen( $buffer );
            }
        }

        $status = fclose( $handle );

        if ( $retbytes && $status ) {
            return $cnt;
        }

        return $status;
    }
}
?>