<?php
/**
 * This file is part of the O2System PHP Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */
// ------------------------------------------------------------------------

namespace O2System\Framework\Cli\Commanders;

// ------------------------------------------------------------------------

use O2System\Framework\Cli\Commander;
use O2System\Kernel\Cli\Writers\Format;
use O2System\Kernel\Cli\Writers\Table;

/**
 * Class Registry
 *
 * @package O2System\Framework\Cli\Commanders
 */
class Registry extends Commander
{
    /**
     * Make::$commandVersion
     *
     * Command version.
     *
     * @var string
     */
    protected $commandVersion = '1.0.0';

    /**
     * Make::$commandDescription
     *
     * Command description.
     *
     * @var string
     */
    protected $commandDescription = 'DESC_CLI_REGISTRY';

    /**
     * Make::$commandOptions
     *
     * Command options.
     *
     * @var array
     */
    protected $commandOptions = [
        'update'   => [
            'description' => 'Update application registry',
            'help'        => 'H_CLI_REGISTRY_UPDATE',
        ],
        'flush'    => [
            'description' => 'Flush application registry',
        ],
        'info'     => [
            'description' => 'Application registry info',
        ],
        'metadata' => [
            'description' => 'Application registry metadata',
            'help'        => 'H_CLI_REGISTRY_METADATA',
        ],
    ];

    public function optionUpdate( $type = null )
    {
        if ( in_array( $type, [ 'modules', 'languages' ] ) ) {
            switch ( $type ) {
                case 'modules':
                    modules()->updateRegistry();
                    break;

                case 'languages':
                    language()->updateRegistry();
                    break;
            }
        } else {
            modules()->updateRegistry();
            language()->updateRegistry();
        }

        exit( EXIT_SUCCESS );
    }

    public function optionFlush( $type = null )
    {
        if ( in_array( $type, [ 'modules', 'languages' ] ) ) {
            switch ( $type ) {
                case 'modules':
                    modules()->flushRegistry();
                    break;

                case 'languages':
                    language()->flushRegistry();
                    break;
            }

        } else {
            modules()->flushRegistry();
            language()->flushRegistry();
        }

        exit( EXIT_SUCCESS );
    }

    public function optionInfo()
    {
        $table = new Table();

        $table
            ->addHeader( 'Metadata' )
            ->addHeader( 'Total' );

        $table
            ->addRow()
            ->addColumn( 'Modules' )
            ->addColumn( modules()->getTotalRegistry() );

        $table
            ->addRow()
            ->addColumn( 'Language' )
            ->addColumn( language()->getTotalRegistry() );

        output()->write(
            ( new Format() )
                ->setString( $table->render() )
                ->setNewLinesBefore( 1 )
                ->setNewLinesAfter( 2 )
        );

        exit( EXIT_SUCCESS );
    }

    public function optionMetadata( $type )
    {
        if ( in_array( $type, [ 'modules', 'languages' ] ) ) {
            switch ( $type ) {
                case 'modules':
                    $line = PHP_EOL . print_r( modules()->getRegistry(), true );
                    break;

                case 'languages':
                    $line = PHP_EOL . print_r( language()->getRegistry(), true );
                    break;
            }

            if ( isset( $line ) ) {
                output()->writeln( $line );

                exit( EXIT_SUCCESS );
            }
        }
    }
}