<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Minimal .xlsx reader - no Composer/PhpSpreadsheet dependency.
 *
 * An .xlsx file is a zip archive. We only need three parts of it:
 *   xl/sharedStrings.xml     - the string pool cells reference by index
 *   xl/workbook.xml (+rels)  - maps sheet names to their worksheet XML file
 *   xl/worksheets/sheetN.xml - the actual rows/cells for one tab
 *
 * We read cached <v> values only (not formulas), which is sufficient here
 * because the source workbook was saved with Excel/LibreOffice's computed
 * values already cached alongside each formula.
 */
class PLSRP_XLSX_Reader {

	private $zip;
	private $shared_strings = array();
	private $sheets = array(); // sheet name => worksheet xml path inside the zip

	public function __construct( $file_path ) {
		$this->zip = new ZipArchive();
		$opened = $this->zip->open( $file_path );
		if ( true !== $opened ) {
			throw new Exception( 'Could not open spreadsheet (zip error code ' . $opened . ').' );
		}
		$this->load_shared_strings();
		$this->load_sheet_map();
	}

	private function load_shared_strings() {
		$xml = $this->zip->getFromName( 'xl/sharedStrings.xml' );
		if ( ! $xml ) {
			return;
		}
		$sx = simplexml_load_string( $xml );
		if ( ! $sx ) {
			return;
		}
		foreach ( $sx->si as $si ) {
			if ( isset( $si->t ) ) {
				$this->shared_strings[] = (string) $si->t;
			} else {
				$text = '';
				if ( isset( $si->r ) ) {
					foreach ( $si->r as $r ) {
						$text .= (string) $r->t;
					}
				}
				$this->shared_strings[] = $text;
			}
		}
	}

	private function load_sheet_map() {
		$workbook_xml = $this->zip->getFromName( 'xl/workbook.xml' );
		$rels_xml     = $this->zip->getFromName( 'xl/_rels/workbook.xml.rels' );
		if ( ! $workbook_xml || ! $rels_xml ) {
			return;
		}

		$wb   = simplexml_load_string( $workbook_xml );
		$rels = simplexml_load_string( $rels_xml );

		$rel_map = array();
		foreach ( $rels->Relationship as $rel ) {
			$rel_map[ (string) $rel['Id'] ] = (string) $rel['Target'];
		}

		$ns   = $wb->getNamespaces( true );
		$r_ns = isset( $ns['r'] ) ? $ns['r'] : 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

		foreach ( $wb->sheets->sheet as $sheet ) {
			$attrs = $sheet->attributes( $r_ns );
			$rid   = (string) $attrs['id'];
			$name  = (string) $sheet['name'];

			if ( ! isset( $rel_map[ $rid ] ) ) {
				continue;
			}

			$target = ltrim( $rel_map[ $rid ], '/' );
			if ( 0 !== strpos( $target, 'xl/' ) ) {
				$target = 'xl/' . $target;
			}
			$this->sheets[ $name ] = $target;
		}
	}

	public function sheet_names() {
		return array_keys( $this->sheets );
	}

	/**
	 * Returns rows as [row_number => [col_index (0-based) => value]].
	 */
	public function read_sheet( $name ) {
		if ( ! isset( $this->sheets[ $name ] ) ) {
			return array();
		}

		$xml = $this->zip->getFromName( $this->sheets[ $name ] );
		if ( ! $xml ) {
			return array();
		}

		$sx = simplexml_load_string( $xml );
		if ( ! $sx || ! isset( $sx->sheetData->row ) ) {
			return array();
		}

		$rows = array();
		foreach ( $sx->sheetData->row as $row ) {
			$row_num = (int) $row['r'];
			$cells   = array();

			foreach ( $row->c as $c ) {
				$ref  = (string) $c['r'];
				$col  = $this->col_index_from_ref( $ref );
				$type = (string) $c['t'];

				if ( 's' === $type ) {
					$idx   = isset( $c->v ) ? (int) $c->v : -1;
					$value = isset( $this->shared_strings[ $idx ] ) ? $this->shared_strings[ $idx ] : '';
				} elseif ( 'str' === $type ) {
					$value = isset( $c->v ) ? (string) $c->v : '';
				} elseif ( 'inlineStr' === $type ) {
					$value = isset( $c->is->t ) ? (string) $c->is->t : '';
				} else {
					$value = isset( $c->v ) ? (string) $c->v : null;
					if ( null !== $value && '' !== $value && is_numeric( $value ) ) {
						$value = $value + 0;
					}
				}

				$cells[ $col ] = $value;
			}

			$rows[ $row_num ] = $cells;
		}

		return $rows;
	}

	private function col_index_from_ref( $ref ) {
		preg_match( '/^([A-Z]+)(\d+)$/', $ref, $m );
		$letters = isset( $m[1] ) ? $m[1] : 'A';
		$col     = 0;
		for ( $i = 0; $i < strlen( $letters ); $i++ ) {
			$col = $col * 26 + ( ord( $letters[ $i ] ) - ord( 'A' ) + 1 );
		}
		return $col - 1;
	}

	public function close() {
		$this->zip->close();
	}
}

/**
 * Seeds the plugin's tables from the "REVISED_PLRP_SRP_WORKING SHEET" workbook.
 *
 * Sheet layout this expects (matches the source file):
 *   - "ECO-BASE PRICES": col A material name, col B base price, no header row.
 *   - One sheet per profession with header row: Item | Materyales / Rekados |
 *     Quantity | SRP/Qty (base price) | MAT COST | TOTAL MAT COST PER ITEM |
 *     MARKUP | SELLING PRICE. Items span multiple rows: the item name only
 *     appears on the first row of its ingredient group; a fully blank row
 *     separates one item's group from the next.
 */
class PLSRP_Importer {

	// Source sheet name => [display name, sort order]. Anything not listed
	// here (ES-AR-PEE, ECO-BASE PRICES, ARMERO DISENYO, unknown tabs) is
	// skipped as an item source.
	private static $profession_sheets = array(
		'PAGKAIN'            => array( 'Pagkain', 1 ),
		'done - MATADERO'    => array( 'Matadero', 2 ),
		'done - ENSAYADOR'   => array( 'Ensayador', 3 ),
		'PANDAY'             => array( 'Panday', 4 ),
		'PANDAY(KAGAMITAN)'  => array( 'Panday - Kagamitan', 5 ),
		'PANDAY(REFINES)'    => array( 'Panday - Refines', 6 ),
		'done-DOCTOR'        => array( 'Doctor', 7 ),
		'done - ARMERO'      => array( 'Armero', 8 ),
		'KATUTUBO'           => array( 'Katutubo', 9 ),
	);

	const MATERIALS_SHEET = 'ECO-BASE PRICES';

	public static function import_from_file( $file_path ) {
		$report = array(
			'materials_imported' => 0,
			'professions'        => array(),
			'warnings'           => array(),
		);

		$reader = new PLSRP_XLSX_Reader( $file_path );

		// 1) Materials master list.
		if ( in_array( self::MATERIALS_SHEET, $reader->sheet_names(), true ) ) {
			$rows = $reader->read_sheet( self::MATERIALS_SHEET );
			foreach ( $rows as $cells ) {
				$name  = isset( $cells[0] ) ? trim( (string) $cells[0] ) : '';
				$price = isset( $cells[1] ) ? (float) $cells[1] : null;
				if ( '' === $name || null === $price ) {
					continue;
				}
				PLSRP_Materials::upsert( $name, $price );
				$report['materials_imported']++;
			}
		} else {
			$report['warnings'][] = sprintf( 'Sheet "%s" not found - no base materials imported.', self::MATERIALS_SHEET );
		}

		// 2) Profession sheets -> items + ingredients.
		foreach ( self::$profession_sheets as $sheet_name => $meta ) {
			list( $display_name, $sort_order ) = $meta;

			if ( ! in_array( $sheet_name, $reader->sheet_names(), true ) ) {
				$report['warnings'][] = sprintf( 'Sheet "%s" not found - skipped.', $sheet_name );
				continue;
			}

			$result = self::import_profession_sheet( $reader, $sheet_name, $display_name, $sort_order );
			$report['professions'][ $display_name ] = $result;
		}

		$reader->close();

		return $report;
	}

	private static function import_profession_sheet( PLSRP_XLSX_Reader $reader, $sheet_name, $display_name, $sort_order ) {
		$rows   = $reader->read_sheet( $sheet_name );
		$result = array( 'items' => 0, 'warnings' => array() );

		if ( empty( $rows ) || ! isset( $rows[1] ) ) {
			$result['warnings'][] = 'Sheet had no header row - skipped.';
			return $result;
		}

		// Map header text -> column index.
		$header_col = array();
		foreach ( $rows[1] as $col => $label ) {
			$header_col[ strtolower( trim( (string) $label ) ) ] = $col;
		}

		$col_item = self::find_column( $header_col, 'item' );
		$col_mat  = self::find_column( $header_col, 'materyales' );
		$col_qty  = self::find_column( $header_col, 'quantity' );
		$col_mark = self::find_column( $header_col, 'markup' );

		if ( null === $col_item || null === $col_mat || null === $col_qty ) {
			$result['warnings'][] = 'Could not find Item/Materyales/Quantity columns - skipped.';
			return $result;
		}

		// Markup % lives one row below the header, in the MARKUP column.
		$markup_pct = 0.0;
		if ( null !== $col_mark && isset( $rows[2][ $col_mark ] ) && is_numeric( $rows[2][ $col_mark ] ) ) {
			$markup_pct = (float) $rows[2][ $col_mark ];
		}

		$profession_id = PLSRP_Professions::upsert_by_name(
			$display_name,
			$markup_pct,
			array( 'sort_order' => $sort_order )
		);

		// Group rows into items: a non-empty Item cell starts a new item; a
		// non-empty Materyales cell (with empty Item cell) continues the
		// current item; a row with both empty is just a spacer and is skipped.
		$max_row = max( array_keys( $rows ) );
		$items   = array();
		$current = null;

		for ( $r = 3; $r <= $max_row; $r++ ) {
			$cells    = isset( $rows[ $r ] ) ? $rows[ $r ] : array();
			$item_val = isset( $cells[ $col_item ] ) ? trim( (string) $cells[ $col_item ] ) : '';
			$mat_val  = isset( $cells[ $col_mat ] ) ? trim( (string) $cells[ $col_mat ] ) : '';
			$qty_val  = isset( $cells[ $col_qty ] ) && is_numeric( $cells[ $col_qty ] ) ? (float) $cells[ $col_qty ] : 0.0;

			if ( '' !== $item_val ) {
				if ( null !== $current ) {
					$items[] = $current;
				}
				$current = array( 'name' => $item_val, 'ingredients' => array() );
				if ( '' !== $mat_val ) {
					$current['ingredients'][] = array( 'material' => $mat_val, 'quantity' => $qty_val );
				}
			} elseif ( '' !== $mat_val ) {
				if ( null !== $current ) {
					$current['ingredients'][] = array( 'material' => $mat_val, 'quantity' => $qty_val );
				}
			}
			// else: fully blank spacer row, skip.
		}
		if ( null !== $current ) {
			$items[] = $current;
		}

		// Persist items + ingredients, creating any missing materials on the
		// fly (flagged as a warning so the admin can fill in a real price).
		$order = 0;
		foreach ( $items as $item_data ) {
			$ingredient_rows = array();
			foreach ( $item_data['ingredients'] as $ing ) {
				$material = PLSRP_Materials::get_by_name( $ing['material'] );
				if ( ! $material ) {
					$material_id = PLSRP_Materials::upsert( $ing['material'], 0 );
					$result['warnings'][] = sprintf(
						'"%s" (used by %s) was not found in ECO-BASE PRICES - created with a base price of 0. Please set its real price.',
						$ing['material'],
						$item_data['name']
					);
				} else {
					$material_id = $material->id;
				}
				$ingredient_rows[] = array( 'material_id' => $material_id, 'quantity' => $ing['quantity'] );
			}

			PLSRP_Items::save( null, $profession_id, $item_data['name'], '', $ingredient_rows );
			$result['items']++;
			$order++;
		}

		return $result;
	}

	private static function find_column( $header_col, $needle ) {
		foreach ( $header_col as $label => $col ) {
			if ( false !== strpos( $label, $needle ) ) {
				return $col;
			}
		}
		return null;
	}
}
